<?php

use artweb\artbox\components\artboximage\ArtboxImageHelper;
use artweb\artbox\event\models\Event;
use artweb\artbox\event\models\EventLang;
use artweb\artbox\language\widgets\LanguageForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Event */
/* @var $modelLangs EventLang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true, 'disabled'=>true]) ?>

    <?= $form->field($model, 'end_at')
        ->widget(DatePicker::className(), [
            'pluginOptions' => [
                'todayHighlight' => true,
                'format' => 'yyyy-mm-dd',
            ]]) ?>

    <?= $form->field($model, 'image')->widget(\kartik\file\FileInput::className(), [
        'language' => 'ru',
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
            'deleteurl' => $model->isNewRecord?false:Url::to(['/event/event/delete-image', 'id' => $model->id]),
            'class'     => $model->isNewRecord?'':'artbox-delete-file',
        ],
        'pluginOptions' => [
            'allowedFileExtensions' => ['jpg', 'gif', 'png'],
            'initialPreview' => !empty($model->getImageUrl(0, false)) ? ArtboxImageHelper::getImage($model->imageUrl, 'list') : '',
            'deleteUrl' => \yii\helpers\Url::to(['/product/manage/delimg', 'id' => $model->primaryKey]),
            'initialPreviewConfig' => $model->getImagesConfig(),
            'initialPreviewShowDelete' => false,
            'overwriteInitial'         => true,
            'showRemove'               => true,
            'showUpload'               => false,
            'showClose'                => false,
            'previewFileType'          => 'image',
        ],
    ]); ?>




    <?= $form->field($model, 'banner')->widget(\kartik\file\FileInput::className(), [
        'language' => 'ru',
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
            'deleteurl' => $model->isNewRecord?false:Url::to(['/event/event/delete-banner', 'id' => $model->id]),
            'class'     => $model->isNewRecord?'':'artbox-delete-file',
        ],
        'pluginOptions' => [
            'allowedFileExtensions' => ['jpg', 'gif', 'png'],
            'initialPreview' => !empty($model->getImageUrl(1, false)) ? ArtboxImageHelper::getImage($model->getImageUrl(1), 'list') : '',
            'deleteUrl' => \yii\helpers\Url::to(['/product/manage/delimg', 'id' => $model->primaryKey]),
            'initialPreviewConfig' => $model->getImagesConfig('banner'),
            'initialPreviewShowDelete' => false,
            'overwriteInitial'         => true,
            'showRemove'               => true,
            'showUpload'               => false,
            'showClose'                => false,
            'previewFileType'          => 'image',
        ],
    ]); ?>


    <?= $form->field($model, 'products_file')->widget(\kartik\file\FileInput::className(), [
        'language' => 'ru'
    ]); ?>



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

    <?= $form->field($model, 'is_sale')->checkbox() ?>

    <?= $form->field($model, 'is_event')->checkbox() ?>

    <?= $form->field($model, 'percent')->textInput() ?>

    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@artweb/artbox/event/views/event/_form_language',
        'form'        => $form,
    ]) ?>







    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
