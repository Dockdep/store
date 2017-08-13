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
    
    class YamlController extends Controller
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
            
            $this->xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $this->xml .= XmlHelper::createOpeningElement('yml_catalog', ['date' => date('Y-m-d G:i')]);
            $this->xml .= '<shop>';
            $this->xml .= XmlHelper::createElement('name', 'Extremstyle');
            $this->xml .= XmlHelper::createElement('company', 'Extrem Style');
            $this->xml .= XmlHelper::createElement('url', $this->domain);
            $this->xml .= '<currencies>
                            <currency id="UAH" rate="1"/>
                            </currencies>';
            
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
                if ($row->parent_id != 0) {
                    $this->xml .= XmlHelper::createElement('category', $row->lang->title, [
                        'id' => $row->id,
                        'parentId' => $row->parent_id,
                    ]);
                } else {
                    $this->xml .= XmlHelper::createElement('category', $row->lang->title, [
                        'id' => $row->id,
                    ]);
                }
            }
            
            $this->xml .= '</categories>';
            
            /**
             * Write file first time with categories
             */
            $file = fopen(\Yii::getAlias('@frontend') . '/web/yaml.xml', 'w');
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
            $this->xml .= '<offers>';
            
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
                                      );
            
            $this->stdout('Going for products' . "\n", Console::FG_YELLOW);
            foreach ($products->each(1000) as $variant) {
                /**
                 * Filtering variants without brand
                 */
                if (empty($variant->product->brand)) {
                    continue;
                }
    
                /**
                 * Filter variant with image in name
                 */
                if (preg_match("@^.*(jpg|png|jpeg)$@i", $variant->lang->title)) {
                    $name = htmlspecialchars($variant->product->lang->title);
                } else {
                    $name = htmlspecialchars($variant->product->lang->title . ' ' . $variant->lang->title);
                }
                
                $this->count++;
                $this->xml .= XmlHelper::createOpeningElement('offer', [
                    'id' => $variant->id,
                    'available' => 'true',
                    'type' => $variant->product->brand->lang->title . ' ' . $name,
                ]);
                $this->xml .= XmlHelper::createElement('categoryId', $variant->product->category->id);
                $this->xml .= XmlHelper::createElement('vendor', $variant->product->brand->lang->title);
                $this->xml .= XmlHelper::createElement('model', $name);
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
                    'picture',
                    'https://extremstyle.ua' . htmlspecialchars($variant->imageUrl)
                );
                $this->xml .= XmlHelper::createElement('price', $variant->price);
                $this->xml .= XmlHelper::createElement('currencyId', 'UAH');
                
                /**
                 * Adding variant's options
                 */
                foreach ($variant->options as $option) {
                    $this->xml .= XmlHelper::createElement('param', $option->lang->value, [
                        'name' => $option->group->lang->title,
                    ]);
                }
                
                
                if ($this->count % 100 == 0) {
                    $this->stdout('# ' . $this->count . ' ', Console::BOLD);
                    $this->stdout($variant->sku . "\n", Console::FG_YELLOW);
                    
                }
                
                $this->xml .= XmlHelper::createClosingElement('offer');
                
                /**
                 * Writing file every 3000 times
                 */
                if ($this->count % 3000 == 0) {
                    $file = fopen(\Yii::getAlias('@frontend') . '/web/yaml.xml', 'a');
                    fwrite($file, $this->xml);
                    fclose($file);
                    $this->xml = '';
                }
                
            }
            
            $this->xml .= '</offers>';
            
            
            $this->xml .= '</shop>';
            $this->xml .= XmlHelper::createClosingElement('yml_catalog');
            
            /**
             * Write the file
             */
            $file = fopen(\Yii::getAlias('@frontend') . '/web/yaml.xml', 'a');
            fwrite($file, $this->xml);
            fclose($file);
            $this->stdout('Done!' . "\n", Console::FG_GREEN);
        }
    }