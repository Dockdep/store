<?php
    
    namespace artweb\artbox\ecommerce\controllers;
    
    use artweb\artbox\ecommerce\models\TaxGroupSearch;
    use Yii;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use yii\db\ActiveQuery;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * TaxGroupController implements the CRUD actions for TaxGroup model.
     */
    class TaxGroupController extends Controller
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
         * Lists all TaxGroup models.
         *
         * @param $level integer
         *
         * @return mixed
         */
        public function actionIndex($level)
        {
            $searchModel = new TaxGroupSearch();
            $dataProvider = $searchModel->search(\Yii::$app->request->queryParams, $level);
            /**
             * @var ActiveQuery $query
             */
            $query = $dataProvider->query;
            $query->with('options')
                  ->with('categories');
            
            return $this->render(
                'index',
                [
                    'dataProvider' => $dataProvider,
                    'searchModel'  => $searchModel,
                    'level'        => $level,
                ]
            );
        }
        
        /**
         * Creates a new TaxGroup model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @param $level integer
         *
         * @return mixed
         */
        public function actionCreate($level)
        {
            $model = new TaxGroup();
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->loadLangs(\Yii::$app->request);
                $model->level = $level;
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'index',
                            'level' => $level,
                        ]
                    );
                }
            }
            return $this->render(
                'create',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'level'      => $level,
                ]
            );
        }
        
        /**
         * Updates an existing TaxGroup model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param         $level integer
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($level, $id)
        {
            $model = $this->findModel($id);
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'index',
                            'level' => $level,
                        ]
                    );
                }
            }
            return $this->render(
                'update',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'level'      => $level,
                ]
            );
        }
        
        /**
         * Deletes an existing TaxGroup model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param         $level integer
         * @param integer $id
         *
         * @return mixed
         */
        public function actionDelete($level, $id)
        {
            $this->findModel($id)
                 ->delete();
            return $this->redirect(
                [
                    'index',
                    'level' => $level,
                ]
            );
        }
        
        /**
         * Finds the TaxGroup model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return TaxGroup the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = TaxGroup::find()
                                   ->with('lang')
                                   ->where([ 'id' => $id ])
                                   ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
