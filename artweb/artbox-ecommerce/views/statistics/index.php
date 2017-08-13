<?php
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use common\models\User;
    use kartik\daterange\DateRangePicker;
    use kartik\grid\GridView;
    use kartik\select2\Select2;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
    use yii\web\View;
    use yiier\chartjs\ChartJs;
    
    /**
     * @var View               $this
     * @var Label[]            $labels
     * @var User[]             $managers
     * @var array              $labelStatistics
     * @var array              $rejectionStatistics
     * @var ActiveDataProvider $dataProvider
     * @var array              $labelChartData1
     * @var array              $labelChartData2
     * @var array              $labelChartData3
     * @var array              $rejectChartData1
     * @var array              $rejectChartData2
     * @var string             $dateValue
     * @var int | boolean      $dataLabel
     * @var int | boolean      $dataManager
     */
  
    $this->registerJsFile('/admin/js/statistics.js', [
      'position' => View::POS_END,
      'depends' => 'yii\web\JqueryAsset'
    ]);

?>

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Поиск заказов</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div><!-- /.box-tools -->
  </div><!-- /.box-header -->
  <div class="box-body">
      <?= Html::beginForm(
          [ '/ecommerce/statistics' ],
          'get'
      ) ?>
    <div class="row">
      <div class="col-md-3">
          <?= DateRangePicker::widget(
              [
                  'name'          => 'date_range',
                  'value'         => $dateValue,
                  'pluginOptions' => [
                      'locale' => [
                          'format'    => 'DD-MM-Y',
                          'separator' => ' : ',
                      ],
                  ],
              ]
          ) ?>
      </div>
      <div class="col-md-4">
          <?= Select2::widget(
              [
                  'name'          => 'label',
                  'value'         => $dataLabel,
                  'data'          => ArrayHelper::map(
                      $labels,
                      function($model) {
                          return $model->id;
                      },
                      function($model) {
                          return $model->lang->title;
                      }
                  ),
                  'options'       => [
                      'placeholder' => 'Все метки',
                  ],
                  'pluginOptions' => [
                      'allowClear' => true,
                  ],
              ]
          ) ?>
      </div>
      <div class="col-md-3">
          <?= Select2::widget(
              [
                  'name'          => 'manager',
                  'value'         => $dataManager,
                  'data'          => ArrayHelper::map(
                      $managers,
                      function(User $model) {
                          return $model->id;
                      },
                      function(User $model) {
                          return $model->username;
                      }
                  ),
                  'options'       => [
                      'placeholder' => 'Все менеджеры',
                  ],
                  'pluginOptions' => [
                      'allowClear' => true,
                  ],
              ]
          ) ?>
      </div>
      <div class="col-md-2">
          <?= Html::submitButton(
              'Поиск',
              [
                  'class' => 'btn btn-success',
              ]
          ) ?>
      </div>
    </div>
      <?= Html::endForm() ?>
  </div><!-- /.box-body -->
