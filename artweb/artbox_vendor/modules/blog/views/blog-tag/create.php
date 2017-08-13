<?php
    
    use artweb\artbox\modules\blog\models\BlogTag;
    use artweb\artbox\modules\blog\models\BlogTagLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View          $this
     * @var BlogTagLang[] $modelLangs
     * @var BlogTag       $model
     */
    
    $this->title = \Yii::t('blog', 'Create Blog Tag');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('blog', 'Blog Tags'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-tag-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
        ]
    ) ?>

</div>
