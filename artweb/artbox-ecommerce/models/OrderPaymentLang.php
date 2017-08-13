<?php

namespace artweb\artbox\ecommerce\models;

use artweb\artbox\language\models\Language;
use Yii;

/**
 * This is the model class for table "order_payment_lang".
 *
 * @property integer $id
 * @property integer $order_payment_id
 * @property integer $language_id
 * @property string $title
 * @property string $text
 *
 * @property Language $language
 * @property OrderPayment $orderPayment
 */
class OrderPaymentLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_payment_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_payment_id', 'language_id', 'title'], 'required'],
            [['order_payment_id', 'language_id'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['order_payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderPayment::className(), 'targetAttribute' => ['order_payment_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_payment_id' => 'Order Payment ID',
            'language_id' => 'Language ID',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPayment()
    {
        return $this->hasOne(OrderPayment::className(), ['id' => 'order_payment_id']);
    }
}
