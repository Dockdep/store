<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model artweb\artbox\seo\models\SeoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'meta') ?>

    <?= $form->field($model, 'title') ?> 

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'h1') ?>

    <?php // echo $form->field($model, 'seo_text') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
