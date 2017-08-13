<?php
    
    use artweb\artbox\models\SliderImage;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    /**
     * @var yii\web\View                    $this
     * @var artweb\artbox\models\SliderImageSearch $searchModel
     * @var yii\data\ActiveDataProvider     $dataProvider
     * @var int                             $slider_id
     */
    $this->title = Yii::t('app', 'Slider Images');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Sliders'),
        'url'   => Url::toRoute([ 'slider/index' ]),
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="slider-image-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            Yii::t('app', 'Create Slider Image'),
            Url::toRoute(
                [
                    'create',
                    'slider_id' => $slider_id,
                ]
            ),
            [ 'class' => 'btn btn-success' ]
        ) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'id',
                'imageUrl:image',
                'url',
                [
                    'attribute' => 'status',
                    'value'     => function ($model) {
                        /**
                         * @var SliderImage $model
                         */
                        return ( !$model->status ) ? \Yii::t('app', 'Скрыто') : \Yii::t('app', 'Показать');
                    },
                    'filter'    => [
                        0 => \Yii::t('app', 'Скрыто'),
                        1 => \Yii::t('app', 'Показать'),
                    ],
                ],
                [
                    'class'   => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view'   => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                Url::toRoute(
                                    [
                                        'view',
                                        'slider_id' => $model->slider_id,
                                        'id'        => $model->id,
                                    ]
                                ),
                                [
                                    'title' => \Yii::t('app', 'Просмотр'),
                                ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                Url::toRoute(
                                    [
                                        'update',
                                        'slider_id' => $model->slider_id,
                                        'id'        => $model->id,
                                    ]
                                ),
                                [
                                    'title' => \Yii::t('app', 'Редактировать'),
                                ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::toRoute(
                                    [
                                        'delete',
                                        'slider_id' => $model->slider_id,
                                        'id'        => $model->id,
                                    ]
                                ),
                                [
                                    'title'        => Yii::t('yii', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                                    'data-method'  => 'post',
                                ]
                            );
                            
                        },
                    ],
                ],
            ],
        ]
    ); ?>
</div>
