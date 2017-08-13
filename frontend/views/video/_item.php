<?php
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\helpers\Html;
    use yii\web\View;
    /**
     * @var View         $this
     * @var ProductVideo $model
     */
    echo Html::a($model->title?:\Yii::t('app', 'Видео без названия'), ['video/view', 'id' => $model->id]);
