<?php

namespace backend\controllers;

use common\models\Export;
use Yii;
use artweb\artbox\ecommerce\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ManageController implements the CRUD actions for Product model.
 */
class ExportController extends Controller
{

    /**
     * Export proccess via AJAX
     *
     * @param int    $from
     * @param string $filename
     *
     * @return array
     * @throws \HttpRequestException
     */
    public function actionExportProcess($from, $filename)
    {

        $model = new Export();
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $model->process($filename, $from);
        } else {
            throw new \HttpRequestException('Must be AJAX');
        }
    }

    /**
     * Perform export
     *
     * @return string
     */
    public function actionExport()
    {
        $model = new Export();

        if ($model->load(Yii::$app->request->post())) {
            \Yii::$app->session->set('export_lang', $model->lang);
            return $this->render(
                'export-process',
                [
                    'model'  => $model,
                    'method' => 'export',
                ]
            );
        }

        return $this->render(
            'export',
            [
                'model' => $model,
            ]
        );
    }

}
