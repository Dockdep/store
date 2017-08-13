<?php
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var yii\web\View                $this
     * @var artweb\artbox\models\BgSearch      $searchModel
     * @var yii\data\ActiveDataProvider $dataProvider
     */
    $this->title = \Yii::t('app', 'Bgs');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="bg-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(\Yii::t('app', 'Create Bg'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],
            'id',
            'url:url',
            [
                'attribute' => 'title',
                'value'     => 'lang.title',
            ],
            'imageUrl:image',
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>
</div>
