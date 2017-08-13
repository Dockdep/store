<?php
    
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\ProductVideo;
    use artweb\artbox\language\widgets\LanguageForm;
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\ProductLang;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use wbraganca\dynamicform\DynamicFormWidget;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use artweb\artbox\components\artboxtree\ArtboxTreeHelper;
    use artweb\artbox\ecommerce\helpers\ProductHelper;
    use kartik\select2\Select2;
    use yii\web\View;
    
    /**
     * @var yii\web\View                           $this
     * @var artweb\artbox\ecommerce\models\Product $model
     * @var ProductLang[]                          $modelLangs
     * @var yii\widgets\ActiveForm                 $form
     * @var ActiveQuery                            $groups
     * @var ProductVideo[]                         $videos
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
            'options' => [
                'enctype' => 'multipart/form-data',
                'id'      => 'dynamic-form',
            ],
        ]
    ); ?>
    
    <?= $form->field($model, 'is_top')
             ->checkbox([ 'label' => 'ТОП' ]) ?>
    <?= $form->field($model, 'is_new')
             ->checkbox([ 'label' => 'Новинка' ]) ?>
    <?= $form->field($model, 'is_discount')
             ->checkbox([ 'label' => 'Акционный' ]) ?>
    
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
            'model'           => $videos[ 0 ],
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
                <i class="glyphicon glyphicon-film"></i> Видео (Пример: &ltiframe src='https://www.youtube.com/embed/6mXhDfoJau4' frameborder='0' allowfullscreen&gt&lt/iframe&gt)
                <button type="button" class="add-item btn btn-success btn-sm pull-right">
                    <i class="glyphicon glyphicon-plus"></i> Add
                </button>
            </h4>
        </div>
        <div class="panel-body">
            <div class="container-items"><!-- widgetBody -->
                <?php foreach ($videos as $i => $video): ?>
                    <div class="item panel panel-default"><!-- widgetItem -->
                        <div class="panel-body">
                            <?php
                                // necessary for update action.
                                if (!$video->isNewRecord) {
                                    echo Html::activeHiddenInput($video, "[{$i}]id");
                                }
                            ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?= $form->field($video, "[{$i}]title")
                                             ->textInput([ 'maxlength' => true ]) ?>
                                </div>
                                <div class="col-sm-7">
                                    <?= $form->field($video, "[{$i}]url")
                                             ->textInput([ 'maxlength' => true ])->label('Iframe') ?>
                                </div>
                                <div class="col-sm-1" style="margin-top: 30px">
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
    
    <?= $form->field($model, 'size_image')
             ->widget(
                 \kartik\file\FileInput::className(),
                 [
                     'language'      => 'ru',
                     'options'       => [
                         'accept'   => 'image/*',
                         'multiple' => false,
                         'deleteurl' => $model->isNewRecord?false:Url::to(['/ecommerce/manage/delete-size', 'id' => $model->id]),
                         'class'     => $model->isNewRecord?'':'artbox-delete-file',
                     ],
                     'pluginOptions' => [
                         'allowedFileExtensions' => [
                             'jpg',
                             'gif',
                             'png',
                         ],
                         'initialPreview'        => !empty( $model->getBehavior('size_image')->getImageUrl(0, false)) ? ArtboxImageHelper::getImage(
                             $model->getBehavior('size_image')->imageUrl,
                             'list'
                         ) : '',
                         'initialPreviewShowDelete' => false,
                         'overwriteInitial'         => true,
                         'showRemove'               => true,
                         'showUpload'               => false,
                         'showClose'                => false,
                         'previewFileType'          => 'image',
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
                              'unselect' => NULL,
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
            'formView'   => '@artweb/artbox/ecommerce/views/manage/_form_language',
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
