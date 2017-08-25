<?php
    
    namespace artweb\artbox\ecommerce\console;
    
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use artweb\artbox\ecommerce\models\TaxOption;
    use Faker\Factory;
    use Faker\Generator;
    use yii\console\Controller;
    use yii\helpers\Console;
    
    /**
     * Class GenerateController to generate ArtBox fake data
     *
     * @package console\controllers
     */
    class GenerateController extends Controller
    {
        /**
         * Faker Generator locales
         *
         * @var array
         */
        private $locales = [
            2 => 'ru_RU',
            3 => 'uk_UA',
        ];
        
        /**
         * Faker Generators instances
         *
         * @var Generator[] $fakers
         */
        private $fakers = [];
        
        /**
         * Create faker Generators for all $locales
         *
         * @param \yii\base\Action $action
         *
         * @return bool
         */
        public function beforeAction($action)
        {
            $parent = parent::beforeAction($action);
            $fakers = [];
            foreach ($this->locales as $locale_id => $locale) {
                $fakers[ $locale_id ] = Factory::create($locale);
            }
            $this->fakers = $fakers;
            return $parent;
        }
        
        /**
         * Generate fake Brands
         *
         * @param int $count Brands count
         *
         * @return int
         */
        public function actionBrand(int $count = 10): int
        {
            /**
             * @var Brand[] $models
             */
            $models = [];
            $fakers = $this->fakers;
            for ($i = 0; $i < $count; $i++) {
                $models[ $i ] = \Yii::createObject(Brand::className());
                $models[ $i ]->generateLangs();
                foreach ($models[ $i ]->modelLangs as $language_id => $modelLang) {
                    $modelLang->title = $fakers[ $language_id ]->company;
                }
                if ($models[ $i ]->save() && $models[ $i ]->transactionStatus) {
                    $title = $this->ansiFormat($models[ $i ]->modelLangs[ 2 ]->title, Console::FG_YELLOW);
                    $id = $this->ansiFormat($models[ $i ]->id, Console::FG_YELLOW);
                    echo "Brand '$title' inserted under $id ID.\n";
                };
            }
            return 0;
        }
        
        /**
         * Generate fake categories
         *
         * @param int $count Category count
         *
         * @return int
         */
        public function actionCategory(int $count = 10):int
        {
            /**
             * @var Category[] $models
             */
            $models = [];
            $fakers = $this->fakers;
            for ($i = 0; $i < $count; $i++) {
                $models[ $i ] = \Yii::createObject(
                    [
                        'class'     => Category::className(),
                        'depth'     => 0,
                        'parent_id' => 0,
                    ]
                );
                $models[ $i ]->generateLangs();
                foreach ($models[ $i ]->modelLangs as $language_id => $modelLang) {
                    $modelLang->title = ucfirst($fakers[ $language_id ]->word);
                }
                if ($models[ $i ]->save() && $models[ $i ]->transactionStatus) {
                    $title = $this->ansiFormat($models[ $i ]->modelLangs[ 2 ]->title, Console::FG_YELLOW);
                    $id = $this->ansiFormat($models[ $i ]->id, Console::FG_YELLOW);
                    echo "Category '$title' inserted under $id ID.\n";
                };
            }
            return 0;
        }
        
        /**
         * Generate fake products with variants, categories and tax options
         *
         * @param int $count Product count
         *
         * @return int
         */
        public function actionProduct(int $count = 10):int
        {
            /**
             * @var Product[] $models
             */
            $models = [];
            $fakers = $this->fakers;
            $brands = Brand::find()
                           ->limit(20)
                           ->asArray()
                           ->column();
            $categories = Category::find()
                                  ->limit(100)
                                  ->asArray()
                                  ->column();
            $product_options = TaxOption::find()
                                        ->joinWith('taxGroup')
                                        ->where([ 'tax_group.level' => TaxGroup::GROUP_PRODUCT ])
                                        ->limit(50)
                                        ->asArray()
                                        ->column();
            $variant_options = TaxOption::find()
                                        ->joinWith('taxGroup')
                                        ->where([ 'tax_group.level' => TaxGroup::GROUP_VARIANT ])
                                        ->limit(50)
                                        ->asArray()
                                        ->column();
            for ($i = 0; $i < $count; $i++) {
                $models[ $i ] = \Yii::createObject(
                    [
                        'class'    => Product::className(),
                        'brand_id' => $fakers[ 2 ]->randomElement($brands),
                    ]
                );
                $models[ $i ]->setCategories(
                    $fakers[ 2 ]->randomElements($categories, $fakers[ 2 ]->randomDigitNotNull)
                );
                $models[ $i ]->setOptions(
                    $fakers[ 2 ]->randomElements($product_options, $fakers[ 2 ]->randomDigitNotNull)
                );
                $models[ $i ]->generateLangs();
                foreach ($models[ $i ]->modelLangs as $language_id => $modelLang) {
                    $modelLang->title = ucfirst($fakers[ $language_id ]->word);
                }
                if ($models[ $i ]->save() && $models[ $i ]->transactionStatus) {
                    $title = $this->ansiFormat($models[ $i ]->modelLangs[ 2 ]->title, Console::FG_YELLOW);
                    $id = $this->ansiFormat($models[ $i ]->id, Console::FG_YELLOW);
                    echo "Product '$title' inserted under $id ID.\n";
                    $variant_count = $fakers[ 2 ]->numberBetween(4, 10);
                    for ($j = 0; $j < $variant_count; $j++) {
                        /**
                         * @var ProductVariant $variant
                         */
                        $variant = \Yii::createObject(
                            [
                                'class'           => ProductVariant::className(),
                                'product_id'      => $models[ $i ]->id,
                                'sku'             => $fakers[ 2 ]->uuid,
                                'price'           => $fakers[ 2 ]->randomFloat(2, 100, 10000),
                                'stock'           => 10,
                            ]
                        );
                        $variant->setOptions(
                            $fakers[ 2 ]->randomElements($variant_options, $fakers[ 2 ]->randomDigitNotNull)
                        );
                        $variant->generateLangs();
                        foreach ($variant->modelLangs as $variant_language_id => $variantLang) {
                            $variantLang->title = ucfirst($fakers[ $variant_language_id ]->word);
                        }
                        if ($variant->save() && $variant->transactionStatus) {
                            $variant_title = $this->ansiFormat($variant->modelLangs[ 2 ]->title, Console::FG_YELLOW);
                            $variant_id = $this->ansiFormat($variant->id, Console::FG_YELLOW);
                            echo "Variant '$variant_title' inserted under $variant_id ID for product $title.\n";
                        }
                    }
                };
            }
            return 0;
        }
        
        /**
         * Generate fake tax groups with tax options.
         *
         * @param int $count_product Tax Groups for Product
         * @param int $count_variant Tax Groups for ProductVariant
         *
         * @return int
         */
        public function actionTax(int $count_product = 10, int $count_variant = 10):int
        {
            $categories = Category::find()
                                  ->asArray()
                                  ->column();
            $this->generateTax(TaxGroup::GROUP_PRODUCT, $count_product, $categories);
            $this->generateTax(TaxGroup::GROUP_VARIANT, $count_variant, $categories);
            return 0;
        }
        
        /**
         * Generates tax groups amd tax options for actionTax
         *
         * @param int   $level      Tax Group for Product or ProductVariant
         * @param int   $count      Tax Group count
         * @param array $categories Categories for Tax Groups
         *
         * @see GenerateController::actionTax()
         */
        private function generateTax(int $level, int $count, array $categories = [])
        {
            $count_option = 10;
            $fakers = $this->fakers;
            /**
             * @var TaxGroup[] $models
             */
            $models = [];
            for ($i = 0; $i < $count; $i++) {
                $models[ $i ] = \Yii::createObject(
                    [
                        'class'     => TaxGroup::className(),
                        'is_filter' => true,
                        'display'   => true,
                    ]
                );
                $models[ $i ]->level = $level;
                $models[ $i ]->setCategories($categories);
                $models[ $i ]->generateLangs();
                foreach ($models[ $i ]->modelLangs as $language_id => $modelLang) {
                    $modelLang->title = ucfirst($fakers[ $language_id ]->word);
                }
                if ($models[ $i ]->save() && $models[ $i ]->transactionStatus) {
                    for ($j = 0; $j < $count_option; $j++) {
                        /**
                         * @var TaxOption $option
                         */
                        $option = \Yii::createObject(
                            TaxOption::className()
                        );
                        $option->tax_group_id = $models[ $i ]->id;
                        $option->generateLangs();
                        foreach ($option->modelLangs as $option_language_id => $taxOptionLang) {
                            $taxOptionLang->value = ucfirst($fakers[ $option_language_id ]->word);
                        }
                        $option->save();
                    }
                    $title = $this->ansiFormat($models[ $i ]->modelLangs[ 2 ]->title, Console::FG_YELLOW);
                    $id = $this->ansiFormat($models[ $i ]->id, Console::FG_YELLOW);
                    $element = $this->ansiFormat(
                        ( $level === TaxGroup::GROUP_PRODUCT ) ? 'Product' : 'Variant',
                        Console::FG_RED
                    );
                    echo "Category '$title' inserted for $element under $id ID.\n";
                };
            }
        }
    }
    