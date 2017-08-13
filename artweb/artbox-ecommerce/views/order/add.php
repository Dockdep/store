<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;


$this->title = 'Добавить товар в заказ';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>Добавить товар в заказ</h1>

    <?php $form = ActiveForm::begin([
        'id' => 'reg-form',
        'options' => ['class' => 'form-vertical','enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            //'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            //'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

<?= $form->field($model, 'art') ?>

<?= $form->field($model, 'count') ?>

 <?= $form->field($model, 'order_id')->hiddenInput(['value'=>$_GET['order_id']])->label(false); ?>    

<div class="form-group">
    <?= Html::submitButton(' Сохранить ', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
</div>
 <?php ActiveForm::end(); ?>