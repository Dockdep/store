<?php
    
    use artweb\artbox\models\Page;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var  yii\web\View                $this
     * @var  artweb\artbox\models\PageSearch    $searchModel
     * @var  yii\data\ActiveDataProvider $dataProvider
     */
    
    $this->title = \Yii::t('app', 'Pages');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="page-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(\Yii::t('app', 'Create Page'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            'id',
            [
                'attribute' => 'title',
                'value'     => 'lang.title',
            ],
            [
                'attribute' => 'in_menu',
                'value'     => function($model) {
                    /**
                     * @var Page $model
                     */
                    return ( !$model->in_menu ) ? \Yii::t('app', 'Не в меню') : \Yii::t('app', 'В меню');
                },
                'filter'    => [
                    0 => \Yii::t('app', 'Не в меню'),
                    1 => \Yii::t('app', 'В меню'),
                ],
            ],
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>
</div>
