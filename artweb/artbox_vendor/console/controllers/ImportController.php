<?php

namespace console\controllers;

use artweb\artbox\modules\catalog\models\Import;
use Yii;
use yii\console\Controller;

class ImportController extends Controller {
    public $errors = [];


    private function getProductsFile($file_type = 'uploadFileProducts') {
        $filename = Yii::getAlias('@uploadDir') .'/'. Yii::getAlias('@'. $file_type);
        if (!is_file($filename)) {
            $this->stderr('Task already executed');
            return Controller::EXIT_CODE_ERROR;
        }
        return fopen ($filename, 'r');
    }

    public function actionProducts() {
//        if (file_exists(Yii::getAlias('@uploadDir/goProducts.lock'))) {
//            $this->errors[] = 'Task already executed';
//            return Controller::EXIT_CODE_ERROR;
//        }
//        $ff = fopen(Yii::getAlias('@uploadDir/goProducts.lock'), 'w+');
//        fclose($ff);
        $model = new Import();
        $model->goProducts(0, null);
//        unlink(Yii::getAlias('@uploadDir/goProducts.lock'));
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
        $data = $model->goPrices(0, null);
        unlink(Yii::getAlias('@uploadDir/goPrices.lock'));
        return Controller::EXIT_CODE_NORMAL;
    }

    private function saveNotFoundRecord (array $line, $filename)
    {
        $str = implode (';', $line)."\n";
        $str = iconv ("UTF-8//TRANSLIT//IGNORE", "windows-1251", $str);

        $fg = fopen (Yii::getAlias('@uploadDir') .'/'. $filename, 'a+');
        fputs ($fg, $str);
        fclose ($fg);
    }
}