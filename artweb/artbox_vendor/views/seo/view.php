<?php
    
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    
    /**
     * @var yii\web\View      $this
     * @var artweb\artbox\models\Seo $model
     */
    $this->title = $model->url;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Seos'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="seo-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            Yii::t('app', 'Update'),
            [
                'update',
                'id' => $model->id,
            ],
            [ 'class' => 'btn btn-primary' ]
        ) ?>
        <?= Html::a(
            Yii::t('app', 'Delete'),
            [
                'delete',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-danger',
                'data'  => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
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
                'url',
                'lang.title',
                'lang.meta_description',
                'lang.h1',
                'lang.meta',
                'lang.seo_text',
            ],
        ]
    ) ?>

</div>
