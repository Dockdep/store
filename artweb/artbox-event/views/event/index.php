<?php

use yii\helpers\Html;
use yii\grid\GridView;
use artweb\artbox\event\models\EventSearch;
/**
* @var $this yii\web\View
* @var $searchModel EventSearch
* @var $dataProvider yii\data\ActiveDataProvider
 * */

$this->title = Yii::t('app', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app','create_item',['item' => 'Event']), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'lang.title:ntext',
            'imageUrl:image',
            // 'meta_title',
            // 'description',
            // 'h1',
            // 'seo_text:ntext',
            // 'created_at',
            // 'updated_at',
            // 'end_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
