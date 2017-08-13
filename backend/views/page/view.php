<?php
    
    use artweb\artbox\models\Page;
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    
    /**
     * @var yii\web\View $this
     * @var  Page        $model
     */
    
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Pages'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="page-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            \Yii::t('app', 'Update'),
            [
                'update',
                'id' => $model->id,
            ],
            [ 'class' => 'btn btn-primary' ]
        ) ?>
        <?= Html::a(
            \Yii::t('app', 'Delete'),
            [
                'delete',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-danger',
                'data'  => [
                    'confirm' => \Yii::t('app', 'Are you sure you want to delete this item?'),
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
                'lang.title',
                [
                    'attribute' => 'in_menu',
                    'value'     => $model->in_menu ? Yii::t('app', 'В меню') : Yii::t('app', 'Не в меню'),
                ],
                'lang.body:html',
            ],
        ]
    ) ?>

</div>
