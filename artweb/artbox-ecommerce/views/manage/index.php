<?php
    
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use kartik\select2\Select2;
    use artweb\artbox\components\artboxtree\ArtboxTreeHelper;
    use artweb\artbox\ecommerce\helpers\ProductHelper;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var ProductSearch      $searchModel
     * @var ActiveDataProvider $dataProvider
     */
    $this->title = Yii::t('product', 'Products');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="product-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('product', 'Create Product'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [
                    'label' => Yii::t('product', 'SKU'),
                    'attribute' => 'variantSku',
                    'value' => 'variant.sku',
                ],
                [
                    'attribute' => 'productName',
                    'value'     => 'lang.title',
                ],
                [
                    'label'     => Yii::t('product', 'Brand'),
                    'attribute' => 'brand_id',
                    'value'     => 'brand.lang.title',
                    'filter'    => Select2::widget(
                        [
                            'model'         => $searchModel,
                            'attribute'     => 'brand_id',
                            'data'          => Brand::find()
                                                    ->joinWith('lang')
                                                    ->select(
                                                        [
                                                            'brand_lang.title',
                                                            'brand.id',
                                                        ]
                                                    )
                                                    ->asArray()
                                                    ->indexBy('id')
                                                    ->column(),
                            'language'      => 'ru',
                            'options'       => [
                                'placeholder' => Yii::t('product', 'Select brand'),
                                'multiple'    => false,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]
                    ),
                ],
                [
                    'label'     => Yii::t('product', 'Category'),
                    'attribute' => 'categoryId',
                    'value'     => function ($model) {
                        /**
                         * @var Product $model
                         */
                        $categories = [];
                        foreach ($model->getCategories()
                                       ->with('lang')
                                       ->all() as $category) {
                            /**
                             * @var Category $category
                             */
                            $categories[] = $category->lang->title;
                        }
                        return implode(", ", $categories);
                    },
                    'filter'    => Select2::widget(
                        [
                            'model'         => $searchModel,
                            'attribute'     => 'categoryId',
                            'data'          => ArtboxTreeHelper::treeMap(
                                ProductHelper::getCategories(),
                                'id',
                                'lang.title'
                            ),
                            'language'      => 'ru',
                            'options'       => [
                                'placeholder' => Yii::t('product', 'Select category'),
                                'multiple'    => false,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]
                    ),
                ],
                [
                    'attribute' => 'variantCount',
                    'value'     => function ($model) {
                        /**
                         * @var Product $model
                         */
                        return count($model->variants);
                    },
                ],
                [
                    'class'      => 'yii\grid\ActionColumn',
                    'template'   => '{items} {view} |  {is_top} {is_new} {is_discount}  | {update} {delete}',
                    'buttons'    => [
                        'is_top'      => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-star' . ( $model->is_top ? '' : '-empty' ) . '"></span>',
                                $url,
                                [
                                    'title' => Yii::t('product', ( $model->is_top ? 'Set not is top' : 'Set is top' )),
                                    'class' => 'toggle-status',
                                ]
                            );
                        },
                        'is_new'      => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-heart' . ( $model->is_new ? '' : '-empty' ) . '"></span>',
                                $url,
                                [
                                    'title' => Yii::t('product', ( $model->is_new ? 'Set not is new' : 'Set is new' )),
                                    'class' => 'toggle-status',
                                ]
                            );
                        },
                        'is_discount' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-tag' . ( $model->is_discount ? 's' : '' ) . '"></span>',
                                $url,
                                [
                                    'title' => Yii::t(
                                        'product',
                                        ( $model->is_discount ? 'Set not is promotion' : 'Set is promotion' )
                                    ),
                                    'class' => 'toggle-status',
                                ]
                            );
                        },
                        'items'       => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-th-list"></span>',
                                $url,
                                [
                                    'title' => Yii::t('product', 'Variants'),
                                ]
                            );
                        },
                    
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        /**
                         * @var Product $model
                         */
                        switch ($action) {
                            case 'items':
                                return \yii\helpers\Url::to(
                                    [
                                        'variant/index',
                                        'product_id' => $model->id,
                                    ]
                                );
                                break;
                            case 'is_top':
                                return \yii\helpers\Url::to(
                                    [
                                        'manage/is-top',
                                        'id' => $model->id,
                                    ]
                                );
                                break;
                            case 'is_new':
                                return \yii\helpers\Url::to(
                                    [
                                        'manage/is-new',
                                        'id' => $model->id,
                                    ]
                                );
                                break;
                            case 'is_discount':
                                return \yii\helpers\Url::to(
                                    [
                                        'manage/is-discount',
                                        'id' => $model->id,
                                    ]
                                );
                                break;
                            case 'view':
                                return \yii\helpers\Url::to(
                                    [
                                        'manage/view',
                                        'id' => $model->id,
                                    ]
                                );
                                break;
                            case 'update':
                                return \yii\helpers\Url::to(
                                    [
                                        'manage/update',
                                        'id' => $model->id,
                                    ]
                                );
                                break;
                            case 'delete':
                                return \yii\helpers\Url::to(
                                    [
                                        'manage/delete',
                                        'id' => $model->id,
                                    ]
                                );
                                break;
                            default:
                                return '';
                                break;
                        }
                    },
                ],
            ],
        ]
    ); ?>
</div>
