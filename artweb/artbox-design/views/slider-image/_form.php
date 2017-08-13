<?php
    
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\design\models\Slider;
    use artweb\artbox\design\models\SliderImage;
    use artweb\artbox\design\models\SliderImageLang;
    use artweb\artbox\language\widgets\LanguageForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
    /**
     * @var View              $this
     * @var SliderImage       $model
     * @var SliderImageLang[] $modelLangs
     * @var Slider            $slider
     * @var ActiveForm        $form
     */

?>

<div class="slider-image-form">
    
    <?php $form = ActiveForm::begin([ 'options' => [ 'enctype' => 'multipart/form-data' ] ]); ?>
    
    <?= $form->field($model, 'image')
             ->widget(\kartik\file\FileInput::className(), [
                 'model'         => $model,
                 'attribute'     => 'image',
                 'options'       => [
                     'accept'   => 'image/*',
                     'multiple' => false,
                     'deleteurl' => $model->isNewRecord?false:Url::to(['/design/slider-image/delete-image', 'id' => $model->id]),
                     'class'     => $model->isNewRecord?'':'artbox-delete-file',
                 ],
                 'pluginOptions' => [
                     'allowedFileExtensions' => [
                         'jpg',
                         'gif',
                         'png',
                     ],
                     'initialPreview'        => !empty($model->getImageUrl(0, false)) ? ArtboxImageHelper::getImage($model->imageUrl, 'slider') : '',
                     'initialPreviewShowDelete' => false,
                     'overwriteInitial'         => true,
                     'showRemove'               => true,
                     'showUpload'               => false,
                     'showClose'                => false,
                     'previewFileType'          => 'image',
                 ],
             ]); ?>
    <?= $form->field($model, 'end_at')
        ->widget(DatePicker::className(), [
            'pluginOptions' => [
                'todayHighlight' => true,
                'format' => 'yyyy-mm-dd',
            ]]) ?>

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
                 'options'       => [ 'placeholder' => 'Select status...' ],
                 'pluginOptions' => [
                     'allowClear' => true,
                 ],
             ] )) ?>
    
    <?= $form->field($model, 'sort')
             ->textInput() ?>
    
    <?php
        echo LanguageForm::widget([
            'modelLangs' => $modelLangs,
            'formView'    => '@artweb/artbox/design/views/slider-image/_form_language',
            'form'        => $form,
        ]);
    ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
