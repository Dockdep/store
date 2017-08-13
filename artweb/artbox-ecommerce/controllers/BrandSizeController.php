<?php
    
    namespace artweb\artbox\ecommerce\controllers;
    
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\Category;
    use Yii;
    use artweb\artbox\ecommerce\models\BrandSize;
    use artweb\artbox\ecommerce\models\BrandSizeSearch;
    use yii\helpers\ArrayHelper;
    use yii\helpers\VarDumper;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * BrandSizeController implements the CRUD actions for BrandSize model.
     */
    class BrandSizeController extends Controller
    {
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => [ 'POST' ],
                    ],
                ],
            ];
        }
        
        /**
         * Lists all BrandSize models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new BrandSizeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render(
                'index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        /**
         * Displays a single BrandSize model.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($id)
        {
            return $this->render(
                'view',
                [
                    'model' => $this->findModel($id),
                ]
            );
        }
        
        /**
         * Creates a new BrandSize model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new BrandSize();
            
            $categories = ArrayHelper::map(
                Category::find()
                        ->with('lang')
                        ->asArray()
                        ->all(),
                'id',
                'lang.title'
            );
            
            $brands = ArrayHelper::map(
                Brand::find()
                     ->with('lang')
                     ->asArray()
                     ->all(),
                'id',
                'lang.title'
            );
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(
                    [
                        'view',
                        'id' => $model->id,
                    ]
                );
            } else {
                return $this->render(
                    'create',
                    [
                        'model'      => $model,
                        'categories' => $categories,
                        'brands' => $brands,
                    ]
                );
            }
        }
        
        /**
         * Updates an existing BrandSize model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
    
            $categories = ArrayHelper::map(
                Category::find()
                        ->with('lang')
                        ->asArray()
                        ->all(),
                'id',
                'lang.title'
            );
    
            $brands = ArrayHelper::map(
                Brand::find()
                        ->with('lang')
                        ->asArray()
                        ->all(),
                'id',
                'lang.title'
            );
            
            if (!empty(\Yii::$app->request->post('BrandSize')[ 'categories' ])) {
                $model->unlinkAll('categories', true);
                foreach (\Yii::$app->request->post('BrandSize')[ 'categories' ] as $item) {
                    if ($category = Category::findOne($item)) {
                        $model->link('categories', $category);
                    }
                }
            }
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(
                    [
                        'view',
                        'id' => $model->id,
                    ]
                );
            } else {
                return $this->render(
                    'update',
                    [
                        'model'      => $model,
                        'categories' => $categories,
                        'brands' => $brands,
                    ]
                );
            }
        }
        
        /**
         * Deletes an existing BrandSize model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionDelete($id)
        {
            $this->findModel($id)
                 ->delete();
            
            return $this->redirect([ 'index' ]);
        }
        
        /**
         * Finds the BrandSize model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return BrandSize the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = BrandSize::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
