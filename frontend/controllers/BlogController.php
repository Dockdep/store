<?php
    namespace frontend\controllers;
    
    use artweb\artbox\blog\models\BlogArticle;
    use artweb\artbox\blog\models\BlogCategory;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    
    class BlogController extends Controller
    {
        /**
         * @return string
         */
        public function actionIndex()
        {
            return $this->render('index');
        }
        
        public function actionCategory(string $slug)
        {
            $model = $this->findCategory($slug);
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $model->getBlogArticles()
                                     ->with('lang'),
                ]
            );
            return $this->render(
                'category',
                [
                    'model'        => $model,
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        public function actionView(string $slug)
        {
            $model = $this->findArticle($slug);
            return $this->render(
                'view',
                [
                    'model' => $model,
                ]
            );
        }
        
        private function findCategory(string $slug): BlogCategory
        {
            $model = BlogCategory::find()
                                 ->joinWith('lang')
                                 ->where([ 'blog_category_lang.alias' => $slug ])
                                 ->one();
            if (empty( $model )) {
                throw new NotFoundHttpException('Category not found');
            }
            return $model;
        }
        
        private function findArticle(string $slug): BlogArticle
        {
            $model = BlogArticle::find()
                                ->joinWith('lang', 'blogCategory.lang')
                                ->where([ 'blog_article_lang.alias' => $slug ])
                                ->one();
            if (empty( $model )) {
                throw new NotFoundHttpException('Article not found');
            }
            return $model;
        }
    }
    