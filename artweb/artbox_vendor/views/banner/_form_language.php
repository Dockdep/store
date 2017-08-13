<?php
    use artweb\artbox\models\BannerLang;
    use artweb\artbox\modules\language\models\Language;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var BannerLang $model_lang
     * @var Language   $language
     * @var ActiveForm $form
     * @var View       $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']alt')
         ->textInput([ 'maxlength' => true ]); ?>

<?= $form->field($model_lang, '['.$language->id.']image')->widget(\kartik\file\FileInput::className(), [
    'model' => $model_lang,
    'attribute' => 'image',
    'options' => [
        'accept' => 'image/*',
        'multiple' => false
    ],
    'pluginOptions' => [
        'allowedFileExtensions' => ['jpg','gif','png'],
        'initialPreview' => $model_lang->imageUrl ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage($model_lang->imageUrl, 'slider') : '',
        'showRemove' => false,
        'overwriteInitial' => true,
        'showUpload' => false,
        'showClose' => false,
    ],
]); ?>