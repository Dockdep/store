<?php
    
    use artweb\artbox\modules\catalog\models\Brand;
    use artweb\artbox\modules\catalog\models\BrandSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var BrandSearch        $searchModel
     * @var ActiveDataProvider $dataProvider
     */
    
    $this->title = Yii::t('product', 'Brands');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="brand-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('product', 'Create Brand'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            'id',
            [
                'attribute' => 'brandName',
                'value'     => 'lang.title',
            ],
            'imageUrl:image',
            [
                'attribute' => 'in_menu',
                'content'   => function($model) {
                    /**
                     * @var Brand $model
                     */
                    return Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-'.($model->in_menu?'ok':'remove'),
                    ]);
                },
            ],
            [ 'class' => 'yii\grid\ActionColumn',
        ],
    ],
    ]); ?>
</div>
