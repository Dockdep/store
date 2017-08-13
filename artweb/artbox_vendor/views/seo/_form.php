<?php
    
    use artweb\artbox\models\Seo;
    use artweb\artbox\models\SeoLang;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View       $this
     * @var Seo        $model
     * @var SeoLang[]  $modelLangs
     * @var ActiveForm $form
     */
?>

<div class="seo-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'url')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@backend/views/seo/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
