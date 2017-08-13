<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel artweb\artbox\ecommerce\models\OrderPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Order Payment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'status',
            'short',
            [
                'attribute' => 'title',
                'value'     => 'lang.title',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
