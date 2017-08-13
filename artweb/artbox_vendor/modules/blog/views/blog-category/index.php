<?php
    
    use artweb\artbox\modules\blog\models\BlogCategory;
    use artweb\artbox\modules\blog\models\BlogCategorySearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var BlogCategorySearch $searchModel
     * @var ActiveDataProvider $dataProvider
     */
    
    $this->title = \Yii::t('blog', 'Blog Categories');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-category-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a(\Yii::t('blog', 'Create Blog Category'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
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
                    'label' => \Yii::t('blog', 'Parent category'),
                    'value' => function($model) {
                        /**
                         * @var BlogCategory $model
                         */
                        if (!empty( $model->parent )) {
                            return $model->parent->lang->title;
                        } else {
                            return false;
                        };
                    },
                ],
                [
                    'attribute' => 'status',
                    'value'     => function($model) {
                        /**
                         * @var BlogCategory $model
                         */
                        return ( !$model->status ) ? \Yii::t('blog', 'Not active') : \Yii::t('blog', 'Active');
                    },
                    'filter'    => [
                        0 => \Yii::t('blog', 'Not active'),
                        1 => \Yii::t('blog', 'Active'),
                    ],
                ],
                [ 'class' => 'yii\grid\ActionColumn' ],
            ],
        ]
    ); ?>
</div>
