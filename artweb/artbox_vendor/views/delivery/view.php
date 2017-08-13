<?php
    
    use artweb\artbox\models\Delivery;
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    use yii\web\View;
    
    /**
     * @var View     $this
     * @var Delivery $model
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => 'Deliveries',
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
    
    $parent_link = '';
    if(!empty( $model->parent )) {
        $parent_link = Html::a($model->parent->lang->title, [
            'view',
            'id' => $model->parent->id,
        ]);
    }
?>
<div class="delivery-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Update', [
            'update',
            'id' => $model->id,
        ], [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::a('Delete', [
            'delete',
            'id' => $model->id,
        ], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method'  => 'post',
            ],
        ]) ?>
    </p>
    
    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            [
                'label'  => 'Вид доставки',
                'value'  => $parent_link,
                'format' => 'html',
            ],
            'lang.title',
            'lang.text',
            'value',
            'sort',
        ],
    ]) ?>

</div>
