<?php
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\TaxOptionLang;
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
