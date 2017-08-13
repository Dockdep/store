<?php
    
    use artweb\artbox\modules\blog\models\BlogCategory;
    use artweb\artbox\modules\blog\models\BlogCategoryLang;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    
    /**
     * @var View               $this
     * @var BlogCategory       $model
     * @var ActiveForm         $form
     * @var BlogCategoryLang[] $modelLangs
     * @var array              $parentCategories
     */
?>

<div class="blog-category-form">
    
    <?php $form = ActiveForm::begin(
        [
            'options' => [ 'enctype' => 'multipart/form-data' ],
        
        ]
    ); ?>
    
    <?php
        echo LanguageForm::widget(
            [
                'modelLangs' => $modelLangs,
                'formView'   => '@common/modules/blog/views/blog-category/_form_language',
                'form'       => $form,
            ]
        );
    ?>
    
    <?= $form->field($model, 'image')
             ->widget(
                 \kartik\file\FileInput::className(),
                 [
                     'language'      => 'ru',
                     'options'       => [
                         'accept'   => 'image/*',
                         'multiple' => false,
                     ],
                     'pluginOptions' => [
                         'allowedFileExtensions' => [
                             'jpg',
                             'gif',
                             'png',
                         ],
                         'initialPreview'        => !empty( $model->imageUrl ) ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage(
                             $model->imageUrl,
                             'list'
                         ) : '',
                         'overwriteInitial'      => true,
                         'showRemove'            => false,
                         'showUpload'            => false,
                         'previewFileType'       => 'image',
                     ],
                 ]
             ); ?>
    
    <?= $form->field($model, 'sort')
             ->textInput() ?>
    
    <?php echo $form->field($model, 'parent_id')
                    ->widget(
                        Select2::className(),
                        [
                            'data'          => $parentCategories,
                            'options'       => [ 'placeholder' => \Yii::t('blog', 'Has no parent rubric') ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]
                    );
    ?>
    
    <?= $form->field($model, 'status')
             ->checkbox() ?>
    
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
        ) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
