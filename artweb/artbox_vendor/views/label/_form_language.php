<?php
    use artweb\artbox\models\OrderDeliveryLang;
    use artweb\artbox\modules\language\models\Language;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var OrderDeliveryLang[] $model_lang
     * @var Language           $language
     * @var ActiveForm         $form
     * @var View               $this
     */
?>
<?= $form->field($model_lang, '[' . $language->id . ']title')
         ->textInput([ 'maxlength' => true ]); ?>
