<?php
    namespace console\controllers;
    
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\console\Controller;
    use yii\db\ActiveQuery;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Console;
    use yii\helpers\Url;
    
    class FeedController extends Controller
    {
        public $file;
        public $domain = 'https://extremstyle.ua';
        public $count = 0;
        
        public function actionGo()
        {
            ini_set('memory_limit', '128M');
            $config = ArrayHelper::merge(
                require( \Yii::getAlias('@frontend/config/') . 'main.php' ),
                require( \Yii::getAlias('@common/config/') . 'main.php' )
            );
            
            \Yii::$app->urlManager->addRules($config[ 'components' ][ 'urlManager' ][ 'rules' ]);
            
            $this->stdout('Started' . "\n", Console::FG_CYAN);
            
            /**
             * Adding products
             *
             * @var ProductVariant $variant
             */
            
            $products = ProductVariant::find()
                                      ->with(
                                          [
                                              'product' => function(ActiveQuery $query) {
                                                  $query->with(
                                                      [
                                                          'lang',
                                                          'brand.lang',
                                                          'category.lang',
                                                      ]
                                                  );
                                              },
                                              'options' => function(ActiveQuery $query) {
                                                  $query->with(
                                                      [
                                                          'lang',
                                                          'group.lang',
                                                      ]
                                                  );
                                              },
                                              'image',
                                          ]
                                      )
                                      ->where(
                                          [
                                              '!=',
                                              'price',
                                              0,
                                          ]
                                      );
            
            $this->stdout('Going for products' . "\n", Console::FG_YELLOW);
            
            $this->file = fopen(\Yii::getAlias('@frontend') . '/web/feed.csv', 'w');
            fputcsv($this->file, ['ID', 'ID2', 'Item Category', 'Item title', 'Item description', 'Price', 'Final URL', 'Image URL']);
            
            foreach ($products->each(1000) as $variant) {
                if (empty($variant->product->brand)) {
                    continue;
                }
                $this->count++;
    
                /**
                 * Generate one line of feed
                 */
                $name = !empty($variant->product->category->lang->category_synonym) ? $variant->product->category->lang->category_synonym : $variant->product->category->lang->title;
                $line = [];
                $line[] = $variant->id;
                $line[] = $variant->product->category->id;
                $line[] = $name;
                $line[] = $variant->product->brand->lang->title . ' ' . $name;
                $line[] = $variant->product->lang->title;
                $line[] = $variant->price . ' UAH';
                $line[] = htmlspecialchars(
                    Url::to(
                        [
                            'catalog/product',
                            'product' => $variant->product->lang->alias,
                            'variant' => $variant->sku,
                        ]
                    )
                );
                $line[] = $this->domain . htmlspecialchars($variant->imageUrl);
    
                /**
                 * Write the line to the table
                 */
                fputcsv($this->file, $line);
                
                /**
                 * Console output
                 */
                if ($this->count % 100 == 0) {
                    $this->stdout('# ' . $this->count . ' ', Console::BOLD);
                    $this->stdout($variant->sku . "\n", Console::FG_YELLOW);
                    
                }
            }
            
            /**
             * Write the file
             */
            fclose($this->file);
            $this->stdout('Done!' . "\n", Console::FG_GREEN);
        }
    }