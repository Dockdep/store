<?php

namespace artweb\artbox\event\models;

use artweb\artbox\ecommerce\models\Product;
use Yii;

/**
 * This is the model class for table "events_to_products".
 *
 * @property integer $events_to_products_id
 * @property integer $event_id
 * @property integer $product_id
 *
 * @property Event $event
 * @property Product $product
 */
class EventsToProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events_to_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'product_id'], 'required'],
            [['event_id', 'product_id'], 'integer'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'events_to_products_id' => 'Events To Products ID',
            'event_id' => 'Event ID',
            'product_id' => 'Product ID',
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
