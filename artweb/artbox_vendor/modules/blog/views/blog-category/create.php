<?php
    
    use artweb\artbox\modules\blog\models\BlogArticleLang;
    use artweb\artbox\modules\blog\models\BlogCategory;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View              $this
     * @var BlogCategory      $model
     * @var BlogArticleLang[] $modelLangs
     * @var array             $parentCategories
     */
    
    $this->title = \Yii::t('blog', 'Create Blog Category');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('blog', 'Blog Categories'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-category-create">
    
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
