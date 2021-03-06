<?php

namespace common\models;

use artweb\artbox\ecommerce\models\Brand;
use artweb\artbox\ecommerce\models\Category;
use artweb\artbox\ecommerce\models\Product;
use artweb\artbox\ecommerce\models\ProductImage;
use artweb\artbox\ecommerce\models\ProductStock;
use artweb\artbox\ecommerce\models\ProductVariant;
use artweb\artbox\ecommerce\models\ProductVideo;
use artweb\artbox\ecommerce\models\Stock;
use artweb\artbox\ecommerce\models\TaxGroup;
use artweb\artbox\ecommerce\models\TaxOption;
use artweb\artbox\language\models\Language;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Import
 *
 * @package artweb\artbox\ecommerce\models
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
        if (file_exists(Yii::getAlias('@uploadDir/goPrices.lock'))) {
            
            
            return 'Task already executed';
        }
        $ff = fopen(Yii::getAlias('@uploadDir/goPrices.lock'), 'w+');
        fclose($ff);
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
        try{
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
            } catch (\Exception $e) {

                $this->output[] = $e->getMessage() . '(line ' . $j .' ' . $e->getLine(). ')';
            }
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
        unlink(Yii::getAlias('@uploadDir/goPrices.lock'));
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


            if(preg_match_all('/\[(.*)>(.*)\]/', $catalog_name, $out,PREG_SET_ORDER)){

                $count = count($out[0]);
                $parent_id = 0;

                for($i=1; $i<$count; $i++){

                    if(isset($out[0][$i])){

                        // ==== Set category ====
                        if($i == 1){
                            if ( ($category = Category::find()
                                    ->joinWith('lang')
                                    ->andFilterWhere(
                                        [ 'title' =>  $out[0][$i] ]
                                    )
                                    ->andWhere(['parent_id' => 0] )
                                    ->one())  !== null
                            ) {
                                if (!empty( $category->lang )) {
                                    $parent_id =  $category->id;
                                    $category->lang->title = $out[0][$i];
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
                                    $category_lang->title = $out[0][$i];
                                }
                                $category->save(false);

                                $parent_id =  $category->id;

                            }
                        } else {

                            if ( ($category = Category::find()
                                    ->joinWith('lang')
                                    ->andFilterWhere(['title'=> $out[0][$i]])
                                    ->andWhere(['parent_id' => $parent_id] )
                                    ->one())  !== null
                            ) {
                                if (!empty( $category->lang )) {
                                    $category->parent_id = $parent_id;
                                    $category->lang->title = $out[0][$i];
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
                                    $category_lang->title = $out[0][$i];
                                }
                                $category->parent_id = $parent_id;
                                $category->save(false);
                            }

                            $category_id[] = $category->id;

                        }


                    }

                }

            } else if(preg_match_all('/\[(.*)\]/', $catalog_name, $out,PREG_SET_ORDER)){

                if(isset($out[0][1])){

                    // ==== Set category ====
                    if ( ($category = Category::find()
                            ->joinWith('lang')
                            ->andFilterWhere(
                                [ 'title' => $out[0][1] ]
                            )
                            ->one())  !== null
                    ) {

                        if (!empty( $category->lang )) {
                            $category->lang->title =$out[0][1];
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
                            $category_lang->title =$out[0][1];
                        }
                        $category->save(false);
                    }
                    $category_id[] = $category->id;

                }


            } else {

                throw new \Exception(
                    'Wrong category format!'
                );
            }

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


        if (!empty( $brand_name )) {
            /**
             * @var Brand $brand
             */
            if (($brand = Brand::find()
                    ->joinWith('lang')
                    ->andFilterWhere(
                        [ 'title'=> $brand_name ]
                    )
                    ->one())  !== null
            ) {
                if (!empty( $brand->lang )) {
                    $brand->lang->title = $brand_name;
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
                    $brand_lang->title = $brand_name;
                }
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
        for ($i = 14; $i < count($data); $i++) {
            if (!empty ( $data[ $i ] )) {
                $mod_arr = explode('=', $data[ $i ]);
                $mod_art = $mod_arr[ 0 ];
                $variant_filters = explode('*', $mod_arr[ 1 ]);
                $mod_image = $mod_arr[ 3 ];
                $mod_name = $mod_arr[ 2 ];
                if (empty( $mod_name )) {
                    $mod_name = !empty($mod_image) ? $mod_image : $mod_art;
                }
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
                            [ 'sku' => $mod_art]
                        )
                        ->andFilterWhere(
                            [ 'product_variant.product_id' => $product_id ]
                        )
                        ->one() ) === null
                ) {
                    $_productVariant = new ProductVariant();
                    $_productVariant->product_id = $product_id;
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
                $_productVariant->sku = $mod_art;
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
        if (file_exists(Yii::getAlias('@uploadDir/goProducts.lock'))) {

            return 'Task already executed';
        }
        $ff = fopen(Yii::getAlias('@uploadDir/goProducts.lock'), 'w+');

        fclose($ff);

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
//
//            $connection = Yii::$app->getDb();
//
//
//
//            $connection->createCommand()->dropForeignKey('product_option_tax_option_tax_option_id_fk','product_option')->execute();
//            $connection->createCommand()->dropForeignKey('product_option_product_product_id_fk','product_option')->execute();
//            $connection->createCommand()->dropForeignKey('product_variant_option_tax_option_tax_option_id_fk','product_variant_option')->execute();
//            $connection->createCommand()->dropForeignKey('product_variant_option_product_variant_product_variant_id_fk','product_variant_option')->execute();
//            $connection->createCommand()->dropPrimaryKey('product_option_pkey','product_option')->execute();
//            $connection->createCommand()->dropForeignKey('product_variant_option_pkey','product_variant_option')->execute();
//

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
                $catalog_names = explode('*', $data[ 0 ]);
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
                $product_body_ru = $data[ 3 ];

                $product_body_ua = $data[ 4 ];


                // 6  Фильтр
                $filters = explode('*', $data[ 5 ]);

                // 11 Цена акция
                $product_cost_old = floatval($data[ 7 ]);

                $product_cost = null;
                // 10 Цена
                if ($product_cost_old) {
                    $product_cost_old = floatval($data[ 6 ]);
                    $product_cost = floatval($data[ 7 ]);
                }

                // 12 Акция
                $product_discount = (bool) $data[ 8 ];

                // 13 Сопуд. Тов.
                $similar = explode(',', $data[ 9 ]);

                // 14 Новинки
                $product_new = (bool) $data[ 10 ];

                // 15 Топ продаж
                $product_top = (bool) $data[ 11 ];

                // 17 ВИДЕО КОД
                $product_video = $data[ 12 ];

                // 18 Галлерея фото
                $fotos = [];
                if (trim($data[ 13 ])) {
                    $fotos = explode(',', trim($data[ 13 ]));
                }

                $categories = $this->saveCatalog($catalog_names);

                $brand_id = $this->saveBrand($brand_name);

                $options = [];
                if (!empty ( $filters )) {
                    $options = $this->saveFilters($filters, 0, $categories);
                }
                /**
                 * @var Product $_product
                 */
                if ( ($_product = Product::find()->joinWith('lang')->andFilterWhere([ 'title' => $product_name ])->one()) !== null) {
                    if (!empty( $_product->lang )) {
                        $_product->lang->title = $product_name;
                        $_product->lang->description = $product_body_ru;
                        $_product->lang->save(false);
                    } else {
                        throw new \Exception(
                            'Product with ID ' . $_product->id . ' and lang ' . Language::getCurrent(
                            )->id . ' doesn\'t exist'
                        );
                    }
                } else {
                    $_product = new Product();
                    $_product->detachBehavior('defaultVariant');
                    $_product->generateLangs();
                    $product_langs = $_product->modelLangs;
                    foreach ($product_langs as $product_lang) {
                        $product_lang->title = $product_name;
                        if($product_lang->language_id == 2){
                            $product_lang->description =  $product_body_ru;
                        } elseif($product_lang->language_id == 3) {
                            $product_lang->description =  isset($product_body_ua) ? $product_body_ua : $product_body_ru;
                        } else {
                            $product_lang->description =  $product_body_ru;
                        }

                    }
                }

                $is_new_product = empty( $_product->id );

                $_product->categories = $categories;

                $_product->brand_id = $brand_id;




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
                $this->saveVideo($product_video,$_product);
                $this->saveFotos($fotos, $_product->id);
                // нужно для проставления характеристик относящихся к модификациям

                $this->saveVariants($data, $product_cost_old, $_product->id, $_product->categories, $product_cost);

                //                    $_product->save(false);

                $result_items[] = "Product {$product_name_inserted} #{$_product->id} saved (" . ( $is_new_product ? 'new product' : 'exists product' ) . ")" . " (line $j)";

            } catch (\Exception $e) {

                $result_items[] = $e->getMessage() . '(line ' . $j .' ' . $e->getLine(). ')';
            }

        }
