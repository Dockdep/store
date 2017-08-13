<?php
    
    /**
     * @var Order $model
     */
    use artweb\artbox\ecommerce\models\Order;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\ArrayHelper;
    use artweb\artbox\ecommerce\models\Delivery;
    
    $this->title = 'Заказ №' . $model->id;
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="show_style">
    
    
    <?php if (!empty( $_GET[ 'success' ] )): ?>
        <div class="alert alert-success">
            Заказ успешно сохранен!
        </div>
    <?php endif; ?>
    
    <?php $form = ActiveForm::begin(
        [
            'id'          => 'reg-form',
            'layout'      => 'horizontal',
            'options'     => [ 'enctype' => 'multipart/form-data' ],
            'fieldConfig' => [
                //'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                //'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
            'action'      => [
                'order/show',
                'id' => $model->id,
            ],
        ]
    ); ?>
    
    <div class="row">
        <div class="row">
            <div class="col-sm-12">
                <h5>Заказ №<?= $model->id ?></h5>
                <label class="control-label col-sm-3">Дата</label>
                <?= $model->date_time ?>
            </div>
            
            <?php /* echo $form->field($model, 'date_dedline')
		->widget(DatePicker::className(), [
			'pluginOptions' => [
				'format' => 'dd-mm-yyyy',
				'todayHighlight' => true
			]]); */ ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'name') ?>
            
            
            <?= $form->field($model, 'phone') ?>
            
            <?php echo $form->field($model, 'phone2'); ?>
            
            <?= $form->field($model, 'email') ?>
            
            <?php echo $form->field($model, 'numbercard'); ?>
            
            <?php echo $form->field($model, 'body')
                            ->textarea([ 'rows' => '3' ]); ?>
        </div>
        <div class="col-sm-6">
            <?php echo $form->field($model, 'delivery')
                            ->dropDownList(
                                ArrayHelper::map(
                                    Delivery::find()
                                            ->joinWith('lang')
                                            ->asArray()
                                            ->all(),
                                    'id',
                                    'lang.title'
                                )
                            ); ?>
            
            <?php echo $form->field($model, 'declaration'); ?>
            
            <?php echo $form->field($model, 'stock'); ?>
            
            <?php echo $form->field($model, 'consignment'); ?>
            
            <?php echo $form->field($model, 'payment')
                            ->dropDownList(
                                [
                                    'Оплатить наличными'               => 'Оплатить наличными',
                                    'Оплатить на карту Приват Банка'   => 'Оплатить на карту Приват Банка',
                                    'Оплатить по безналичному расчету' => 'Оплатить по безналичному расчету',
                                    'Оплатить Правекс-телеграф'        => 'Оплатить Правекс-телеграф',
                                    'Наложенным платежом'              => 'Наложенным платежом',
                                ],
                                [ 'prompt' => '...' ]
                            ); ?>
            
            <?php echo $form->field($model, 'insurance'); ?>
            
            <?php echo $form->field($model, 'amount_imposed'); ?>
            
            <?php echo $form->field($model, 'shipping_by'); ?>
            
            <?php echo $form->field($model, 'city');
                
                echo $form->field($model, 'adress');
                
                echo $form->field($model, 'total');
                
                echo $form->field($model, 'status')
                          ->dropDownList(
                              [
                                  'Нет'              => 'Нет',
                                  'Обработан'        => 'Обработан',
                                  'На комплектации'  => 'На комплектации',
                                  'Укомплектован'    => 'Укомплектован',
                                  'Доставка'         => 'Доставка',
                                  'Выполнен'         => 'Выполнен',
                                  'Резерв оплачен'   => 'Резерв оплачен',
                                  'Резерв неоплачен' => 'Резерв неоплачен',
                              ],
                              [ 'prompt' => '...' ]
                          ); ?>
            
            <?= $form->field($model, 'comment')
                     ->textarea([ 'rows' => '3' ]) ?>
        </div>
    </div>
    <div class="both"></div>
    
    <hr/>
    &nbsp;
    
    
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'columns'      => [
                [
                    'attribute'      => 'id',
                    'value'          => 'id',
                ],
                [
                    'attribute'      => 'sku',
                    'value'          => 'sku',
                ],
                [
                    'attribute'      => 'product_name',
                    'value'          => 'product_name',
                ],
                //		[
                //		'attribute' => 'size',
                //		'value'=>'mod.size',
                //			'contentOptions'=>['style'=>'width: 100px;']
                //		],
                //		[
                //			'attribute' => 'size',
                //			'value'=>'mod.color',
                //			'contentOptions'=>['style'=>'width: 100px;']
                //		],
                [
                    'attribute'      => 'price',
                    'value'          => 'price',
                ],
                [
                    'attribute'      => 'count',
                    'value'          => 'count',
                ],
                [
                    'attribute'      => 'sum_cost',
                    'value'          => 'sum_cost',
                ],
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '{delete}',
                    'buttons'        => [
                        'delete' => function($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                [
                                    'order/delete-product',
                                    'id'       => $model->id,
                                    'order_id' => (int)\Yii::$app->request->get('id'),
                                ],
                                [
                                    'class' => 'delete-ajax',
                                ]
                            );
                        },
                    ],
                ],
            
            ],
        ]
    ) ?>
    <div class="form-group">
        <?= Html::submitButton(
            ' Сохранить ',
            [
                'class' => 'btn btn-primary btn-lg btn-block',
                'name'  => 'login-button',
            ]
        ) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
    
    <div class="row">
        <div class="col-sm-6">
            <h1>Добавить товар в заказ</h1>
            
            <?php $form = ActiveForm::begin(
                [
                    'enableClientScript' => false,
                    'id'                 => 'add_mod',
                    'options'            => [
                        'class'   => 'form-vertical',
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig'        => [
                        //'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        //'labelOptions' => ['class' => 'col-lg-2 control-label'],
                    ],
                ]
            ); ?>
            
            <?= $form->field($model_orderproduct, 'sku') ?>
            
            <?= $form->field($model_orderproduct, 'count') ?>
            
            <?= $form->field($model_orderproduct, 'order_id')
                     ->hiddenInput([ 'value' => $model->id ])
                     ->label(false); ?>
            
            <div class="form-group">
                <?= Html::submitButton(
                    ' Добавить товар ',
                    [
                        'class' => 'btn btn-primary',
                        'name'  => 'login-button',
                    ]
                ) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>



