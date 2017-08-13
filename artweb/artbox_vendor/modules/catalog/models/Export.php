<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\TaxOption;
    use yii\base\Model;
    
    class Export extends Model
    {
        /**
         * Language ID to export language tables
         *
         * @var int $lang
         */
        public $lang;
        
        public $file;
        
        public $errors = [];
        
        public $output = [];
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    'lang',
                    'integer',
                ],
                [
                    'lang',
                    'default',
                    'value' => Language::getCurrent()->id,
                ],
            ];
        }
        
        /**
         * Perform product export
         *
         * @param null|string $filename Export csv file name
         * @param int         $from     Product start
         *
         * @return array
         */
        public function process($filename = null, $from = 0)
        {
            $limit = 100;
            
            if (empty( $filename )) {
                $filename = 'products_' . date('d_m_Y_H_i') . '.csv';
                $handle = fopen(\Yii::getAlias('@storage/sync/') . $filename, "w");
            } else {
                $handle = fopen(\Yii::getAlias('@storage/sync/') . $filename, "a");
            }
            
            $language = Language::findOne(\Yii::$app->session->get('export_lang', Language::getDefaultLanguage()->id));
            Language::setCurrent($language->url);
            
            /**
             * @var Product[] $products
             */
            $products = Product::find()
                               ->with('variantsWithFilters', 'brand.lang', 'categories.lang', 'filters', 'images')
                               ->joinWith('lang', true, 'INNER JOIN')
                               ->limit($limit)
                               ->offset($from)
                               ->all();
            $filesize = Product::find()
                               ->joinWith('lang', true, 'INNER JOIN')
                               ->count();
            foreach ($products as $product) {
                $mods = [];
                $filterString = $this->convertFilterToString($product->filters);
                
                foreach ($product->variantsWithFilters as $variant) {
                    /**
                     * @var ProductVariant $variant
                     */
                    $color = $variant->lang->title;
                    $mods[] = $variant->sku . $this->generateID(
                            $variant->remote_id
                        ) . '=' . $this->convertFilterToString(
                            $variant->filters
                        ) . '=' . $color . '=' . ( ( !empty( $variant->image ) ) ? $variant->image->image : '' ) . '=' . $variant->stock;
                }
                
                $fotos = [];
                foreach ($product->images as $image) {
                    $fotos[] = $image->image;
                }
                
                $categories = [];
                foreach ($product->categories as $value) {
                    $categories[] = $value->lang->title . $this->generateID($value->remote_id);
                }
                
                $categories = implode(',', $categories);
                
                $list = [
                    $categories,
                    //A - категории через запятую Название(remote_id)
                    ( ( !empty( $product->brand ) ) ? $product->brand->lang->title . $this->generateID(
                            $product->brand->remote_id
                        ) : '' ),
                    //B - бренд Название(remote_id)
                    $product->lang->title . $this->generateID($product->remote_id),
                    //C - название товара Название(remote_id)
                    ( ( !empty( $product->lang->description ) ) ? $product->lang->description : '' ),
                    //D - описание товара Описание(remote_id)
                    $filterString,
                    //E - характеристики товара. Структура: [Группа1(remote_id):Характеристика11(remote_id),Характеристика12(remote_id)]*[Группа2(remote_id):Характеристика21(remote_id),Характеристика22(remote_id)]
                    ( !empty( $product->variant ) ) ? $product->variant->price_old : '',
                    //F - страрая цена
                    ( !empty( $product->variant ) ) ? $product->variant->price : '',
                    //G - новая цена
                    intval($product->is_discount),
                    //H - товар акционный (1/0)
                    '',
                    //I - пустой
                    intval($product->is_new),
                    //J - товар новинка
                    intval($product->is_top),
                    //K - товар в топе
                    $product->video,
                    //L - ссылка на видео (iframe)
                    implode(',', $fotos),
                    //M - название файлов через запятую, картинки должны хранится в /storage/sync/product_images
                    // Все последующие модификации: SKU(remote_id)=[Группа1(remote_id):Характеристика11(remote_id),Характеристика12(remote_id)]*[Группа2(remote_id):Характеристика21(remote_id),Характеристика22(remote_id)]=Название=Изображение=Остаток
                ];
                $to_write = array_merge($list, $mods);
                fputcsv($handle, $to_write, ';');
                unset( $product );
            }
            
            fclose($handle);
            
            $from += $limit;
            $end = false;
            if ($from > $filesize) {
                $end = true;
            }
            
            $result = [
                'end'       => $end,
                'from'      => $from,
                'totalsize' => $filesize,
                'filename'  => $filename,
            ];
            
            if ($end) {
                $result = array_merge(
                    $result,
                    [
                        'link' => '/storage/sync/' . $filename,
                    ]
                );
            }
            
            return $result;
        }
        
        /**
         * Stringify filters for export
         * * Result: [filterName1:filterValue11,filterValue12]*[filterName2:filterValue21,filterValue22]
         *
         * @param $filters
         *
         * @return string
         */
        public function convertFilterToString($filters)
        {
            $filtersArray = [];
            /**
             * @var TaxOption[] $filters
             */
            foreach ($filters as $filter) {
                $filtersArray[ $filter->taxGroup->lang->title . $this->generateID(
                    $filter->taxGroup->remote_id
                ) ][] = $filter->lang->value . $this->generateID($filter->remote_id);
            }
            $filterString = [];
            
            foreach ($filtersArray as $filterName => $filterRows) {
                $row = implode(',', $filterRows);
                $filterString[] = "[{$filterName}:{$row}]";
            }
            return implode('*', $filterString);
        }
        
        /**
         * Generate remote ID string
         *
         * @param string $id Remote ID
         *
         * @return string Formatted remote ID
         */
        private function generateID(string $id):string
        {
            return sprintf('(#%s#)', $id);
        }
    }
    