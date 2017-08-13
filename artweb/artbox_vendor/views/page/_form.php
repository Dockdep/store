<?php
    
    use artweb\artbox\models\Page;
    use artweb\artbox\models\PageLang;
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View       $this
     * @var Page       $model
     * @var ActiveForm $form
     * @var PageLang[] $modelLangs
     */
?>

<div class="page-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= LanguageForm::widget([
        'modelLangs' => $modelLangs,
        'formView'    => '@backend/views/page/_form_language',
        'form'        => $form,
    ]) ?>
    
    <?= $form->field($model, 'in_menu')
             ->checkbox() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
