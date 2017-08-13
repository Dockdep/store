<?php
    
    use artweb\artbox\models\Service;
    use artweb\artbox\models\ServiceLang;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View          $this
     * @var Service       $model
     * @var ServiceLang[] $modelLangs
     * @var ActiveForm    $form
     */
?>

<div class="service-form">
    
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>
    
    <?= $form->field($model, 'image')
             ->widget(\kartik\file\FileInput::className(), [
                 'language'      => 'ru',
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
                     'initialPreview'        => !empty( $model->imageUrl ) ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage($model->imageUrl, 'list') : '',
                     'overwriteInitial'      => true,
                     'showRemove'            => false,
                     'showUpload'            => false,
                     'previewFileType'       => 'image',
                 ],
             ]); ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@backend/views/service/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
