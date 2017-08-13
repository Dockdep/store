<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\Delivery;


$this->title = 'Заказ №'.$model->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>Заказ №<?=$model->id?></h1>

<?php if(!empty($_GET['success'])):?>
<div class="alert alert-success">
    Заказ успешно сохранен!
</div>
<?php endif;?>

    <?php $form = ActiveForm::begin([
        'id' => 'reg-form',
		'layout' => 'horizontal',
        'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            //'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            //'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>


<div class="col-sm-6">
	<div class="form-group">
		<label class="control-label col-sm-3">Дата</label>
		<?=$model->date_time?>
	</div>
<?= $form->field($model,'date_dedline')->widget(\yii\jui\DatePicker::className(),['clientOptions' => [],'options' => ['class'=>'form-control','style'=>'width:150px;'],'dateFormat' => 'yyyy-MM-dd',]) ?>

<?= $form->field($model, 'surname') ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'patronymic') ?>

<?= $form->field($model, 'phone') ?>

<?= $form->field($model, 'phone2') ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'numbercard') ?>

<?= $form->field($model, 'delivery')->dropDownList(ArrayHelper::map(Delivery::find()->asArray()->all(), 'id', 'title')) ?>

<?= $form->field($model, 'declaration') ?>

<?= $form->field($model, 'stock') ?> 

<?= $form->field($model, 'consignment') ?>
</div>
<div class="col-sm-6">
<?=$form->field($model, 'payment')->dropDownList(['Оплатить наличными'=>'Оплатить наличными','Оплатить на карту Приват Банка'=>'Оплатить на карту Приват Банка','Оплатить по безналичному расчету'=>'Оплатить по безналичному расчету','Оплатить Правекс-телеграф'=>'Оплатить Правекс-телеграф','Наложенным платежом'=>'Наложенным платежом'],['prompt'=>'...']); ?>

<?= $form->field($model, 'insurance') ?>

<?= $form->field($model, 'amount_imposed') ?>

<?= $form->field($model, 'shipping_by') ?>

<?= $form->field($model, 'city') ?>

<?= $form->field($model, 'adress') ?>

<?= $form->field($model, 'body')->textArea(['rows' => '6']) ?>

<?= $form->field($model, 'total') ?>

<?=$form->field($model, 'status')->dropDownList(['Нет'=>'Нет','Обработан'=>'Обработан','На комплектации'=>'На комплектации','Укомплектован'=>'Укомплектован','Доставка'=>'Доставка','Выполнен'=>'Выполнен','Резерв оплачен'=>'Резерв оплачен','Резерв неоплачен'=>'Резерв неоплачен'],['prompt'=>'...']); ?>   

<?= $form->field($model, 'comment')->textArea(['rows' => '6']) ?>
</div>
<div class="form-group">
            <?= Html::submitButton(' Сохранить ', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<hr />
<?= Html::a('Добавить товар', ['/admin/order/add','order_id'=>$model->id], ['class'=>'btn btn-success']) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
		[
		'attribute' => 'id',
		'value'=>'id',
		'contentOptions'=>['style'=>'width: 70px;']
		],
		[
		'attribute' => 'art',
		'value'=>'art',
		'contentOptions'=>['style'=>'width: 50px;']
		],        
		[
		'attribute' => 'product_name',
		'value'=>'product_name',
		//'contentOptions'=>['style'=>'max-width: 300px;']
		],
		[
		'attribute' => 'name',
		'value'=>'name',
		//'contentOptions'=>['style'=>'max-width: 300px;']
		],
		[
		'attribute' => 'cost',
		'value'=>'cost',
		'contentOptions'=>['style'=>'width: 100px;']
		],       
		[
		'attribute' => 'count',
		'value'=>'count',
		'contentOptions'=>['style'=>'width: 30px;']
		], 
		[
		'attribute' => 'sum_cost',
		'value'=>'sum_cost',
		'contentOptions'=>['style'=>'width: 100px;']
		],
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'contentOptions'=>['style'=>'width: 20px;'],
            'buttons' => [
              'delete' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/admin/order/delete_product','id'=>$model->id,'order_id'=>$_GET['id']],
                        [
                            'title' => "Удалить",'data-confirm'=>'Удалить?',
                        ]);
              }  
            ],    
        ],        
		
    ],
]) ?>



