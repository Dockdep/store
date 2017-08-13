<?php
    /**
     * @var View
     * @var ProductVariant[] $models
     * @var Basket           $basket
     * @var array            $data
     */
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\Basket;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\web\View;
    
    $ids = '[';
    foreach ($models as $model) {
        $ids .= '"' . $model->id . '",';
    }
    $ids .= ']';
    
    $conversions = <<< JS
var google_tag_params = {
    dynx_itemid: {$ids},
    dynx_pagetype: "conversionintent",
    dynx_totalvalue: {$basket->sum}
};
/* <![CDATA[ */
    var google_conversion_id = 881201405;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
JS;
    
    $this->registerJs($conversions, View::POS_BEGIN);

?>
<table class="basket-tb" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td><?= \Yii::t('app', 'photo') ?></td>
        <td><?= \Yii::t('app', 'item') ?></td>
        <td><?= \Yii::t('app', 'price_one') ?></td>
        <td><?= \Yii::t('app', 'count') ?></td>
        <td><?= \Yii::t('app', 'for_all_items') ?></td>
        <td><?= \Yii::t('app', 'delete') ?></td>
    </tr>
    <?php
        foreach ($data as $product_variant_id => $item) {
            if (!array_key_exists($product_variant_id, $models)) {
                continue;
            } else {
                $variant = $models[ $product_variant_id ];
            }
            ?>
            <tr class="variant_tr" data-variant="<?php echo $variant->id; ?>">
                <td>
                    <?php
                        echo Html::a(
                            ArtboxImageHelper::getImage(
                                $variant->imageUrl,
                                'basket_item',
                                [
                                    'alt'   => $variant->fullname,
                                    'title' => $variant->fullname,
                                ],
                                90
                            ),
                            [
                                'catalog/product',
                                'product' => $variant->product->lang->alias,
                                'variant' => $variant->sku,
                            ],
                            [
                                'target' => '_blank',
                            ]
                        );
                    ?>
                </td>
                <td>
                    <?php
                        echo Html::a(
                            Html::tag('span', $variant->fullname),
                            [
                                'catalog/product',
                                'product' => $variant->product->lang->alias,
                                'variant' => $variant->sku,
                            ],
                            [
                                'class'  => 'title',
                                'target' => '_blank',
                            ]
                        );
                    ?>
                    <span class="size">
                                        <?php
                                            foreach ($variant->properties as $property) {
                                                echo $property->lang->title . ': ' . implode(
                                                        ',',
                                                        ArrayHelper::getColumn(
                                                            $property->customOptions,
                                                            'lang.value'
                                                        )
                                                    ) . ', ';
                                            }
                                        ?>
                                    </span>
                    <span class="code"><?php echo \Yii::t(
                                'app',
                                'code'
                            ) . ': ' . $variant->sku; ?></span>
                </td>
                <td>
                    <p class="price"><?php echo $variant->price; ?>
                        <span> грн.</span><span class="hidden-sm hidden-md hidden-lg price-one-item"> (<?php echo \Yii::t(
                                'app',
                                'price_1_item'
                            ); ?>)</span>
                    </p>
                </td>
                <td>
                    <div class="quantity-wr">
                        <span class="minus">-</span>
                        <input type="text" value="<?php echo $item[ 'count' ]; ?>" class="prod_count">
                        <span class="plus">+</span>
                    </div>
                </td>
                <td>
                    <p class="new_price"><?php echo $item[ 'count' ] * $variant->price; ?>
                        <span> <?= \Yii::t('app', 'hrn') ?></span></p>
                </td>
                <td>
                    <span class="remove_ico"></span>
                    <div class="remove_confirm">
                        <a class="remove-yes" href="#"><?= \Yii::t('app', 'yes') ?></a>
                        <span class="lang_sep"></span>
                        <a class="remove-no" href="#"><?= \Yii::t('app', 'no') ?></a>
                    </div>
                </td>
            </tr>
            <?php
        }
    ?>
</table>
<div class="col-xs-12 col-sm-12 price-total-wr">
    <div class="row">
        <div class="hidden-xs col-sm-6"><span class="total_txt"><?= \Yii::t('app', 'total') ?></span></div>
        <div class="col-sm-12 col-sm-6 price-total"><?php echo $basket->sum; ?><span> <?= \Yii::t(
                    'app',
                    'hrn'
                ) ?></span></div>
    </div>
</div>
