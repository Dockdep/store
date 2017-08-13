<?php
    
    use artweb\artbox\models\Delivery;
    use artweb\artbox\models\OrderDeliveryLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View                 $this
     * @var Delivery             $model
     * @var OrderDeliveryLang[] $modelLangs
     * @var array                $parent_items
     */
    
    $this->title = \Yii::t('product', 'Create Delivery');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('product', 'Deliveries'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="delivery-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'        => $model,
        'modelLangs'  => $modelLangs,
        'parent_items' => $parent_items,
    ]) ?>

</div>
