<?php
    
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    
    /**
     * @var yii\web\View         $this
     * @var artweb\artbox\design\models\Banner $model
     */
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Banners'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="banner-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('app', 'Update'), [
            'update',
            'id' => $model->id,
        ], [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::a(Yii::t('app', 'Delete'), [
            'delete',
            'id' => $model->id,
        ], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'  => 'post',
            ],
        ]) ?>
    </p>
    
    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'url',
                'value'     => Html::a($model->url, \Yii::$app->urlManagerFrontend->createUrl($model->url)),
                'format'    => 'html',
            ],
            'lang.title',
            'lang.imageUrl:image',
            [
                'attribute' => 'status',
                'value'     => $model->status ? \Yii::t('app', 'Показать') : \Yii::t('app', 'Скрыть'),
            ],
        ],
    ]) ?>

</div>
