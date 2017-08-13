<?php

namespace frontend\controllers;

use artweb\artbox\ecommerce\helpers\CatalogFilterHelper;
use artweb\artbox\ecommerce\helpers\FilterHelper;
use artweb\artbox\ecommerce\models\Brand;
use artweb\artbox\ecommerce\models\Category;
use artweb\artbox\ecommerce\models\ProductVariant;
use artweb\artbox\ecommerce\widgets\specialProducts;
use artweb\artbox\language\models\Language;
use artweb\artbox\models\Feedback;
use frontend\models\Catalog;
use yii\db\ActiveQuery;
use yii\web\Controller;
class AjaxController extends Controller
{
    public function actionFeedback() {
        $response = \Yii::$app->response;
        $response->format = $response::FORMAT_JSON;
        $request = \Yii::$app->request;
        $model = new Feedback([
            'scenario' => Feedback::SCENARIO_FEEDBACK,
        ]);
        if($model->load($request->post())) {
            if($model->validate()) {
                $model->save(false);
                return [
                    'result' => 'Запрос успешно отправлен.',
                ];
            } else {
                return [
                    'error' => 'Validation failed',
                    'result' => [
                        'errors' => $model->getFirstErrors(),
                    ],
                ];
            }
        }
        $response->statusCode = 400;
        $response->statusText = 'Empty request';
        return ['error' => 'Empty request'];
    }


    public function actionNew(){
        return specialProducts::widget(['type' => 'new']);
    }

    public function actionTop(){
        return specialProducts::widget(['type' => 'top']);
    }

    public function actionProm(){
        return specialProducts::widget(['type' => 'promo']);
    }


    public function actionCount(){
        //throw new NotFoundHttpException('Not available now');
        $filter = \Yii::$app->request->get('info');

        $id = \Yii::$app->request->get('category');

        if (!empty( $filter[ 'special' ] )) {

            $params = $filter[ 'special' ];
            unset($filter[ 'special' ]);
            if (in_array('new', $params)) {
                $filter[ 'special' ][ 'is_new' ] = true;
            }
            if (in_array('top', $params)) {
                $filter[ 'special' ][ 'is_top' ] = true;
            }
            if (in_array('promo', $params)) {
                $filter[ 'special' ][ 'is_discount' ] = true;
            }
        }

        if(!empty( $filter[ 'brands' ] )) {
            $brands = Brand::find()
                ->select('id')
                ->joinWith('lang')
                ->where([
                    'in',
                    'alias',
                    $filter[ 'brands' ],
                ])
                ->all();
            $filter[ 'brands' ] = [ ];
            foreach($brands as $brand) {
                $filter[ 'brands' ][] = $brand->id;
            }
        }

        if(!empty($filter)){
            $category = Category::findOne($id);
            return $this->findItem($category,$filter);
        }

    }





    public function findItem($category,$params){
        $lang = Language::getCurrent();
        $catalog = CatalogFilterHelper::setQueryParams($params, $category->id, $lang->id);
        return $catalog['hits']['total'];

    }
}