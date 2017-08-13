<?php
    
    namespace artweb\artbox\controllers;
    
    use artweb\artbox\models\SeoCategory;
    use Yii;
    use artweb\artbox\models\SeoDynamic;
    use artweb\artbox\models\SeoDynamicSearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    
    /**
     * SeoDynamicController implements the CRUD actions for SeoDynamic model.
     */
    class SeoDynamicController extends Controller
    {
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessBehavior::className(),
                    'rules' => [
                        'site' => [
                            [
                                'actions' => [
                                    'login',
                                    'error',
                                ],
                                'allow'   => true,
                            ],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class' => VerbFilter::className(),
                
                ],
            ];
        }
        
        /**
         * Lists all SeoDynamic models.
         *
         * @param int $seo_category_id
         *
         * @return mixed
         */
        public function actionIndex($seo_category_id)
        {
            $seo_category = $this->findCategory($seo_category_id);
            $searchModel = new SeoDynamicSearch();
            $dataProvider = $searchModel->search($seo_category_id, Yii::$app->request->queryParams);
            
            return $this->render(
                'index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'seo_category' => $seo_category,
                ]
            );
        }
        
        /**
         * Displays a single SeoDynamic model.
         *
         * @param integer $seo_category_id
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($seo_category_id, $id)
        {
            $seo_category = $this->findCategory($seo_category_id);
            return $this->render(
                'view',
                [
                    'model'        => $this->findModel($id),
                    'seo_category' => $seo_category,
                ]
            );
        }
        
        /**
         * Creates a new SeoDynamic model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $seo_category_id
         *
         * @return mixed
         */
        public function actionCreate($seo_category_id)
        {
            $seo_category = $this->findCategory($seo_category_id);
            $model = new SeoDynamic();
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                $model->seo_category_id = $seo_category_id;
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'index',
                            'seo_category_id' => $seo_category_id,
                        ]
                    );
                }
            }
            return $this->render(
                'create',
                [
                    'model'        => $model,
                    'modelLangs'   => $model->modelLangs,
                    'seo_category' => $seo_category,
                ]
            );
        }
        
        /**
         * Updates an existing SeoDynamic model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $seo_category_id
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($seo_category_id, $id)
        {
            $seo_category = $this->findCategory($seo_category_id);
            $model = $this->findModel($id);
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'index',
                            'seo_category_id' => $seo_category_id,
                        ]
                    );
                }
            }
            return $this->render(
                'update',
                [
                    'model'        => $model,
                    'modelLangs'   => $model->modelLangs,
                    'seo_category' => $seo_category,
                ]
            );
        }
        
        /**
         * Deletes an existing SeoDynamic model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param integer $seo_category_id
         * @param integer $id
         *
         * @return mixed
         */
        public function actionDelete($seo_category_id, $id)
        {
            $this->findModel($id)
                 ->delete();
            
            return $this->redirect(
                [
                    'index',
                    'seo_category_id' => $seo_category_id,
                ]
            );
        }
        
        /**
         * Finds the SeoDynamic model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return SeoDynamic the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = SeoDynamic::find()
                                     ->where([ 'id' => $id ])
                                     ->with('lang')
                                     ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        protected function findCategory($id)
        {
            if (( $model = SeoCategory::find()
                                      ->where([ 'id' => $id ])
                                      ->with('lang')
                                      ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
