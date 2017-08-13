<?php
    
    use artweb\artbox\design\models\Banner;
    use artweb\artbox\design\models\BannerLang;
    use artweb\artbox\language\widgets\LanguageForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View         $this
     * @var Banner       $model
     * @var ActiveForm   $form
     * @var BannerLang[] $modelLangs
     */
?>

<div class="banner-form">
    
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>
    
    <?= $form->field($model, 'url')
             ->textInput([ 'maxlength' => true ]) ?>
    
    <?= $form->field($model, 'status')
             ->widget(Select2::className(), ( [
                 'name'          => 'status',
                 'hideSearch'    => true,
                 'data'          => [
                     1 => \Yii::t('app', 'Active'),
                     2 => \Yii::t('app', 'Inactive'),
                 ],
                 'options'       => [ 'placeholder' => \Yii::t('app', 'Select status...') ],
                 'pluginOptions' => [
                     'allowClear' => true,
                 ],
             ] )) ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@artweb/artbox/design/views/banner/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
