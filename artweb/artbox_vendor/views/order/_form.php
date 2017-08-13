<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use artweb\artbox\models\Delivery;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model artweb\artbox\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>
<div class="container" style="margin-left: 0;">
<div class="col-sm-6">


    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'phone2') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'numbercard') ?>

    <?= $form->field($model, 'body')->textArea(['rows' => '3']) ?>
        
    <?php /* $form->field($model, 'delivery')->dropDownList(ArrayHelper::map(Delivery::find()->asArray()->all(), 'id', 'title')) */ ?>
        
    <?= $form->field($model, 'declaration') ?>

    <?= $form->field($model, 'stock') ?>

    <?= $form->field($model, 'consignment') ?>
</div>
<div class="col-sm-6">

    <?=$form->field($model, 'payment')->dropDownList(['Оплатить наличными'=>'Оплатить наличными','Оплатить на карту Приват Банка'=>'Оплатить на карту Приват Банка','Оплатить по безналичному расчету'=>'Оплатить по безналичному расчету','Оплатить Правекс-телеграф'=>'Оплатить Правекс-телеграф','Наложенным платежом'=>'Наложенным платежом'],['prompt'=>'...']); ?>

    <?= $form->field($model, 'insurance') ?>

    <?= $form->field($model, 'amount_imposed') ?>

    <?= $form->field($model, 'shipping_by') ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'adress') ?>


    <?= $form->field($model, 'total') ?>

    <?=$form->field($model, 'status')->dropDownList(['Нет'=>'Нет','Обработан'=>'Обработан','На комплектации'=>'На комплектации','Укомплектован'=>'Укомплектован','Доставка'=>'Доставка','Выполнен'=>'Выполнен','Резерв оплачен'=>'Резерв оплачен','Резерв неоплачен'=>'Резерв неоплачен'],['prompt'=>'...']); ?>

    <?= $form->field($model, 'comment')->textArea(['rows' => '3']) ?>
</div>
</div>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
    <?php ActiveForm::end(); ?>

