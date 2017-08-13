<?php
    
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View           $this
     * @var ProductVariant $model
     * @var TaxGroup[]     $properties
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Products'),
        'url'   => [ 'manage/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->product->lang->title,
        'url'   => [
            'manage/view',
            'id' => $model->product->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Variants'),
        'url'   => [
            'variant/index',
            'product_id' => $model->product->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
    $properties_string = '';
    foreach ($properties as $property) {
        $options_string = '';
        foreach ($property->options as $option) {
            $options_string .= Html::tag('li', $option->lang->value);
        }
        $properties_string .= Html::tag('p', $property->lang->title) . Html::tag('ul', $options_string);
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
    </p>
    
    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                'id',
                'lang.title',
                'sku',
                'price',
                'price_old',
                'stock',
                'productUnit.lang.title',
                [
                    'attribute' => 'product_id',
                    'value'     => Html::a(
                        $model->product->fullname,
                        [
                            'product/manage/view',
                            'id' => $model->id,
                        ]
                    ),
                    'format'    => 'html',
                ],
                'image.imageUrl:image',
                [
                    'label'  => \Yii::t('app', 'Properties'),
                    'value'  => $properties_string,
                    'format' => 'html',
                ],
            ],
        ]
    ) ?>

</div>
