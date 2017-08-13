<?php
    /**
     * @var ActiveDataProvider $dataProvider
     * @var OrderSearch        $searchModel
     * @var View               $this
     */
    
    use artweb\artbox\ecommerce\models\Delivery;
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\OrderSearch;
    use common\models\User;
    use kartik\daterange\DateRangePicker;
    use kartik\select2\Select2;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use kartik\grid\GridView;
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
    use yii\web\JsExpression;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;
    
    $this->title = 'Заказы';
    $this->params[ 'breadcrumbs' ][] = $this->title;
    
    $js = <<< JS
$('[name="OrderSearch[phone]"]').mask('+38(000)000-00-00', {
    placeholder: '+38(___)___-__-__'
});
JS;
    
    $this->registerJs($js, View::POS_READY);

?>
<h1>Заказы</h1>
<p>
    <?= Html::a(\Yii::t('app', 'Добавить заказ'), [ 'create' ], [ 'class' => 'btn btn-success btn-lg' ]) ?>
</p>
<?php Pjax::begin(); ?>
<?php
    $searchForm = ActiveForm::begin(
        [
            'method' => 'GET',
            'action' => Url::to([ 'order/index' ]),
            'id'     => 'search-form',
        ]
    );
?>


    
   

<?php
//    echo Html::button(\Yii::t('app', 'Скрыть/Показать'), [
//  'data-toggle' => 'collapse',
//  'data-target' => '#search-fields',
//  'class' => 'btn btn-default'
//]);
//?>
<div class="box box-primary">
  <div class="box-header with-border">
<!--    <h3 class="box-title"></h3>-->
      <?php echo Html::submitButton(
          \Yii::t('app', 'Искать'),
          [
              'class' => 'btn btn-primary',
          ]
      ) ?>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  
<div class="row box-body" id="search-fields">
  <div class="col-md-4">
      <?php
          echo $searchForm->field($searchModel, 'label')
                          ->widget(
                              Select2::className(),
                              [
                                  'data'          => Label::find()
                                                          ->joinWith('lang')
                                                          ->select(
                                                              [
                                                                  'CONCAT(order_label.label,order_label_lang.title) AS name',
                                                                  'id',
                                                              ]
                                                          )
                                                          ->indexBy('id')
                                                          ->asArray()
                                                          ->column(),
                                  'options'       => [ 'placeholder' => 'Выберите метки ...' ],
                                  'pluginOptions' => [
                                      'allowClear' => true,
                                      'multiple'   => true,
                                  ],
                              ]
                          );
      ?>
      <?php
          echo $searchForm->field($searchModel, 'delivery')
                          ->widget(
                              Select2::className(),
                              [
                                  'data'          => Delivery::find()
                                                             ->joinWith('lang')
                                                             ->select('order_delivery_lang.title, id')
                                                             ->indexBy('id')
                                                             ->asArray()
                                                             ->column(),
                                  'options'       => [ 'placeholder' => 'Выберите способ доставки ...' ],
                                  'pluginOptions' => [
                                      'allowClear' => true,
                                      'multiple'   => true,
                                  ],
                              ]
                          );
      ?>
      
      <?php
          $query = new JsExpression(
              'function(params) { return {q:params.term}; }'
          );
          echo $searchForm->field($searchModel, 'sku')
                          ->widget(
                              Select2::className(),
                              [
                                  'options'       => [ 'placeholder' => 'Search for a product ...' ],
                                  'pluginOptions' => [
                                      'allowClear'         => true,
                                      'minimumInputLength' => 3,
                                      'language'           => [
                                          'errorLoading' => new JsExpression(
                                              "function () { return 'Waiting for results...'; }"
                                          ),
                                      ],
                                      'ajax'               => [
                                          'url'      => \yii\helpers\Url::to([ 'find-product' ]),
                                          'dataType' => 'json',
                                          'data'     => $query,
                                      ],
                                      'escapeMarkup'       => new JsExpression(
                                          'function (markup) { return markup; }'
                                      ),
                                      'templateResult'     => new JsExpression(
                                          'function(data) { return data.sku; }'
                                      ),
                                      'templateSelection'  => new JsExpression(
                                          'function (data) {
                                     if(data.sku == undefined) {
                                         return "sku"; 
                                     } else {
                                         return data.sku;
                                     }
                                    }'
                                      ),
                                  ],
                              ]
                          );
      
      ?>
  </div>


  <div class="col-md-4">
      <?= $searchForm->field($searchModel, 'manager_id')
                     ->dropDownList(
                         ArrayHelper::map(
                             User::find()
                                 ->asArray()
                                 ->all(),
                             'id',
                             'username'
                         ),
                         [ 'prompt' => \Yii::t('app', 'Выберите менеджера ...') ]
                     ) ?>
      
      <?= $searchForm->field($searchModel, 'email')
                     ->textInput() ?>

  </div>
  <div class="col-md-4">
      <?= $searchForm->field($searchModel, 'declaration')
                     ->textInput() ?>
      
      <?= $searchForm->field($searchModel, 'consignment')
                     ->textInput() ?>
  </div>
