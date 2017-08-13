<?php
    
    namespace artweb\artbox\design\controllers;
    
    use Yii;
    use artweb\artbox\design\models\Bg;
    use artweb\artbox\design\models\BgSearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    
    /**
     * BgController implements the CRUD actions for Bg model.
     */
    class BgController extends Controller
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
         * Lists all Bg models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new BgSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
        /**
         * Displays a single Bg model.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($id)
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        
        /**
         * Creates a new Bg model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Bg();
            $model->generateLangs();
            
            if($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if($model->save() && $model->transactionStatus) {
                    return $this->redirect([
                        'view',
                        'id' => $model->id,
                    ]);
                }
            }
            return $this->render('create', [
                'model'       => $model,
                'modelLangs' => $model->modelLangs,
            ]);
        }
        
        /**
         * Updates an existing Bg model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
            $model->generateLangs();
            
            if($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if($model->save() && $model->transactionStatus) {
                    return $this->redirect([
                        'view',
                        'id' => $model->id,
                    ]);
                }
            }
            return $this->render('update', [
                'model'       => $model,
                'modelLangs' => $model->modelLangs,
            ]);
        }
        
        /**
         * Deletes an existing Bg model.
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
         * Finds the Bg model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Bg the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if(( $model = Bg::find()
                            ->where([ 'id' => $id ])
                            ->with('lang')
                            ->one() ) !== NULL
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
