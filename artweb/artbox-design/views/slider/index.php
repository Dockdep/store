<?php
    
    use artweb\artbox\design\models\Slider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    /**
     * @var yii\web\View                $this
     * @var artweb\artbox\design\models\SliderSearch  $searchModel
     * @var yii\data\ActiveDataProvider $dataProvider
     */
    $this->title = Yii::t('app', 'Sliders');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="slider-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(\Yii::t('app', 'create_item',['item'=>'Slider']), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'id',
                'title',
                [
                    'attribute' => 'status',
                    'value'     => function ($model) {
                        /**
                         * @var Slider $model
                         */
                        return ( !$model->status ) ? \Yii::t('app', 'Скрыто') : \Yii::t('app', 'Показать');
                    },
                    'filter'    => [
                        0 => \Yii::t('app', 'Скрыто'),
                        1 => \Yii::t('app', 'Показать'),
                    ],
                ],
                [
                    'label'   => \Yii::t('app', 'Slide count'),
                    'content' => function ($model) {
                        /**
                         * @var Slider $model
                         */
                        return count($model->sliderImages);
                    },
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{update}&nbsp;{image}&nbsp;{delete}',
                    'buttons'  => [
                        'image' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-picture"></span>',
                                Url::toRoute(
                                    [
                                        'slider-image/index',
                                        'slider_id' => $model->id,
                                    ]
                                ),
                                [
                                    'title' => \Yii::t('app', "слайды"),
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]
    ); ?>
</div>
