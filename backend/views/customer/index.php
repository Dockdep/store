<?php
    
    use artweb\artbox\models\CustomerSearch;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var yii\web\View                $this
     * @var CustomerSearch              $searchModel
     * @var yii\data\ActiveDataProvider $dataProvider
     */
    $this->title = 'Customers';
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="customer-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Create Customer', [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                'id',
                'username',
                'phone',
                'email',
                [ 'class' => 'yii\grid\ActionColumn' ],
            ],
        ]
    ); ?>
</div>
