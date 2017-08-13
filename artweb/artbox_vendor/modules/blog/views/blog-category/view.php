<?php
    
    use artweb\artbox\modules\blog\models\BlogCategory;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View         $this
     * @var BlogCategory $model
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('blog', 'Blog Categories'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-category-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            'Update',
            [
                'update',
                'id' => $model->id,
            ],
            [ 'class' => 'btn btn-primary' ]
        ) ?>
        <?= Html::a(
            'Delete',
            [
                'delete',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-danger',
                'data'  => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method'  => 'post',
                ],
            ]
        ) ?>
    </p>
    
    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                'id',
                'sort',
                'imageUrl:image',
                [
                    'attribute' => 'parent_id',
                    'value'     => ( !empty( $model->parent ) ) ? $model->parent->lang->title : '',
                ],
                'lang.alias',
                'lang.description:text',
                [
                    'attribute' => 'status',
                    'value'     => ( $model->status ) ? \Yii::t('blog', 'Active') : \Yii::t('blog', 'Not active'),
                ],
            ],
        ]
    ) ?>

</div>
