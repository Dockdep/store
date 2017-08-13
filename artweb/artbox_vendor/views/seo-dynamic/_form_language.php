<?php
    use artweb\artbox\models\SeoDynamicLang;
    use artweb\artbox\modules\language\models\Language;
    use mihaildev\ckeditor\CKEditor;
    use mihaildev\elfinder\ElFinder;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var SeoDynamicLang $model_lang
     * @var Language       $language
     * @var ActiveForm     $form
     * @var View           $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']meta_title')
         ->textInput(); ?>
<?= $form->field($model_lang, '[' . $language->id . ']meta_description')
         ->widget(CKEditor::className(), [
             'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                 'preset'               => 'full',
                 'inline'               => false,
                 'filebrowserUploadUrl' => Yii::$app->getUrlManager()
                                                    ->createUrl('file/uploader/images-upload'),
             ]),
         ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']seo_text')
         ->widget(CKEditor::className(), [
             'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                 'preset'               => 'full',
                 'inline'               => false,
                 'filebrowserUploadUrl' => Yii::$app->getUrlManager()
                                                    ->createUrl('file/uploader/images-upload'),
             ]),
         ]) ?>
<?= $form->field($model_lang, '[' . $language->id . ']key')
         ->textInput([ 'maxlength' => true ]) ?>
<?= $form->field($model_lang, '[' . $language->id . ']h1')
         ->textInput([ 'maxlength' => true ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta')
         ->textInput([ 'maxlength' => true ]) ?>