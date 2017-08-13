<?php
    
    use artweb\artbox\ecommerce\models\Category;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View       $this
     * @var Category   $model
     * @var Category[] $tree
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Categories'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
    $tree_links = [];
    foreach($tree as $item) {
        $tree_links[] = Html::a($item->lang->title, [
            'view',
            'id' => $item->id,
        ]);
    }
    if(empty($tree_links)) {
        $tree_string = \Yii::t('product', 'No parent categories');
    } else {
        $tree_string = implode('&nbsp;&rarr;&nbsp;', $tree_links);
    }
?>
<div class="category-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(Yii::t('product', 'Update'), [
            'update',
            'id' => $model->id,
        ], [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::a(Yii::t('product', 'Delete'), [
            'delete',
            'id' => $model->id,
        ], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => Yii::t('product', 'Are you sure you want to delete this item?'),
                'method'  => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('product', 'Create Category'), [ 'category/create' ], [ 'class' => 'btn btn-success' ]) ?>
        <?php
            if(!empty( $model->parent_id )) {
                echo Html::a(Yii::t('product', 'Create category By {title}', [ 'title' => $model->parent->lang->title ]), [ 'category/create?parent=' . $model->parent->id ], [ 'class' => 'btn btn-success' ]);
            }
        ?>
    </p>
    
    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            [
                'label'  => \Yii::t('product', 'Category tree'),
                'value'  => $tree_string,
                'format' => 'html',
            ],
            'imageUrl:image',
            'lang.alias',
            'lang.meta_title',
            'lang.meta_robots',
            'lang.meta_description',
            'lang.seo_text',
            'lang.h1',
        ],
    ]) ?>

</div>
