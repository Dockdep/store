<?php
    
    use artweb\artbox\modules\language\models\Language;
    use artweb\artbox\modules\catalog\models\Export;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View   $this
     * @var Export $model
     */
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
    
    <?= $form->field($model, 'lang')
             ->dropDownList(Language::find()
                                    ->select([
                                        'name',
                                        'id',
                                    ])
                                    ->where([ 'status' => 1 ])
                                    ->orderBy([ 'default' => SORT_DESC ])
                                    ->asArray()
                                    ->indexBy('id')
                                    ->column()) ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('product', 'Export'), [ 'class' => 'btn btn-success' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>