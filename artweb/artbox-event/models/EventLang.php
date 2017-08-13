<?php

namespace artweb\artbox\event\models;

use artweb\artbox\language\models\Language;
use Yii;

/**
 * This is the model class for table "event_lang".
 *
 * @property integer $event_id
 * @property integer $language_id
 * @property string $title
 * @property string $body
 * @property string $meta_title
 * @property string $meta_description
 * @property string $seo_text
 * @property string $h1
 * @property string $alias
 *
 * @property Event $event
 * @property Language $language
 */
class EventLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_lang';
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'artweb\artbox\behaviors\Slug',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'language_id', 'title', 'body'], 'required'],
            [['event_id', 'language_id'], 'integer'],
            [['body', 'seo_text'], 'string'],
            [['title', 'meta_title', 'meta_description', 'h1', 'alias'], 'string', 'max' => 255],
            [['alias'], 'unique'],
            [['event_id', 'language_id'], 'unique', 'targetAttribute' => ['event_id', 'language_id'], 'message' => 'The combination of Event ID and Language ID has already been taken.'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'language_id' => 'Language ID',
            'title' => 'Title',
            'body' => 'Body',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'seo_text' => 'Seo Text',
            'h1' => 'H1',
            'alias' => 'Alias',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
