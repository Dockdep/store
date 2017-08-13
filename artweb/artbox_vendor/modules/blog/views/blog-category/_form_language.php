<?php
    use artweb\artbox\models\ArticleLang;
    use artweb\artbox\modules\language\models\Language;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var ArticleLang $model_lang
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
         ->textarea(
             [
                 'rows' => '10',
             ]
         ) ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_title')
         ->textInput([ 'maxlength' => true ]); ?>

<?= $form->field($model_lang, '[' . $language->id . ']meta_description')
         ->textInput([ 'maxlength' => true ]); ?>

<?= $form->field($model_lang, '[' . $language->id . ']seo_text')
         ->textInput([ 'maxlength' => true ]); ?>

<?= $form->field($model_lang, '[' . $language->id . ']h1')
         ->textInput([ 'maxlength' => true ]); ?>
