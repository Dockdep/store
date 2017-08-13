<?php
    
    use artweb\artbox\seo\models\SeoCategory;
    use artweb\artbox\seo\models\SeoDynamic;
    use artweb\artbox\seo\models\SeoDynamicLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View             $this
     * @var SeoDynamic       $model
     * @var SeoDynamicLang[] $modelLangs
     * @var SeoCategory      $seo_category
     */
    
    $this->title = Yii::t(
            'app',
            'Update {modelClass}: ',
            [
                'modelClass' => 'Seo Dynamic',
            ]
        ) . $model->lang->title;
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
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'seo_category_id' => $seo_category->id,
            'id'              => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="seo-dynamic-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
        ]
    ) ?>

</div>
