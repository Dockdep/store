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
    
    $this->title = \Yii::t('app', 'Update Page') . ': ' . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Pages'),
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
<div class="page-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
