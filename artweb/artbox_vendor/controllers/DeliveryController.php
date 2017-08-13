<?php
    
    namespace artweb\artbox\controllers;
    
    use artweb\artbox\models\DeliverySearch;
    use Yii;
    use artweb\artbox\models\Delivery;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\helpers\ArrayHelper;
    
    /**
     * DeliveryController implements the CRUD actions for Delivery model.
     */
    class DeliveryController extends Controller
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
         * Lists all Delivery models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new DeliverySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel'  => $searchModel,
            ]);
        }
        
        /**
         * Displays a single Delivery model.
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
         * Creates a new Delivery model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Delivery();
            $model->generateLangs();
            $parent_items = Delivery::find()
                                    ->with('lang')
                                    ->all();
            $parent_items = ArrayHelper::map($parent_items, 'id', 'lang.title');
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
                'model'        => $model,
                'modelLangs'  => $model->modelLangs,
                'parent_items' => $parent_items,
            ]);
        }
        
        /**
         * Updates an existing Delivery model.
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
            $parent_items = Delivery::find()
                                    ->with('lang')
                                    ->all();
            $parent_items = ArrayHelper::map($parent_items, 'id', 'lang.title');
            
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
                'model'        => $model,
                'modelLangs'  => $model->modelLangs,
                'parent_items' => $parent_items,
            ]);
        }
        
        /**
         * Deletes an existing Delivery model.
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
         * Finds the Delivery model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Delivery the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if(( $model = Delivery::find()
                                  ->with('parent')
                                  ->where([
                                      'id' => $id,
                                  ])
                                  ->one() ) !== NULL
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
