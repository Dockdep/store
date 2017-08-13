<?php
namespace artweb\artbox\components;

use yii\base\Component;

class SmsSender extends Component
{

    public $tel;
    public $text;

    public function send($tel, $text)
    {

       // $text = iconv('windows-1251', 'utf-8', htmlspecialchars($text));
        $description = iconv('windows-1251', 'utf-8', htmlspecialchars($tel . ' description'));
        $start_time = 'AUTO'; // отправить немедленно или ставим дату и время  в формате YYYY-MM-DD HH:MM:SS
        $end_time = 'AUTO'; // автоматически рассчитать системой или ставим дату и время  в формате YYYY-MM-DD HH:MM:SS
        $rate = 1; // скорость отправки сообщений (1 = 1 смс минута). Одиночные СМС сообщения отправляются всегда с максимальной скоростью.
        $lifetime = 4; // срок жизни сообщения 4 часа

        $source = 'rukzachok'; // Alfaname
        $recipient = $tel;
        $user = '380674064008';
        $password = 'smsartweb2012';

        $myXML = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $myXML .= "<request>";
        $myXML .= "<operation>SENDSMS</operation>";
        $myXML .= '		<message start_time="' . $start_time . '" end_time="' . $end_time . '" lifetime="' . $lifetime . '" rate="' . $rate . '" desc="' . $description . '" source="' . $source . '">' . "\n";
        $myXML .= "		<body>" . $text . "</body>";
        $myXML .= "		<recipient>" . $recipient . "</recipient>";
        $myXML .= "</message>";
        $myXML .= "</request>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://sms-fly.com/api/api.php');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myXML);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
