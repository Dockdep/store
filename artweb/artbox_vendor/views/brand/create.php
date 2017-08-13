<?php
    
    use artweb\artbox\modules\catalog\models\Brand;
    use artweb\artbox\modules\catalog\models\BrandLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View        $this
     * @var Brand       $model
     * @var BrandLang[] $modelLangs
     */
    
    $this->title = Yii::t('product', 'Create Brand');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Brands'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="brand-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
