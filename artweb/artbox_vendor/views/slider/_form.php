<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model artweb\artbox\models\Slider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slider-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'speed')->textInput() ?>

    <?= $form->field($model, 'duration')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'status')->widget(Select2::className(),([
        'name' => 'status',
        'hideSearch' => true,
        'data' => [1 => 'Active', 2 => 'Inactive'],
        'options' => ['placeholder' => 'Select status...'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ])) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
