<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model artweb\artbox\event\models\EventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'event_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'body') ?>

    <?= $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'meta_title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'h1') ?>

    <?php // echo $form->field($model, 'seo_text') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'end_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
