<?php

use artweb\artbox\ecommerce\models\OrderPaymentLang;
use artweb\artbox\language\widgets\LanguageForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model artweb\artbox\ecommerce\models\OrderPayment */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelLangs OrderPaymentLang */
?>

<div class="order-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')
        ->widget(Select2::className(), ( [
            'name'          => 'status',
            'hideSearch'    => true,
            'data'          => [
                1 => \Yii::t('app', 'Active'),
                2 => \Yii::t('app', 'Inactive'),
            ],
            'options'       => [ 'placeholder' => \Yii::t('app', 'Select status...') ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ] )) ?>

    <?= $form->field($model, 'short')->textInput(); ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@artweb/artbox/ecommerce/views/order-payment/_form_language',
        'form'        => $form,
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
