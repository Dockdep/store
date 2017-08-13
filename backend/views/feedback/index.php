<?php
    use artweb\artbox\models\FeedbackSearch;
    use yii\bootstrap\Html;
    use yii\data\ActiveDataProvider;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var ActiveDataProvider $dataProvider
     * @var FeedbackSearch     $searchModel
     * @var View               $this
     */
    $this->title = 'Feedback';
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="feedback-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                'id',
                'name',
                'phone',
                'created_at:date',
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                ],
            ],
        ]
    ); ?>
</div>