<?php
    
    use artweb\artbox\ecommerce\models\OrderDeliveryLang;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use artweb\artbox\language\widgets\LanguageForm;
    
    /**
     * @var yii\web\View           $this
     * @var artweb\artbox\ecommerce\models\Delivery $model
     * @var yii\widgets\ActiveForm $form
     * @var OrderDeliveryLang[]   $modelLangs
     */
?>

<div class="delivery-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'parent_id')
             ->dropDownList($parent_items, [
                 'prompt' => "Выберите вид доставки..."
             ]) ?>
    
    <?= $form->field($model, 'value')
             ->textInput() ?>
    
    <?= $form->field($model, 'sort')
             ->textInput() ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@artweb/artbox/ecommerce/views/delivery/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
