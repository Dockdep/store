<?php
    
    use artweb\artbox\modules\blog\models\BlogCategory;
    use artweb\artbox\modules\blog\models\BlogCategoryLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View             $this
     * @var BlogCategory     $model
     * @var BlogCategoryLang $modelLangs
     * @var array            $parentCategories
     */
    
    $this->title = \Yii::t('blog', 'Update Blog Category: ') . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('blog', 'Blog Categories'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = \Yii::t('blog', 'Update');
?>
<div class="blog-category-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'            => $model,
            'modelLangs'       => $modelLangs,
            'parentCategories' => $parentCategories,
        ]
    ) ?>

</div>
