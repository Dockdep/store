<?php
    
    namespace frontend\widgets;
    
    use artweb\artbox\blog\models\BlogArticleLang;
    use artweb\artbox\blog\models\BlogCategoryLang;
    use artweb\artbox\ecommerce\models\BrandLang;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\CategoryLang;
    use artweb\artbox\ecommerce\models\ProductLang;
    use artweb\artbox\ecommerce\models\TaxOption;
    use artweb\artbox\ecommerce\models\TaxOptionLang;
    use artweb\artbox\event\models\EventLang;
    use artweb\artbox\language\models\Language;
    use artweb\artbox\models\PageLang;
    use yii\base\Widget;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    
    class LinkerWidget extends Widget
    {
        public $languageId = null;
        
        private $languageText = [];
        
        public function init()
        {
            parent::init();
            if ($this->languageId === null) {
                $this->languageId = Language::$current->id;
            }
            $this->languageText = [
                2 => \Yii::t('app', 'рус'),
                3 => \Yii::t('app', 'укр'),
            ];
        }
        
        public function run()
        {
            if ($this->languageId != Language::$current->id) {
                $controller = \Yii::$app->controller;
                $action = $controller->action;
                $urlParam = $controller->id . '/' . $action->id;
                switch ($urlParam) {
                    case 'site/page':
                        $link = $this->buildLink($urlParam, PageLang::className(), 'page_id');
                        break;
                    case 'event/show':
                        $link = $this->buildLink($urlParam, EventLang::className(), 'event_id', 'alias');
                        break;
                    case 'blog/view':
                        $link = $this->buildLink($urlParam, BlogArticleLang::className(), 'blog_article_id');
                        break;
                    case 'blog/category':
                        $link = $this->buildLink($urlParam, BlogCategoryLang::className(), 'blog_category_id');
                        break;
                    case 'brand/view':
                        $link = $this->buildLink($urlParam, BrandLang::className(), 'brand_id');
                        break;
                    case 'catalog/category':
                        /**
                         * @var Category $category
                         */
                        $category = \Yii::$app->request->get('category');
                        $filters = \Yii::$app->request->get('filters', []);
                        if (!empty( $filters )) {
                            $filters = $this->buildFilters($filters);
                        }
                        $link = $this->buildLink(
                            $urlParam,
                            CategoryLang::className(),
                            'category_id',
                            'category',
                            true,
                            $category->lang->alias
                        );
                        if(!empty($filters)) {
                            $link['filters'] = $filters;
                        }
                        break;
                    case 'catalog/product':
                        /**
                         * @var string $product
                         */
                        $product = \Yii::$app->request->get('product');
                        $link = $this->buildLink(
                            $urlParam,
                            ProductLang::className(),
                            'product_id',
                            'product',
                            true,
                            $product
                        );
                        break;
                    default:
                        $link = $this->buildDefault();
                        break;
                }
            } else {
                $link = $this->buildDefault();
            }
            return Html::a(
                $this->languageText[ $this->languageId ],
                $link,
                [
                    'class' => ( ( $this->languageId == Language::$current->id ) ? 'active' : '' ),
                ]
            );
        }
        
        /**
         * Builds link param to be passed to Url::to()
         *
         * @param string $urlParam   <controller>/<action> String
         * @param string $className  Fully classified classname
         * @param string $key        Key to link multilanguage models (foreign key of non multilanguage model)
         * @param string $param      Get param to find alias if not $force
         * @param bool   $force      Whether to find by param, not by $_GET['param']
         * @param string $paramValue Value to search if $force is true
         *
         * @return array
         */
        protected function buildLink(
            string $urlParam,
            string $className,
            string $key,
            string $param = 'slug',
            bool $force = false,
            string $paramValue = null
        ):array
        {
            if ($force) {
                if (empty( $paramValue )) {
                    $slug = $param;
                } else {
                    $slug = $paramValue;
                }
            } else {
                $slug = \Yii::$app->request->get($param);
            }
            $id = $className::find()
                            ->where(
                                [
                                    'language_id' => Language::$current->id,
                                    'alias'       => $slug,
                                ]
                            )
                            ->select($key)
                            ->limit(1)
                            ->scalar();
            $slug_translated = $className::find()
                                         ->select('alias')
                                         ->where(
                                             [
                                                 'language_id' => $this->languageId,
                                                 $key          => $id,
                                             ]
                                         )
                                         ->limit(1)
                                         ->scalar();
            $get_params = \Yii::$app->request->get();
            if (isset( $get_params[ $param ] )) {
                unset( $get_params[ $param ] );
            }
            return array_merge(
                [
                    $urlParam,
                    'language_id' => $this->languageId,
                    $param        => $slug_translated,
                ],
                $get_params
            );
        }
        
        /**
         * Build default link if no pattern matches
         *
         * @return array
         */
        protected function buildDefault():array
        {
            return array_merge(
                [
                    '',
                    'language_id' => $this->languageId,
                ],
                \Yii::$app->request->get()
            );
        }
        
        protected function buildFilters(array $filters):array
        {
            if (isset( $filters[ 'prices' ] )) {
                unset( $filters[ 'prices' ] );
            }
            if (isset( $filters[ 'special' ] )) {
                unset( $filters[ 'special' ] );
            }
            if (isset( $filters[ 'brands' ] ) && !empty( $filters[ 'brands' ] )) {
                $brandFilters = $this->buildBrands($filters[ 'brands' ]);
                unset( $filters[ 'brands' ] );
            }
            if (!empty( $filters )) {
                $filters = $this->buildGroups($filters);
            }
            if (!empty( $brandFilters )) {
                $filters[ 'brands' ] = $brandFilters;
            }
            return $filters;
        }
        
        protected function buildBrands(array $brands):array
        {
            $brandIds = BrandLang::find()
                                 ->select('brand_id')
                                 ->where(
                                     [
                                         'alias'       => $brands,
                                         'language_id' => Language::$current->id,
                                     ]
                                 )
                                 ->column();
            $brandSlugs = BrandLang::find()
                                   ->select('alias')
                                   ->where(
                                       [
                                           'brand_id'    => $brandIds,
                                           'language_id' => $this->languageId,
                                       ]
                                   )
                                   ->column();
            return $brandSlugs;
        }
        
        protected function buildGroups(array $groups):array
        {
            $options = [];
            foreach ($groups as $group) {
                foreach ($group as $option) {
                    $options[] = $option;
                }
            }
            $optionIds = TaxOptionLang::find()
                                      ->select('tax_option_id')
                                      ->where(
                                          [
                                              'alias'       => $options,
                                              'language_id' => Language::$current->id,
                                          ]
                                      )
                                      ->column();
            /**
             * @var TaxOptionLang[] $taxOptions
             */
            $taxOptions = TaxOptionLang::find()
                                       ->where(
                                           [
                                               'tax_option_id' => $optionIds,
                                               'language_id'   => $this->languageId,
                                           ]
                                       )
                                       ->with(['taxOption.taxGroup.langInteractive' => function($query) {
                                           /**
                                            * @var ActiveQuery $query
                                            */
                                           $query->andWhere(['tax_group_lang.language_id' => $this->languageId]);
                                       }])
                                       ->all();
            $groups = [];
            foreach ($taxOptions as $taxOptionLang) {
                if(isset($groups[$taxOptionLang->taxOption->taxGroup->langInteractive->alias])) {
                    $groups[$taxOptionLang->taxOption->taxGroup->langInteractive->alias][] = $taxOptionLang->alias;
                } else {
                    $groups[$taxOptionLang->taxOption->taxGroup->langInteractive->alias] = [$taxOptionLang->alias];
                }
            }
            return $groups;
        }
    }
    