<?php
    /**
     * @var View          $this
     * @var OrderFrontend $order
     * @var Delivery[]    $deliveries
     */
    use artweb\artbox\ecommerce\models\Delivery;
    use artweb\artbox\ecommerce\models\OrderPayment;
    use common\components\CreditHelper;
    use frontend\models\OrderFrontend;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap\Html;
    use yii\helpers\ArrayHelper;
    use yii\web\View;

?>
<div class="style desk_delivery">
    <div class="forms_ form-register">
        <?php
            $form = ActiveForm::begin(
                [
                    'id' => 'basket-form',
                ]
            );
        ?>
        <?php
            if (\Yii::$app->user->isGuest) {
                ?>
                <div class="row">
                    <?php
                        echo $form->field(
                            $order,
                            'name',
                            [
                                'options' => [
                                    'class' => 'col-xs-12 col-sm-6 col-md-6 input-wr medium-label',
                                ],
                            ]
                        )
                                  ->textInput();
                    ?>
                </div>
                <?php
            }
        ?>
        
        <div class="row">
            <?php
                echo $form->field(
                    $order,
                    'email',
                    [
                        'errorOptions' => [
                            'encode' => false,
                        ],
                        'options'      => [
                            'class' => 'col-xs-12 col-sm-5 col-md-3 input-wr medium-label',
                        ],
                    ]
                )
                          ->textInput();
            ?>
        </div>
        <?php
            if (\Yii::$app->user->isGuest) {
                ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 input-wr emails_txt">
                        <?= \Yii::t('app', 'ordform1') ?><br/>
                        <?= \Yii::t('app', 'ordform2') ?>
                        <a class="terms_of_use" href="#"><?= \Yii::t('app', 'ordform3') ?></a>
                    </div>
                </div>
                <?php /*
            <div class="row">
                <?php
                    echo $form->field(
                        $order,
                        'subscribe',
                        [
                            'options'  => [
                                'class' => 'col-xs-12 col-sm-12 input-wr check-box-form',
                            ],
                            'template' => "{input}\n{label}\n{hint}\n{error}",
                        ]
                    )
                              ->checkbox([ 'class' => 'custom-check' ], false)
                              ->label(
                                  Html::tag('span') . Html::a(
                                      $order->getAttributeLabel('subscribe'),
                                      [
                                          '',
                                          '#' => '',
                                      ]
                                  )
                              );
                    echo $form->field(
                        $order,
                        'notRegister',
                        [
                            'options'  => [
                                'class' => 'col-xs-12 col-sm-12 input-wr check-box-form',
                            ],
                            'template' => "{input}\n{label}\n{hint}\n{error}",
                        ]
                    )
                              ->checkbox([ 'class' => 'custom-check' ], false)
                              ->label(
                                  Html::tag('span') . Html::a(
                                      $order->getAttributeLabel('notRegister'),
                                      [
                                          '',
                                          '#' => '',
                                      ]
                                  )
                              );
                ?>
            </div> */
                ?>
                <?php
            }
        ?>
        <div class="row">
            <?php
                echo $form->field(
                    $order,
                    'phone',
                    [
                        'options' => [
                            'class' => 'col-xs-12 col-sm-5 col-md-3 input-wr medium-label',
                        ],
                    ]
                )
                          ->textInput(
                              [
                                  "placeholder" => "+38(_ _ _) _ _ _- _ _ - _ _",
                              ]
                          );
            ?>
        </div>
        <div class="row">
            <?php
                echo $form->field(
                    $order,
                    'city',
                    [
                        'options' => [
                            'class' => 'col-xs-12 col-sm-5 col-md-3 input-wr medium-label',
                        ],
                    ]
                )
                          ->textInput();
                echo $form->field(
                    $order,
                    'adress',
                    [
                        'options' => [
                            'class' => 'col-xs-12 col-sm-5 col-md-3 input-wr medium-label',
                        ],
                    ]
                )
                          ->textInput();
            ?>
        </div>
        
        <div class="delivery-wrapper">
            <div class="row">
                <div class="col-xs-12 col-sm-12 input-wr required medium-label">
                    <label for=""><?= \Yii::t('app', 'Вариант') ?> доставки</label>
                </div>
            </div>
            
            <div class="row">
                <?php
                    $deliveries = Delivery::find()
                                          ->with('children.lang', 'lang')
                                          ->where([ 'parent_id' => null ])
                                          ->orderBy([ 'sort' => SORT_ASC ])
                                          ->all();
                    foreach ($deliveries as $delivery) {
                        if (empty( $delivery->children )) {
                            echo $form->field(
                                $order,
                                'delivery',
                                [
                                    'enableClientValidation' => false,
                                    'options'                => [
                                        'class' => 'col-xs-12 col-sm-12 input-wr field-orderfrontend-delivery check-box-form hidden_txt',
                                    ],
                                    'template'               => "{input}\n{label}\n{hint}\n{error}",
                                ]
                            )
                                      ->radio(
                                          [
                                              'uncheck' => null,
                                              'value'   => $delivery->id,
                                              'class'   => 'custom-radio root-radio',
                                              'id'      => 'orderfrontend-delivery-' . $delivery->id,
                                          ],
                                          false
                                      )
                                      ->label(
                                          Html::tag('span') . Html::a(
                                              $delivery->lang->title,
                                              [
                                                  '',
                                                  '#' => '',
                                              ]
                                          )
                                      )
                                      ->hint(
                                          $delivery->lang->text,
                                          [
                                              'class' => 'hidden_form_txt',
                                          ]
                                      );
                        } else {
                            ?>
                            <div class="col-xs-12 col-sm-12 input-wr check-box-form  hidden_txt">
                                <input class="custom-radio parent_radio" id="parent_radio_<?php echo $delivery->id; ?>" type="radio" name="parent_radio">
                                <label for="parent_radio_<?php echo $delivery->id; ?>"><span></span><a href="#"><?php echo $delivery->lang->title; ?></a></label>
                                
                                <div class="hidden_form_txt">
                                    <?php
                                        echo $delivery->lang->text;
                                    ?>
                                    <div class="style medium-label">
                                        <label for=""><?= \Yii::t('app', 'chosepunkt') ?>:</label>
                                    </div>
                                    <div class="hidden_form_radio">
                                        <?php
                                            foreach ($delivery->children as $child) {
                                                echo $form->field(
                                                    $order,
                                                    'delivery',
                                                    [
                                                        'enableClientValidation' => false,
                                                        'options'                => [
                                                            'class' => 'col-xs-12 col-sm-12 input-wr field-orderfrontend-delivery check-box-form hidden_txt',
                                                        ],
                                                        'template'               => "{input}\n{label}\n{hint}\n{error}",
                                                    ]
                                                )
                                                          ->radio(
                                                              [
                                                                  'uncheck' => null,
                                                                  'value'   => $child->id,
                                                                  'class'   => 'custom-radio',
                                                                  'id'      => 'orderfrontend-delivery-' . $child->id,
                                                              ],
                                                              false
                                                          )
                                                          ->label(
                                                              Html::tag('span') . Html::a(
                                                                  $child->lang->title,
                                                                  [
                                                                      '',
                                                                      '#' => '',
                                                                  ]
                                                              )
                                                          )
                                                          ->hint(
                                                              $child->lang->text,
                                                              [
                                                                  'class' => 'hidden_form_txt',
                                                              ]
                                                          );
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
        
        <div class="payment-wrapper">
            <div class="row">
                <div class="col-xs-12 col-sm-12 input-wr required medium-label">
                    <label for=""><?= \Yii::t('app', 'Способы оплаты') ?></label>
                </div>
            </div>
            <div class="row">
                <?php
                    foreach (ArrayHelper::index(
                        OrderPayment::find()
                                    ->where([ 'status' => OrderPayment::ACTIVE ])
                                    ->with('lang')
                                    ->asArray()
                                    ->all(),
                        "id"
                    ) as $index => $item) {
                        if($index == 10 && \Yii::$app->basket->getSum() < CreditHelper::MIN_CREDIT_SUM) {
                            continue;
                        }
                        echo $form->field(
                            $order,
                            'payment',
                            [
                                'options'                => [
                                    'class' => 'col-xs-12 col-sm-12 input-wr field-orderfrontend-payment check-box-form' . ( ( $index == 8 ) ? ' hidden' : '' ),
                                    'id'    => 'payment-wrapper-' . $index,
                                ],
                                'enableClientValidation' => false,
                                'template'               => "{input}\n{label}\n{hint}\n{error}",
                            ]
                        )
                                  ->radio(
                                      [
                                          'uncheck' => null,
                                          'class'   => 'custom-radio',
                                          'value'   => $index,
                                          'id'      => 'orderfrontend-payment-' . $index,
                                      ],
                                      false
                                  )
                                  ->label(
                                      Html::tag('span') . Html::a(
                                          $item[ 'lang' ][ 'title' ],
                                          [
                                              '',
                                              '#' => '',
                                          ]
                                      )
                                  )
                                  ->hint(
                                      ( $index == 10 ) ? $this->render(
                                          '_credit_form',
                                          [
                                              'order' => $order,
                                              'form' => $form,
                                          ]
                                      ) : $item[ 'lang' ][ 'text' ],
                                      [
                                          'tag'   => 'div',
                                          'class' => 'hint_block'.(($order->payment == 10)?'':' hidden_form_txt'),
                                      ]
                                  );
                    }
                ?>
            </div>
        </div>
        
        <div class="row">
            <?php
                echo $form->field(
                    $order,
                    'comment',
                    [
                        'options' => [
                            'class' => 'col-xs-12 col-sm-10 col-md-6 input-wr medium-label _area_',
                        ],
                    ]
                )
                          ->textarea(
                              [
                                  'cols' => 30,
                                  'rows' => 10,
                              ]
                          );
            ?>
            <div class="col-xs-12 col-sm-12 add_inform"><?= \Yii::t('app', 'dopinfo') ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 input-wr check-box-form check-box-black plus_txt">
                <?php
                    echo $form->field(
                        $order,
                        'confirm',
                        [
                            'options'  => [
                                'class' => 'form-group',
                            ],
                            'template' => "{input}\n{label}\n{hint}\n{error}",
                        ]
                    )
                              ->checkbox(
                                  [
                                      'class' => 'custom-check',
                                  ],
                                  false
                              )
                              ->label(
                                  Html::tag('span') . Html::a(
                                      $order->getAttributeLabel('confirm'),
                                      [
                                          '',
                                          '#' => '',
                                      ]
                                  )
                              );
                ?>
                <a class="rules_of" href="#"><?= \Yii::t('app', 'rules1') ?></a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 input-wr">
                <button type="submit"><?= \Yii::t('app', 'makeorder') ?></button>
            </div>
        </div>
        <?php
            $form::end();
        ?>
    </div>
</div>
<?php
    $js = "

$('#orderfrontend-phone').mask('+38(000)000-00-00');";
    $this->registerJs($js, View::POS_READY);

?>
