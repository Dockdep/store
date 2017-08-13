<?php
    
    use artweb\artbox\models\Event;
    use artweb\artbox\models\EventLang;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use kartik\date\DatePicker;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use mihaildev\ckeditor\CKEditor;
    use mihaildev\elfinder\ElFinder;
    
    /**
     * @var View        $this
     * @var Event       $model
     * @var EventLang[] $modelLangs
     * @var ActiveForm  $form
     */
?>

<div class="event-form">
    
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options'                => [ 'enctype' => 'multipart/form-data' ],
    ]); ?>
    
    <?= $form->field($model, 'end_at')
             ->widget(DatePicker::className(), [
                 'pluginOptions' => [
                     'format'         => 'dd-mm-yyyy',
                     'todayHighlight' => true,
                 ],
             ]) ?>
    
    
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
        'formView'    => '@backend/views/event/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
