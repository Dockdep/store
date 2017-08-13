<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use artweb\artbox\models\Label;
use yii\bootstrap\Modal;


$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>Заказы</h1>
    <p>
        <?= Html::a('Add order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php \yii\widgets\Pjax::begin(   [

]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [

		[
                'attribute' => 'id',
                'format' => 'raw',
                'options' => ['class' => 'btn btn-warning'],
                'value' => function($model){
                    return Html::button($model->id, ['id'=>$model->id, 'class' => 'btn btn-warning']);

                }

            ],
		[
		'attribute' => 'date_time',
		'value'=>'date_time',
		],

		[
		'attribute' => 'name',
		'value'=>'name',
                'format'=>'raw',
		],
		[
		'attribute' => 'phone',
		'value'=>'phone',
		],
//		[
//		'attribute' => 'total',
//		'value'=>'total',
//		],
//		[
//			'filter'    => yii\helpers\ArrayHelper::map(Label::find()->orderBy('id')->asArray()->all(), 'id', 'label'),
//			'attribute' => 'label',
//			'value' => function ($model, $key, $index, $column) {
//					return Html::activeDropDownList($model, 'label',
//						yii\helpers\ArrayHelper::map(Label::find()->orderBy('id')->asArray()->all(), 'id', 'label'),
//						[
//							'prompt' => 'Нет',
//							'onchange' => "$.ajax({
//								 url: \"/admin/order/label-update\",
//								 type: \"post\",
//								 data: { order_id:  $model->id, label_id : this.value},
//								});"
//						]
//
//					);
//				},
//			'format' => 'raw',
//		],
//        [
//            'attribute' => 'pay',
//			'filter'    => [
//				0 => 'Нет',1=>'Да'
//			],
//			'value' => function ($model, $key, $index, $column) {
//                return Html::activeDropDownList($model, 'pay',[0 => 'Нет',1=>'Да'],
//                    [
//                        'onchange' => "$.ajax({
//                                     url: \"/admin/order/pay-update\",
//                                     type: \"post\",
//                                     data: { order_id:  $model->id, pay_id : this.value},
//                                    });"
//                    ]
//
//                );
//            },
//            'format' => 'raw',
//        ],
		 [
		 'attribute' => 'status',
		 'value'=>'status',
		 'contentOptions'=>['style'=>'width: 5px;']
		 ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'contentOptions'=>['style'=>'width: 70px;']
        ],		
    ],
]) ?>
<?php \yii\widgets\Pjax::end(); ?>