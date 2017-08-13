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
    
    $this->title = Yii::t('app', 'Create Brand Size');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Brand Sizes'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="brand-size-create">

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
