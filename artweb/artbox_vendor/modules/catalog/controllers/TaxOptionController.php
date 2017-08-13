<?php
    
    namespace artweb\artbox\modules\catalog\controllers;
    
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use Yii;
    use artweb\artbox\modules\catalog\models\TaxOption;
    use artweb\artbox\modules\catalog\models\TaxOptionSearch;
    use yii\db\ActiveQuery;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * TaxOptionController implements the CRUD actions for TaxOption model.
     */
    class TaxOptionController extends Controller
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
         * Lists all TaxOption models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $group = $this->findGroup(Yii::$app->request->queryParams[ 'group' ]);
            $searchModel = new TaxOptionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            /**
             * @var ActiveQuery $query
             */
            $query = $dataProvider->query;
            $query->andWhere([ 'tax_group_id' => $group->id ]);
            if ($group->level) {
                $query->with('productVariants');
            } else {
                $query->with('products');
            }
            return $this->render(
                'index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'group'        => $group,
                ]
            );
        }
        
        /**
         * Creates a new TaxOption model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $group = $this->findGroup(Yii::$app->request->queryParams[ 'group' ]);
            $model = new TaxOption(
                [
                    'tax_group_id' => $group->id,
                ]
            );
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return is_null(Yii::$app->request->post('create_and_new')) ? $this->redirect(
                        [
                            'index',
                            'group' => $group->id,
                        ]
                    ) : $this->redirect(array_merge([ 'create' ], Yii::$app->request->queryParams));
                }
            }
            return $this->render(
                'create',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'group'      => $group,
                ]
            );
        }
        
        /**
         * Updates an existing TaxOption model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param string $id
         *
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
            $group = $this->findGroup($model->tax_group_id);
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'index',
                            'group' => $group->id,
                        ]
                    );
                }
            }
            return $this->render(
                'update',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'group'      => $group,
                ]
            );
        }
        
        /**
         * Deletes an existing TaxOption model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param string $id
         *
         * @return mixed
         */
        public function actionDelete($id)
        {
            $model = $this->findModel($id);
            $group_id = $model->tax_group_id;
            
            $model->delete();
            
            return $this->redirect(
                [
                    'index',
                    'group' => $group_id,
                ]
            );
        }
        
        /**
         * Finds the TaxOption model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param string $id
         *
         * @return TaxOption the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = TaxOption::find()
                                    ->with('lang')
                                    ->where([ 'id' => $id ])
                                    ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        /**
         * @param int $id
         *
         * @return null|TaxGroup
         * @throws NotFoundHttpException
         */
        protected function findGroup($id)
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
