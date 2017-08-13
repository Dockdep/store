<?php
    
    use artweb\artbox\ecommerce\models\BrandSize;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View      $this
     * @var BrandSize $model
     * @var array     $categories
     * @var array     $brands
     */
    
    $this->title = Yii::t(
            'app',
            'Update {modelClass}: ',
            [
                'modelClass' => 'Brand Size',
            ]
        ) . $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Brand Sizes'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->id,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="brand-size-update">

  <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'categories' => $categories,
            'brands'     => $brands,
        ]
    ) ?>

</div>
