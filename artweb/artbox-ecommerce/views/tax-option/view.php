<?php
    
    use artweb\artbox\ecommerce\models\TaxGroup;
    use artweb\artbox\ecommerce\models\TaxOption;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View      $this
     * @var TaxOption $model
     * @var TaxGroup  $group
     */
    
    $this->title = $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('rubrication', 'Groups'),
        'url'   => [ 'tax-group/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('rubrication', $group->id),
        'url'   => [
            'index',
            'group' => $group->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('rubrication', Yii::t('rubrication', 'Options of {title}', [ 'title' => $group->id ])),
        'url'   => [
            'index',
            'group' => $group->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="tax-option-view">
    
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
            'model'      => $model,
            'attributes' => [
                'id',
                'group.id',
                'sort',
            ],
        ]
    ) ?>

</div>
