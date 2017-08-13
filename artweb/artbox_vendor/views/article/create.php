<?php
    use artweb\artbox\models\Article;
    use artweb\artbox\models\ArticleLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View           $this
     * @var Article       $model
     * @var ArticleLang[] $modelLangs
     */
    $this->title = \Yii::t('app', 'Create Article');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Article'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="article-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
