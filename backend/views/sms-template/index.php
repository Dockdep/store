<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SmsTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sms Templates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(\Yii::t('app', 'create_item',['item'=>'SMS Template']), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'text:ntext',
            'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
