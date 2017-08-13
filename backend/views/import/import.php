<?php
    
    /**
     * @var Import $model
     * @var array  $languages
     */
    
    use artweb\artbox\ecommerce\models\Import;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

?>

<div class="product-import-form">
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options'                => [ 'enctype' => 'multipart/form-data' ],
    ]); ?>
    
    <?php if($model->errors) : ?>
        <div class="error">
            <?= implode("<br>\n", $model->errors); ?>
        </div>
    <?php endif ?>
    
    <?php if($model->output) : ?>
        <h2>Лог операции</h2>
        <div class="success" style="height: 10em;overflow: auto;border: 1px solid #000">
            <?= implode("<br>\n", $model->output); ?>
        </div>
    <?php endif ?>
    
    <?= $form->field($model, 'type')
             ->radioList([
                 'products' => Yii::t('product', 'Load products'),
                 'prices'   => Yii::t('product', 'Load prices'),
             ]); ?>
    
    <?= $form->field($model, 'lang')
             ->dropDownList($languages); ?>
    
    <?= $form->field($model, 'file')
             ->fileInput([ 'multiple' => false, ]) ?>
    
    <?php /*= $form->field($model, 'file')->widget(\kartik\file\FileInput::classname(), [
        'language' => 'ru',
        'options' => [
            'multiple' => false,
        ],
        'pluginOptions' => [
            'allowedFileExtensions' => ['csv'],
            'overwriteInitial' => true,
            'showRemove' => false,
            'showUpload' => false,
        ],
    ])*/ ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('product', 'Import'), [ 'class' => 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
