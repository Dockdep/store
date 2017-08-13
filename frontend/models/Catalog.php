<?php
namespace frontend\models;


/**
 * @property integer $id
 * @property bool    $language_id
 * @property bool    $is_top
 * @property bool    $is_new
 * @property bool    $is_discount
 * @property string  $alias
 * @property string  $title
 * @property string  $full_name
 * @property array   $brand
 * @property array   $categories
 * @property array   $groups
 * @property array   $options
 * @property array   $variants
 */
use Yii;
use yii\db\ActiveQueryInterface;
use yii\elasticsearch\ActiveRecord;
use artweb\artbox\ecommerce\models\Product;
use artweb\artbox\ecommerce\models\ProductVariant;
use artweb\artbox\ecommerce\models\Category;
use artweb\artbox\ecommerce\models\TaxGroup;
use artweb\artbox\ecommerce\models\TaxOption;
use artweb\artbox\ecommerce\models\Brand;
use artweb\artbox\ecommerce\models\ProductImage;

Class Catalog extends ActiveRecord
{

    public static function index(){
        return "catalog";
    }

    public static function type(){
        return "product";
    }



    public function attributes()
    {
        return [
            'id',
            'language_id',
            'is_top',
            'is_new',
            'is_discount',
            'title',
            'full_name',
            'alias',
            'videos',
            'stock',
            'price',
            'brand',
            'categories',
            'groups',
            'options',
            'variants',
        ];
    }

    public function rules()
    {
        return [
            [$this->attributes(), 'safe']
        ];
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping()
    {
        return [
            static::type() => [
                'properties' => [
                    'id'             => ['type' => 'integer'],
                    'language_id'    => ['type' => 'byte', 'index' => 'not_analyzed'],
                    'is_top'         => ['type' => 'boolean'],
                    'is_new'         => ['type' => 'boolean'],
                    'is_discount'    => ['type' => 'boolean'],
                    'title'          => ['type' => 'text', 'index' => 'analyzed'],
                    'full_name'      => ['type' => 'text', 'index' => 'analyzed'],
                    'alias'          => ['type' => 'text'],
                    'videos'         => ['type' => 'integer'],
                    'stock'          => ['type' => 'integer'],
                    'price'          => ['type' => 'double'],
                    'brand'          => [
                        'properties' => [
                            'id'             => ['type' => 'integer', 'index' => 'not_analyzed'],
                            'title'          => ['type' => 'text', 'index' => 'analyzed'],
                            'alias'          => ['type' => 'keyword', 'index' => 'not_analyzed'],
                        ]
                    ],
                    'categories'     => [
                        'type'      => 'nested',
                        'properties' => [
                            'id'            =>  ['type' => 'integer', 'index' => 'analyzed'],
                            'title'            => ['type' => 'text', 'index' => 'analyzed'],
                            'category_synonym' => ['type' => 'keyword', 'index' => 'not_analyzed'],
                        ]
                    ],
                    'options' => [
                        'properties' => [
                            'alias'            => ['type' => 'keyword', 'index' => 'not_analyzed'],
                            'title'            => ['type' => 'text', 'index' => 'analyzed'],
                        ]
                    ],
                    'variants'       => [
                        'type'      => 'nested',
                        'properties' => [
                            'id'             => ['type' => 'integer'],
                            'sku'            => ['type' => 'text', 'index' => 'analyzed'],
                            'price'          => ['type' => 'double'],
                            'price_old'      => ['type' => 'double'],
                            'stock'          => ['type' => 'integer'],
                            'title'          => ['type' => 'keyword', 'index' => 'not_analyzed'],
                            'options' => [
                                'properties' => [
                                    'alias'            => ['type' => 'keyword', 'index' => 'not_analyzed'],
                                    'title'            => ['type' => 'text', 'index' => 'analyzed'],
                                ]
                            ],
                            'image'     => ['type' => 'text'],
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }

    /**
     * Create this model's index
     */
    public static function createIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->createIndex(static::index(), [
            //'settings' => [ /*.....*/],
            'mappings' => static::mapping(),
            //'warmers' => [ /* ... */ ],
            //'aliases' => [ /* ... */ ],
            //'creation_date' => '...'
        ]);
    }

    /**
     * Delete this model's index
     */
    public static function deleteIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index());
    }


    /**
     * @param Product $product
     * @param $columns
     * @return bool|int
     */
    public static function updateRecord($product, $columns){
        try{
            $record = self::get($product->id.$product->lang->language_id);
            foreach($columns as $key => $value){
                $record->$key = $value;
            }

            return $record->update();
        }
        catch(\Exception $e){
            //handle error here
            return false;
        }
    }

    /**
     * @param Product $product
     * @return bool|int
     */
    public static function deleteRecord($product)
    {
        try{
            $record = self::get($product->id.$product->lang->language_id);
            if (empty($record)) {
                return 1;
            }
            $record->delete();
            return 1;
        }
        catch(\Exception $e){
            //handle error here
            return false;
        }
    }


    /**
     * @return ActiveQueryInterface
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }


    /**
     * @return ActiveQueryInterface
     */
    public function getProductVariantImages()
    {
        return $this->hasMany(ProductImage::className(), ['product_variant_id' => 'id']);
    }

    /**
     * @param Product $product
     * @return bool|int
     */
    public static function addRecord(Product $product){

        $id = $product->id.$product->lang->language_id;
        $record = self::get($id);

        if($record == null){
            $record = new self();
            $record->setPrimaryKey($id);
        }

        $record->id          = $product->id;
        $record->language_id = $product->lang->language_id;
        $record->is_top      = $product->is_top;
        $record->is_new      = $product->is_new;
        $record->is_discount = $product->is_discount;
        $record->title       = $product->lang->title;
        $record->full_name   = $product->fullName;
        $record->alias       = $product->lang->alias;
        $record->videos       = empty( $product->videos ) ? 0 : 1 ;
        $record->stock       = self::checkStock($product->variants);
        $record->price       = $product->getMaxPrice();

        $record->brand      = self::prepareBrand($product->getBrand()->one());
        $record->categories = self::prepareCategories($product->getCategories()->all());
        //$record->groups     = self::prepareGroups($product->getGroups()->all());
        $record->options    = self::prepareOptions($product->filterOptions);
        $record->variants   = self::prepareVariants($product->variants);


        $record->save();




    }


    /**
     * @param ProductVariant[] $variants
     * @return int
     */
    public static function checkStock($variants){
        $stock = 0;
        foreach ($variants as $variant){
            if($variant instanceof ProductVariant){
                if($variant->stock > 0){
                    $stock = 1;
                }
            }


        }
        return $stock;
    }

    /**
     * @param Brand $brand
     * @return array
     */
    private static function prepareBrand($brand){

        if($brand != null){

            $productBrand = [
                'id' => $brand->id,
                'title' => $brand->lang->title,
                'alias' => $brand->lang->alias
            ];

            return $productBrand;
        }
        return [];
    }


    /**
     * @param Category[] $categories
     * @return array
     */
    private static function prepareCategories($categories){

        $productCategories = [];
        foreach ($categories as $category){

            $productCategories[] = [
                'id'               => $category->id,
                'title'            => $category->lang->title,
                'category_synonym' => $category->lang->category_synonym

            ];
        }

        return $productCategories;
    }

    /**
     * @param TaxGroup[] $groups
     * @return array
     */
    private static function prepareGroups($groups){
        $productGroups = [];
        foreach ($groups as $group){
            if($group->use_in_name == 1){
                $productGroups[] = [
                    'title'          => $group->lang->title,
                    'alias'          => $group->lang->alias,
                ];
            }

        }

        return $productGroups;
    }

    /**
     * @param TaxOption[] $options
     * @return array
     */
    private static function prepareOptions($options){

        $productOptions = [];

        foreach ($options as $option){
            $productOptions[] = [
                'alias' => $option->lang->alias,
                'title' => $option->lang->value
            ];
        }

        return $productOptions;
    }

    /**
     * @param ProductVariant[] $variants
     * @return array
     */
    private static function prepareVariants($variants){
        $productVariants = [];

        foreach ($variants as $variant){
            if($variant instanceof ProductVariant){
                $productVariants[] = [
                    'id'        => $variant->id,
                    'sku'       => $variant->sku,
                    'price'     => $variant->price,
                    'price_old' => $variant->price_old,
                    'stock'     => $variant->stock,
                    'title'     => $variant->lang->title,
                    'options'   => self::prepareOptions($variant->filterOptions),
                    'image'     => $variant->imageUrl
                ];
            }


        }

        return $productVariants;

    }





}