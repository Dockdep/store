<?php

namespace backend\controllers;

use artweb\artbox\language\models\Language;
use common\models\Import;
use Yii;
use artweb\artbox\ecommerce\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ManageController implements the CRUD actions for Product model.
 */
class ImportController extends Controller
{


    /**
     * Perform product import
     *
     * @return string
     */
    public function actionImport()
    {
        $model = new Import();

        $languages = Language::find()
            ->select(
                [
                    'name',
                    'id',
                ]
            )
            ->where([ 'status' => 1 ])
            ->orderBy([ 'default' => SORT_DESC ])
            ->asArray()
            ->indexBy('id')
            ->column();

        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->session->set('export_lang', $model->lang);
            $file = UploadedFile::getInstances($model, 'file');
            $method = 'go' . ucfirst($model->type);
            $target = Yii::getAlias('@uploadDir') . '/' . Yii::getAlias('@uploadFile' . ucfirst($model->type));
            if (empty( $file )) {
                $model->errors[] = 'File not upload';
            } elseif ($method == 'goPrices' && $file[ 0 ]->name != 'file_1.csv') {
                $model->errors[] = 'File need "file_1.csv"';
            } elseif ($method == 'goProducts' && $file[ 0 ]->name == 'file_1.csv') {
                $model->errors[] = 'File can not "file_1.csv"';
            } elseif ($model->validate() && $file[ 0 ]->saveAs($target)) {
                // PROCESS PAGE
                return $this->render(
                    'import-process',
                    [
                        'model'  => $model,
                        'method' => $model->type,
                        'target' => $target,
                    ]
                );
            } else {
                $model->errors[] = 'File can not be upload or other error';
            }
        }

        return $this->render(
            'import',
            [
                'model'     => $model,
                'languages' => $languages,
            ]
        );
    }

    /**
     * Import products via AJAX
     *
     * @return array
     * @throws \HttpRequestException
     */
    public function actionProducts()
    {
        $from = Yii::$app->request->get('from', 0);

        $model = new Import();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $model->goProducts($from, 10);
        } else {
            throw new \HttpRequestException('Must be AJAX');
        }
    }

    /**
     * Import prices via AJAX
     *
     * @return array
     * @throws \HttpRequestException
     */
    public function actionPrices()
    {
        $from = Yii::$app->request->get('from', 0);

        $model = new Import();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $model->goPrices($from, 10);
        } else {
            throw new \HttpRequestException('Must be AJAX');
        }


    }


}
