<?php
    
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model artweb\artbox\modules\catalog\models\TaxGroup */
    
    $this->title = $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('rubrication', 'Tax Groups'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="tax-group-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            Yii::t('rubrication', 'Update'),
            [
                'update',
                'id' => $model->id,
            ],
            [ 'class' => 'btn btn-primary' ]
        ) ?>
        <?= Html::a(
            Yii::t('rubrication', 'Delete'),
            [
                'delete',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-danger',
                'data'  => [
                    'confirm' => Yii::t('rubrication', 'Are you sure you want to delete this item?'),
                    'method'  => 'post',
                ],
            ]
        ) ?>
        <?= Html::a(
            Yii::t('rubrication', 'Create Option'),
            [ 'tax-option/create?group=' . $model->id ],
            [ 'class' => 'btn btn-success' ]
        ) ?>
    </p>
    
    <?= DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'id',
                'is_filter:boolean',
            ],
        ]
    ) ?>

</div>
