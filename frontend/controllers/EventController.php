<?php

namespace frontend\controllers;

use artweb\artbox\ecommerce\models\ProductVariant;
use artweb\artbox\event\models\EventLang;
use Yii;
use artweb\artbox\event\models\Event;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;


class EventController extends Controller
{

    public function actionPromo()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Event::find()->where(['is_event'=>1])->joinWith('lang'),

        ]);

        return $this->render('promo', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionSale()
    {

        $model = Event::find()->where(['is_sale'=>1])->joinWith('lang');

        $dataProvider = new ActiveDataProvider([
            'query' =>$model,
        ]);

        return $this->render('sale', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionShow($type,$alias)
    {

        if($type == 'promo') {
            if (($model = Event::find()->joinWith('lang')->where([EventLang::tableName().".alias"=>$alias,'is_event' => 1])->one()) == null) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } elseif($type == 'sale') {
            if (($model = Event::find()->joinWith('lang')->where([EventLang::tableName().".alias"=>$alias,'is_sale' => 1])->one()) == null) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $productProvider = new ArrayDataProvider([
            'allModels' =>$model->getProducts()->with([
                'images',
                'enabledVariants',
                'enabledVariants.image',
                'comments',
                'comments',
                'averageRating',
            ])->joinWith("variants")->where(['!=',ProductVariant::tableName() . '.stock',  0,])->all(),
               'pagination' => [
                'pageSize' => 12,
            ],
        ]);


        return $this->render('event-show', [
            'productProvider' => $productProvider,
            'model' => $model,
            'type' => $type,
        ]);
    }






}