<?php
    use artweb\artbox\language\models\Language;
    use artweb\artbox\event\models\EventLang;
    use mihaildev\ckeditor\CKEditor;
    use mihaildev\elfinder\ElFinder;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var EventLang  $model_lang
     * @var Language   $language
     * @var ActiveForm $form
     * @var View       $this
     */


?>





<?= $form->field($model_lang, '[' . $language->id . ']title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']alias')->textInput(['maxlength' => true]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']body')->widget(CKEditor::className(),
    [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[
                'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                'inline' => false, //по умолчанию false]),
                'filebrowserUploadUrl'=>Yii::$app->getUrlManager()->createUrl('file/uploader/images-upload')
            ]
        )
    ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_description')->textInput(['maxlength' => true]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']h1')->textInput(['maxlength' => true]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']seo_text')->textarea(['rows' => 6]) ?>



