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
<?= $form->field($model_lang, '[' . $language->id . ']label')
         ->textInput([ 'maxlength' => true ]); ?>
