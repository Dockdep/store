<?php
    
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\OrderPayment;
    use artweb\artbox\ecommerce\models\OrderProduct;
    use backend\models\SmsTemplate;
    use common\models\User;
    use kartik\grid\GridView;
    use kartik\grid\SerialColumn;
    use kartik\widgets\DatePicker;
    use kartik\widgets\Select2;
    use kartik\widgets\SwitchInput;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\ArrayHelper;
    use artweb\artbox\ecommerce\models\Delivery;
    use yii\helpers\StringHelper;
    use yii\web\View;
    use yii\web\JsExpression;
    use yii\widgets\Pjax;
    
    /**
     * @var View               $this
     * @var Order              $model
     * @var ActiveForm         $form
     * @var ActiveDataProvider $dataProvider
     * @var User               $user
     */
    
    $user = \Yii::$app->user->identity;
    
    $js = <<< JS
$(document).on('submit', '#add-product-form', function(e) {
    e.preventDefault();
    var addFormData = $(this).serializeArray();
    var addFormAction = this.action;
    $.ajax({
        url: addFormAction,
        type: "POST",
        data: addFormData,
        success: function (data) {
             if (data.status === "success") {
                 $.pjax.reload({container:"#order-products-grid"});
             }
        },
        error: function () {
        }
    });
});
$(document).on('click', '.delete-button', function(e) {
    e.preventDefault();
    var link = $(this).attr('href') + '&order_id=' + {$model->id};
    $.ajax({
        url: link,
        type: "GET",
        success: function (data) {
             if (data.status === "success") {
                 $.pjax.reload({container:"#order-products-grid"});
                 $('[data-toggle="popover"]').popover();
             }
        },
        error: function () {
        }
    });
});
JS;
    
    $this->registerJs($js, View::POS_READY);
    
    $js = <<< JS
$('#order-phone, #order-phone2').mask('+38(000)000-00-00', {
    placeholder: '+38(___)___-__-__'
});
$('[data-toggle="popover"]').popover();
$(document).on('pjax:end', '#order-products-grid', function() {
  $('[data-toggle="popover"]').popover();
  $.pjax.reload({container: '#total-cost'});
});
JS;
    
    $this->registerJs($js, View::POS_READY);
    
    $js = <<< JS
$(document).on('change', '#sms-template-selector', function(event) {
    var text = $('#select2-sms-template-selector-container').attr('title');
    var val = $('option:contains(' + text + ')').attr('value');
    $('#sms-text-area').val(val);
});

$(document).on('click', '#send-sms-action', function(event) {
    var variant = $('input[name=send-phone]:checked').val();
    var content = $('#sms-text-area').val();
    if (variant == 1) {
        var phone = $('input#order-phone').val();    
    } else if (variant == 2) {
        var phone = $('input#order-phone2').val();    
    }
    console.log(phone);
    $.ajax({
        url: "/admin/ecommerce/order/send-sms",
        method: "POST",
        data: { 
            phone: phone,
            content: content
         },
        success: function(data) {
          console.log(data);
          var newButton = document.createElement('button');
          newButton.classList.add('btn', 'btn-default');
          newButton.innerText = 'Отправлено';
          var current = document.getElementById("send-sms-action");
          var parentDiv = current.parentNode;
          parentDiv.replaceChild(newButton, current);
        }
    });
});
JS;
    
    $this->registerJs($js, View::POS_READY);
    
    $js = <<< JS
$(document).on('click', '#page-submit', function() {
  var phone =  $('#order-phone').val(); 
  $.ajax({
    url: "/admin/ecommerce/order/publish-order",
    type: "GET",
    data: {
        id: {$model->id},
        phone: phone
    },
    success: function (data) {

    },
    error: function () {
    }
  });
  $('#main-form').trigger('submit');
});
JS;
    
    $this->registerJs($js, View::POS_READY);

?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'main-form',
    ]
); ?>


