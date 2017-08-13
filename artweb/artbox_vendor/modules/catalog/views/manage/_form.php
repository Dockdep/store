<?php
    
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use artweb\artbox\modules\catalog\models\Brand;
    use artweb\artbox\modules\catalog\models\ProductLang;
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use artweb\artbox\components\artboxtree\ArtboxTreeHelper;
    use artweb\artbox\modules\catalog\helpers\ProductHelper;
    use kartik\select2\Select2;
    
    /**
     * @var yii\web\View                          $this
     * @var artweb\artbox\modules\catalog\models\Product $model
     * @var ProductLang[]                         $modelLangs
     * @var yii\widgets\ActiveForm                $form
     * @var ActiveQuery                           $groups
     */
?>

<div class="product-form">
    
    <?php $form = ActiveForm::begin(
        [
            'options' => [ 'enctype' => 'multipart/form-data' ],
        ]
    ); ?>
    
    <?= $form->field($model, 'is_top')
             ->checkbox([ 'label' => 'ТОП' ]) ?>
    <?= $form->field($model, 'is_new')
             ->checkbox([ 'label' => 'Новинка' ]) ?>
    <?= $form->field($model, 'is_discount')
             ->checkbox([ 'label' => 'Акционный' ]) ?>
    
    <?= $form->field($model, 'video')
             ->textarea(); ?>
    
    <?= $form->field($model, 'brand_id')
             ->dropDownList(
                 ArrayHelper::map(
                     Brand::find()
                          ->with('lang')
                          ->all(),
                     'id',
                     'lang.title'
                 ),
                 [
                     'prompt' => Yii::t('product', 'Select brand'),
                 ]
             ) ?>
    
    <?= $form->field($model, 'categories')
             ->widget(
                 Select2::className(),
                 [
                     'data'          => ArtboxTreeHelper::treeMap(ProductHelper::getCategories(), 'id', 'lang.title'),
                     'language'      => 'ru',
                     'options'       => [
                         'placeholder' => Yii::t('product', 'Select categories'),
                         'multiple'    => true,
                     ],
                     'pluginOptions' => [
                         'allowClear' => true,
                     ],
                 ]
             ) ?>
    
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
    }
    ?>
    
    <hr>
    
    <?= LanguageForm::widget(
        [
            'modelLangs' => $modelLangs,
            'formView'   => '@common/modules/product/views/manage/_form_language',
            'form'       => $form,
        ]
    ) ?>
    
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('product', 'Create') : Yii::t('product', 'Update'),
            [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
        ) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
