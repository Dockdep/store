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
    
    $this->title = Yii::t('product', 'Create Product Unit');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Product Units'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="product-unit-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
