<?php
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\ProductVariantLang;
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