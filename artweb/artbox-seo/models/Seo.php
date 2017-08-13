<?php

namespace artweb\artbox\seo\models;

use Yii;

/**
 * This is the model class for table "seo".
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $meta
 * @property string $description
 * @property string $h1
 * @property string $seo_text
 */
class Seo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['seo_text'], 'string'],
            [['url', 'title', 'meta', 'description', 'h1'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'seo_id'),
            'url' => Yii::t('app', 'url'),
            'title' => Yii::t('app', 'title'),
            'meta' => Yii::t('app', 'meta_title'),
            'description' => Yii::t('app', 'description'),
            'h1' => Yii::t('app', 'h1'),
            'seo_text' => Yii::t('app', 'seo_text'),
        ];
    }
}
