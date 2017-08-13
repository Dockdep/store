<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model artweb\artbox\modules\blog\models\BlogArticleSearch */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-article-search">
    
    <?php $form = ActiveForm::begin(
        [
            'action' => [ 'index' ],
            'method' => 'get',
        ]
    ); ?>
    
    <?= $form->field($model, 'id') ?>
    
    <?= $form->field($model, 'image') ?>
    
    <?= $form->field($model, 'created_at') ?>
    
    <?= $form->field($model, 'updated_at') ?>
    
    <?= $form->field($model, 'deleted_at') ?>
    
    <?php // echo $form->field($model, 'sort') ?>
    
    <?php // echo $form->field($model, 'status')->checkbox() ?>
    
    <?php // echo $form->field($model, 'author_id') ?>
    
    <div class="form-group">
        <?= Html::submitButton('Search', [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton('Reset', [ 'class' => 'btn btn-default' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
