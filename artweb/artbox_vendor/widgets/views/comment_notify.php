<?php
    /**
     * @var array $params
     */
    use yii\db\ActiveRecord;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="uk">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rukzachok.com.ua</title>
    <style type="text/css">
        body {
            font-family: helvetica neue, arial, sans-serif;
            line-height: 1.5;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body style="margin: 0;">
<div class="container" style="
        margin: 0 auto;
        font-family: helvetica neue, arial, sans-serif;
        font-size: 16px;
        line-height: 1.5;
        width: 100%;
        max-width:  740px;
        min-width: 360px;
        ">
    <h3>Ваш коммертарий успешно опубликован.</h3>
    <p>Добрый день!</p>
    <p>Ваш комментарий прошел модерацию и успешно опубликован.</p>
    <p><?= $params['comment']->text; ?></p>
    <p>Чтобы просмотреть комментарий перейдите по ссылке: <a href="<?= $params['url']; ?>">Прочесть комментарий</a></p>
</div>
</body>
</html>