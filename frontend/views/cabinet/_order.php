<?php
    
    /**
     * @var Order $model
     */
    
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\helpers\ProductHelper;
    use artweb\artbox\ecommerce\models\Order;
    use yii\bootstrap\Html;
    use yii\helpers\Url;

?>

<div class="_orders">
    <table class="_orders_title">
        <tr>
            <td class="_order_num"><span>№<?= $model->id ?></span></td>
            <td class="_order_date"><?= \Yii::$app->formatter->asDatetime($model->created_at) ?></td>
            <td><?= count($model->products) ?> <?= ProductHelper::trueWordForm(
                    count($model->products),
                    Yii::t('app', 'tovar1'),
                    Yii::t('app', 'tovar2'),
                    Yii::t('app', 'tovar3')
                ) ?> на <?= $model->total ?> грн
            </td>
            <td class="_in_proces"><?= !empty( $model->orderLable ) ? $model->orderLable->lang->title : '' ?></td>
            <td class="_print_order">
                <?= Html::a(
                    '',
                    [
                        'cabinet/print',
                        'order_id' => $model->id,
                    ],
                    [
                        'target' => '_blank',
                    ]
                ); ?>
            </td>
        </tr>
    </table>
    <table class="_order_tovar">
        <?php foreach ($model->products as $product) { ?>
            <tr>
                <td></td>
                <td class="_order_img">
                    <?= Html::a(
                        ArtboxImageHelper::getImage(
                            $product->productVariant->image->imageUrl,
                            'category_item',
                            [
                                'alt'   => $product->productVariant->product->lang->title,
                                'title' => $product->productVariant->product->lang->title,
                            ],
                            90,
                            true
                        ),
                        [
                            'catalog/product',
                            'product' => $product->productVariant->product->lang->alias,
                            'variant' => $product->productVariant->sku,
                        ],
                        [
                            "width"  => "70",
                            "height" => "47",
                        ]
                    ); ?>
                </td>
                <td class="_order_name">
                    <?= Html::a(
                        $product->productVariant->product->lang->title,
                        [
                            'catalog/product',
                            'product' => $product->productVariant->product->lang->alias,
                            'variant' => $product->productVariant->sku,
                        ]
                    ); ?>
                    <br/>
                    <?= $product->price ?> <?= \Yii::t('app', 'hrn') ?></td>
                <td class="_order_quant"><?= $product->count ?> шт.</td>
                <td class="_order_price"><?= $product->sum_cost ?> <?= \Yii::t('app', 'hrn') ?></td>
            </tr>
            <tr></tr>
        <?php } ?>
    </table>
    <div class="_order_delivery-wr style">
        <div class="_order_delivery style">
            <div title="<?= $model->deliveryString ?>"><?= $model->deliveryString ?></div>
        </div>
    </div>
    <div class="_order_coast style"><?= \Yii::t('app', 'in_total_pay') ?> <span><?= $model->total ?> <?= \Yii::t(
                'app',
                'hrn'
            ) ?></span></div>
</div>