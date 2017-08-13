<?php
    
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\modules\catalog\models\ProductStock;
    use artweb\artbox\modules\catalog\models\ProductVariant;
    use artweb\artbox\modules\catalog\models\ProductVariantLang;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View                 $this
     * @var ProductVariant       $model
     * @var ProductVariantLang[] $modelLangs
     * @var ActiveQuery          $groups
     * @var ProductStock[]       $stocks
     * @var Product              $product
     */
    $this->title = Yii::t(
            'product',
            'Update {modelClass}: ',
            [
                'modelClass' => 'Product',
            ]
        ) . ' ' . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Products'),
        'url'   => [ '/product/manage/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->product->lang->title,
        'url'   => [
            '/product/manage/view',
            'id' => $model->product->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Variants'),
        'url'   => Url::to(
            [
                'index',
                'product_id' => $model->product->id,
            ]
        ),
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', $model->lang->title),
        'url'   => Url::to(
            [
                'view',
                'id' => $model->id,
            ]
        ),
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('product', 'Update');
?>
<div class="product-update">
    
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
