<?php
    
    namespace artweb\artbox\controllers;
    
    use Yii;
    use artweb\artbox\models\Slider;
    use artweb\artbox\models\SliderSearch;
    use yii\db\ActiveQuery;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    
    /**
     * SliderController implements the CRUD actions for Slider model.
     */
    class SliderController extends Controller
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
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => [ 'POST' ],
                    ],
                ],
            ];
        }
        
        /**
         * Lists all Slider models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new SliderSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            /**
             * @var ActiveQuery $query
             */
            $query = $dataProvider->query;
            $query->with('sliderImages');
            
            return $this->render(
                'index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        /**
         * Creates a new Slider model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Slider();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(
                    [
                        'index',
                    ]
                );
            } else {
                
                return $this->render(
                    'create',
                    [
                        'model' => $model,
                    ]
                );
            }
        }
        
        /**
         * Updates an existing Slider model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(
                    [
                        'index',
                    ]
                );
            } else {
                return $this->render(
                    'update',
                    [
                        'model' => $model,
                    ]
                );
            }
        }
        
        /**
         * Deletes an existing Slider model.
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
         * Finds the Slider model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Slider the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = Slider::findOne($id) ) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
