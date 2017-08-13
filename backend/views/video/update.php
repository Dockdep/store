<?php
    
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View         $this
     * @var ProductVideo $model
     */
    
    $this->title = \Yii::t('app', 'Update Video') . ': ' . $model->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Videos'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = \Yii::t('app', 'Update');
?>
<div class="page-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
