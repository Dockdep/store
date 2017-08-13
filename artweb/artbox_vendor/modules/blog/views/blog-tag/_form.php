<?php
    
    use artweb\artbox\modules\blog\models\BlogTag;
    use artweb\artbox\modules\blog\models\BlogTagLang;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    
    /**
     * @var View          $this
     * @var BlogTag       $model
     * @var ActiveForm    $form
     * @var BlogTagLang[] $modelLangs
     */
?>

<div class="blog-tag-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?php
        echo LanguageForm::widget(
            [
                'modelLangs' => $modelLangs,
                'formView'   => '@common/modules/blog/views/blog-tag/_form_language',
                'form'       => $form,
            ]
        );
    ?>
    
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]
        ) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
