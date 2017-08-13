<?php
    
    namespace artweb\artbox\modules\comment\controllers;
    
    use artweb\artbox\modules\comment\models\CommentModel;
    use artweb\artbox\modules\comment\models\CommentModelSearch;
    use artweb\artbox\modules\comment\Module;
    use Yii;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    
    class ManageController extends Controller
    {
        
        /**
         * Returns a list of behaviors that this component should behave as.
         * @return array
         */
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'index'  => [ 'get' ],
                        'update' => [
                            'get',
                            'post',
                        ],
                        'delete' => [ 'post' ],
                    ],
                ],
            ];
        }
        
        /**
         * Lists all comments.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new CommentModelSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $commentModel = Yii::$app->getModule(Module::$name)->commentModelClass;
            
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel'  => $searchModel,
                'commentModel' => $commentModel,
            ]);
        }
        
        /**
         * Updates an existing CommentModel model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
            
            if($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('artbox_comment_success', /*Yii::t('yii2mod.comments', 'Comment has been saved.')*/
                    'Comment has been saved.');
                return $this->redirect([ 'index' ]);
            }
            
            return $this->render('update', [
                'model' => $model,
            ]);
            
        }
        
        /**
         * Deletes an existing CommentModel model.
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
            Yii::$app->session->setFlash('artbox_comment_success', Yii::t('artbox-comment', 'Comment has been deleted.'));
            return $this->redirect([ 'index' ]);
        }
        
        /**
         * Finds the CommentModel model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return CommentModel the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if(( $model = CommentModel::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException(/*Yii::t('yii2mod.comments', 'The requested page does not exist.')*/
                    'The requested page does not exist.');
            }
        }
    }