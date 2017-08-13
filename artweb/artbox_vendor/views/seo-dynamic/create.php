<?php
    
    use artweb\artbox\models\SeoCategory;
    use artweb\artbox\models\SeoDynamic;
    use artweb\artbox\models\SeoDynamicLang;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View             $this
     * @var SeoDynamic       $model
     * @var SeoDynamicLang[] $modelLangs
     * @var SeoCategory      $seo_category
     */
    
    $this->title = Yii::t('app', 'Create Seo Dynamic');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Seo Categories'),
        'url'   => [ '/seo-category/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $seo_category->lang->title,
        'url'   => [
            'index',
            'seo_category_id' => $seo_category->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="seo-dynamic-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
        ]
    ) ?>

</div>
