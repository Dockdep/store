<?php
    use artweb\artbox\language\models\Language;
    use artweb\artbox\ecommerce\models\ProductVariantLang;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var ProductVariantLang $model_lang
     * @var Language           $language
     * @var ActiveForm         $form
     * @var View               $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>