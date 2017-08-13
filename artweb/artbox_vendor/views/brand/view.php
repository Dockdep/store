<?php
    
    use artweb\artbox\modules\catalog\models\Brand;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View  $this
     * @var Brand $model
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('product', 'Brands'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="brand-view">
    
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
    </p>
    
    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'lang.title',
            'lang.alias',
            [
                'attribute' => 'in_menu',
                'value'     => Html::tag('span', '', [
                    'class' => 'glyphicon glyphicon-' . ( $model->in_menu ? 'ok' : 'remove' ),
                ]),
                'format'    => 'html',
            ],
            'imageUrl:image',
            'lang.meta_title',
            'lang.meta_robots',
            'lang.meta_description',
            'lang.seo_text',
        ],
    ]) ?>

</div>
