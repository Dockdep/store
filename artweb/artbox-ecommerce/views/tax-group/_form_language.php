<?php
    use artweb\artbox\language\models\Language;
    use artweb\artbox\ecommerce\models\TaxGroupLang;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var TaxGroupLang $model_lang
     * @var Language     $language
     * @var ActiveForm   $form
     * @var View         $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']alias')
         ->textInput([ 'maxlength' => true ]); ?>
<?= $form->field($model_lang, '[' . $language->id . ']description')
         ->textarea([ 'rows' => 6 ]) ?>