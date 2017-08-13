<?php
    
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\modules\catalog\models\ProductVariantSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View                 $this
     * @var ProductVariantSearch $searchModel
     * @var ActiveDataProvider   $dataProvider
     * @var Product              $product
     */
    
    $this->title = Yii::t('product', 'Variants for ') . $product->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Products'),
        'url'   => [ '/product/manage/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $product->lang->title,
        'url'   => [
            '/product/manage/view',
            'id' => $product->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = \Yii::t('product', 'Variants');
?>
<div class="product-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            Yii::t('product', 'Create Variant'),
            Url::toRoute(
                [
                    'create',
                    'product_id' => $product->id,
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
                [
                    'attribute' => 'variantName',
                    'value'     => 'lang.title',
                ],
                'sku',
                'price',
                'price_old',
                'stock',
                'image.imageUrl:image',
                [
                    'class'   => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'view'   => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                Url::to(
                                    [
                                        'view',
                                        'product_id' => $model->product_id,
                                        'id'         => $model->id,
                                    ]
                                ),
                                [
                                    'title' => \Yii::t('app', "Просмотр"),
                                ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                Url::to(
                                    [
                                        'update',
                                        'product_id' => $model->product_id,
                                        'id'         => $model->id,
                                    ]
                                ),
                                [
                                    'title' => \Yii::t('app', "Редактировать"),
                                ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Url::to(
                                    [
                                        'delete',
                                        'product_id' => $model->product_id,
                                        'id'         => $model->id,
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
