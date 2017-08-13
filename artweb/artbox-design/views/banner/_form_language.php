<?php
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\design\models\BannerLang;
    use artweb\artbox\language\models\Language;
    use yii\helpers\Url;
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

<?= $form->field($model_lang, '[' . $language->id . ']image')
         ->widget(
             \kartik\file\FileInput::className(),
             [
                 'language'      => 'ru',
                 'options'       => [
                     'accept'   => 'image/*',
                     'multiple' => false,
                     'deleteurl' => $model_lang->isNewRecord?false:Url::to(['/design/banner/delete-image', 'id' => $model_lang->banner_id, 'lang_id' => $model_lang->language_id]),
                     'class'     => $model_lang->isNewRecord?'':'artbox-delete-file',
                 ],
                 'pluginOptions' => [
                     'allowedFileExtensions'    => [
                         'jpg',
                         'gif',
                         'png',
                     ],
                     'initialPreview'           => !empty( $model_lang->getImageUrl(
                         0,
                         false
                     ) ) ? ArtboxImageHelper::getImage($model_lang->imageUrl, 'slider') : '',
                     'initialPreviewShowDelete' => false,
                     'overwriteInitial'         => true,
                     'showRemove'               => true,
                     'showUpload'               => false,
                     'showClose'                => false,
                     'previewFileType'          => 'image',
                 ],
             ]
         ); ?>