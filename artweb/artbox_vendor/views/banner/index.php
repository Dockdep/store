<?php
    
    use artweb\artbox\models\Banner;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var yii\web\View                $this
     * @var artweb\artbox\models\BannerSearch  $searchModel
     * @var yii\data\ActiveDataProvider $dataProvider
     */
    $this->title = Yii::t('app', 'Banners');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="banner-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('app', 'Create Banner'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            'id',
            [
                'attribute' => 'url',
                'content'   => function($model) {
                    /**
                     * @var Banner $model
                     */
                    return Html::a($model->url, \Yii::$app->urlManagerFrontend->createUrl($model->url));
                },
            ],
            [
                'attribute' => 'title',
                'value' => 'lang.title',
            ],
            'lang.imageUrl:image',
            [
                'attribute' => 'status',
                'value'     => function($model) {
                    /**
                     * @var Banner $model
                     */
                    return ( !$model->status ) ? \Yii::t('app', 'Скрыто') : \Yii::t('app', 'Показать');
                },
                'filter'    => [
                    0 => \Yii::t('app', 'Скрыто'),
                    1 => \Yii::t('app', 'Показать'),
                ],
            ],
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>
</div>
