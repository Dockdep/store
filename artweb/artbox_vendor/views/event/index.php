<?php
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    /**
    * @var yii\web\View $this
    * @var artweb\artbox\models\EventSearch $searchModel
    * @var yii\data\ActiveDataProvider $dataProvider
    */
    $this->title = Yii::t('app', 'Events');
    $this->params[ 'breadcrumbs' ][] = $this->title;

?>
<div class="event-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a(Yii::t('app', 'Create Event'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],
            'id',
            'imageUrl:image',
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]); ?>
</div>
