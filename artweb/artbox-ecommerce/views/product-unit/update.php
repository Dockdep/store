<?php
    
    use artweb\artbox\ecommerce\models\ProductUnit;
    use artweb\artbox\ecommerce\models\ProductUnitLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View              $this
     * @var ProductUnit       $model
     * @var ProductUnitLang[] $modelLangs
     */
    
    $this->title = Yii::t('product', 'Update {modelClass}: ', [
            'modelClass' => 'Product Unit',
        ]) . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Product Units'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('product', 'Update');
?>
<div class="product-unit-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
