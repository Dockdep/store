<?php
    namespace frontend\controllers;
    
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    
    class VideoController extends Controller
    {
        /**
         * @return string
         */
        public function actionIndex()
        {
            return $this->render('index');
        }
        
        public function actionList()
        {
            $query = ProductVideo::find()
                                 ->where([ 'product_id' => null, ])
                                 ->orderBy(
                                     [
                                         'is_main' => SORT_DESC,
                                         'is_display' => SORT_DESC,
                                     ]
                                 );
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                ]
            );
            return $this->render(
                'list',
                [
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        public function actionView(int $id)
        {
            $model = $this->findVideo($id);
            return $this->render(
                'view',
                [
                    'model' => $model,
                ]
            );
        }
        
        private function findVideo(int $id): ProductVideo
        {
            /**
             * @var ProductVideo $model
             */
            $model = ProductVideo::find()
                                 ->where([ 'id' => $id ])
                                 ->one();
            if (empty( $model )) {
                throw new NotFoundHttpException('Video not found');
            }
            return $model;
        }
    }
    