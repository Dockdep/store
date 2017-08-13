<?php

use artweb\artbox\ecommerce\models\Order;
use artweb\artbox\ecommerce\models\OrderProduct;use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var Order $order
 * @var ArrayDataProvider $dataProvider
 */
?>
<?php $this->beginPage(); ?>﻿
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php $this->head() ?>
    <script>
//        window.print();
    </script>
</head>
<body>
<?php  $this->beginBody(); ?>
<div>
<?php
    echo DetailView::widget([
        'model' => $order,
        'attributes' => [
            [
                'attribute' => 'manager.username',
                'label' => \Yii::t('app', 'Manager Username'),
            ],
            'id',
            'created_at:date',
            [
                'attribute' => 'user.username',
                'label' => \Yii::t('app', 'Client Username'),
            ],
            [
                'attribute' => 'email',
                'value' => $order->email?:(!empty($order->user)?$order->user->email:null),
            ],
            [
                'attribute' => 'phone',
                'value' => $order->phone?:(!empty($order->user)?$order->user->phone:null),
            ],
            [
                'attribute' => 'phone2',
                'value' => $order->phone2?:null,
            ],
            [
                'attribute' => 'numbercard',
                'value' => $order->numbercard?:null,
            ],
            [
                'attribute' => 'comment',
                'value' => $order->comment?:null,
            ],
            [
                'attribute' => 'delivery',
                'value' => $order->deliveryString?:null,
            ],
            [
                'attribute' => 'declaration',
                'value' => $order->declaration?:null,
            ],
            [
                'attribute' => 'stock',
                'value' => $order->stock?:null,
            ],
            [
                'attribute' => 'payment',
                'value' => $order->orderPayment?$order->orderPayment->lang->title:null,
            ],
            [
                'attribute' => 'insurance',
                'value' => $order->insurance?:null,
            ],
            [
                'attribute' => 'amount_imposed',
                'value' => $order->amount_imposed?:null,
            ],
            [
                'attribute' => 'shipping_by',
                'value' => $order->shipping_by?:null,
            ],
            [
                'attribute' => 'city',
                'value' => $order->city?:null,
            ],
            [
                'attribute' => 'adress',
                'value' => $order->adress?:null,
            ],
            [
                'attribute' => 'body',
                'value' => $order->body?:null,
            ],
        ],
    ]);
 ?>
</div>
<div>
<?php
    if(!empty($order->products)) {
        echo GridView::widget([
            'tableOptions' => [
                'cellspacing' => '10',
                'cellpadding' => '3',
            ],
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                ],
                'sku',
                [
                    'attribute' => 'productVariant.product.brand.lang.title',
                    'label' => \Yii::t('app', 'Brand'),
                ],
                [
                    'attribute' => 'productVariant.product.fullName',
                    'label' => \Yii::t('app', 'Fullname'),
                ],
                [
                    'label' => \Yii::t('app', 'Properties'),
                    'value' => function($model) {
                        /**
                        * @var OrderProduct $model
                        */
                        $value = '';
                        foreach ($model->productVariant->properties as $property) {
                                $value .= $property->lang->title.':'.implode(',', ArrayHelper::getColumn($property->customOptions, 'lang.value')).'<br />';
                            }
                        return $value;
                    },
                    'format' => 'html',
                ],
                'count',
                'price',
                'sum_cost',
                'booking',
            ],
            'showOnEmpty' => false,
            'layout' => "{items}",
    ]);
}
 ?>
</div>
<div>
<div style="display: inline-block; padding-right: 10px"><strong><?php echo $order->getAttributeLabel('total').': '.\Yii::$app->formatter->asDecimal($order->total); ?> грн.</strong></div>
<div style="display: inline-block"><strong><?php echo $order->getAttributeLabel('delivery_cost').': '.\Yii::$app->formatter->asText($order->delivery_cost); ?></strong></div>
</div>
<div style="border-bottom: 4px dotted #a1a1a1; width: 100%; margin: 20px 0"></div>
<div>
<?php
echo DetailView::widget([
    'model' => $order,
    'attributes' => [
        'id',
        'created_at:date',
        [
            'attribute' => 'user.username',
            'label' => \Yii::t('app', 'Client Username'),
        ],
        [
            'attribute' => 'phone',
            'value' => $order->phone?:(!empty($order->user)?$order->user->phone:null),
        ],
        [
            'attribute' => 'city',
            'value' => $order->city?:null,
        ],
        [
            'attribute' => 'adress',
            'value' => $order->adress?:null,
        ],
        [
            'attribute' => 'comment',
            'value' => $order->comment?:null,
        ],
        [
            'attribute' => 'stock',
            'value' => $order->stock?:null,
        ],
        [
            'attribute' => 'insurance',
            'value' => $order->insurance?:null,
        ],
        [
            'attribute' => 'amount_imposed',
            'value' => $order->amount_imposed?:null,
        ],
        [
            'attribute' => 'shipping_by',
            'value' => $order->shipping_by?:null,
        ],
    ],
]);
 ?>
</div>
<?php  $this->endBody(); ?>
</body>
</html>
<?php  $this->endPage(); ?>