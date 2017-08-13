<?php
    
    use artweb\artbox\modules\language\widgets\LanguageForm;
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use artweb\artbox\modules\catalog\models\TaxGroupLang;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use artweb\artbox\modules\catalog\helpers\ProductHelper;
    use artweb\artbox\components\artboxtree\ArtboxTreeHelper;
    
    /**
     * @var View           $this
     * @var TaxGroup       $model
     * @var TaxGroupLang[] $modelLangs
     * @var ActiveForm     $form
     */
?>

<div class="tax-group-form">
    
    <?php $form = ActiveForm::begin([ 'options' => [ 'enctype' => 'multipart/form-data' ] ]); ?>
    
    <?= $form->field($model, 'categories')
             ->dropDownList(ArtboxTreeHelper::treeMap(ProductHelper::getCategories(), 'id', 'lang.title'), [
                 'multiple' => true,
             ])
             ->label('Use in the following categories') ?>
    
    <?= $form->field($model, 'is_filter')
             ->checkbox() ?>
    
    <?= $form->field($model, 'display')
             ->checkbox() ?>
    
    <?= $form->field($model, 'is_menu')
             ->checkbox() ?>
    
    <?= $form->field($model, 'sort')
             ->textInput() ?>
    
    <?php
        echo LanguageForm::widget([
            'modelLangs' => $modelLangs,
            'formView'    => '@common/modules/rubrication/views/tax-group/_form_language',
            'form'        => $form,
        ]);
    ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rubrication', 'Create') : Yii::t('rubrication', 'Update'), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
