<?php
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    use yii\data\ActiveDataProvider;
    use artweb\artbox\models\DeliverySearch;
    
    /**
     * @var View               $this
     * @var ActiveDataProvider $dataProvider
     * @var DeliverySearch     $searchModel
     */
    
    $this->title = 'Deliveries';
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="delivery-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Create Delivery', [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            
            'id',
            [
                'attribute' => 'title',
                'value'     => 'lang.title',
            ],
            [
                'attribute' => 'parentTitle',
                'value'     => 'parent.lang.title',
            ],
            'value',
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>
</div>
