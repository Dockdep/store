<?php
    
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductStock;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\ProductVariantLang;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View                 $this
     * @var ProductVariant       $model
     * @var ProductVariantLang[] $modelLangs
     * @var ActiveQuery          $groups
     * @var ProductStock[]       $stocks
     * @var Product              $product
     */
    $this->title = Yii::t('product', 'Create Variant');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Products'),
        'url'   => [ 'manage/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $product->lang->title,
        'url'   => [
            'manage/view',
            'id' => $product->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Variants'),
        'url'   => [
            'index',
            'product_id' => $product->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="product-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
            'groups'     => $groups,
            'stocks'     => $stocks,
            'product'    => $product,
        ]
    ) ?>

</div>
