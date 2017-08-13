<?php
    
    use artweb\artbox\modules\catalog\models\ProductUnitSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var ProductUnitSearch  $searchModel
     * @var ActiveDataProvider $dataProvider
     */
    
    $this->title = Yii::t('product', 'Product Units');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="product-unit-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('product', 'Create Product Unit'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'id',
                [
                    'attribute' => 'is_default',
                    'format'    => 'boolean',
                    'filter'    => [
                        \Yii::$app->formatter->asBoolean(false),
                        \Yii::$app->formatter->asBoolean(true),
                    ],
                ],
                [
                    'attribute' => 'title',
                    'value'     => 'lang.title',
                ],
                'lang.short',
                [ 'class' => 'yii\grid\ActionColumn' ],
            ],
        ]
    ); ?>
</div>
