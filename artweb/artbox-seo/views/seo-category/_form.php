<?php
    
    use artweb\artbox\seo\models\SeoCategory;
    use artweb\artbox\seo\models\SeoCategoryLang;
    use artweb\artbox\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View              $this
     * @var SeoCategory       $model
     * @var SeoCategoryLang[] $modelLangs
     * @var ActiveForm        $form
     */
?>

<div class="seo-category-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'controller')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'status')
             ->textInput() ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@artweb/artbox/seo/views/seo-category/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
