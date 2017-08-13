<?php
    use artweb\artbox\models\ServiceLang;
    use artweb\artbox\modules\language\models\Language;
    use mihaildev\ckeditor\CKEditor;
    use mihaildev\elfinder\ElFinder;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var ServiceLang $model_lang
     * @var Language    $language
     * @var ActiveForm  $form
     * @var View        $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']alias')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']body')
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

<?= $form->field($model_lang, '[' . $language->id . ']meta_title')
         ->textInput([ 'maxlength' => true ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_description')
         ->textInput([ 'maxlength' => true ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']h1')
         ->textInput([ 'maxlength' => true ]) ?>