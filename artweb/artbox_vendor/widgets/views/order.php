
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
    <div style="text-align: center">
        <br>
        <h1 style="margin: -5px 40px 10px; line-height: 1.2;">Добрый день, <?php echo $params['order']->name ?>!</h1>
        <h2 style="font-weight: 300; margin: 10px 40px 40px; line-height: 1.2;">Ваш заказ получен. В ближайшее время с Вами свяжется менеджер для уточнения деталей</h2>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" valign="top" style="
        background: #eee; border-radius: 4px;
        width: 100%;
        ">
        <tr>
            <td style="text-align: left; vertical-align: top; font-size: 85%; padding: 20px 15px 15px 20px; border-right: 1px solid #ddd;">
             <strong>Заказ №<?php echo $params['order']->id ?></strong>
                <br>
                <br>
                На сумму <strong><?php echo $params['order']->total ?></strong> грн
            </td>
            <td style="text-align: left; vertical-align: top; font-size: 85%; padding: 20px 15px 15px 20px;">
                <strong>Данные покупателя</strong>
                <br>
                <?php echo $params['order']->name ?>
                <br>
                <nobr><?php echo $params['order']->phone ?></nobr>
                <br>
                <?php echo $params['order']->email ?>
            </td>
            <td style="text-align: left; vertical-align: top; font-size: 85%; padding: 20px 15px 15px;">
                <strong>Доставка</strong>
                <br>
                <?php echo $params['order']->city ?>
                <br>
                <?php echo $params['order']->adress ?>

            </td>
            <td style="text-align: left; vertical-align: top; font-size: 85%; padding: 20px 20px 15px 15px;">
                <strong>Спасибо за покупку!</strong>
                <br>
                <a href="http://www.rukzachok.com.ua"
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background: #fff;">
                <table cellpadding="0" cellspacing="0" border="0" valign="top" style="
              width: 100%;">
                    <thead style="font-size: 70%;">
                      <tr>
                        <th style="border-bottom: 2px solid #eee; padding: 10px 10px 5px 0;">&nbsp;</th>
                        <th style="border-bottom: 2px solid #eee; text-align: left; padding: 10px 10px 5px;">Название</th>
                        <th style="border-bottom: 2px solid #eee; text-align: right; padding: 10px 10px 5px;">Количество</th>
                        <th style="border-bottom: 2px solid #eee; text-align: right; padding: 10px 30px 5px 10px;">Цена</th>
                      </tr>
                    </thead>
                    <tbody>
                   <?php foreach ( $params['variants'] as $thisItem) { ?>
                    <tr>
                        <td style="padding: 0; vertical-align: top; line-height: 0; text-align: left; border-bottom: 1px solid #ddd; border-left: 1px solid #eee;">
                            <img src="http://rukzachok.com.ua<?= $thisItem['img'] ?>" width="150">
                        </td>
                        <td style="padding: 65px 10px 20px; vertical-align: top; text-align: left; border-bottom: 1px solid #ddd; font-size: 18px;">
                            <?php echo $thisItem['product_name'] ?>
                        </td>
                        <td style="padding: 65px 10px 20px; vertical-align: top; text-align: right; border-bottom: 1px solid #ddd; font-size: 18px;">
                            <small style="margin-right: 1px;">×</small><?php echo $thisItem['count'] ?>
                        </td>
                        <td style="padding: 65px 30px 20px 10px; vertical-align: top; text-align: right; border-bottom: 1px solid #ddd; border-right: 1px solid #eee; font-size: 18px;">
                            <?php echo number_format($thisItem['sum_cost']) ?> грн
                        </td>
                    </tr>
                   <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" style="background: #eee; vertical-align: top; border-radius: 0 0 0 4px; padding: 13px 10px 20px; border-top: 1px solid #ddd; text-align: right; font-size: 14px;">
                            <strong>Всего к оплате:</strong>
                        </td>
                        <td style="background: #eee; vertical-align: top; border-radius: 0 0 4px 0; padding: 10px 30px 20px 10px; border-top: 1px solid #ddd; text-align: right; font-size: 18px;">
                            <strong><?php echo number_format($params['order']->total) ?> грн</strong>
                        </td>
                    </tr>
                    <tfoot>
                </table>
            </td>
        </tr>
    </table>
    <div style="padding: 40px;">
        <center><img src="http://rukzachok.com.ua/img/logo.png"></center>

        <table cellpadding="0" cellspacing="0" border="0" valign="top" style="
              width: 100%; margin: 20px 0;">
            <tr>
                <td style="border-top: 1px solid #eee; text-align: right; font-size: 100%; padding: 30px 20px 0 0;">
                    +38 (067) 395 65 73
                </td>
                <td style="border-top: 1px solid #eee; text-align: left; font-size: 100%; padding: 30px 0 0 20px;">
                    <a href="http://rukzachok.com.ua">rukzachok.com.ua</a>
                </td>
            </tr>
        </table>

    </div>
</div>
</body>
</html>