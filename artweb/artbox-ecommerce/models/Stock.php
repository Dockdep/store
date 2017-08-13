<?php

namespace artweb\artbox\ecommerce\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property integer $id
 * @property string $title
 *
 * @property ProductStock[] $productStocks
 */
class Stock extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 150],
            [['title'], 'required'],
        ];
    }


    public function getSiteName(){
        switch(mb_strtolower($this->title)){
            case "харьков свет":
                return 'МАГАЗИН ХАРЬКОВ';
                break;
            case "осокорки":
                return 'МАГАЗИН "ОСОКОРКИ"';
                break;
            case "олимп":
                return 'ТЦ "ОЛИМПИЙСКИЙ"';
                break;
            case "магазин":
                return "МАГАЗИН ГЛУБОЧЕЦКАЯ";
                break;
            default:
                //return "На складе";
                break;
        }
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stock_id' => Yii::t('product', 'Stock ID'),
            'name' => Yii::t('product', 'Name'),
        ];
    }

}
