<?php
    use artweb\artbox\models\Article;
    use artweb\artbox\models\ArticleLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View          $this
     * @var Article       $model
     * @var ArticleLang[] $modelLangs
     */
    $this->title = \Yii::t('app', 'Update Article') . ': ' . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Article'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = \Yii::t('app', 'Update');
?>
<div class="article-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
