<?php
    
    namespace frontend\controllers;

    use artweb\artbox\language\models\Language;
    use frontend\models\SearchForm;
    use yii\helpers\Url;
    use yii\data\ArrayDataProvider;
    use yii\data\Pagination;

    class SearchController extends \yii\web\Controller
    {
        public function actionMain($word)
        {

            $elastic = new SearchForm(
                [
                    'word' => $word,
                ]
            );
            
            if (empty( $word ) || !$elastic->validate()) {
                return $this->redirect(Url::home());
            }

            $page = $paginate = \Yii::$app->request->get('page', []);
            $lang = Language::getCurrent();
            $result  = $elastic->search($page,12,$lang->id);

            $pages  = new Pagination(['totalCount' => $result['hits']['total'] , "defaultPageSize" => 12]);

            $productProvider =  new ArrayDataProvider([
                'allModels' => $result['hits']['hits']
            ]);

            return $this->render('search', [
                'searchModel'  => $elastic,
                'productProvider' => $productProvider,
                'query'        => $elastic->word,
                'pages'       => $pages,
            ]);
        }

    }