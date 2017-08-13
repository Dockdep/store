<?php
    
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var yii\web\View                $this
     * @var artweb\artbox\models\SeoSearch     $searchModel
     * @var yii\data\ActiveDataProvider $dataProvider
     */
    $this->title = Yii::t('app', 'Seo');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="seo-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('app', 'Create Seo'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                'id',
                'url',
                [
                    'attribute' => 'title',
                    'value'     => 'lang.title',
                ],
                [
                    'attribute' => 'meta_description',
                    'value'     => 'lang.meta_description',
                ],
                [
                    'attribute' => 'h1',
                    'value'     => 'lang.h1',
                ],
                [
                    'attribute' => 'meta',
                    'value'     => 'lang.meta',
                ],
                [
                    'attribute' => 'seo_text',
                    'value'     => 'lang.seo_text',
                ],
                [ 'class' => 'yii\grid\ActionColumn' ],
            ],
        ]
    ); ?>
</div>
