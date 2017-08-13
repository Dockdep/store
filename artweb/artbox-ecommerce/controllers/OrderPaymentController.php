<?php

namespace artweb\artbox\ecommerce\controllers;

use Yii;
use artweb\artbox\ecommerce\models\OrderPayment;
use artweb\artbox\ecommerce\models\OrderPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderPaymentController implements the CRUD actions for OrderPayment model.
 */
class OrderPaymentController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all OrderPayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OrderPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderPayment();
        $model->generateLangs();
        if ($model->load(Yii::$app->request->post())) {
            $model->loadLangs(\Yii::$app->request);
            if ($model->save() && $model->transactionStatus) {
                return $this->redirect([
                    'view',
                    'id' => $model->id,
                ]);
            }

        }

        return $this->render('create', [
            'model' => $model,
            'modelLangs' => $model->modelLangs,
        ]);
    }

    /**
     * Updates an existing OrderPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->generateLangs();
        if ($model->load(Yii::$app->request->post())) {
            $model->loadLangs(\Yii::$app->request);
            if ($model->save() && $model->transactionStatus) {

                return $this->redirect([
                    'view',
                    'id' => $model->id,
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelLangs' => $model->modelLangs,
        ]);
    }

    /**
     * Deletes an existing OrderPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OrderPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
