<?php
    
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use artweb\artbox\modules\catalog\models\TaxGroupLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View           $this
     * @var TaxGroup       $model
     * @var TaxGroupLang[] $modelLangs
     * @var int            $level
     */
    
    $this->title = Yii::t('rubrication', 'Update {modelClass}: ', [
            'modelClass' => 'Tax Group',
        ]) . ' ' . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $level ? Yii::t('rubrication', 'Modification Groups') : Yii::t('rubrication', 'Product Groups'),
        'url'   => [
            'index',
            'level' => $level,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('rubrication', 'Update');
?>
<div class="tax-group-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
