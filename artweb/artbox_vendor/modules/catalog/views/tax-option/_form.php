<?php
    
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use artweb\artbox\modules\catalog\models\TaxOptionLang;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use artweb\artbox\modules\catalog\models\TaxOption;
    
    /**
     * @var yii\web\View                                $this
     * @var artweb\artbox\modules\catalog\models\TaxOption $model
     * @var yii\widgets\ActiveForm                      $form
     * @var TaxGroup                                    $group
     * @var TaxOptionLang[]                             $modelLangs
     */
?>

<div class="tax-option-form">
    
    <?php $form = ActiveForm::begin([ 'options' => [ 'enctype' => 'multipart/form-data' ] ]); ?>
    <?php if (empty( $group->id )) : ?>
        <?= $form->field($model, 'tax_group_id')
                 ->dropDownList(
                     ArrayHelper::map(
                         TaxOption::find()
                                  ->all(),
                         'tax_group_id',
                         'tax_group_id'
                     ),
                     [
                         'prompt' => Yii::t('rubrication', 'Select group'),
                     ]
                 ) ?>
    <?php else : ?>
        <?= $form->field($model, 'tax_group_id')
                 ->hiddenInput()
                 ->label('') ?>
    <?php endif ?>
    
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
             )
             ->hint(
                 ( ( $model->tax_group_id == 5 ) ? 'Для корректного отображения на сайте, размер изображения должен быть 262x144 либо соблюдать соотношение сторон примерно 2:1' : '' )
             ); ?>
    <?= $form->field($model, 'sort')
             ->textInput() ?>
    
    <?php
        echo LanguageForm::widget(
            [
                'modelLangs' => $modelLangs,
                'formView'   => '@common/modules/rubrication/views/tax-option/_form_language',
                'form'       => $form,
            ]
        );
    ?>
    
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('rubrication', 'Create') : Yii::t('rubrication', 'Update'),
            [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
        ) ?>
        <?php if ($model->isNewRecord) : ?>
            <?= Html::submitButton(
                Yii::t('rubrication', 'Create and continue'),
                [
                    'name'  => 'create_and_new',
                    'class' => 'btn btn-primary',
                ]
            ) ?>
        <?php endif ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
