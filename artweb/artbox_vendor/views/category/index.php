<?php
    
    use artweb\artbox\modules\catalog\models\Category;
    use yii\helpers\Html;
    use kartik\grid\GridView;
    
    /**
     * @var $this         yii\web\View
     * @var $searchModel  artweb\artbox\modules\catalog\models\CategorySearch
     * @var $dataProvider yii\data\ActiveDataProvider
     */
    $this->title = Yii::t('product', 'Categories');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="category-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('product', 'Create Category'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],
            'id',
            [
                'attribute' => 'categoryName',
                'content' => function($data) {
                    /**
                     * @var Category $data
                     */
                    $op = [];
                    foreach($data->getParents()->with('lang')
                                 ->all() as $parent) {
                        $op[] = $parent->lang->title;
                    }
                    $op[] = $data->lang->title;
                    return implode('&nbsp;&rarr;&nbsp;', $op);
                },
            ],
            'imageUrl:image',
            [
                'class'      => 'yii\grid\ActionColumn',
            ],
        ],
        'panel'        => [
            'type' => 'success',
        ],
    ]); ?>
</div>
