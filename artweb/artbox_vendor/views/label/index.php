<?php
    
    use artweb\artbox\models\LabelSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var LabelSearch        $searchModel
     * @var ActiveDataProvider $dataProvider
     */
    
    $this->title = 'Labels';
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="label-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a('Create Label', [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            'id',
            'label',
            [
                'attribute' => 'title',
                'value'     => 'lang.title',
            ],
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>
</div>
