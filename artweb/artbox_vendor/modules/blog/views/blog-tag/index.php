<?php
    
    use artweb\artbox\modules\blog\models\BlogTagSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var BlogTagSearch      $searchModel
     * @var ActiveDataProvider $dataProvider
     */
    
    $this->title = \Yii::t('blog', 'Blog Tags');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-tag-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a(\Yii::t('blog', 'Create Blog Tag'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'id',
                [
                    'attribute' => 'label',
                    'value'     => 'lang.label',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                ],
            ],
        ]
    ); ?>
</div>
