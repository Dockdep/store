<?php
    
    namespace artweb\artbox\controllers;
    
    use Yii;
    use artweb\artbox\models\Event;
    use artweb\artbox\models\EventSearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    
    /**
     * EventController implements the CRUD actions for Event model.
     */
    class EventController extends Controller
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
         * Lists all Event models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new EventSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
        /**
         * Displays a single Event model.
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
         * Creates a new Event model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Event();
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
         * Updates an existing Event model.
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
         * Deletes an existing Event model.
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
         * Finds the Event model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Event the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if(( $model = Event::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
