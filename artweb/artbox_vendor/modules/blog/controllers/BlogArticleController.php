<?php
    
    namespace artweb\artbox\modules\blog\controllers;
    
    use artweb\artbox\modules\blog\models\BlogCategory;
    use artweb\artbox\modules\blog\models\BlogTag;
    use artweb\artbox\modules\catalog\models\Product;
    use Yii;
    use artweb\artbox\modules\blog\models\BlogArticle;
    use artweb\artbox\modules\blog\models\BlogArticleSearch;
    use yii\helpers\ArrayHelper;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\web\Response;
    
    /**
     * BlogArticleController implements the CRUD actions for BlogArticle model.
     */
    class BlogArticleController extends Controller
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
         * Lists all BlogArticle models.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new BlogArticleSearch();
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
         * Displays a single BlogArticle model.
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
         * Creates a new BlogArticle model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new BlogArticle();
            $model->generateLangs();
            
            $categories = ArrayHelper::map(
                BlogCategory::find()
                            ->joinWith('lang')
                            ->all(),
                'id',
                'lang.title'
            );
            
            $tags = ArrayHelper::map(
                BlogTag::find()
                       ->joinWith('lang')
                       ->all(),
                'id',
                'lang.label'
            );
            
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'blogCategories' ] )) {
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'blogCategories' ] as $item) {
                            if ($category = BlogCategory::findOne($item)) {
                                $model->link('blogCategories', $category);
                            }
                        }
                    }
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'blogTags' ] )) {
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'blogTags' ] as $item) {
                            if ($category = BlogTag::findOne($item)) {
                                $model->link('blogTags', $category);
                            }
                        }
                    }
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'products' ] )) {
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'products' ] as $item) {
                            if ($product = Product::findOne($item)) {
                                $model->link('products', $product);
                            }
                        }
                    }
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'blogArticles' ] )) {
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'blogArticles' ] as $item) {
                            if ($article = Product::findOne($item)) {
                                $model->link('blogArticles', $article);
                            }
                        }
                    }
                    
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
                    'categories' => $categories,
                    'tags'       => $tags,
                    'products'   => [],
                    'articles'   => [],
                ]
            );
            
        }
        
        /**
         * Updates an existing BlogArticle model.
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
            
            $categories = ArrayHelper::map(
                BlogCategory::find()
                            ->joinWith('lang')
                            ->all(),
                'id',
                'lang.title'
            );
            
            $tags = ArrayHelper::map(
                BlogTag::find()
                       ->joinWith('lang')
                       ->all(),
                'id',
                'lang.label'
            );
            
            $products = ArrayHelper::map(
                $model->getProducts()
                      ->joinWith('lang')
                      ->asArray()
                      ->all(),
                'id',
                'lang.title'
            );
            
            $articles = ArrayHelper::map(
                $model->getBlogArticles()
                      ->joinWith('lang')
                      ->asArray()
                      ->all(),
                'id',
                'lang.title'
            );
            
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'blogCategories' ] )) {
                        $model->unlinkAll('blogCategories', true);
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'blogCategories' ] as $item) {
                            if ($category = BlogCategory::findOne($item)) {
                                $model->link('blogCategories', $category);
                            }
                        }
                    }
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'blogTags' ] )) {
                        $model->unlinkAll('blogTags', true);
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'blogTags' ] as $item) {
                            if ($tag = BlogTag::findOne($item)) {
                                $model->link('blogTags', $tag);
                            }
                        }
                    }
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'products' ] )) {
                        $model->unlinkAll('products', true);
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'products' ] as $item) {
                            if ($product = Product::findOne($item)) {
                                $model->link('products', $product);
                            }
                        }
                    }
                    
                    if (!empty( \Yii::$app->request->post('BlogArticle')[ 'blogArticles' ] )) {
                        $model->unlinkAll('blogArticles', true);
                        foreach (\Yii::$app->request->post('BlogArticle')[ 'blogArticles' ] as $item) {
                            if ($article = BlogArticle::findOne($item)) {
                                $model->link('blogArticles', $article);
                            }
                        }
                    }
                    
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
                    'categories' => $categories,
                    'tags'       => $tags,
                    'products'   => $products,
                    'articles'   => $articles,
                ]
            );
            
        }
        
        /**
         * Deletes an existing BlogArticle model.
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
         * Finds the BlogArticle model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param integer $id
         *
         * @return BlogArticle the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (( $model = BlogArticle::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        /**
         * @param string $q
         * @param null   $id
         *
         * @return array
         */
        public function actionProductList($q = NULL, $id = NULL)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $out = [
                'results' => [
                    'id'   => '',
                    'text' => '',
                ],
            ];
            if (!is_null($q)) {
                $out[ 'results' ] = Product::find()
                                           ->joinWith('lang')
                                           ->select(
                                               [
                                                   'id',
                                                   'product_lang.title as text',
                                               ]
                                           )
                                           ->where(
                                               [
                                                   'like',
                                                   'product_lang.title',
                                                   $q,
                                               ]
                                           )
                                           ->limit(20)
                                           ->asArray()
                                           ->all();
            } elseif ($id > 0) {
                $out[ 'results' ] = [
                    'id'   => $id,
                    'text' => Product::find()
                                     ->joinWith('lang')
                                     ->where([ 'id' => $id ])
                                     ->one()->title,
                ];
            }
            return $out;
        }
        
        /**
         * @param string  $q
         * @param integer $id
         *
         * @return array
         */
        public function actionArticleList($q = NULL, $id = NULL)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $out = [
                'results' => [
                    'id'   => '',
                    'text' => '',
                ],
            ];
            if (!is_null($q)) {
                $out[ 'results' ] = BlogArticle::find()
                                               ->joinWith('lang')
                                               ->select(
                                                   [
                                                       'blog_article.id as id',
                                                       'blog_article_lang.title as text',
                                                   ]
                                               )
                                               ->where(
                                                   [
                                                       'like',
                                                       'blog_article_lang.title',
                                                       $q,
                                                   ]
                                               )
                                               ->andWhere(
                                                   [
                                                       '!=',
                                                       'blog_article.id',
                                                       $id,
                                                   ]
                                               )
                                               ->limit(20)
                                               ->asArray()
                                               ->all();
            }
            return $out;
        }
    }
