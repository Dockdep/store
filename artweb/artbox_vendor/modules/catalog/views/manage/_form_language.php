<?php
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\ProductLang;
    use mihaildev\ckeditor\CKEditor;
    use mihaildev\elfinder\ElFinder;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var ProductLang $model_lang
     * @var Language    $language
     * @var ActiveForm  $form
     * @var View        $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']alias')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']description')
         ->widget(CKEditor::className(), [
             'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                 'preset'               => 'full',
                 'inline'               => false,
                 'filebrowserUploadUrl' => Yii::$app->getUrlManager()
                                                    ->createUrl('file/uploader/images-upload'),
             ]),
         ]) ?>