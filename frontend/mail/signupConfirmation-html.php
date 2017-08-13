<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $user common\models\User */

$resetLink =
    Yii::$app->urlManager->createAbsoluteUrl(
        ['site/confirm','id'=>$user->id,'key'=>$user->auth_key]
    );
?>

<p><?= \Yii::t('app','Здравствуйте'); ?> <?= $user->username ?>!</p>

<p><?= Html::a(Html::encode(\Yii::t('app','Перейдите по этой ссылке для активации аккаунта')), $resetLink) ?></p>