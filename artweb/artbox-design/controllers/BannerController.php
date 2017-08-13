<?php
    
    namespace artweb\artbox\design\controllers;
    
    use artweb\artbox\design\models\BannerLang;
    use Yii;
    use artweb\artbox\design\models\Banner;
    use artweb\artbox\design\models\BannerSearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    
    /**
     * BannerController implements the CRUD actions for Banner model.
     */
    class BannerController extends Controller
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
         * Lists all Banner models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new BannerSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
        /**
         * Displays a single Banner model.
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
         * Creates a new Banner model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Banner();
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
         * Updates an existing Banner model.
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
         * Deletes an existing Banner model.
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
    
        public function actionDeleteImage($id, $lang_id)
        {
            /**
             * @var BannerLang $model
             */
            $model = BannerLang::find()->where(['banner_id' => $id, 'language_id' => $lang_id])->one();
            if(empty($model)) {
                throw new NotFoundHttpException();
            }
            $model->image = null;
            $model->updateAttributes(['image']);
            return true;
        }
        
        /**
         * Finds the Banner model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Banner the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if(( $model = Banner::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