<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Заказ</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="box-body">
    
    
<div class="container">
  <div class="form-group">
    <br>


    
    <div class="row">
      <div class="col-sm-6">
          
          <?= $form->field($model, 'deadline')
                   ->widget(
                       DatePicker::className(),
                       [
              
                       ]
                   ) ?>
          
          <?php
              if ($user->isAdmin()) {
                  echo $form->field($model, 'pay')
                            ->widget(
                                SwitchInput::className(),
                                [
                                    'name'          => 'pay',
                                    'pluginOptions' => [
                                        'onText'  => \Yii::t('app', 'Оплачено'),
                                        'offText' => \Yii::t('app', 'Не оплачено'),
                                    ],
                                ]
                            );
              }
          ?>
          
          <?= $form->field($model, 'reason')
                   ->dropDownList(
                       Order::REASONS,
                       [ 'prompt' => 'Выберите причину' ]
                   ) ?>
          
          <?= $form->field($model, 'label')
                   ->dropDownList(
                       ArrayHelper::map(
                           Label::find()
                                ->asArray()
                                ->all(),
                           'id',
                           'label'
                       ),
                       [ 'prompt' => 'Выберите метку' ]
                   ); ?>
          
          <?= $form->field($model, 'name') ?>
          
          <?= $form->field($model, 'phone') ?>
          
          <?= $form->field($model, 'phone2') ?>
          
          <?= $form->field($model, 'email')
                   ->textInput([ 'readonly' => $user->isAdmin() ? false : true ]) ?>
          
          <?= $form->field(
              $model,
              'numbercard'
          )
                   ->textInput([ 'readonly' => true ]) ?>
          
          <?= $form->field($model, 'comment')
                   ->textarea([ 'rows' => '3' ]) ?>
          <?= $form->field($model, 'delivery')
                   ->dropDownList(
                       ArrayHelper::map(
                           Delivery::find()
                                   ->joinWith('lang')
                                   ->asArray()
                                   ->all(),
                           'id',
                           'lang.title'
                       ),
                       [ 'prompt' => \Yii::t('app', 'Выберите доставку ...') ]
                   ) ?>
          
          <?php
              
              if ($user->isAdmin()) {
                  echo $form->field($model, 'manager_id')
                            ->dropDownList(
                                ArrayHelper::map(
                                    User::find()
                                        ->asArray()
                                        ->all(),
                                    'id',
                                    'username'
                                ),
                                [ 'prompt' => \Yii::t('app', 'Менеджер') ]
                            );
              }
          ?>

        <h2><?php echo \Yii::t('app', 'Отправить смс'); ?></h2>
          <?php
              echo Select2::widget(
                  [
                      'id'            => 'sms-template-selector',
                      'name'          => 'select-sms-template',
                      'data'          => ArrayHelper::map(
                          SmsTemplate::find()
                                     ->asArray()
                                     ->all(),
                          'text',
                          'title'
                      ),
                      'options'       => [ 'placeholder' => \Yii::t('app', 'Выберите шаблон') ],
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                  ]
              );
          
          ?>
        <br>
          <?php
              echo Html::textarea(
                  'sms-text',
                  '',
                  [
                      'rows'  => 3,
                      'id'    => 'sms-text-area',
                      'class' => 'form-control',
                  ]
              );
          ?>
        <br>
        <div class="row">
          <div class="col-md-6">
              <?php
                  if ($model->isNewRecord) {
                      echo Html::button(
                          \Yii::t('app', 'Отправить'),
                          [
                              'class' => 'btn btn-warning disabled',
                          ]
                      );
                  } else {
                      echo Html::button(
                          \Yii::t('app', 'Отправить'),
                          [
                              'class' => 'btn btn-warning',
                              'id'    => 'send-sms-action',
                          ]
                      );
                  }
              ?>
          </div>
          <div class="col-md-6">
              <?php
                  echo Html::radioList(
                      'send-phone',
                      '1',
                      [
                          '1' => 'Первый номер',
                          '2' => 'Второй номер',
                      ]
                  );
              ?>
          </div>
        </div>

      </div>
      <div class="col-sm-6">
          
          <?= $form->field($model, 'declaration') ?>
          
          <?= $form->field($model, 'stock') ?>
          
          <?= $form->field($model, 'consignment') ?>
          
          <?= $form->field($model, 'payment')
                   ->dropDownList(
                       ArrayHelper::map(
                           OrderPayment::find()
                                       ->where([ 'status' => OrderPayment::ACTIVE ])
                                       ->asArray()
                                       ->all(),
                           'id',
                           'short'
                       ),
                       [
                         'prompt' => 'Способ оплаты ...',
                         'disabled' => $model->payment == 10 ? 'disabled' : false,
                       ]
                   ); ?>
          
          <?= $form->field($model, 'insurance') ?>
          
          <?= $form->field($model, 'amount_imposed') ?>
          
          <?= $form->field($model, 'shipping_by')
                   ->dropDownList(
                       ArrayHelper::getColumn(Order::SHIPPING_BY, 'label'),
                       [ 'prompt' => 'Оплата доставки ...' ]
                   ); ?>
          
          <?= $form->field($model, 'city') ?>
          
          <?= $form->field($model, 'adress') ?>
          
          <?= $form->field($model, 'body')
                   ->textarea([ 'rows' => '3' ]) ?>
          
          <?= $form->field($model, 'check') ?>
          
          <?= $form->field($model, 'sms') ?>
          
          <?= $form->field($model, 'delivery_cost') ?>

      </div>
    </div>


    
  </div>