</div><!-- /.box -->

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Метки, статистика за <?= empty($dateValue) ? 'всё время' : $dateValue ?></h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div><!-- /.box-tools -->
  </div><!-- /.box-header -->
  <div class="box-body table-responsive no-padding">
    <table class="table table-hover">
      <tr>
        <td><b>Метка</b></td>
        <td><b>Заказов, шт.</b></td>
        <td><b>На сумму, грн.</b></td>
        <td><b>Заказано товаров, шт.</b></td>
        <td><b>Уникальных товаров, шт.</b></td>
      </tr>
        <?php
          $total_count = 0;
          $total_sum = 0;
          $total_products = 0;
          $total_unique = 0;
            foreach ($labelStatistics as $name => $statistic) {
                $total_count += $statistic[ 'count' ];
                $total_sum += $statistic[ 'sum' ];
                $total_products += $statistic[ 'products' ];
                $total_unique += $statistic[ 'unique' ];
                echo Html::tag(
                    'tr',
                    Html::tag('td', $name) . Html::tag('td', $statistic[ 'count' ]) . Html::tag(
                        'td',
                        $statistic[ 'sum' ]
                    ) . Html::tag('td', $statistic[ 'products' ]) . Html::tag('td', $statistic[ 'unique' ])
                );
            }
            ?>
      <tr>
        <td><b>Всего</b></td>
        <td><b><?=$total_count?></b></td>
        <td><b><?=$total_sum?></b></td>
        <td><b><?=$total_products?></b></td>
        <td><b><?=$total_unique?></b></td>
      </tr>
    </table>
  </div><!-- /.box-body -->

  <div class="box-footer">
    <div class="nav-tabs-custom">

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
          <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Заказы</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Сумма</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Товары</a>
        </li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <?= ChartJs::widget(
                [
                    'type'          => 'bar',
                    'options'       => [
                        'height' => 200,
                        'width'  => 600,
                    ],
                    'data'          => $labelChartData1,
                    'clientOptions' => [
                        'title'  => [
                            'display' => true,
                            'text'    => 'Статистика меток',
                        ],
                        'scales' => [
                            'xAxes' => [
                                [
                                    'display' => false,
                                ],
                            ],
                        ],
                    ],
                ]
            ); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">
            <?= ChartJs::widget(
                [
                    'type'          => 'bar',
                    'options'       => [
                        'height' => 200,
                        'width'  => 600,
                    ],
                    'data'          => $labelChartData2,
                    'clientOptions' => [
                        'title'  => [
                            'display' => true,
                            'text'    => 'Статистика меток',
                        ],
                        'scales' => [
                            'xAxes' => [
                                [
                                    'display' => false,
                                ],
                            ],
                        ],
                    ],
                ]
            ); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="messages">
            <?= ChartJs::widget(
                [
                    'type'          => 'bar',
                    'options'       => [
                        'height' => 200,
                        'width'  => 600,
                    ],
                    'data'          => $labelChartData3,
                    'clientOptions' => [
                        'title'  => [
                            'display' => true,
                            'text'    => 'Статистика меток',
                        ],
                        'scales' => [
                            'xAxes' => [
                                [
                                    'display' => false,
                                ],
                            ],
                        ],
                    ],
                ]
            ); ?>
        </div>
      </div>

    </div>
  </div>
</div><!-- /.box -->

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Причины отказа, статистика за <?= empty($dateValue) ? 'всё время' : $dateValue ?></h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div><!-- /.box-tools -->
  </div><!-- /.box-header -->
  <div class="box-body table-responsive no-padding">
    <table class="table table-hover">
      <tr>
        <td><b>Причина</b></td>
        <td><b>Заказов, шт.</b></td>
        <td><b>На сумму, грн.</b></td>
      </tr>
        <?php
            foreach ($rejectionStatistics as $name => $statistic) {
                echo Html::tag(
                    'tr',
                    Html::tag('td', $name) . Html::tag('td', $statistic[ 'count' ]) . Html::tag(
                        'td',
                        $statistic[ 'sum' ]
                    )
                );
            }
        ?>
    </table>
  </div><!-- /.box-body -->

  <div class="box-footer">
    <div class="nav-tabs-custom">

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
          <a href="#count" aria-controls="count" role="tab" data-toggle="tab">Заказы</a></li>
        <li role="presentation"><a href="#sum" aria-controls="sum" role="tab" data-toggle="tab">Сумма</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="count">
            <?= ChartJs::widget(
                [
                    'type'          => 'bar',
                    'options'       => [
                        'height' => 200,
                        'width'  => 600,
                    ],
                    'data'          => $rejectChartData1,
                    'clientOptions' => [
                        'title'  => [
                            'display' => true,
                            'text'    => 'Статистика отказов',
                        ],
                        'scales' => [
                            'xAxes' => [
                                [
                                    'display' => false,
                                ],
                            ],
                        ],
                    ],
                ]
            ); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="sum">
            <?= ChartJs::widget(
                [
                    'type'          => 'bar',
                    'options'       => [
                        'height' => 200,
                        'width'  => 600,
                    ],
                    'data'          => $rejectChartData2,
                    'clientOptions' => [
                        'title'  => [
                            'display' => true,
                            'text'    => 'Статистика отказов',
                        ],
                        'scales' => [
                            'xAxes' => [
                                [
                                    'display' => false,
                                ],
                            ],
                        ],
                    ],
                ]
            ); ?>
        </div>
      </div>

    </div>
  </div>

