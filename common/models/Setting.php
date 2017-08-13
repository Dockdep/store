<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 */
class Setting extends \yii\db\ActiveRecord
{
    const ELASTIC_SEARCH = 'elastic_search';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }
}
