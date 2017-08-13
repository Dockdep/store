<?php
    
    use artweb\artbox\ecommerce\models\BrandSize;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    
    /**
     * @var View       $this
     * @var BrandSize  $model
     * @var ActiveForm $form
     * @var array      $categories
     * @var array      $brands
     */
?>

<div class="brand-size-form">
    
    <?php $form = ActiveForm::begin(
        [
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]
    ); ?>
    
    <?= $form->field($model, 'brand_id')
             ->widget(
                 Select2::className(),
                 [
                     'data'          => $brands,
                     'language'      => 'ru',
                     'options'       => [
                         'placeholder' => Yii::t('product', 'Select categories'),
                         'multiple'    => false,
                     ],
                     'pluginOptions' => [
                         'allowClear' => true,
                     ],
                 ]
             ) ?>
    
    <?= $form->field($model, 'image')
             ->widget(
                 \kartik\file\FileInput::className(),
                 [
                     'language'      => 'ru',
                     'options'       => [
                         'accept'    => 'image/*',
                         'multiple'  => false,
                         'deleteurl' => $model->isNewRecord ? false : Url::to(
                             [
                                 '/ecommerce/manage/delete-size',
                                 'id' => $model->id,
                             ]
                         ),
                         'class'     => $model->isNewRecord ? '' : 'artbox-delete-file',
                     ],
                     'pluginOptions' => [
                         'allowedFileExtensions'    => [
                             'jpg',
                             'gif',
                             'png',
                         ],
                         'initialPreview'           => !empty(
                         $model->getBehavior('image')
                               ->getImageUrl(0, false)
                         ) ? ArtboxImageHelper::getImage(
                             $model->getBehavior('image')->imageUrl,
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
    
    <?= $form->field($model, 'categories')
             ->widget(
                 Select2::className(),
                 [
                     'data'          => $categories,
                     'language'      => 'ru',
                     'options'       => [
                         'placeholder' => Yii::t('product', 'Select categories'),
                         'multiple'    => true,
                     ],
                     'pluginOptions' => [
                         'allowClear' => true,
                     ],
                 ]
             )
             ->label(\Yii::t('app', 'Категории')) ?>

  <div class="form-group">
      <?= Html::submitButton(
          $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
          [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
      ) ?>
  </div>
    
    <?php ActiveForm::end(); ?>

</div>
