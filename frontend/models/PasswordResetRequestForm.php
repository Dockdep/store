<?php
namespace frontend\models;

use artweb\artbox\models\Customer;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'artweb\artbox\models\Customer',
                'filter' => ['status' => Customer::STATUS_ACTIVE],
                'message' => \Yii::t('app','Нет ни одного пользователя с такой электронной почтой.')
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'                => Yii::t('app', 'Ваш e-mail')
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = Customer::findOne([
            'status' => Customer::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!Customer::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }

        if (!$user->save()) {
            return false;
        }



        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html'],
                ['user' => $user]
            )
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject(\Yii::t('app','Восстановление пароля для') .' '. \Yii::$app->name)
            ->send();
    }
}