//            $connection->createCommand()->addPrimaryKey('product_variant_option_pkey','product_variant_option',['product_variant_id', 'option_id'])->execute();
//            $connection->createCommand()->addPrimaryKey('product_option_pkey','product_option',['product_id', 'option_id'])->execute();
//            $connection->createCommand()->addForeignKey('product_variant_option_product_variant_product_variant_id_fk','product_variant_option','product_variant_id','product_variant','id')->execute();
//            $connection->createCommand()->addForeignKey('product_variant_option_tax_option_tax_option_id_fk','product_variant_option','option_id','tax_option','id')->execute();
//            $connection->createCommand()->addForeignKey('product_option_product_product_id_fk','product_option','product_id','product','id','CASCADE','CASCADE' )->execute();
//            $connection->createCommand()->addForeignKey('product_option_tax_option_tax_option_id_fk','product_option','option_id', 'tax_option', 'id','CASCADE','CASCADE' )->execute();




        $result = [
            'end'       => feof($handle),
            'from'      => ftell($handle),
            'totalsize' => $filesize,
            'items'     => $result_items,
        ];

        fclose($handle);

        if ($result[ 'end' ]) {


                unlink(Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFileProducts'));


        }
        unlink(Yii::getAlias('@uploadDir/goProducts.lock'));
        return $result;
    }


    /**
     * @param string $product_video
     * @param Product $product
     */
    public function saveVideo($product_video,$product){
        $videos = explode(',',$product_video);
        ProductVideo::deleteAll(['product_id'=>$product->id]);
        foreach($videos as $video){
            $videoModel = new ProductVideo();
            $videoModel->product_id = $product->id;
            $videoModel->url = $video;
            $videoModel->save();
        }
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

            /**
             * @var TaxGroup $taxGroup
             */
            if ( ($taxGroup = TaxGroup::find()
                    ->joinWith('lang')
                    ->andFilterWhere(
                        [ 'alias' => $filter_name]
                    )
                    ->one())  !== null
            ) {
                if (!empty( $taxGroup->lang )) {
                    $taxGroup->lang->title = !empty($taxGroup->lang->title) ? $taxGroup->lang->title : $filter_name;
                    $taxGroup->lang->save(false);
                } else {
                    throw new \Exception(
                        'Tax group with ID ' . $taxGroup->id . ' and lang ' . Language::getCurrent()->id . ' doesn\'t exist'
                    );
                }
            } else {

                $taxGroup = new TaxGroup();
                $taxGroup->generateLangs();
                $tax_group_langs = $taxGroup->modelLangs;
                foreach ($tax_group_langs as $tax_group_lang) {
                    $tax_group_lang->title = $filter_name;
                }

                $taxGroup->level = $level;
                $taxGroup->categories = $catalog_names;
                $taxGroup->is_filter = false;
                $taxGroup->save(false);
            }
            $filters_options = explode(',', $filter[ 2 ][ 0 ]);
            foreach ($filters_options as $filter_options) {
                /**
                 * @var TaxOption $option
                 */

                if (($option = TaxOption::find()
                        ->joinWith('lang')
                        ->andFilterWhere(
                            [ 'value' => $filter_options ]
                        )
                        ->andFilterWhere(
                            [ 'tax_group_id' => $taxGroup->id ]
                        )
                        ->one())  !== null
                ) {
                    if (!empty( $option->lang )) {
                        $option->lang->value = $filter_options;
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
                        $option_lang->value = $filter_options;
                    }
                    $option->tax_group_id = $taxGroup->id;
                    $option->save(false);
                }
                $options[] = $option->id;
            }
        }
        return $options;
    }
}
