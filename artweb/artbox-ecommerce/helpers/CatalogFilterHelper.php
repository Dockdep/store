<?php

namespace artweb\artbox\ecommerce\helpers;

use artweb\artbox\ecommerce\models\BrandLang;
use artweb\artbox\ecommerce\models\CategoryLang;
use artweb\artbox\ecommerce\models\Product;
use artweb\artbox\ecommerce\models\ProductLang;
use artweb\artbox\ecommerce\models\ProductVariant;
use artweb\artbox\ecommerce\models\ProductVariantLang;
use artweb\artbox\ecommerce\models\TaxGroup;
use frontend\models\Catalog;
use yii\base\Object;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\elasticsearch\Query;

class CatalogFilterHelper extends Object
{

    public static $optionsList = [];

    /**
     * Get TaxGroups
     *
     * @return array
     */
    public static function optionsTemplate()
    {
        if (empty( static::$optionsList )) {
            return static::$optionsList = ArrayHelper::getColumn(
                TaxGroup::find()
                    ->joinWith('lang')
                    ->where([ 'is_filter' => 'TRUE' ])
                    ->all(),
                'lang.alias'
            );
        } else {
            return static::$optionsList;
        }

    }

    /**
     * Return custom filter-option link
     *
     * @param array  $filter
     * @param string $key
     * @param mixed  $value
     * @param bool   $remove
     *
     * @return array
     */
    public static function getFilterForOption(array $filter, string $key, $value, bool $remove = false)
    {

        $optionsTemplate = self::optionsTemplate();
        array_unshift($optionsTemplate, "special", "brands");

        $result = $filter;

        if (is_array($value)) {
            foreach ($value as $value_key => $value_items) {
                if (!is_array($value_items)) {
                    $value_items = [ $value_items ];
                }
                foreach ($value_items as $value_item) {
                    if ($remove && isset( $result[ $key ] ) && ( $i = array_search(
                            $value_item,
                            $result[ $key ][ $value_key ]
                        ) ) !== false
                    ) {
                        unset( $result[ $key ][ $value_key ][ $i ] );
                        if (empty( $result[ $key ][ $value_key ] )) {
                            unset( $result[ $key ][ $value_key ] );
                        }
                    } else {
                        if (!isset( $result[ $key ][ $value_key ] ) || array_search(
                                $value_item,
                                $result[ $key ][ $value_key ]
                            ) === false
                        ) {
                            $result[ $key ][ $value_key ][] = $value_item;
                        }
                    }
                }
            }
        } else {
            if ($remove && isset( $result[ $key ] ) && ( $i = array_search($value, $result[ $key ]) ) !== false) {
                unset( $result[ $key ][ $i ] );
                if (empty( $result[ $key ] )) {
                    unset( $result[ $key ] );
                }
            } else {
                if (!isset( $result[ $key ] ) || array_search($value, $result[ $key ]) === false) {
                    $result[ $key ][] = $value;
                }
            }
        }

        $filterView = [];

        foreach ($optionsTemplate as $optionKey) {
            if (isset( $result[ $optionKey ] )) {
                $filterView[ $optionKey ] = $result[ $optionKey ];
            }

        }

        return $filterView;
    }



    /**
     * select options for product variants with selected category
     *
     * @param integer $categoryId
     * @param integer $langId
     * @return mixed
     */
    public static function getProductVariantOptions($categoryId,$langId){

        $cacheKey = [
            'OptionsForFilter',
            'categoryId' => $categoryId,
            'langId' =>$langId
        ];
        if (!$OptionsForFilter = \Yii::$app->cache->get($cacheKey)) {
            $OptionsForFilter =  ( new \yii\db\Query() )->distinct()->select('tax_group_lang.alias')
                ->from('product_variant_option')
                ->innerJoin(
                    'tax_option',
                    'product_variant_option.option_id = tax_option.id'
                )
                ->innerJoin(
                    'tax_group',
                    'tax_group.id = tax_option.tax_group_id'
                )
                ->innerJoin(
                    'tax_group_lang',
                    'tax_group_lang.tax_group_id = tax_group.id'
                )
                ->innerJoin(
                    'tax_group_to_category',
                    'tax_group_to_category.tax_group_id = tax_group.id'
                )
                ->where([
                    'tax_group_lang.language_id' => $langId,
                    'tax_group_to_category.category_id' => $categoryId,
                    'tax_group.is_filter' => true
                ])->all();
            $OptionsForFilter = ArrayHelper::getColumn($OptionsForFilter,'alias');
            \Yii::$app->cache->set($cacheKey, $OptionsForFilter, 3600 * 24);
        }

        return $OptionsForFilter;
    }

