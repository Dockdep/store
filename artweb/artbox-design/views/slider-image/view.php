<?php
    
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\DetailView;
    
    /**
     * @var yii\web\View              $this
     * @var artweb\artbox\design\models\SliderImage $model
     * @var int                       $slider_id
     */
    $this->title = \Yii::t('app', 'Slide') . ': ' . $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Sliders'),
        'url'   => Url::toRoute(
            [
                'slider/index',
            ]
        ),
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Slider Images'),
        'url'   => Url::toRoute(
            [
                'index',
                'slider_id' => $slider_id,
            ]
        ),
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="slider-image-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            Yii::t('app', 'Update'),
            [
                'update',
                'slider_id' => $slider_id,
                'id'        => $model->id,
            ],
            [ 'class' => 'btn btn-primary' ]
        ) ?>
        <?= Html::a(
            Yii::t('app', 'Delete'),
            [
                'delete',
                'slider_id' => $slider_id,
                'id'        => $model->id,
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
                [
                    'attribute' => 'slider_id',
                    'format'    => 'html',
                    'value'     => Html::a(
                        $model->slider->title,
                        [
                            'slider/update',
                            'id' => $model->slider_id,
                        ]
                    ),
                ],
                'lang.title',
                'lang.alt',
                'imageUrl:image',
                'url:url',
                [
                    'attribute' => 'status',
                    'value'     => $model->status ? \Yii::t('app', 'Показать') : \Yii::t('app', 'Скрыть'),
                ],
                'sort',
            ],
        ]
    ) ?>

</div>
