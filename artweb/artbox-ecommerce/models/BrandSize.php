<?php

namespace artweb\artbox\ecommerce\models;

use artweb\artbox\behaviors\SaveImgBehavior;
use Yii;

/**
 * This is the model class for table "brand_size".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $image
 *
 * @property Brand $brand
 * @property Category[] $categories
 */
class BrandSize extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'image'     => [
                'class'  => SaveImgBehavior::className(),
                'fields' => [
                    [
                        'name'      => 'image',
                        'directory' => 'products',
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'brand_id' => Yii::t('app', 'Брэнд'),
            'image' => Yii::t('app', 'Сетка'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id'])->with('lang');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('brand_size_to_category', ['brand_size_id' => 'id']);
    }
}
