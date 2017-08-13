<?php
    
    use artweb\artbox\models\Event;
    use artweb\artbox\models\EventLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View        $this
     * @var Event       $model
     * @var EventLang[] $modelLangs
     */
    
    $this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Event',
        ]) . $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Events'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->id,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="event-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
