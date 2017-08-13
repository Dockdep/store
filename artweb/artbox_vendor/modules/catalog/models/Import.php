<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use artweb\artbox\modules\catalog\models\TaxOption;
    use Yii;
    use yii\base\Model;
    use yii\helpers\ArrayHelper;

    /**
     * Class Import
     *
     * @package artweb\artbox\modules\catalog\models
     */
    class Import extends Model
    {
        /**
         * Import csv file
         *
         * @var string $file
         */
        public $file;
        
        /**
         * Import type
         * * product
         * * price
         *
         * @var string $type
         */
        public $type;
        
        /**
         * Import language ID
         *
         * @var int $lang
         */
        public $lang;
        
        public $errors = [];
        
        public $output = [];
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'type',
                        'lang',
                    ],
                    'required',
                ],
                [
                    [ 'lang' ],
                    'integer',
                ],
                [
                    [ 'type' ],
                    'string',
                ],
                [
                    [ 'file' ],
                    'file',
                    'extensions' => 'csv',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'file' => Yii::t('product', 'File'),
            ];
        }
        
        /**
         * Get import type
         *
         * @see Import::type
         * @return string
         */
        public function getType()
        {
            if (!$this->type) {
                $this->type = 'products';
            }
            return $this->type;
        }
        
        /**
         * Import prices
         *
         * @param int  $from  Start row
         * @param null $limit Row limit
         *
         * @return array|bool Array if OK, false otherwise
         */
        public function goPrices($from = 0, $limit = null)
        {
            set_time_limit(0);
            
            if (!( $handle = $this->getProductsFile('uploadFilePrices') )) {
                $this->errors[] = 'File not found';
                return false;
            }
            
            $filesize = filesize(Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFilePrices'));
            if ($from) {
                fseek($handle, $from);
            }
            
            $j = 0;
            
            $is_utf = ( preg_match(
                '//u',
                file_get_contents(
                    Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFilePrices'),
                    null,
                    null,
                    null,
                    1000000
                )
            ) );
            
            while (( empty( $limit ) || $j++ < $limit ) && ( $data = fgetcsv($handle, 10000, ";") ) !== false) {
                foreach ($data as &$value) {
                    if (!$is_utf) {
                        $value = iconv('windows-1251', "UTF-8//TRANSLIT//IGNORE", $value);
                    }
                    $value = trim($value);
                }
                
                // данные строк
                $modification_code = @$data[ 0 ];
                $price = floatval(@$data[ 1 ]);
                $price_promo = floatval(@$data[ 2 ]);
                $count = intval(@$data[ 3 ]);
                $city_name = @$data[ 4 ];
                $product_title = @$data[ 5 ];
                
                if (empty ( $modification_code )) {
                    continue;
                }
                // товары в пути
                if (empty ( $city_name )) {
                    $this->output[] = 'Товар ' . $product_title . ' в пути';
                    continue;
                }
                /**
                 * @var ProductVariant $productVariant
                 */
                if (( $productVariant = ProductVariant::find()
                                                      ->filterWhere([ 'sku' => $modification_code ])
                                                      ->one() ) === null
                ) {
                    $this->output[] = 'Для товара ' . $product_title . ' не найдено соотвествие';
                    continue;
                }
                // ===== Set stock ====
                if ($city_name) {
                    if (( $stock = Stock::find()
                                        ->filterWhere([ 'title' => trim($city_name) ])
                                        ->one() ) === null
                    ) {
                        // Create stock
                        $stock = new Stock();
                        $stock->title = trim($city_name);
                        $stock->save(false);
                    }
                    
                    $productStock = ProductStock::find()
                                                ->where(
                                                    [
                                                        'product_variant_id' => $productVariant->id,
                                                        'stock_id'           => $stock->id,
                                                    ]
                                                )
                                                ->one();
                    if (!$productStock instanceof ProductStock) {
                        $productStock = new ProductStock();
                        $productStock->product_variant_id = $productVariant->id;
                        $productStock->stock_id = $stock->id;
                    }
                    $productStock->quantity = $count;
                    
                    $productStock->save(false);
                    $productStocks = ProductStock::find()
                                                 ->where(
                                                     [ 'product_variant_id' => $productVariant->id ]
                                                 )
                                                 ->andWhere(
                                                     [
                                                         '<>',
                                                         'stock_id',
                                                         $stock->id,
                                                     ]
                                                 )
                                                 ->all();
                    
                    $quantity = array_sum(ArrayHelper::getColumn($productStocks, 'quantity')) + $count;
                } else {
                    
                    $productStocks = ProductStock::find()
                                                 ->where(
                                                     [ 'product_variant_id' => $productVariant->id ]
                                                 )
                                                 ->all();
                    
                    if ($productStocks instanceof ProductStock) {
                        $quantity = array_sum(ArrayHelper::getColumn($productStocks, 'quantity')) + $count;
                    } else {
                        $quantity = 0;
                    }
                    
                }
                
                if ($price_promo) {
                    $productVariant->price_old = $price;
                    $productVariant->price = $price_promo;
                } else {
                    $productVariant->price = $price;
                    $productVariant->price_old = $price_promo;
                }
                
                $productVariant->stock = $quantity;
                
                $productVariant->save(false);
                
                $this->output[] = '<span style="color:blue">Товар ' . $product_title . ' успешно сохранен</span>';
            }
            
            $result = [
                'end'       => feof($handle),
                'from'      => ftell($handle),
                'totalsize' => $filesize,
                'items'     => $this->output,
            
            ];
            
            fclose($handle);
            
            if ($result[ 'end' ]) {
                unlink(Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFilePrices'));
            }
            
            return $result;
        }
        
        /**
         * Pull name and remote_id from formatted string
         *
         * @param string $name
         *
         * @return array
         */
        private function parseName(string $name):array
        {
            $pattern = '/^(?P<name>.*)(?:\(#(?P<remote_id>\w+)#\))?$/U';
            $name = trim($name);
            $matches = [];
            if (preg_match($pattern, $name, $matches)) {
                if (!isset( $matches[ 'remote_id' ] )) {
                    $matches[ 'remote_id' ] = '';
                }
                return $matches;
            }
            return [
                'name'      => $name,
                'remote_id' => '',
            ];
        }
        
        /**
         * Save categories
         *
         * @param array $catalog_names
         *
         * @return int[] Category IDs
         * @throws \Exception
         */
        private function saveCatalog(array $catalog_names):array
        {
            $category_id = [];
            
            foreach ($catalog_names as $catalog_name) {
                // ==== Set category ====
                $parsed_name = $this->parseName($catalog_name);
                if (!empty( $parsed_name[ 'remote_id' ] ) && ( $category = Category::find()
                                                                                   ->joinWith('lang')
                                                                                   ->andFilterWhere(
                                                                                       [ 'remote_id' => $parsed_name[ 'remote_id' ] ]
                                                                                   )
                                                                                   ->one() ) !== null
                ) {
                    if (!empty( $category->lang )) {
                        $category->lang->title = $parsed_name[ 'name' ];
                        $category->lang->save(false);
                    } else {
                        throw new \Exception(
                            'Category with ID ' . $category->id . ' and lang ' . Language::getCurrent(
                            )->id . ' doesn\'t exist'
                        );
                    }
                    
                } else {
                    // Create category
                    $category = new Category();
                    $category->generateLangs();
                    $category_langs = $category->modelLangs;
                    foreach ($category_langs as $category_lang) {
                        $category_lang->title = $parsed_name[ 'name' ];
                    }
                    $category->remote_id = $parsed_name[ 'remote_id' ];
                    $category->save(false);
                }
                $category_id[] = $category->id;
            }
            return $category_id;
        }
        
        /**
         * Save brand
         *
         * @param string|null $brand_name
         *
         * @return int|null New Brand ID if inserted or exist or null if skipped
         * @throws \Exception
         */
        private function saveBrand(string $brand_name = null):int
        {
            
            $parsed_name = $this->parseName($brand_name);
            if (!empty( $brand_name )) {
                /**
                 * @var Brand $brand
                 */
                if (!empty( $parsed_name[ 'remote_id' ] ) && ( $brand = Brand::find()
                                                                             ->joinWith('lang')
                                                                             ->andFilterWhere(
                                                                                 [ 'remote_id' => $parsed_name[ 'remote_id' ] ]
                                                                             )
                                                                             ->one() ) !== null
                ) {
                    if (!empty( $brand->lang )) {
                        $brand->lang->title = $parsed_name[ 'name' ];
                        $brand->lang->save(false);
                    } else {
                        throw new \Exception(
                            'Brand with ID ' . $brand->id . ' and lang ' . Language::getCurrent(
                            )->id . ' doesn\'t exist'
                        );
                    }
                    return $brand->id;
                } else {
                    // Create brand
                    $brand = new Brand();
                    $brand->generateLangs();
                    $brand_langs = $brand->modelLangs;
                    foreach ($brand_langs as $brand_lang) {
                        $brand_lang->title = $parsed_name[ 'name' ];
                    }
                    $brand->remote_id = $parsed_name[ 'remote_id' ];
                    $brand->save(false);
                    return $brand->id;
                }
            }
            return null;
        }
        
        /**
         * Save Product or ProductVariant photoes
         *
         * @param string[] $fotos              Photoes names
         * @param int      $product_id
         * @param int|null $product_variant_id Null if photo for Product
         */
        private function saveFotos(array $fotos, int $product_id, int $product_variant_id = null)
        {
            if (!empty( $fotos )) {
                foreach ($fotos as $foto) {
                    if (empty( $foto )) {
                        continue;
                    }
                    $source_image = Yii::getAlias('@uploadDir') . '/product_images/' . urlencode($foto);
                    if (file_exists($source_image)) {
                        if (( $productImage = ProductImage::find()
                                                          ->andWhere([ 'image' => $foto ])
                                                          ->andWhere([ 'product_id' => $product_id ])
                                                          ->andFilterWhere(
                                                              [ 'product_variant_id' => $product_variant_id ]
                                                          )
                                                          ->one() ) === null
                        ) {
                            copy($source_image, Yii::getAlias('@productsDir') . "/" . $foto);
                            $productImage = new ProductImage();
                            $productImage->product_id = $product_id;
                            $productImage->product_variant_id = $product_variant_id;
                            $productImage->image = $foto;
                            $productImage->save(false);
                        }
                    }
                }
            }
        }
        
        /**
         * Save ProductVariants
         *
         * @param array      $data             ProductVariats data
         * @param float      $product_cost_old Old price
         * @param int        $product_id       Product ID
         * @param array      $category_id      Ca
         * @param float|null $product_cost
         *
         * @return int[] Array of ProductVariants IDs
         * @throws \Exception
         */
        private function saveVariants(
            array $data,
            float $product_cost_old,
            int $product_id,
            array $category_id,
            float $product_cost = null
        ):array
        {
            $MOD_ARRAY = [];
            for ($i = 13; $i < count($data); $i++) {
                if (!empty ( $data[ $i ] )) {
                    $mod_arr = explode('=', $data[ $i ]);
                    $mod_art = $mod_arr[ 0 ];
                    $mod_art_parsed = $this->parseName($mod_art);
                    $variant_filters = explode('*', $mod_arr[ 1 ]);
                    $mod_name = $mod_arr[ 2 ];
                    if (empty( $mod_name )) {
                        $mod_name = $mod_art_parsed[ 'name' ];
                    }
                    $mod_image = $mod_arr[ 3 ];
                    $mod_stock = isset( $mod_arr[ 4 ] ) ? $mod_arr[ 4 ] : 1;
                    $mod_cost = isset( $product_cost ) ? floatval($product_cost) : 0;
                    $mod_old_cost = floatval($product_cost_old);
                    // Check product variant
                    /**
                     * @var ProductVariant $_productVariant
                     */
                    if (( $_productVariant = ProductVariant::find()
                                                           ->joinWith('lang')
                                                           ->andFilterWhere(
                                                               [ 'remote_id' => $mod_art_parsed[ 'remote_id' ] ]
                                                           )
                                                           ->andFilterWhere(
                                                               [ 'product_variant.product_id' => $product_id ]
                                                           )
                                                           ->one() ) === null
                    ) {
                        $_productVariant = new ProductVariant();
                        $_productVariant->product_id = $product_id;
                        if (!empty( $mod_art_parsed[ 'remote_id' ] )) {
                            $_productVariant->remote_id = $mod_art_parsed[ 'remote_id' ];
                        }
                        $_productVariant->generateLangs();
                        $product_variant_langs = $_productVariant->modelLangs;
                        foreach ($product_variant_langs as $product_variant_lang) {
                            $product_variant_lang->title = $mod_name;
                        }
                    } else {
                        if (!empty( $_productVariant->lang )) {
                            $_productVariant->lang->title = $mod_name;
                            $_productVariant->lang->save(false);
                        } else {
                            throw new \Exception(
                                'Product variant with ID ' . $_productVariant->id . ' and lang ' . Language::getCurrent(
                                )->id . ' doesn\'t exist'
                            );
                        }
                    }
                    $_productVariant->product_unit_id = 1;
                    $_productVariant->sku = $mod_art_parsed[ 'name' ];
                    $_productVariant->price = $mod_cost;
                    $_productVariant->price_old = $mod_old_cost;
                    $_productVariant->stock = $mod_stock;
                    
                    if (!empty ( $variant_filters )) {
                        $variants_options = $this->saveFilters($variant_filters, 1, $category_id);
                    }
                    
                    if (isset( $variants_options ) && !empty( $variants_options )) {
                        $_productVariant->options = $variants_options;
                    }
                    
                    /**
                     * @todo set to false
                     */
                    $_productVariant->save(false);
                    
                    $MOD_ARRAY[] = $_productVariant->id;
                    $this->saveFotos([ $mod_image ], $product_id, $_productVariant->id);
                }
            }
            return $MOD_ARRAY;
        }
        
        /**
         * Perform product import
         *
         * @param int  $from  Begin row
         * @param null $limit Row limit
         *
         * @return array|bool Array if OK, false if error
         */
        public function goProducts($from = 0, $limit = null)
        {
            set_time_limit(0);
            
            if (!( $handle = $this->getProductsFile('uploadFileProducts') )) {
                $this->errors[] = 'File not found';
                return false;
            }
            
            $filesize = filesize(Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFileProducts'));
            
            if ($from) {
                fseek($handle, $from);
            }
            
            $j = 0;
            
            $is_utf = ( preg_match(
                '//u',
                file_get_contents(
                    Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFileProducts'),
                    null,
                    null,
                    null,
                    1000000
                )
            ) );
            
            $result_items = [];
            
            while (( empty( $limit ) || $j++ < $limit ) && ( $data = fgetcsv($handle, 10000, ";") ) !== false) {
                try {
                    
                    foreach ($data as &$value) {
                        if (!$is_utf) {
                            $value = iconv('windows-1251', "UTF-8//TRANSLIT//IGNORE", $value);
                        }
                        $value = trim($value);
                    }
                    // будет всегда 19 элементов
                    for ($i = 0; $i <= 18; $i++) {
                        if (!isset ( $data[ $i ] )) {
                            $data[ $i ] = null;
                        }
                    }
                    // 1  Группа (категория)
                    $catalog_names = explode(',', $data[ 0 ]);
                    if (empty ( $catalog_names )) {
                        $result_items[] = "Не указана категория (строка $j)";
                        continue;
                    }
                    
                    // 2  Бренд
                    $brand_name = $data[ 1 ];
                    //                if(empty ( $brand_name )) {
                    //                    $result_items[] = "Не указан бренд (строка $j)";
                    //                    continue;
                    //                }
                    
                    // 3  Название товара
                    $product_name = $data[ 2 ];
                    if (empty ( $product_name )) {
                        $result_items[] = "Не указано наименование товара (строка $j)";
                        continue;
                    }
                    
                    // 5  Описание товара
                    $product_body = $data[ 3 ];
                    
                    // 6  Фильтр
                    $filters = explode('*', $data[ 4 ]);
                    
                    // 11 Цена акция
                    $product_cost_old = floatval($data[ 6 ]);
                    
                    $product_cost = null;
                    // 10 Цена
                    if ($product_cost_old) {
                        $product_cost_old = floatval($data[ 5 ]);
                        $product_cost = floatval($data[ 6 ]);
                    }
                    
                    // 12 Акция
                    $product_discount = (bool) $data[ 7 ];
                    
                    // 13 Сопуд. Тов.
                    $similar = explode(',', $data[ 8 ]);
                    
                    // 14 Новинки
                    $product_new = (bool) $data[ 9 ];
                    
                    // 15 Топ продаж
                    $product_top = (bool) $data[ 10 ];
                    
                    // 17 ВИДЕО КОД
                    $product_video = $data[ 11 ];
                    
                    // 18 Галлерея фото
                    $fotos = [];
                    if (trim($data[ 12 ])) {
                        $fotos = explode(',', trim($data[ 12 ]));
                    }
                    
                    //                    $lang = \Yii::$app->session->get('export_lang', Language::getDefaultLanguage()->id);
                    //                    /**
                    //                     * @var Language $language
                    //                     */
                    //                    $language = Language::find()
                    //                                        ->where([ 'id' => $lang ])
                    //                                        ->one();
                    //                    Language::setCurrent($language->url);
                    $categories = $this->saveCatalog($catalog_names);
                    
                    $brand_id = $this->saveBrand($brand_name);
                    
                    $options = [];
                    if (!empty ( $filters )) {
                        $options = $this->saveFilters($filters, 0, $categories);
                    }
                    $parsed_name = $this->parseName($product_name);
                    /**
                     * @var Product $_product
                     */
                    if (!empty( $parsed_name[ 'remote_id' ] ) && ( $_product = Product::find()
                                                                                      ->joinWith('lang')
                                                                                      ->andFilterWhere(
                                                                                          [ 'remote_id' => $parsed_name[ 'remote_id' ] ]
                                                                                      )
                                                                                      ->one() ) !== null
                    ) {
                        if (!empty( $_product->lang )) {
                            $_product->lang->title = $parsed_name[ 'name' ];
                            $_product->lang->description = $product_body;
                            $_product->lang->save(false);
                        } else {
                            throw new \Exception(
                                'Product with ID ' . $_product->id . ' and lang ' . Language::getCurrent(
                                )->id . ' doesn\'t exist'
                            );
                        }
                    } else {
                        $_product = new Product();
                        $_product->generateLangs();
                        $product_langs = $_product->modelLangs;
                        foreach ($product_langs as $product_lang) {
                            $product_lang->title = $parsed_name[ 'name' ];
                            $product_lang->description = $product_body;
                        }
                    }
                    
                    $is_new_product = empty( $_product->id );
                    
                    $_product->categories = $categories;
                    
                    $_product->brand_id = $brand_id;
                    
                    $_product->video = $product_video;
                    $_product->is_top = $product_top;
                    $_product->is_discount = $product_discount;
                    $_product->is_new = $product_new;
                    if (!empty( $options )) {
                        $_product->options = $options;
                    }
                    
                    if (!empty( $_product->lang )) {
                        $product_name_inserted = $_product->lang->title;
                    } else {
                        $product_name_inserted = $_product->modelLangs[ Language::$current->id ]->title;
                    }
                    
                    if (( $_product->save(false) === false ) || !$_product->transactionStatus) {
                        $result_items[] = 'Product #' . $product_name_inserted . ' not saved' . " (line $j)";
                        continue;
                    }
                    
                    $this->saveFotos($fotos, $_product->id);
                    // нужно для проставления характеристик относящихся к модификациям
                    
                    $this->saveVariants($data, $product_cost_old, $_product->id, $_product->categories, $product_cost);
                    
                    //                    $_product->save(false);
                    
                    $result_items[] = "Product {$product_name_inserted} #{$_product->id} saved (" . ( $is_new_product ? 'new product' : 'exists product' ) . ")" . " (line $j)";
                    
                } catch (\Exception $e) {
                    $result_items[] = $e->getMessage() . '(line ' . $j . ')';
                }
                
            }
            
            $result = [
                'end'       => feof($handle),
                'from'      => ftell($handle),
                'totalsize' => $filesize,
                'items'     => $result_items,
            ];
            
            fclose($handle);
            
            if ($result[ 'end' ]) {
                //                unlink(Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFileProducts'));
            }
            
            return $result;
        }
        
        /**
         * Get import file
         *
         * @param string $file_type
         *
         * @return bool|resource false if File not found and file resource if OK
         */
        private function getProductsFile($file_type)
        {
            $filename = Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@' . $file_type);
            if (!is_file($filename)) {
                $this->errors[] = "File $filename not found";
                return false;
            }
            return fopen($filename, 'r');
        }
        
        /**
         * Save filters
         *
         * @param array $filters       array of filters like [['pol'='мужской'],['god' =
         *                             '2013'],['volume'='25 л']*['size'='49 x 30 x
         *                             20см'],['composition'='600D полиэстер']]
         * @param int   $level         0 for products and 1 for product variant
         * @param int[] $catalog_names array catalogs id
         *
         * @return array
         * @throws \Exception
         */
        private function saveFilters(array $filters, int $level, array $catalog_names):array
        {
            $options = [];
            foreach ($filters as $filter) {
                
                preg_match_all('/\[(.*):(.*)\]/', $filter, $filter);
                
                if (empty( $filter[ 1 ][ 0 ] )) {
                    continue;
                }
                $filter_name = trim($filter[ 1 ][ 0 ]);
                $parsed_group_name = $this->parseName($filter_name);
                
                /**
                 * @var TaxGroup $taxGroup
                 */
                if (!empty( $parsed_group_name[ 'remote_id' ] ) && ( $taxGroup = TaxGroup::find()
                                                                                         ->joinWith('lang')
                                                                                         ->andFilterWhere(
                                                                                             [ 'remote_id' => $parsed_group_name[ 'remote_id' ] ]
                                                                                         )
                                                                                         ->one() ) !== null
                ) {
                    if (!empty( $taxGroup->lang )) {
                        $taxGroup->lang->title = $parsed_group_name[ 'name' ];
                        $taxGroup->lang->save(false);
                    } else {
                        throw new \Exception(
                            'Tax group with ID ' . $taxGroup->id . ' and lang ' . Language::getCurrent(
                            )->id . ' doesn\'t exist'
                        );
                    }
                } else {
                    $taxGroup = new TaxGroup();
                    $taxGroup->generateLangs();
                    $tax_group_langs = $taxGroup->modelLangs;
                    foreach ($tax_group_langs as $tax_group_lang) {
                        $tax_group_lang->title = $parsed_group_name[ 'name' ];
                    }
                    $taxGroup->level = $level;
                    $taxGroup->categories = $catalog_names;
                    $taxGroup->is_filter = false;
                    $taxGroup->save(false);
                }
                $filters_options = explode(',', $filter[ 2 ][ 0 ]);
                foreach ($filters_options as $filter_options) {
                    $parsed_option_name = $this->parseName($filter_options);
                    /**
                     * @var TaxOption $option
                     */
                    
                    if (!empty( $parsed_option_name[ 'remote_id' ] ) && ( $option = TaxOption::find()
                                                                                             ->joinWith('lang')
                                                                                             ->andFilterWhere(
                                                                                                 [ 'remote_id' => $parsed_option_name[ 'remote_id' ] ]
                                                                                             )
                                                                                             ->andFilterWhere(
                                                                                                 [ 'tax_group_id' => $taxGroup->id ]
                                                                                             )
                                                                                             ->one() ) !== null
                    ) {
                        if (!empty( $option->lang )) {
                            $option->lang->value = $parsed_option_name[ 'name' ];
                            $option->lang->save(false);
                        } else {
                            throw new \Exception(
                                'Tax option with ID ' . $option->id . ' and lang ' . Language::getCurrent(
                                )->id . ' doesn\'t exist'
                            );
                        }
                    } else {
                        // Create option
                        $option = new TaxOption();
                        $option->generateLangs();
                        $option_langs = $option->modelLangs;
                        foreach ($option_langs as $option_lang) {
                            $option_lang->value = $parsed_option_name[ 'name' ];
                        }
                        $option->id = $taxGroup->id;
                        $option->save(false);
                    }
                    $options[] = $option->id;
                }
            }
            return $options;
        }
    }
    