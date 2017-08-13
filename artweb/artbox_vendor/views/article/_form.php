<?php
    use artweb\artbox\models\Article;
    use artweb\artbox\models\ArticleLang;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use yii\jui\DatePicker;
    
    /**
     * @var View          $this
     * @var Article       $model
     * @var ArticleLang[] $modelLangs
     * @var ActiveForm    $form
     */
?>

<div class="article-form">
    
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options'                => [ 'enctype' => 'multipart/form-data' ],
    ]); ?>
    
    
    <?= $form->field($model, 'created_at')
             ->widget(DatePicker::className(), [
                 'dateFormat' => 'dd-MM-yyyy',
             ]) ?>
    
    <?= $form->field($model, 'image')
             ->widget(\kartik\file\FileInput::className(), [
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
                     'initialPreview'        => !empty( $model->imageUrl ) ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage($model->imageUrl, 'list') : '',
                     'overwriteInitial'      => true,
                     'showRemove'            => false,
                     'showUpload'            => false,
                     'previewFileType'       => 'image',
                 ],
             ]); ?>
    
    <?php
        echo LanguageForm::widget([
            'modelLangs' => $modelLangs,
            'formView'    => '@backend/views/article/_form_language',
            'form'        => $form,
        ]);
    ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? \Yii::t('app', 'Create') : \Yii::t('app', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
