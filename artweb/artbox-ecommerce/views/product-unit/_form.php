<?php
    
    use artweb\artbox\language\widgets\LanguageForm;
    use artweb\artbox\ecommerce\models\ProductUnit;
    use artweb\artbox\ecommerce\models\ProductUnitLang;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View              $this
     * @var ProductUnit       $model
     * @var ProductUnitLang[] $modelLangs
     * @var ActiveForm        $form
     */
?>

<div class="product-unit-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'is_default')
             ->checkbox() ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'form'        => $form,
        'formView'    => '@artweb/artbox/ecommerce/views/product-unit/_form_language',
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('product', 'Create') : Yii::t('product', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
