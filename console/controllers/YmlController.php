<?php
    namespace console\controllers;
    
    use artweb\artbox\ecommerce\helpers\XmlHelper;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\console\Controller;
    use yii\db\ActiveQuery;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Console;
    use yii\helpers\Url;
    
    class YmlController extends Controller
    {
        public $file;
        public $domain = 'https://extremstyle.ua/ru';
        public $count = 0;
        public $xml = '';
        
        public function actionGo()
        {
            ini_set('memory_limit', '128M');
            $config = ArrayHelper::merge(
                require( \Yii::getAlias('@frontend/config/') . 'main.php' ),
                require( \Yii::getAlias('@common/config/') . 'main.php' )
            );
            
            \Yii::$app->urlManager->addRules($config[ 'components' ][ 'urlManager' ][ 'rules' ]);
            
            $this->stdout('Started' . "\n", Console::FG_CYAN);
            
            $this->xml = '<?xml version="1.0" encoding="UTF-8"?><price>';
            $this->xml .= XmlHelper::createElement('date', date('Y-m-d G:i'));
            $this->xml .= XmlHelper::createElement('firmName', 'Extrem Style');
            $this->xml .= XmlHelper::createElement('firmId', '22013');
            
            /**
             * Adding categories
             *
             * @var Category $row
             */
            $this->xml .= '<categories>';
            
            $categories = Category::find()
                                  ->with('lang');
            
            $this->stdout('Going for categories' . "\n", Console::FG_YELLOW);
            
            foreach ($categories->each(100) as $row) {
                $this->xml .= '<category>';
                $this->xml .= XmlHelper::createElement('id', $row->id);
                if ($row->parent_id != 0) {
                    $this->xml .= XmlHelper::createElement('parentId', $row->parent_id);
                }
                $this->xml .= XmlHelper::createElement('name', $row->lang->title);
                $this->xml .= '</category>';
            }
            
            $this->xml .= '</categories>';
            
            /**
             * Write file first time with categories
             */
            $file = fopen(\Yii::getAlias('@frontend') . '/web/hotline.xml', 'w');
            fwrite($file, $this->xml);
            fclose($file);
            
            /**
             * Flush variable
             */
            $this->xml = '';
            
            /**
             * Adding products
             *
             * @var ProductVariant $variant
             */
            $this->xml .= '<items>';
            
            $products = ProductVariant::find()
                                      ->with(
                                          [
                                              'product' => function(ActiveQuery $query) {
                                                  $query->with(
                                                      [
                                                          'lang',
                                                          'brand.lang',
                                                          'category',
                                                          'options' => function(ActiveQuery $query) {
                                                              $query->with(
                                                                  [
                                                                      'lang',
                                                                      'group.lang',
                                                                  ]
                                                              );
                                                          },
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
                                      )
                                      ->andWhere(
                                          [
                                              '!=',
                                              'stock',
                                              0,
                                          ]
                                      );
            
            $this->stdout('Going for products' . "\n", Console::FG_YELLOW);
            foreach ($products->each(1000) as $variant) {
                /**
                 * Filtering variants without brand
                 */
                if (empty($variant->product->brand)) {
                    continue;
                }
                
                $this->count++;
                $this->xml .= '<item>';
                $this->xml .= XmlHelper::createElement('id', $variant->id);
                $this->xml .= XmlHelper::createElement('categoryId', $variant->product->category->id);
                $this->xml .= XmlHelper::createElement('code', $variant->sku);
                $this->xml .= XmlHelper::createElement('vendor', $variant->product->brand->lang->title);
                
                /**
                 * Filter variant with image in name
                 */
                if (preg_match("@^.*(jpg|png|jpeg)$@i", $variant->lang->title)) {
                    $name = htmlspecialchars($variant->product->lang->title);
                } else {
                    $name = htmlspecialchars($variant->product->lang->title . ' ' . $variant->lang->title);
                }
                if (preg_match("@(.*)коньки(.*)@iu", $variant->lang->title) || preg_match(
                        "@(.*)велосипеды(.*)@iu",
                        $variant->lang->title
                    )
                ) {
                    foreach ($variant->options as $option) {
                        if (preg_match('@(.*)размер(.*)@iu', $option->group->lang->title)) {
                            $name .= ' ' . $option->lang->value;
                        }
                    }
                }
                $this->xml .= XmlHelper::createElement('name', $name);
                $this->xml .= XmlHelper::createElement(
                    'description',
                    htmlspecialchars(
                        strip_tags($variant->product->lang->description)
                    )
                );
                $this->xml .= XmlHelper::createElement(
                    'url',
                    htmlspecialchars(
                        Url::to(
                            [
                                'catalog/product',
                                'product' => $variant->product->lang->alias,
                                'variant' => $variant->sku,
                            ]
                        )
                    )
                );
                $this->xml .= XmlHelper::createElement(
                    'image',
                    'https://extremstyle.ua' . htmlspecialchars($variant->imageUrl)
                );
                $this->xml .= XmlHelper::createElement('priceRUAH', $variant->price);
                if ($variant->price_old != 0) {
                    $this->xml .= XmlHelper::createElement('oldprice', $variant->price_old);
                }
                
                /**
                 * Adding variant's options
                 */
                foreach ($variant->options as $option) {
                    $attributes = [];
                    $attributes[ 'name' ] = $option->group->lang->title;
                    if (preg_match('@(.*)размер(.*)@iu', $option->group->lang->title)) {
                        if (preg_match('@\d@', $option->lang->value)) {
                            $attributes[ 'unit' ] = 'UA';
                        } else {
                            $attributes[ 'unit' ] = 'INT';
                        }
                    }
                    $this->xml .= XmlHelper::createElement('param', $option->lang->value, $attributes);
                }
                
                /**
                 * Adding products options
                 */
                foreach ($variant->product->options as $option) {
                    $this->xml .= XmlHelper::createElement(
                        'param',
                        $option->lang->value,
                        [
                            'name' => $option->group->lang->title,
                        ]
                    );
                }
                
                if ($this->count % 100 == 0) {
                    $this->stdout('# ' . $this->count . ' ', Console::BOLD);
                    $this->stdout($variant->sku . "\n", Console::FG_YELLOW);
                    
                }
                
                $this->xml .= '</item>';
                
                /**
                 * Writing file every 3000 times
                 */
                if ($this->count % 3000 == 0) {
                    $file = fopen(\Yii::getAlias('@frontend') . '/web/hotline.xml', 'a');
                    fwrite($file, $this->xml);
                    fclose($file);
                    $this->xml = '';
                }
                
            }
            
            $this->xml .= '</items>';
            $this->xml .= '</price>';
            
            /**
             * Write the file
             */
            $file = fopen(\Yii::getAlias('@frontend') . '/web/hotline.xml', 'a');
            fwrite($file, $this->xml);
            fclose($file);
            $this->stdout('Done!' . "\n", Console::FG_GREEN);
        }
    }