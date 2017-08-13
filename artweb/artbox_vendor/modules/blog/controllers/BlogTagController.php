<?php
    
    namespace artweb\artbox\modules\blog\controllers;
    
    use Yii;
    use artweb\artbox\modules\blog\models\BlogTag;
    use artweb\artbox\modules\blog\models\BlogTagSearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * BlogTagController implements the CRUD actions for BlogTag model.
     */
    class BlogTagController extends Controller
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
         * Lists all BlogTag models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new BlogTagSearch();
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
         * Displays a single BlogTag model.
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
         * Creates a new BlogTag model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new BlogTag();
            $model->generateLangs();
            
            if (\Yii::$app->request->isPost) {
                $model->loadLangs(\Yii::$app->request);
                $model->markAttributeDirty('id');
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ]
                    );
                }
            }
            return $this->render(
                'create',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                ]
            );
            
        }
        
        /**
         * Updates an existing BlogTag model.
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
            
            if (Yii::$app->request->isPost) {
                $model->loadLangs(\Yii::$app->request);
                $model->markAttributeDirty('id');
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ]
                    );
                }
            }
            return $this->render(
                'update',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                ]
            );
            
        }
        
        /**
         * Deletes an existing BlogTag model.
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
         * Finds the BlogTag model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return BlogTag the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = BlogTag::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
