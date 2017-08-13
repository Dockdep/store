<?php
    
    use artweb\artbox\models\Customer;
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    
    /**
     * @var yii\web\View $this
     * @var Customer     $model
     */
    $this->title = $model->name;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => 'Customers',
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="customer-view">
    
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
                'username',
                'name',
                'surname',
                'phone',
                'gender',
                'birthday',
                'body:ntext',
                'group_id',
                'email',
                'created_at:date',
            ],
        ]
    ) ?>

</div>
