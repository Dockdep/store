<?php
    
    use artweb\artbox\models\Slider;
    use artweb\artbox\models\SliderImage;
    use artweb\artbox\models\SliderImageLang;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
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
                     'multiple' => true,
                 ],
                 'pluginOptions' => [
                     'allowedFileExtensions' => [
                         'jpg',
                         'gif',
                         'png',
                     ],
                     'initialPreview'        => $model->imageUrl ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage($model->imageUrl, 'slider') : '',
                     'overwriteInitial'      => true,
                     'showRemove'            => true,
                     'showUpload'            => false,
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
            'formView'    => '@backend/views/slider-image/_form_language',
            'form'        => $form,
        ]);
    ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
