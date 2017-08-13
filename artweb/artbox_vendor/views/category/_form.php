<?php
    
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use artweb\artbox\modules\catalog\models\Category;
    use artweb\artbox\modules\catalog\models\CategoryLang;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View           $this
     * @var Category       $model
     * @var CategoryLang[] $modelLangs
     * @var string[]       $categories
     * @var ActiveForm     $form
     */
?>

<div class="category-form">
    
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options'                => [ 'enctype' => 'multipart/form-data' ],
    ]); ?>
    
    <?= $form->field($model, 'parent_id')
             ->dropDownList($categories, [
                 'prompt'  => Yii::t('rubrication', 'Root category'),
                 'options' => [
                     $model->id => [ 'disabled' => true ],
                 ],
             ])
             ->label(Yii::t('product', 'Parent category')) ?>
    
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
             ])
             ->hint('Для корректного отображения на сайте, размер изображения должен быть 262x144 либо соблюдать соотношение сторон примерно 2:1'); ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@backend/views/category/_form_language',
        'form'        => $form,
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('product', 'Create') : Yii::t('product', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
        <?php if($model->isNewRecord) : ?>
            <?= Html::submitButton(Yii::t('product', 'Create and continue'), [
                'name'  => 'create_and_new',
                'class' => 'btn btn-primary',
            ]) ?>
        <?php endif ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
