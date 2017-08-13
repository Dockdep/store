<?php
    
    use artweb\artbox\models\Label;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View  $this
     * @var Label $model
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => 'Labels',
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="label-view">
    
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
            'label',
            [
                'label' => 'Name',
                'value' => $model->lang->title,
            ],
        ],
    ]) ?>

</div>
