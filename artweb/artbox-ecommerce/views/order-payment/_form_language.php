<?php
use artweb\artbox\ecommerce\models\OrderPaymentLang;
use artweb\artbox\language\models\Language;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var OrderPaymentLang   $model_lang
 * @var Language           $language
 * @var ActiveForm         $form
 * @var View               $this
 */
?>




<?= $form->field($model_lang, '[' . $language->id . ']title')->textInput(['maxlength' => true]) ?>