</div>

  </div><!-- /.box-body -->
</div><!-- /.box -->

<?php ActiveForm::end(); ?>


<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Товары</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="box-body">

<div class="container">
    <div class="row">
        <?php
            echo GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'rowOptions'   => function ($model) {
                        if ($model->removed) {
                            return [ 'class' => 'danger' ];
                        } else {
                            return [];
                        }
                    },
                    'layout'       => '{items}{pager}',
                    'columns'      => [
                        [
                            'class' => SerialColumn::className(),
                        ],
                        'sku',
                        [
                            'attribute' => 'product_name',
                            'content'   => function ($model) {
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
                            'content'   => function ($model) {
                                
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
                        [
                            'class'           => 'kartik\grid\EditableColumn',
                            'attribute'       => 'count',
                            'editableOptions' => [
                                'header'       => \Yii::t('app', 'Количество'),
                                'inputType'    => kartik\editable\Editable::INPUT_SPIN,
                                'options'      => [
                                    'pluginOptions' => [
                                        'min' => 0,
                                        'max' => 5000,
                                    ],
                                ],
                                'pluginEvents' => [
                                    'editableSuccess' => 'function(event) { $.pjax.reload({container:"#order-products-grid"}); }',
                                ],
                            ],
                            'format'          => [
                                'decimal',
                                0,
                            ],
                            'pageSummary'     => false,
                        ],
                        'sum_cost',
                        [
                            'class'           => 'kartik\grid\EditableColumn',
                            'attribute'       => 'booking',
                            'editableOptions' => [
                                'header'       => \Yii::t('app', 'Бронь'),
                                'inputType'    => kartik\editable\Editable::INPUT_TEXT,
                                'options'      => [
                                    'class'         => 'booking-typeahead',
                                    'pluginOptions' => [
                                        'min' => 0,
                                        'max' => 20,
                                    ],
                                ],
                                'pluginEvents' => [
                                    'editableSuccess' => 'function(event) { $.pjax.reload({container:"#order-products-grid"}); }',
                                ],
                            ],
                            'format'          => [
                                'text',
                            ],
                            'pageSummary'     => false,
                        ],
                        [
                            'class'           => 'kartik\grid\EditableColumn',
                            'attribute'       => 'status',
                            'editableOptions' => [
                                'header'       => \Yii::t('app', 'Статус'),
                                'inputType'    => kartik\editable\Editable::INPUT_TEXT,
                                'options'      => [
                                    'class'         => 'status-typeahead',
                                    'pluginOptions' => [
                                        'min' => 0,
                                        'max' => 20,
                                    ],
                                ],
                                'pluginEvents' => [
                                    'editableSuccess' => 'function(event) { $.pjax.reload({container:"#order-products-grid"}); }',
                                ],
                            ],
                            'format'          => [
                                'text',
                            ],
                            'pageSummary'     => false,
                        ],
                        [
                            'class'           => 'kartik\grid\EditableColumn',
                            'attribute'       => 'return',
                            'editableOptions' => [
                                'header'       => \Yii::t('app', 'Возврат'),
                                'inputType'    => kartik\editable\Editable::INPUT_CHECKBOX,
                                'options'      => [],
                                'pluginEvents' => [
                                    'editableSuccess' => 'function(event) { $.pjax.reload({container:"#order-products-grid"}); }',
                                ],
                            ],
                            'format'          => [
                                'boolean',
                            ],
                            'pageSummary'     => false,
                        ],
                        [
                            'content' => function ($model) {
                                
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
                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'buttons'  => [
                                'delete' => function ($url, $product) {
                                    if ($product->removed) {
                                        return '';
                                    } else {
                                        return Html::a(
                                            Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-trash' ]),
                                            [
                                                'delete-product',
                                                'id' => $product->id,
                                            ],
                                            [
                                                'class' => 'delete-button',
                                            ]
                                        );
                                    }
                                },
                            ],
                        ],
                    ],
                    'responsive'   => true,
                    'hover'        => true,
                    'pjax'         => true,
                    'pjaxSettings' => [
                        'options' => [
                            'scrollTo' => 'false',
                            'id'       => 'order-products-grid',
                        ],
                    ],
                ]
            );
        ?>
    </div>
</div>
<div class="container">
    <?php Pjax::begin([ 'id' => 'total-cost' ]); ?>
    <h2>Сумма заказа : <span class="label label-success"><?php echo $model->total; ?><?php echo \Yii::t(
                'app',
                'грн'
            ) ?></span></h2>
    <?php Pjax::end(); ?>
</div>
<div class="container">
    <div class="row">
        <?php $newProductForm = ActiveForm::begin(
            [
                'action' => yii\helpers\Url::to([ 'add' ]),
                'id'     => 'add-product-form',
            ]
        );
            $newOrderProduct = new OrderProduct();
        ?>
        <div class="col-md-8">
            <?php echo $newProductForm->field($newOrderProduct, 'id')
                                      ->widget(
                                          Select2::className(),
                                          [
                                              'options'       => [ 'placeholder' => 'Search for a product ...' ],
                                              'pluginOptions' => [
                                                  'allowClear'         => true,
                                                  'disabled'           => $model->isNewRecord ? true : false,
                                                  'minimumInputLength' => 3,
                                                  'language'           => [
                                                      'errorLoading' => new JsExpression(
                                                          "function () { return 'Waiting for results...'; }"
                                                      ),
                                                  ],
                                                  'ajax'               => [
                                                      'url'      => \yii\helpers\Url::to([ 'find-product' ]),
                                                      'dataType' => 'json',
                                                      'data'     => new JsExpression(
                                                          'function(params) { return {q:params.term}; }'
                                                      ),
                                                  ],
                                                  'escapeMarkup'       => new JsExpression(
                                                      'function (markup) { return markup; }'
                                                  ),
                                                  'templateResult'     => new JsExpression(
                                                      'function(data) { return data.sku; }'
                                                  ),
                                                  'templateSelection'  => new JsExpression(
                                                      'function (data) { return data.sku; }'
                                                  ),
                                              ],
                                          ]
                                      )
                                      ->label('Артикул');
            
            ?>
        </div>
        <div class="col-md-2">
            <?php echo $newProductForm->field(
                $newOrderProduct,
                'count'
            )
                                      ->input(
                                          'number',
                                          [
                                              'disabled' => $model->isNewRecord ? true : false,
                                          ]
                                      ); ?>
        </div>
        <div class="col-md-2" style="margin-top: 23px">
            <?php echo Html::submitButton(
                \Yii::t('app', 'Добавить'),
                [
                    'class' => $model->isNewRecord ? 'btn btn-primary disabled' : 'btn btn-primary',
                ]
            ) ?>
        </div>
        <?php echo $newProductForm->field($newOrderProduct, 'order_id')
                                  ->hiddenInput(
                                      [
                                          'value' => $model->id,
                                      ]
                                  )
                                  ->label(false) ?>
        <?php ActiveForm::end(); ?>
    </div>
    


</div>

  </div><!-- /.box-body -->
</div><!-- /.box -->
    
    
    <br>
<div class="container">
    <div class="row">
        <?= Html::button(
            $model->isNewRecord ? \Yii::t('app', 'Создать') : \Yii::t('app', 'Сохранить'),
            [
                'class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg',
                'id'    => 'page-submit',
            ]
        ) ?>
        <?= Html::a(
            \Yii::t('app', 'Печать'),
            yii\helpers\Url::to(
                [
                    'order/print',
                    'order_id' => $model->id,
                ]
            ),
            [
                'class'  => $model->isNewRecord ? 'btn btn-info disabled btn-lg' : 'btn btn-info btn-lg',
                'target' => '_blank',
            ]
        ) ?>
        <?= Html::a(
            \Yii::t('app', 'Выйти'),
            yii\helpers\Url::to(
                [
                    'close-order',
                    'id' => $model->id,
                ]
            ),
            [
                'class' => $model->isNewRecord ? 'btn btn-info disabled btn-lg' : 'btn btn-info btn-lg',
            ]
        ) ?>
    </div>
</div>
<br>
<br>
