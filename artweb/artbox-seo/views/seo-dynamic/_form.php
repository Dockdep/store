<?php
    
    use artweb\artbox\seo\models\SeoDynamic;
    use artweb\artbox\seo\models\SeoDynamicLang;
    use artweb\artbox\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View             $this
     * @var SeoDynamic       $model
     * @var SeoDynamicLang[] $modelLangs
     * @var int              $seo_category_id
     * @var ActiveForm       $form
     */
?>
<style>
    #seodynamic-filter_mod {
        display: none;
    }
</style>
<div class="seo-dynamic-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'action')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'param')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'fields')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'status')
             ->textInput() ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@artweb/artbox/seo/views/seo-dynamic/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
