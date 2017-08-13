<?php
    
    use artweb\artbox\models\SliderImage;
    use artweb\artbox\models\SliderImageLang;
    use yii\helpers\Html;
    use artweb\artbox\models\Slider;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View              $this
     * @var SliderImage       $model
     * @var SliderImageLang[] $modelLangs
     * @var Slider            $slider
     * @var int               $slider_id
     */
    
    $this->title = Yii::t(
            'app',
            'Update {modelClass}: ',
            [
                'modelClass' => 'Slider Image',
            ]
        ) . $model->id;
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
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Slide') . ': ' . $model->id,
        'url'   => Url::toRoute(
            [
                'view',
                'slider_id' => $slider_id,
                'id'        => $model->id,
            ]
        ),
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="slider-image-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
            'slider'     => $slider,
        ]
    ) ?>

</div>
