<?php
    
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\modules\catalog\models\ProductStock;
    use artweb\artbox\modules\catalog\models\ProductUnit;
    use artweb\artbox\modules\catalog\models\ProductVariant;
    use artweb\artbox\modules\catalog\models\ProductVariantLang;
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use wbraganca\dynamicform\DynamicFormWidget;
    
    /**
     * @var View                 $this
     * @var ProductVariant       $model
     * @var ProductVariantLang[] $modelLangs
     * @var ActiveQuery          $groups
     * @var ProductStock[]       $stocks
     * @var ActiveForm           $form
     * @var Product              $product
     */
    
    $js = '
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Are you sure you want to delete this item?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
});
';
    
    $this->registerJs($js, View::POS_END);
?>
<div class="product-form">
    
    <?php $form = ActiveForm::begin(
        [
            'id'      => 'dynamic-form',
            'options' => [ 'enctype' => 'multipart/form-data' ],
        ]
    ); ?>
    
    <?= $form->field($model, 'product_id')
             ->hiddenInput()
             ->label(false); ?>
    
    <?= $form->field($model, 'sku')
             ->textarea(); ?>
    <?= $form->field($model, 'price')
             ->textarea(); ?>
    <?= $form->field($model, 'price_old')
             ->textarea(); ?>
    <?= $form->field($model, 'imagesUpload[]')
             ->widget(
                 \kartik\file\FileInput::className(),
                 [
                     'language'      => 'ru',
                     'options'       => [
                         'accept'   => 'image/*',
                         'multiple' => true,
                     ],
                     'pluginOptions' => [
                         'allowedFileExtensions' => [
                             'jpg',
                             'gif',
                             'png',
                         ],
                         'initialPreview'        => !empty( $model->imagesHTML ) ? $model->imagesHTML : [],
                         'initialPreviewConfig'  => $model->imagesConfig,
                         'overwriteInitial'      => false,
                         'showRemove'            => false,
                         'showUpload'            => false,
                         'uploadAsync'           => !empty( $model->id ),
                         'previewFileType'       => 'image',
                     ],
                 ]
             ); ?>
    
    <?= LanguageForm::widget(
        [
            'modelLangs' => $modelLangs,
            'formView'   => '@common/modules/product/views/variant/_form_language',
            'form'       => $form,
        ]
    ) ?>
    
    <?php DynamicFormWidget::begin(
        [
            'widgetContainer' => 'dynamicform_wrapper',
            // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody'      => '.container-items',
            // required: css class selector
            'widgetItem'      => '.item',
            // required: css class
            'limit'           => 10,
            // the maximum times, an element can be added (default 999)
            'min'             => 0,
            // 0 or 1 (default 1)
            'insertButton'    => '.add-item',
            // css class
            'deleteButton'    => '.remove-item',
            // css class
            'model'           => $stocks[ 0 ],
            'formId'          => 'dynamic-form',
            'formFields'      => [
                'quantity',
                'title',
            ],
        ]
    ); ?>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-envelope"></i> Склады
                <button type="button" class="add-item btn btn-success btn-sm pull-right">
                    <i class="glyphicon glyphicon-plus"></i> Add
                </button>
            </h4>
        </div>
        <div class="panel-body">
            <div class="container-items"><!-- widgetBody -->
                <?php foreach ($stocks as $i => $stock): ?>
                    <div class="item panel panel-default"><!-- widgetItem -->
                        <div class="panel-body">
                            <?php
                                // necessary for update action.
                                if (!$stock->isNewRecord) {
                                    echo Html::activeHiddenInput($stock, "[{$i}]stock_id");
                                }
                            ?>
                            <div class="row">
                                <div class="col-sm-5">
                                    <?= $form->field($stock, "[{$i}]quantity")
                                             ->textInput([ 'maxlength' => true ]) ?>
                                </div>
                                <div class="col-sm-5">
                                    <?= $form->field($stock, "[{$i}]title")
                                             ->textInput([ 'maxlength' => true ]) ?>
                                </div>
                                <div class="col-sm-2" style="margin-top: 30px">
                                    <button type="button" class="remove-item btn btn-danger btn-xs">
                                        <i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            </div><!-- .row -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div><!-- .panel -->
    <?php DynamicFormWidget::end(); ?>
    
    <?= $form->field($model, 'product_unit_id')
             ->dropDownList(
                 ArrayHelper::map(
                     ProductUnit::find()
                                ->with('lang')
                                ->all(),
                     'id',
                     'lang.title'
                 )
             )
             ->label(Yii::t('product', 'Unit')) ?>
    
    <?php if (!empty( $groups )) {
        foreach ($groups->with('lang')
                        ->all() as $group) {
            /**
             * @var TaxGroup $group
             */
            echo $form->field($model, 'options')
                      ->checkboxList(
                          ArrayHelper::map(
                              $group->getOptions()
                                    ->with('lang')
                                    ->all(),
                              'id',
                              'lang.value'
                          ),
                          [
                              'multiple' => true,
                              'unselect' => null,
                          ]
                      )
                      ->label($group->lang->title);
        }
    } ?>
    
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('product', 'Create') : Yii::t('product', 'Update'),
            [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
        ) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
