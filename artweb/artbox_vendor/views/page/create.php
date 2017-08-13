<?php
    
    use artweb\artbox\models\Page;
    use artweb\artbox\models\PageLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View       $this
     * @var Page       $model
     * @var PageLang[] $modelLangs
     */
    $this->title = \Yii::t('app', 'Create Page');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Pages'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="page-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
