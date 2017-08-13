<?php
    
    use artweb\artbox\seo\models\SeoCategory;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    
    /**
     * @var yii\web\View                   $this
     * @var artweb\artbox\seo\models\SeoDynamicSearch $searchModel
     * @var yii\data\ActiveDataProvider    $dataProvider
     * @var SeoCategory                    $seo_category
     */
    $this->title = Yii::t(
        'app',
        'Seo Dynamics for {seo_category}',
        [
            'seo_category' => $seo_category->lang->title,
        ]
    );
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Seo Categories'),
        'url'   => [ '/seo/seo-category/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="seo-dynamic-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            \Yii::t('app', 'create_item',['item'=>'Seo Dynamic']),
            Url::toRoute(
                [
                    'create',
                    'seo_category_id' => $seo_category->id,
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
                [ 'class' => 'yii\grid\SerialColumn' ],
                'id',
                'action',
                'fields',
                'param',
                'status',
                [
                    'class'   => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view'   => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                [
                                    'view',
                                    'seo_category_id' => $model->id,
                                    'id'              => $model->id,
                                ],
                                [
                                    'title' => \Yii::t('app', 'Просмотр'),
                                ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                [
                                    'update',
                                    'seo_category_id' => $model->seo_category_id,
                                    'id'              => $model->id,
                                ],
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
                                        'seo_category_id' => $model->seo_category_id,
                                        'id'              => $model->id,
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
