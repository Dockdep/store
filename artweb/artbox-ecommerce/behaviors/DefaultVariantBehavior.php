<?php
    namespace artweb\artbox\ecommerce\behaviors;
    
    use artweb\artbox\ecommerce\models\ProductImage;
    use artweb\artbox\language\models\Language;
    use artweb\artbox\ecommerce\models\ProductVariantLang;
    use yii\base\Behavior;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\ProductUnit;
    use artweb\artbox\ecommerce\models\ProductStock;
    use artweb\artbox\ecommerce\models\Stock;
    
    /**
     * Class DefaultVariantBehavior
     *
     * @package common/behaviors
     * @property Product $owner
     * @see     ProductVariant
     */
    class DefaultVariantBehavior extends Behavior
    {
        
        /**
         * @todo add default variant image also
         */
        
        /**
         * Catches product's insert event
         *
         * @return array
         */
        public function events()
        {
            return [
                Product::EVENT_AFTER_INSERT => 'addDefaultVariant',
            ];
        }
        
        /**
         * Creates new default product's variant and sets it's to stock
         * marked as default and sets to it unit also marked as default
         */
        public function addDefaultVariant()
        {
            /**
             * @var Stock       $stock
             * @var ProductUnit $defaultUnit
             */
            $defaultVariant = new ProductVariant();
            $defaultVariant->product_id = $this->owner->id;

            /**
             * Gets default unit for variant
             */
//            $defaultUnit = ProductUnit::find()
//                                      ->where(
//                                          [
//                                              'is_default' => true,
//                                          ]
//                                      )
//                                      ->one();
//            $defaultVariant->product_unit_id = $defaultUnit->id;
            $defaultVariant->stock = 1;
            
            $defaultVariant->sku = 'default';
            $defaultVariant->remote_id = time();
            $defaultVariant->save();
            
            /**
             * Saving languages
             */
            $activeLanguageIds = Language::find()
                                         ->select('id')
                                         ->where(
                                             [
                                                 'status' => true,
                                             ]
                                         )
                                         ->asArray()
                                         ->column();
            foreach ($activeLanguageIds as $languageId) {
                $variantLanguage = new ProductVariantLang();
                $variantLanguage->language_id = $languageId;
                $variantLanguage->product_variant_id = $defaultVariant->id;
                $variantLanguage->title = 'default_' . $languageId;
                $variantLanguage->save();
            }
            /**
             * Gets default stock
             */
            $stock = Stock::find()
                          ->one();
            
                        $image = ProductImage::find()
                                             ->where(
                                                 [
                                                     'product_id' => $this->owner->product_id,
                                                 ]
                                             )
                                             ->one();
                                $image->product_variant_id = $defaultVariant->product_variant_id;
                                $image->save();
            
            /**
             * Add a new stock record
             */
            $defaultStock = new ProductStock();
            $defaultStock->product_variant_id = $defaultVariant->id;
            $defaultStock->stock_id = $stock->id;
            $defaultStock->quantity = $defaultVariant->stock;
            $defaultStock->save();
        }
        
    }
    