</div><!-- /.box -->

<p>
<?php
    echo Html::button(Html::tag('i', '', [
                          'class' => 'glyphicon glyphicon-cog',
                      ]) . ' Создать выгрузку', [
                          'class' => 'btn bg-navy',
                          'id' => 'generate-button',
                          'data-link' => Url::to([
                                                     'export',
                                                     'date_range' => $dateValue,
                                                     'label' => $dataLabel,
                                                     'manager' => $dataManager,
                                                 ])
                      ])
?>
</p>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Заказы</h3>
   
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div><!-- /.box-tools -->
  </div><!-- /.box-header -->
  <div class="box-body table-responsive">
      <?= GridView::widget(
          [
              'dataProvider' => $dataProvider,
              'columns'      => [
                  'id',
                  [
                    'label' => 'Дата добавления',
                    'content' => function(Order $model) {
                      return \Yii::$app->formatter->asDate($model->created_at) .
                          '<br>' . \Yii::$app->formatter->asTime($model->created_at);
                    },
                  ],
                  'name',
                  [
                      'label'   => 'Товары',
                      'content' => function(Order $model) {
                          if (empty($model->products)) {
                              return '';
                          } else {
                              $content = '';
                              $i = 0;
                              foreach ($model->products as $product) {
                                if(empty($product->productVariant)){
                                  $image = '';
                                } else {
                                  $image = $product->productVariant->imageUrl;
                                }
                                  $i++;
                                  $content .= Html::a(
                                      $product->sku,
                                      '#',
                                      [
                                          'onclick'        => 'event.preventDefault();',
                                          'data-toggle'    => 'popover',
                                          'data-placement' => 'right',
                                          'data-html'      => 'true',
                                          'data-content'   => Html::img(
                                                  $image,
                                                  [
                                                      'class' => 'img-rounded',
                                                  ]
                                              ) . Html::tag('p', $product->product_name),
                                      ]
                                  );
                                  if ($i != count($model->products)) {
                                      $content .= ', ';
                                  }
                                  if ($i % 2 == 0) {
                                      $content .= '<br>';
                                  }
                              }
                              return $content;
                          }
                      },
                  ],
                  'city',
                  [
                      'attribute' => 'orderLabel.label',
                      'label'     => 'Метка',
                  ],
                  'total',
                  [
                      'attribute' => 'reason',
                      'content'   => function($model) {
                          /**
                           * @var Order $model
                           */
                          if (empty($model->reason)) {
                              return '';
                          } else {
                              return Order::REASONS[ $model->reason ];
                          }
                      },
                  ],
                  [
                      'attribute' => 'manager.username',
                      'label'     => 'Менеджер',
                  ],
                  [
                      'attribute' => 'body',
                      'content'   => function($model) {
                          /**
                           * @var Order $model
                           */
                          if (empty($model->body)) {
                              return '';
                          } else {
                              return Html::a(
                                  StringHelper::truncate($model->body, 10, '...'),
                                  '#',
                                  [
                                      'data-toggle' => 'tooltip',
                                      'title'       => $model->body,
                                      'onclick'     => 'event.preventDefault();',
                                  ]
                              );
                          }
                      },
                  ],
                  [
                      'content' => function($model) {
                          /**
                           * @var Order $model
                           */
                          return Html::a(
                              Html::tag('i', '', [ 'class' => 'glyphicon glyphicon-eye-open' ]),
                              [
                                  '/ecommerce/order/view',
                                  'id' => $model->id,
                              ],
                              [
                                  'target'    => '_blank',
                                  'data-pjax' => '0',
                              ]
                          );
                      },
                  ],
              ],
          ]
      ) ?>

  </div><!-- /.box-body -->
</div><!-- /.box -->