    /**
     * @param array $params
     * @param $categoryId
     * @param $langId
     * @param bool $in_stock
     * @return mixed
     */
    public static function setQueryParams(array $params, $categoryId, $langId,$in_stock=true)
    {

        if (!empty( $params[ 'special' ] )) {



            if (in_array('new', $params[ 'special' ])) {
                $reform[ 'special' ][ 'is_new' ] = true;
            }
            if (in_array('top', $params[ 'special' ])) {
                $reform[ 'special' ][ 'is_top' ] = true;
            }
            if (in_array('promo', $params[ 'special' ])) {
                $reform[ 'special' ][ 'is_discount' ] = true;
            }

            $params[ 'special' ] = $reform[ 'special' ];
        }

        $last_query = null;
        $productVariantOptions = self::getProductVariantOptions($categoryId,$langId);
        $filters = [];
        foreach ($params as $key => $param) {
            switch ($key) {
                case 'special':
                    unset($params[$key]);
                    self::filterSpecial($param, $filters);
                    break;
                case 'brands':
                    unset($params[$key]);
                    self::filterBrands($param, $filters);
                    break;
                case 'prices':
                    unset($params[$key]);
                    self::filterPrices($param, $filters);
                    break;

            }
        }

        if(!empty($params)){
            self::filterOptions($params, $productVariantOptions, $filters,  $langId);
        }


        $filterQuery = new Query();
        $filterQuery->source('*');
        $filterQuery->from(Catalog::index(), Catalog::type());
        if($in_stock){
            $filterVO['nested']['path'] = 'variants';
            $filterVO['nested']['query']['bool']['must_not'][]['match']['variants.stock'] = 0;
            $filters['bool']['must'][] = $filterVO;
        }
        if($filters){
            $filters['bool']['must'][]['term']['language_id'] = $langId;
            $filterC['nested']['path'] = 'categories';
            $filterC['nested']['query']['bool']['must']['term']['categories.id'] = $categoryId;
            $filters['bool']['must'][] = $filterC;
            $filterQuery->query($filters);
        }
        return $filterQuery;
    }



    /**
     * Tax Option filter
     *
     * @param string[]           $params
     * @param string[]           $productVariantOptions
     * @param array              $filters
     * @param integer            $langId
     * @param bool               $in_stock
     *
     * @return \yii\db\Query
     */
    private static function filterOptions(array $params,$productVariantOptions, array &$filters, $langId, bool $in_stock = true)
    {


        $filterVO['nested']['path'] = 'variants';
        foreach ($params as $key=>$param){
            if(in_array($key, $productVariantOptions)){
                unset($params[$key]);
                $filterVO['nested']['query']['bool']['filter'][]['terms']['variants.options.alias'] = $param;
            }

        }

        if($in_stock){
            $filterVO['nested']['path'] = 'variants';
            $filterVO['nested']['query']['bool']['must_not'][]['match']['variants.stock'] = 0;
        }

        $filters['bool']['must'][] = $filterVO;

        if(!empty($params)) {

            $filterO = [];

            foreach ($params as $key=>$param){

                $filterO['bool']['filter'][]['terms']['options.alias'] = $param;

            }

            $filters['bool']['must'][] = $filterO;
        }


    }



    /**
     * Fill $query with special filters (used in Product)
     *
     * @param array               $params
     * @param array               $filters
     */
    private static function filterSpecial(array $params, array &$filters)
    {
        /**
         * @var string $key
         */

        foreach ($params as $key => $param) {
            $filters['bool']['must'][]['term'][$key] = $param;
        }

    }

    /**
     * Fill query with brands filter
     *
     * @param int[]               $params
     * @param array               $filters
     */
    private static function filterBrands(array $params, array &$filters)
    {


            $filters['bool']['filter'][]['terms']['brand.alias'] = $params;


    }

    /**
     * Fill query with price limits filter
     *
     * @param array               $params
     * @param array               $filters
     */
    private static function filterPrices(array $params, array &$filters)
    {

        if (!empty( $params[ 'min' ] ) && $params[ 'min' ] > 0) {
            $filters['bool']['filter'][]['range']['price']['gte'] = $params[ 'min' ];
        }
        if (!empty( $params[ 'max' ] ) && $params[ 'max' ] > 0) {
            $filters['bool']['filter'][]['range']['price']['lte'] = $params[ 'max' ];
        }

    }

}
