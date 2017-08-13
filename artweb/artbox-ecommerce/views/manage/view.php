<?php
    
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View             $this
     * @var Product          $model
     * @var Category[]       $categories
     * @var TaxGroup[]       $properties
     * @var ProductVariant[] $variants
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Products'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
    $properties_string = '';
    foreach ($properties as $property) {
        $property_list = '';
        foreach ($property->options as $option) {
            $property_list .= Html::tag('li', $option->lang->value);
        }
        $properties_string .= Html::tag('p', $property->lang->title) . Html::tag('ul', $property_list);
    }
    $variants_string = '';
    foreach ($variants as $variant) {
        $variants_string .= Html::a(
                $variant->lang->title,
                [
                    'variant/view',
                    'id' => $variant->id,
                ]
            ) . '<br>';
    }
?>
<div class="product-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            Yii::t('product', 'Update'),
            [
                'update',
                'id' => $model->id,
            ],
            [ 'class' => 'btn btn-primary' ]
        ) ?>
        <?= Html::a(
            Yii::t('product', 'Delete'),
            [
                'delete',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-danger',
                'data'  => [
                    'confirm' => Yii::t('product', 'Are you sure you want to delete this item?'),
                    'method'  => 'post',
                ],
            ]
        ) ?>
        <?= Html::a(
            Yii::t('product', 'Variants'),
            [
                'variant/index',
                'product_id' => $model->id,
            ],
            [ 'class' => 'btn btn-info' ]
        ) ?>
    </p>
    
    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                'id',
                'brand.lang.title',
                [
                    'label'  => \Yii::t('app', 'Categories'),
                    'value'  => implode('<br>', ArrayHelper::getColumn($categories, 'lang.title')),
                    'format' => 'html',
                ],
                [
                    'attribute' => 'is_top',
                    'value'     => $model->is_top ? Html::tag(
                        'span',
                        '',
                        [ 'class' => 'glyphicon glyphicon-ok' ]
                    ) : Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-remove' ]),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'is_new',
                    'value'     => $model->is_new ? Html::tag(
                        'span',
                        '',
                        [ 'class' => 'glyphicon glyphicon-ok' ]
                    ) : Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-remove' ]),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'is_discount',
                    'value'     => $model->is_discount ? Html::tag(
                        'span',
                        '',
                        [ 'class' => 'glyphicon glyphicon-ok' ]
                    ) : Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-remove' ]),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'video',
                    'format'    => 'html',
                ],
                [
                    'label'  => \Yii::t('app', 'Properties'),
                    'value'  => $properties_string,
                    'format' => 'html',
                ],
                [
                    'label'  => \Yii::t('app', 'Variants'),
                    'value'  => $variants_string,
                    'format' => 'html',
                ],
                'lang.description:html',
                'image.imageUrl:image',
            ],
        ]
    ) ?>

</div>
