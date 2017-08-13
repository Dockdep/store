<?php
    
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View         $this
     * @var ProductVideo $model
     */
    $this->title = \Yii::t('app', 'create_item', [ 'item' => 'Video' ]);
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Videos'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="page-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ) ?>

</div>
