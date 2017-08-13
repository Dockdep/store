<?php

namespace artweb\artbox\widgets;

use yii\base\Widget;

class Mailer extends Widget{
    public $message;
    public $email;
    public $text;
    public $subject;
    public $type;
    public $params;


    public function init(){

        parent::init();

    }

    public function run(){

        $mail = new \PHPMailer();

        $mail->IsSMTP();
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Host       = "195.248.225.139";
        $mail->SMTPDebug  = true;
        $mail->SMTPAuth   = 0;
        $mail->Port       = 25;
        $mail->CharSet = 'UTF-8';
        $mail->Username = "";
        $mail->Password = "";
        $mail->SetFrom('Rukzachok.com.ua@gmail.com');
        $mail->Subject = $this->subject;
        $mail->MsgHTML($this->render($this->type, ['params' => $this->params]));
        $address = "Rukzachok.com.ua@gmail.com";
        $mail->AddAddress($address);
        $mail->AddAddress($this->email);
        $mail->AddAddress('dockdep@gmail.com');
        if(!$mail->send()) {

            \Yii::$app->getSession()->setFlash('error', 'Mailer Error: ' . $mail->ErrorInfo);
            
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {


            return 'Message has been sent';
        }
    }

}

