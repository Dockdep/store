<?php
    
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\OrderLabelLang;
    use artweb\artbox\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View $this
     * @var Label $model
     * @var ActiveForm $form
     * @var orderLabelLang[] $modelLangs
     */
?>

<div class="label-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@artweb/artbox/ecommerce/views/label/_form_language',
        'form'        => $form,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
