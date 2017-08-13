<?php

use artweb\artbox\components\artboximage\ArtboxImageHelper;
use artweb\artbox\ecommerce\models\Order;
use frontend\assets\PrintAsset;
use yii\helpers\ArrayHelper;use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Order $order
 */
\Yii::$app->i18n->translations['app'] = [
                        'class'          => 'yii\i18n\PhpMessageSource',
                        'basePath'       => '@frontend/translations',
                        'sourceLanguage' => 'en-EN',
                        'fileMap'        => [
                            'app' => 'app.php',
                        ],
];
PrintAsset::register($this);
?>
<?php $this->beginPage(); ?>﻿
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <?php $this->head() ?>
    <script>
        window.print();
    </script>
</head>

<body>
<?php  $this->beginBody(); ?>
<div class="section-box">
    <div class="box-wr">
        <div class="box-all">
            <div class="_print-header-wr style">
                <div class="_print_header-img">
                    <?= Html::a(Html::img("/images/print/logo-extreme.jpg"),["/"]); ?>
                </div>
                <div class="_print_slogan-wr">
                    <div class="_print_slogan"><?= \Yii::t('app','slogan')?></div>
                </div>

                <div class="_print_graph-wr">
                    <div class="_print_graph">
                        <span><?= \Yii::t('app','graph1')?></span><br />
                        Пн-Пт.: <?= \Yii::t('app','from')?> 10.00 - 19.00<br />
                        <?= \Yii::t('app','graph3')?>
                    </div>
                </div>

                <div class="_print_tel-wr">
                    <div class="_print_tel">
                        <span>044 303-90-10</span>
                        <span>044 428-65-38</span>
                        <span>050 382-03-00</span>
                    </div>
                </div>
            </div>
            <div class="_print_orders-wr style">
                <div class="_print_orders-num"><?= \Yii::t('app','order_num')?> <?= $order->id ?></div>

                <table class="_print_title">
                    <tr>
                        <td width="622"><?= \Yii::t('app','print_t1')?></td>
                        <td><?= \Yii::t('app','print_t2')?></td>
                        <td width="140" align="right"><?= \Yii::t('app','print_t3')?></td>
                    </tr>
                </table>

                <table class="_print_tovar">
                <?php
                foreach($order->products as $product) {
                ?>
                    <tr>
                        <td class="_print_img">
                                     <?= Html::a(
                                            ArtboxImageHelper::getImage($product->productVariant->image->imageUrl, 'category_item', [
                                                'alt' => $product->productVariant->product->lang->title,
                                                'title' => $product->productVariant->product->lang->title,
                                                ]),
                                            [
                                                'catalog/product',
                                                'product' => $product->productVariant->product->lang->alias,
                                                'variant' => $product->productVariant->sku,
                                            ],
                                            [
                                                "width"=>"70",
                                                "height"=>"47",
                                            ]
                                        );
                                        ?>
                        </td>
                        <td class="_print_name"><?= Html::a(
                                $product->productVariant->product->lang->title,
                                [
                                    'catalog/product',
                                    'product' => $product->productVariant->product->lang->alias,
                                    'variant' => $product->productVariant->sku,
                                ]
                            );
                            ?>
                            <br/>
                            <?= $product->count ?> <?= \Yii::t('app','hrn')?>
                        </td>
                        <td class="_print_sku"><?= $product->productVariant->sku; ?></td>
                        <td class="_print_properties">
                        <?php foreach ($product->productVariant->properties as $property) {
                            echo $property->lang->title.':'.implode(',', ArrayHelper::getColumn($property->customOptions, 'lang.value')).'<br />';
                        } ?>
                        </td>
                        <td class="_print_quant"><?= $product->count ?> шт.</td>
                        <td class="_print_price"><?= $product->sum_cost ?> <?= \Yii::t('app','hrn')?></td>
                    </tr>
                    <?php } ?>
                </table>

                <div class="_print_delivery style">
                    <div>Доставка</div>
                    <div><?= $order->deliveryString ?></div>
                </div>
                <div class="_print_coast style"><?= \Yii::t('app', 'Доставка') ?> <span><?= $order->delivery_cost?></span> <?= \Yii::t('app','hrn')?> | <?= \Yii::t('app','order_p1')?>  <span><?= $order->total ?> <?= \Yii::t('app','hrn')?></span></div>
                <div class="_print_details style">
                    <div class="_print_details-title"><?= \Yii::t('app','order_p2')?></div>
                    <div class="style">
                        <table class="_print_det">
                            <tr>
                                <td><?= \Yii::t('app','order_p3')?></td>
                                <td>ExtremStyle</td>
                            </tr>

                            <tr>
                                <td><?= \Yii::t('app','order_p4')?></td>
                                <td><?= \Yii::$app->formatter->asDatetime($order->created_at) ?></td>
                            </tr>
                        </table>

                        <table class="_print_det">
                            <tr>
                                <td><?= \Yii::t('app','order_p5')?></td>
                                <td><?= $order->deliveryString ?></td>
                            </tr>

                            <tr>
                                <td><?= \Yii::t('app','order_p6')?></td>
                                <td><?= !empty($order->orderPayment) ? $order->orderPayment->lang->title : '' ?></td>
                            </tr>
                        </table>

                        <table class="_print_det">
                            <tr>
                                <td><?= \Yii::t('app','name_surname')?></td>
                                <td><?= $order->name ?></td>
                            </tr>

                            <tr>
                                <td><?= \Yii::t('app','phone')?></td>
                                <td><?= $order->phone ?></td>
                            </tr>

                            <tr>
                                <td><?= \Yii::t('app','email')?></td>
                                <td><?= $order->email ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php  $this->endBody(); ?>
</body>
</html>
<?php  $this->endPage(); ?>