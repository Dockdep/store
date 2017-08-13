<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model artweb\artbox\modules\blog\models\BlogCategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sort') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
