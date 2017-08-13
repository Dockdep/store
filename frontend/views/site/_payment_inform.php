<?php
    /**
     * @var PaymentInform $model
     * @var View          $this
     */
    use frontend\models\PaymentInform;
    use kartik\widgets\DatePicker;
    use kartik\widgets\TimePicker;
    use yii\captcha\Captcha;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;

?>
<div class="modal-body">
    <div class="style model-name-test"> <?php echo \Yii::t('app', 'tellpay'); ?></div>
    <div class="style paym_form-txt">
        <span style="font-weight:700; color:#f26522;"><?php echo \Yii::t('app', 'p_inform1'); ?></span> <?php echo \Yii::t('app', 'p_inform2'); ?>
    </div>
    <div class="style paym_form-txt-2"><?php echo \Yii::t('app', 'p_inform3'); ?></div>
    
    <div class="style inform_form-wr">
        <?php
            $form = ActiveForm::begin(
                [
                    'validationUrl' => [ 'site/payment-inform' ],
                    'ajaxParam'     => 'validation',
                    'options'       => [
                        'enctype' => 'multipart/form-data',
                        'id'      => 'payment_form',
                    ],
                ]
            );
        ?>
        <div class="form-bg-wr">
            <?php
                echo $form->field(
                    $model,
                    'orderId',
                    [
                        'options'              => [
                            'class' => 'input-wr-num',
                        ],
                        'enableAjaxValidation' => true,
                    ]
                )
                          ->textInput();
                echo $form->field(
                    $model,
                    'name',
                    [
                        'options' => [
                            'class' => 'input-wr-name',
                        ],
                    ]
                )
                          ->textInput();
            ?>
            <div class="style download_file">
                <div class="style download_file-txt"><?php echo $model->getAttributeLabel('file'); ?></div>
                <?php
                    echo $form->field(
                        $model,
                        'file',
                        [
                            'options' => [
                                'class' => 'input-wr-file',
                            ],
                        ]
                    )
                              ->fileInput();
                ?>
                <div class="input-wr-file-txt">(gif, jpeg, jpg, bmp)</div>
            </div>
        </div>
        
        <div class="style new_wrapp_in">
            <?php
                echo $form->field(
                    $model,
                    'address',
                    [
                        'options' => [
                            'class' => 'input-wr',
                        ],
                    ]
                )
                          ->textInput();
                echo $form->field(
                    $model,
                    'sum',
                    [
                        'options' => [
                            'class' => 'input-wr-sum',
                        ],
                    ]
                )
                          ->textInput();
                echo Html::tag(
                    'div',
                    \Yii::t(
                        'app',
                        '(Сумма платежа указывается без комиссии. Напечатанная на квитанции над строчкой "Комиссия")'
                    ),
                    [
                        'class' => 'input-wr-sum-txt',
                    ]
                );
                echo $form->field(
                    $model,
                    'bank',
                    [
                        'options' => [
                            'class' => 'input-wr-bank',
                        ],
                    ]
                )
                          ->textInput();
            ?>
            <div class="style">
                <?php
                    echo $form->field(
                        $model,
                        'payedOn',
                        [
                            'options' => [
                                'class' => 'input-wr-payment',
                            ],
                        ]
                    )
                              ->textInput([
                                  'class' => '_datepicer-payment'
                              ]);
                    //                              ->widget(
                    //                                  DatePicker::className(),
                    //                                  [
                    //                                      'layout'        => '{input}{remove}',
                    //                                      'pluginOptions' => [
                    //                                          'autoclose' => true,
                    //                                          'endDate'   => date('d.m.Y'),
                    //                                          'startDate' => date('d.m.Y', strtotime("-1 year")),
                    //                                      ],
                    //                                  ]
                    //                              );
                    echo $form->field(
                        $model,
                        'payedAt',
                        [
                            'options' => [
                                'class' => 'input-wr-payment',
                            ],
                        ]
                    )
                              ->textInput([
                                  "placeholder" => "15:30"
                              ]);
                    //                              ->widget(
                    //                                  TimePicker::className(),
                    //                                  [
                    //                                      'addon'         => Html::tag(
                    //                                          'span',
                    //                                          '',
                    //                                          [
                    //                                              'class' => 'glyphicon glyphicon-remove',
                    //                                          ]
                    //                                      ),
                    //                                      'addonOptions'  => [
                    //                                          'class' => 'timepicker-clear',
                    //                                      ],
                    //                                      'pluginOptions' => [
                    //                                          'showMeridian' => false,
                    //                                          'minuteStep'   => 1,
                    //                                          'defaultTime'  => false,
                    //                                      ],
                    //                                  ]
                    //                              );
                    echo $form->field(
                        $model,
                        'checkNum',
                        [
                            'options' => [
                                'class' => 'input-wr-payment',
                            ],
                        ]
                    )
                              ->textInput();
                ?>
            </div>
            <?php
                echo $form->field(
                    $model,
                    'comment',
                    [
                        'options' => [
                            'class' => 'input-wr',
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
            <div class="style">
                <?php
                    echo $form->field($model, 'captcha')
                              ->label(false)
                              ->widget(Captcha::className());
                ?>
            </div>
            <div class="style">
                <?php
                    echo Html::submitButton(\Yii::t('app', 'отправить'));
                ?>
            </div>
        </div>
        <?php
            $form::end();
        ?>
    </div>
</div>
