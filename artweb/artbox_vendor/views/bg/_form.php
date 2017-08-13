<?php
    
    use artweb\artbox\models\Bg;
    use artweb\artbox\models\BgLang;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View       $this
     * @var Bg         $model
     * @var BgLang[]   $modelLangs
     * @var ActiveForm $form
     */
?>

<div class="bg-form">
    
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>
    
    <?= $form->field($model, 'url')
             ->textInput([ 'maxlength' => true ]) ?>
    
    
    <?= $form->field($model, 'image')
             ->widget(\kartik\file\FileInput::className(), [
                 'model'         => $model,
                 'attribute'     => 'image',
                 'options'       => [
                     'accept'   => 'image/*',
                     'multiple' => false,
                 ],
                 'pluginOptions' => [
                     'allowedFileExtensions' => [
                         'jpg',
                         'gif',
                         'png',
                     ],
                     'initialPreview'        => $model->imageUrl ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage($model->imageUrl, 'slider') : '',
                     'showRemove'            => false,
                     'overwriteInitial'      => true,
                     'showUpload'            => false,
                     'showClose'             => false,
                 ],
             ]); ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'form'        => $form,
        'formView'    => '@backend/views/bg/_form_language',
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? \Yii::t('app', 'Create') : \Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
