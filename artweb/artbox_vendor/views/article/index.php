<?php
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var yii\web\View                $this
     * @var artweb\artbox\models\ArticleSearch $searchModel
     * @var yii\data\ActiveDataProvider $dataProvider
     */
    
    $this->title = \Yii::t('app', 'Articles');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="articles-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(\Yii::t('app', 'Create Articles'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
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
            'created_at:date',
            'imageUrl:image',
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>
</div>
