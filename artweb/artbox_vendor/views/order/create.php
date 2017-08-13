<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;


$this->title = 'Добавить товар в заказ';
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

