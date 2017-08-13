<?php

namespace artweb\artbox\ecommerce\console;

use artweb\artbox\ecommerce\models\Import;
use artweb\artbox\language\models\Language;
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

        if (file_exists(Yii::getAlias('@uploadDir/goProducts_'.$lang.'.lock'))) {
            $this->errors[] = 'Task already executed';
            return Controller::EXIT_CODE_ERROR;
        }

        $ff = fopen(Yii::getAlias('@uploadDir/goProducts.lock'), 'w+');
        fclose($ff);
        $model = new Import();
        $model->goProducts(0, null);
        unlink(Yii::getAlias('@uploadDir/goProducts_'.$lang.'.lock'));

        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionPrices() {

        if (file_exists(Yii::getAlias('@uploadDir/goPrices.lock'))) {
            $this->stderr('Task already executed');
            return Controller::EXIT_CODE_ERROR;
        }
        $ff = fopen(Yii::getAlias('@uploadDir/goPrices.lock'), 'w+');
        fclose($ff);
        $model = new Import();
        $model->goPrices(0, null);
        unlink(Yii::getAlias('@uploadDir/goPrices.lock'));
        return Controller::EXIT_CODE_NORMAL;
    }


}