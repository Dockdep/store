<?php
    
    use artweb\artbox\modules\blog\models\BlogArticle;
    use artweb\artbox\modules\blog\models\BlogArticleLang;
    use artweb\artbox\modules\blog\models\BlogCategory;
    use artweb\artbox\modules\blog\models\BlogTag;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View              $this
     * @var BlogArticle       $model
     * @var BlogArticleLang[] $modelLangs
     * @var BlogCategory[]    $categories
     * @var BlogTag[]         $tags
     * @var array             $products
     * @var array             $articles
     */
    
    $this->title = \Yii::t('blog', 'Create Blog Article');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('blog', 'Blog Articles'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-article-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
            'categories' => $categories,
            'tags'       => $tags,
            'products'   => $products,
            'articles'   => $articles,
        ]
    ) ?>

</div>
