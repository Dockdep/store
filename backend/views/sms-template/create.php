<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SmsTemplate */

$this->title = \Yii::t('app', 'create_item',['item'=>'SMS Template']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sms Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
