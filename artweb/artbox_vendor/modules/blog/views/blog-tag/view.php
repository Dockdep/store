<?php
    
    use artweb\artbox\modules\blog\models\BlogTag;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View    $this
     * @var BlogTag $model
     */
    
    $this->title = $model->lang->label;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('blog', 'Blog Tags'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="blog-tag-view">
    
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
                'lang.label',
            ],
        ]
    ) ?>

</div>
