<?php
    
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductLang;
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View           $this
     * @var Product        $model
     * @var ProductLang[]  $modelLangs
     * @var ActiveQuery    $groups
     * @var ProductVideo[] $videos
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
<div class="product-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
            'groups'     => $groups,
            'videos'     => $videos,
        ]
    ) ?>

</div>
