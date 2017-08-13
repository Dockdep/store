<?php
    
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\OrderProduct;
    use common\components\CreditHelper;
    use kartik\grid\GridView;
    use yii\data\ActiveDataProvider;
    use yii\grid\SerialColumn;
    use yii\helpers\Html;
    use yii\helpers\StringHelper;
    use yii\web\View;
    use yii\widgets\DetailView;
    use yii\widgets\ListView;
    
    /**
     * @var View               $this
     * @var Order              $model
     * @var ActiveDataProvider $products
     * @var ActiveDataProvider $historyData
     */
    
    $this->title = 'Заказ #' . $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Заказы'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
    
    if (empty($model->payment)) {
        $payment = '';
    } elseif ($model->payment == 10) {
        $payment = Html::tag('h4', $model->orderPayment->lang->title, [ 'class' => 'text-navy' ]) . Html::beginTag(
                'table',
                [ 'class' => 'table table-bordered' ]
            ) . Html::tag(
                'tr',
                Html::tag('td', $model->getAttributeLabel('credit_sum')) . Html::tag(
                    'td',
                    CreditHelper::checkSum(
                        $model->credit_sum
                    )
                )
            ) . Html::tag(
                'tr',
                Html::tag('td', $model->getAttributeLabel('credit_month')) . Html::tag(
                    'td',
                    CreditHelper::checkMonth(
                        $model->credit_month
                    )
                )
            ) . Html::tag(
                'tr',
                Html::tag('td', 'Кредит') . Html::tag('td', $model->total - $model->credit_sum)
            ) . Html::tag(
                'tr',
                Html::tag('td', 'Оплата в месяц') . Html::tag(
                    'td',
                    CreditHelper::getCredit(
                        $model->total - $model->credit_sum,
                        $model->credit_month
                    ) . ' грн/мес'
                )
            ) . Html::endTag('table');
    } else {
        $payment = $model->orderPayment->lang->title;
    }

  $js = <<< JS
  $('[data-toggle="popover"]').popover();
JS;
  $this->registerJs($js, View::POS_READY);

?>
<div class="order-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
      <?= Html::a(
          'Обновить',
          [
              'update',
              'id' => $model->id,
          ],
          [ 'class' => 'btn btn-primary' ]
      ) ?>
      
      <?= Html::a(
          'История',
          [
              'log',
              'id' => $model->id,
          ],
          [ 'class' => 'btn bg-orange' ]
      ) ?>
  </p>

  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Данные заказа</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body">
        <?= DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'id',
                    'deadline',
                    'pay',
                    [
                        'label' => 'Причина',
                        'value' => empty($model->reason) ? '' : Order::REASONS[ $model->reason ],
                    ],
                    [
                        'label' => 'Статус',
                        'value' => empty($model->label) ? '' : $model->orderLabel->lang->title,
                    ],
                    'name',
                    'phone',
                    'email',
                    'comment',
                    [
                        'label' => 'Способ доставки',
                        'value' => empty($model->delivery) ? '' : $model->orderDelivery->lang->title,
                    ],
                    'declaration',
                    'stock',
                    [
                        'label'  => 'Способ оплаты',
                        'value'  => $payment,
                        'format' => 'html',
                    ],
                    'insurance',
                    'city',
                    'adress',
                    'body',
                    'check',
                    'sms',
                ],
            ]
        ) ?>
    </div><!-- /.box-body -->
  </div><!-- /.box -->


  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Товары</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body">
        <?php
            echo GridView::widget(
                [
                    'dataProvider' => $products,
                    'columns'      => [
                        [
                            'class' => SerialColumn::className(),
                        ],
                        'sku',
                        [
                            'attribute' => 'product_name',
                            'content'   => function (OrderProduct $model) {
                                if (!empty( $model->product_name )) {
            
                                    if (empty( $model->productVariant )) {
                                        return '';
                                    }
            
                                    return Html::a(
                                        StringHelper::truncate($model->product_name, 10, '...'),
                                        '#',
                                        [
                                            'onclick'        => 'event.preventDefault();',
                                            'data-toggle'    => 'popover',
                                            'data-placement' => 'right',
                                            'data-html'      => 'true',
                                            'data-content'   => Html::img(
                                                    $model->productVariant->imageUrl,
                                                    [
                                                        'class' => 'img-rounded',
                                                    ]
                                                ) . Html::tag('p', $model->product_name),
                                        ]
                                    );
                                } else {
                                    return '';
                                }
                            },
                        ],
                        [
                            'attribute' => 'productVariant.product.brand.lang.title',
                            'label'     => 'Брэнд',
                        ],
                        [
                            'attribute' => 'productVariant.lang.title',
                            'label'     => \Yii::t('app', 'Цвет'),
                            'content'   => function (OrderProduct $model) {
        
                                if (empty( $model->productVariant )) {
                                    return '';
                                }
        
                                if (preg_match('@.*\.(png|jpg|gif)@i', $model->productVariant->lang->title)) {
                                    return '';
                                } else {
                                    return $model->productVariant->lang->title;
                                }
                            },
                        ],
                        [
                            'attribute' => 'productVariant.size',
                            'label'     => 'Размер',
                        ],
                        'price',
                        'count',
                        'sum_cost',
                        'booking',
                        'status',
                        'return',
                        [
                            'content' => function (OrderProduct $model) {
        
                                if (empty( $model->productVariant )) {
                                    return '<i class="glyphicon glyphicon-remove"></i>';
                                }
        
                                $content = '<table class="table"><tbody><tr><th>Склад</th><th>кол.</th></tr>';
                                foreach ($model->productVariant->variantStocks as $stock) {
                                    $content .= '<tr><td>' . $stock->stock->title . '</td><td>' . $stock->quantity . '</td></tr>';
                                }
                                return Html::a(
                                    '<i class="glyphicon glyphicon-home"></i>',
                                    '#',
                                    [
                                        'onclick'        => 'event.preventDefault();',
                                        'data-toggle'    => 'popover',
                                        'data-placement' => 'left',
                                        'data-html'      => 'true',
                                        'data-content'   => $content . '</tbody></table>',
                                    ]
                                );
                            },
                        ],
                    ],
                ]
            );
        ?>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">История</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body">
        
        
        <?php
            echo ListView::widget(
                [
                    'dataProvider'     => $historyData,
                    'layout'           => '{items}',
                    'itemView'         => '_timeline_item',
                    'itemOptions'      => [
                        'tag' => false,
                    ],
                    'options'          => [
                        'tag'   => $historyData->totalCount == 0 ? 'div' : 'ul',
                        'class' => $historyData->totalCount == 0 ? 'list-view' : 'list-view timeline',
                    ],
                    'emptyText'        => 'У этого заказа пока нет истории',
                    'emptyTextOptions' => [
                        'class' => 'callout callout-info',
                    ],
                ]
            );
        ?>


    </div><!-- /.box-body -->
  </div><!-- /.box -->

</div>
