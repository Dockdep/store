<?php
    
    use artweb\artbox\models\SeoCategory;
    use artweb\artbox\models\SeoCategoryLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View              $this
     * @var SeoCategory       $model
     * @var SeoCategoryLang[] $modelLangs
     */
    
    $this->title = Yii::t('app', 'Create Seo Category');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Seo Categories'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="seo-category-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
