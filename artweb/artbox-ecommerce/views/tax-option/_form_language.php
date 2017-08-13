<?php
    use artweb\artbox\language\models\Language;
    use artweb\artbox\ecommerce\models\TaxOptionLang;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var TaxOptionLang $model_lang
     * @var Language      $language
     * @var ActiveForm    $form
     * @var View          $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']value')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']alias')
         ->textInput([ 'maxlength' => true ]); ?>
