<?php
    use artweb\artbox\models\Customer;
    use yii\helpers\Url;
    
    /**
     * @var Customer $model
     * @var string   $password
     */
    $site = Url::home(true);
?>
<h3>Вы успешно зарегистрированы!</h3>
<p>Вы сделали заказ и указали email, поэтому были зарегистрированы на сайте
    <a href="<?php echo $site; ?>"><?php echo $site; ?></a>.</p>
<div>
    <p>Для Вас был сгенерирован пользователь со следующими данными:</p>
    <ul>
        <li>Имя: <?php echo $model->username; ?></li>
        <li>Логин: <?php echo $model->email; ?></li>
        <li>Пароль: <?php echo $password; ?></li>
    </ul>
    <p>Аккаунт нужно активировать, для этого перейдите <a href="<?php echo Url::to(
            [
                'site/confirm',
                'id' => $model->id,
                'key' => $model->auth_key,
            ],
            true
        ); ?>">по ссылке</a></p>
</div>
<div>
    <p><strong>Extremstyle</strong></p>
    <p><?php echo date('Y'); ?></p>
</div>