<?php
    
    use artweb\artbox\modules\blog\models\BlogArticle;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View        $this
     * @var BlogArticle $model
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('blog', 'Blog Articles'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-article-view">
    
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
                'imageUrl:image',
                'created_at:date',
                'updated_at:date',
                [
                    'attribute' => 'status',
                    'value'     => ( !$model->status ) ? \Yii::t('blog', 'Not active') : \Yii::t('blog', 'Active'),
                ],
                'lang.alias',
                'lang.body:html',
            ],
        ]
    ) ?>

</div>
