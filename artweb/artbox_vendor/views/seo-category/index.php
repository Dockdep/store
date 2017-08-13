<?php
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    /**
     * @var yii\web\View                    $this
     * @var artweb\artbox\models\SeoCategorySearch $searchModel
     * @var yii\data\ActiveDataProvider     $dataProvider
     */
    $this->title = Yii::t('app', 'Seo Categories');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="seo-category-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('app', 'Create Seo Category'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                'id',
                'controller',
                [
                    'attribute' => 'title',
                    'value'     => 'lang.title',
                ],
                'status',
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{update}&nbsp;{image}&nbsp;{delete}',
                    'buttons'  => [
                        'image' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-picture"></span>',
                                Url::toRoute(
                                    [
                                        'seo-dynamic/index',
                                        'seo_category_id' => $model->id,
                                    ]
                                ),
                                [
                                    'title' => \Yii::t('app', 'слайды'),
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]
    ); ?>
</div>
