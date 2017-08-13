<?php
    
    use artweb\artbox\models\OrderDeliveryLang;
    use yii\helpers\Html;
    use artweb\artbox\models\Delivery;
    use yii\web\View;
    
    /**
     * @var View                 $this
     * @var Delivery             $model
     * @var array                $parent_items
     * @var OrderDeliveryLang[] $modelLangs
     */
    
    $this->title = 'Update Delivery: ' . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => 'Deliveries',
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = 'Update';
?>
<div class="delivery-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'        => $model,
        'modelLangs'  => $modelLangs,
        'parent_items' => $parent_items,
    ]) ?>

</div>
