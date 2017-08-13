<?php
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\CategoryLang;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var CategoryLang $model_lang
     * @var Language     $language
     * @var ActiveForm   $form
     * @var View         $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>

<?= $form->field($model_lang, '[' . $language->id . ']alias')
         ->textInput([ 'maxlength' => true ]); ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_title')
         ->textInput([ 'maxlength' => true ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_robots')
         ->textInput([ 'maxlength' => true ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_description')
         ->textInput([ 'maxlength' => true ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']seo_text')
         ->textarea([ 'rows' => 6 ]) ?>

<?= $form->field($model_lang, '[' . $language->id . ']h1')
         ->textInput([ 'maxlength' => true ]) ?>