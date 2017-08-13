<?php
    
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\modules\catalog\models\ProductLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View          $this
     * @var Product       $model
     * @var ProductLang[] $modelLangs
     */
    
    $this->title = Yii::t('product', 'Create Product');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Products'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="product-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
