<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sms_template".
 *
 * @property integer $id
 * @property string $text
 * @property string $title
 */
class SmsTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Text'),
            'title' => Yii::t('app', 'Title'),
        ];
    }
}
