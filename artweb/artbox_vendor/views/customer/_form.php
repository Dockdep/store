<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    
    /**
     * @var yii\web\View           $this
     * @var artweb\artbox\models\Customer $model
     * @var yii\widgets\ActiveForm $form
     */
?>
<div class="customer-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'username')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'name')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'surname')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'phone')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'gender')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'birth_day')
             ->textInput() ?>
    
    <?= $form->field($model, 'birth_month')
             ->textInput() ?>
    
    <?= $form->field($model, 'birth_year')
             ->textInput() ?>
    
    <?= $form->field($model, 'body')
             ->textarea([ 'rows' => 6 ]) ?>
    
    <?= $form->field($model, 'group_id')
             ->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
