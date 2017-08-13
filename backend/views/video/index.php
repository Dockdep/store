<?php
    
    use backend\models\ProductVideoSearch;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var  yii\web\View                $this
     * @var  ProductVideoSearch          $searchModel
     * @var  yii\data\ActiveDataProvider $dataProvider
     */
    
    $this->title = \Yii::t('app', 'Videos');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="page-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            \Yii::t('app', 'create_item', [ 'item' => 'Video' ]),
            [ 'create' ],
            [ 'class' => 'btn btn-success' ]
        ) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'id',
                'title',
                'url:raw',
                [
                    'attribute' => 'is_main',
                    'format' => 'boolean',
                    'filter' => \Yii::$app->formatter->booleanFormat,
                ],
                [
                    'attribute' => 'is_display',
                    'format' => 'boolean',
                    'filter' => \Yii::$app->formatter->booleanFormat,
                ],
                [ 'class' => 'yii\grid\ActionColumn' ],
            ],
        ]
    ); ?>
</div>
