<?php

use yii\helpers\Html;
use artweb\artbox\event\models\EventLang;
/**
 * @var $this yii\web\View
 * @var $model artweb\artbox\event\models\Event
 * @var EventLang[] $modelLangs
 */
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Event',
]) . $model->lang->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lang->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelLangs' => $modelLangs
    ]) ?>

</div>
