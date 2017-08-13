<?php
    
    use artweb\artbox\modules\blog\models\BlogArticle;
    use artweb\artbox\modules\blog\models\BlogArticleSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var BlogArticleSearch  $searchModel
     * @var ActiveDataProvider $dataProvider
     */
    
    $this->title = \Yii::t('blog', 'Blog Articles');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-article-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a(\Yii::t('blog', 'Create Blog Article'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                'id',
                [
                    'attribute' => 'title',
                    'value'     => 'lang.title',
                ],
                'imageUrl:image',
                [
                    'attribute' => 'status',
                    'value'     => function($model) {
                        /**
                         * @var BlogArticle $model
                         */
                        return ( !$model->status ) ? \Yii::t('blog', 'Not active') : \Yii::t('blog', 'Active');
                    },
                    'filter'    => [
                        0 => \Yii::t('blog', 'Not active'),
                        1 => \Yii::t('blog', 'Active'),
                    ],
                ],
                'created_at:date',
                'updated_at:date',
                [ 'class' => 'yii\grid\ActionColumn' ],
            ],
        ]
    ); ?>
</div>
