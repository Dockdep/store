<?php
    namespace frontend\controllers;
    
    use artweb\artbox\ecommerce\helpers\ProductHelper;
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\Product;
    use yii\db\ActiveQuery;
    use yii\web\Controller;
    
    class BrandController extends Controller
    {
        /**
         * @return string
         */
        public function actionIndex()
        {
            $brands = Brand::find()
                           ->where(
                               [
                                   'not',
                                   [ 'image' => null ],
                               ]
                           )
                           ->with('lang')
                           ->all();
            return $this->render(
                'index',
                [
                    'brands' => $brands,
                ]
            );
        }
        
        /**
         * @param string $slug Brand slug
         *
         * @return string
         */
        public function actionView(string $slug)
        {
            /**
             * @var Brand     $model
             * @var Product[] $products
             */
            $model = Brand::find()
                          ->joinWith('lang')
                          ->where([ 'brand_lang.alias' => $slug ])
                          ->one();
            $products = $model->getProducts()
                              ->innerJoinWith('enabledVariant')
                              ->with(
                                  [
                                      'categories' => function ($query) {
                                          /**
                                           * @var ActiveQuery $query
                                           */
                                          $query->with('lang')
                                                ->with('parentAR.lang');
                                      },
                                  ]
                              )
                              ->all();
            $categoryList = ProductHelper::groupByCategories($products);
            return $this->render(
                'view',
                [
                    'model'        => $model,
                    'categoryList' => $categoryList,
                ]
            );
        }
    }
    