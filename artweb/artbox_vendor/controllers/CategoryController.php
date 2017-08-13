<?php
    
    namespace artweb\artbox\controllers;
    
    use developeruz\db_rbac\behaviors\AccessBehavior;
    use artweb\artbox\components\artboxtree\ArtboxTreeHelper;
    use Yii;
    use artweb\artbox\modules\catalog\models\Category;
    use artweb\artbox\modules\catalog\models\CategorySearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * CategoryController implements the CRUD actions for Category model.
     */
    class CategoryController extends Controller
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
                        'logout' => [ 'post' ],
                    ],
                ],
            ];
        }
        
        /**
         * Lists all Category models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new CategorySearch();
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
         * Displays a single Category model.
         *
         * @param integer $id
         *
         * @return mixed
         */
        public function actionView($id)
        {
            $model = $this->findModel($id);
            $tree = $model->getParents()
                          ->with('lang')
                          ->all();
            return $this->render(
                'view',
                [
                    'model' => $model,
                    'tree'  => $tree,
                ]
            );
        }
        
        /**
         * Creates a new Category model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new Category();
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return is_null(Yii::$app->request->post('create_and_new')) ? $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ]
                    ) : $this->redirect(array_merge([ 'create' ], Yii::$app->request->queryParams));
                }
            }
            if (!empty( Yii::$app->request->queryParams[ 'parent' ] )) {
                $model->parent_id = Yii::$app->request->queryParams[ 'parent' ];
            }
            return $this->render(
                'create',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'categories' => ArtboxTreeHelper::treeMap(
                        Category::find()
                                ->getTree(),
                        'id',
                        'id',
                        '.'
                    ),
                ]
            );
        }
        
        /**
         * Updates an existing Category model.
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
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
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
                    'categories' => ArtboxTreeHelper::treeMap(
                        Category::find()
                                ->getTree(),
                        'id',
                        'id',
                        '.'
                    ),
                ]
            );
        }
        
        /**
         * Deletes an existing Category model.
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
         * Finds the Category model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return Category the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = Category::find()
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
