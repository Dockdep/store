<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user artweb\artbox\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $params->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($params->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
<?php die();?>