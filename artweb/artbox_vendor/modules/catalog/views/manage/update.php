<?php
    
    use artweb\artbox\modules\catalog\models\Product;
    use artweb\artbox\modules\catalog\models\ProductLang;
    use yii\db\ActiveQuery;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View          $this
     * @var Product       $model
     * @var ProductLang[] $modelLangs
     * @var ActiveQuery   $groups
     */
    
    $this->title = Yii::t('product', 'Update {modelClass}: ', [
            'modelClass' => 'Product',
        ]) . ' ' . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Products'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('product', 'Update');
?>
<div class="product-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
        'groups'      => $groups,
    ]) ?>

</div>
