<?php
    
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View         $this
     * @var ProductVideo $model
     */
?>

<div class="page-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'title')
             ->textInput() ?>
    
    <?= $form->field($model, 'url')
             ->textarea() ?>
    
    <?= $form->field($model, 'is_main')
             ->checkbox() ?>
    
    <?= $form->field($model, 'is_display')
             ->checkbox() ?>
    
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
        ) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
