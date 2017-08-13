<?php
namespace backend\controllers;

use common\models\Setting;
use yii\web\Controller;

/**
 * Site controller
 */
class SettingController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $post = \Yii::$app->request->post('refresh');
        if(!empty($post)){

            switch ($post) {

                case "cache":

                    if(\Yii::$app->cache->flush()){
                        \Yii::$app->session->setFlash('success', "Кеш был успешно обновлен");
                    } else{
                        \Yii::$app->session->setFlash('error', "При обновлении кеша произошла ошибка");
                    }
                    header("Location: /admin/setting");
                    exit;
                    break;
                case "elastic-search":
                    $path = \Yii::getAlias('@projectRoot');
                    $date = new \DateTime('NOW');
                    $setting = Setting::find()->where(['name'=> Setting::ELASTIC_SEARCH])->one();
                    if($setting !=  null  && (int)$setting->value < $date->getTimestamp() ){
                        $setting->name = Setting::ELASTIC_SEARCH;
                        $setting->value = (string)$date->modify('+ 1 hours')->getTimestamp();
                        $setting->save();
                        $comand = "php /opt/extremstyle.ua/yii elasticsearch/cache-products  > /dev/null &";
                        exec($comand);
                        \Yii::$app->session->setFlash('success', "Elastic Search заустил обновление, это займет от 20 до 40 минут");
                    } else if($setting ==  null) {
                        $setting = new Setting();
                        $setting->name = Setting::ELASTIC_SEARCH;
                        $setting->value = (string)$date->modify('+ 1 hours')->getTimestamp();
                        $setting->save();
                        $comand = "php /opt/extremstyle.ua/yii elasticsearch/cache-products  > /dev/null &";
                        exec($comand);
                        \Yii::$app->session->setFlash('success', "Elastic Search заустил обновление, это займет от 20 до 40 минут");
                    } else {
                        \Yii::$app->session->setFlash('error', "При запуске обновления Elastic Search произошла ошибка. Обновление Elastic Search уже запущено.");
                    }
                    header("Location: /admin/setting");
                    exit;
                    break;
            }
        }

        return $this->render('index');
    }




}
