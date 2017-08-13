<?php

namespace console\controllers;

use artweb\artbox\ecommerce\models\Product;
use common\models\Import;
use artweb\artbox\language\models\Language;
use frontend\models\Catalog;
use Yii;
use yii\console\Controller;


class ElasticsearchController extends Controller {
    public $errors = [];


    public function actionCacheProducts(){
        $count = 0;
      // Catalog::createIndex();
        /**
         * @var Language[] $languages
         */
        $languages= Language::find()->where(['status'=>true])->all();

        foreach ($languages as $lang){

            Language::setCurrent($lang->url);
            $query = Product::find()
//                ->where(['category.id' => 551])
                ->joinWith('categories.lang')
                ->with([
                        'brand.lang',
                        'filterOptions',
                        'variants.lang',
                        'variants.options'
                    ]
                );

            foreach ($query->each() as $product){

                $count++;

                    Catalog::addRecord($product);


                if(!($count%1000)){
                    print $count;
                }


            }
        }


    }

    public function actionUpdateMap(){
        Catalog::updateMapping();
    }


    public function actionCreateIndex(){
         Catalog::createIndex();
         print 'done';
    }

}