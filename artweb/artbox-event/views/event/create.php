<?php

use yii\helpers\Html;
use artweb\artbox\event\models\EventLang;
/**
 * @var $this yii\web\View
 * @var $model artweb\artbox\event\models\Event
 * @var EventLang[] $modelLangs
 */

$this->title = \Yii::t('app', 'create_item',['item'=>'Event']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelLangs' => $modelLangs
    ]) ?>

</div>