</div>

</div>
  
<p>
    <?php
        echo GridView::widget(
            [
                'hover'        => true,
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'rowOptions'   => function($model) {
                    if ($model->wasted) {
                        return [ 'class' => 'danger' ];
                    } else {
                        return [];
                    }
                },
                'columns'      => [
                    [
                        'attribute' => 'id',
                        'filter'    => $searchForm->field($searchModel, 'id')
                                                  ->textInput(),
                        'content'   => function($model) {
                            $manager = $model->manager;
                            if (empty($manager)) {
                                return Html::a($model->id, ['update', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => '0']);
                            } else {
                                return Html::a($model->id, ['update', 'id' => $model->id], ['target' => '_blank', 'data-pjax' => '0']) . '<br>' . $manager->username;
                            }
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'content'   => function($model) {
                            return date('d/m/Y', $model->created_at) . '<br>' . date('G:i', $model->created_at);
                        },
                        'filter'    => $searchForm->field($searchModel, 'date_range')
                                                  ->widget(
                                                      DateRangePicker::className(),
                                                      [
                                                          'convertFormat' => false,
                                                          'pluginOptions' => [
                                                              'locale' => [
                                                                  'format'    => 'D-M-Y',
                                                                  'separator' => ' to ',
                                                              ],
                                                              'opens'  => 'left',
                                                          ],
                                                      ]
                                                  )
                                                  ->label(false)
                                                  ->render(),
                    ],
                    [
                        'attribute' => 'deadline',
                        'content'   => function($model) {
                            if ($model->deadline == '') {
                                return '';
                            } else {
                                return \Yii::$app->formatter->asDate(
                                        $model->deadline,
                                        'php:d M'
                                    ) . '<br>' . \Yii::$app->formatter->asDate($model->deadline, 'php:Y г');
                            }
                        },
                    ],
                    'name',
                    [
                        'attribute' => 'total',
                        'content'   => function($model) {
                            if (empty($model->total)) {
                                return '';
                            } else {
                                return $model->total;
                            }
                        },
                    ],
                    [
                        'attribute' => 'pay',
                        'content'   => function($model) {
                            if ($model->pay == false) {
                                return '<span class="glyphicon glyphicon-remove"></span>';
                            } else {
                                return '<span class="glyphicon glyphicon-ok"></span>';
                            }
                        },
                    ],
                    [
                      'attribute' => 'phone',
                      'content' => function($model) {
                          return $model->phone . '<br>' . $model->phone2;
                      }
                    ],
                    [
                        'attribute' => 'adress',
                        'content'   => function($model) {
                            if (!empty($model->adress)) {
                                return Html::a(
                                    StringHelper::truncate($model->adress, 10, '...'),
                                    '#',
                                    [
                                        'data-toggle' => 'tooltip',
                                        'title'       => $model->adress,
                                        'onclick'     => 'event.preventDefault();',
                                    ]
                                );
                            } else {
                                return '';
                            }
                        },
                    ],
                    [
                        'attribute' => 'label',
                        'filter'    => false,
                        'value'     => function($model) {
                            /**
                             * @var Order $modl
                             */
                            if (empty($model->orderLabel)) {
                                return '--';
                            } else {
                                return $model->orderLabel->label;
                            }
                        },
                    ],
                    [
                        'attribute' => 'body',
                        'content'   => function($model) {
                            if (!empty($model->body)) {
                                return StringHelper::truncate($model->body, 10, '...');
                            } else {
                                return '';
                            }
                        },
                    ],
                    [
                        'attribute' => 'sms',
                        'content'   => function($model) {
                            if (!empty($model->sms)) {
                                return Html::a(
                                    StringHelper::truncate($model->sms, 10, '...'),
                                    '#',
                                    [
                                        'data-toggle' => 'tooltip',
                                        'title'       => $model->sms,
                                        'onclick'     => 'event.preventDefault();',
                                    ]
                                );
                            } else {
                                return '';
                            }
                        },
                    ],
                    [
                        'class'    => 'yii\grid\ActionColumn',
                        'template' => \Yii::$app->user->identity->isAdmin(
                        ) ? '{history} {view} {update} {delete}' : '{view} {update}',
                        'buttons'  => [
                            'update' => function($url, $model) {
                                return Html::a(
                                    Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-pencil' ]),
                                    $url,
                                    [
                                      'target' => '_blank',
                                      'data-pjax' => '0',
                                    ]
                                );
                            },
                            'history' => function($url, $model) {
                                return Html::a(
                                    Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-time' ]),
                                    ['log', 'id' => $model->id],
                                    [
                                        'target' => '_blank',
                                        'data-pjax' => '0',
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]
        );
    ?>
</p>
<?php
    ActiveForm::end();
?>
<?php Pjax::end(); ?>
