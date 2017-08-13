<?php

namespace console\controllers;

use artweb\artbox\ecommerce\models\Product;
use common\models\Import;
use artweb\artbox\language\models\Language;
use frontend\models\Catalog;
use Yii;
use yii\console\Controller;

/**
 * Class ImportController
 *
 * @todo Refactor
 *
 * @package console\controllers
 */
class ImportController extends Controller {
    public $errors = [];

    public function actionProducts() {

        $lang = 'ru';

        Language::setCurrent($lang);

        $model = new Import();
        $model->goProducts(0, null);


        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionPrices() {

        $model = new Import();
        $model->goPrices(0, null);
        return Controller::EXIT_CODE_NORMAL;
    }


}