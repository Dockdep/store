<?php
    
    use yii\helpers\Html;
    
    /**
     * @var yii\web\View $this
     * @var artweb\artbox\design\models\Slider $model
     */
    $this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Slider',
        ]) . $model->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Sliders'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="slider-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
