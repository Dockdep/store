<?php
    
    namespace artweb\artbox\models;
    
    use artweb\artbox\modules\catalog\models\Product;
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_to_rating".
     * @property integer $product_to_rating_id
     * @property integer $product_id
     * @property double  $value
     * @property Product $product
     */
    class ProductToRating extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_to_rating';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'product_id' ],
                    'required',
                ],
                [
                    [ 'product_id' ],
                    'integer',
                ],
                [
                    [ 'value' ],
                    'number',
                    'min' => 0,
                ],
                [
                    [ 'product_id' ],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Product::className(),
                    'targetAttribute' => [ 'product_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'product_to_rating_id' => Yii::t('app', 'product_to_rating_id'),
                'product_id'           => Yii::t('app', 'product_id'),
                'value'                => Yii::t('app', 'value'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getProduct()
        {
            return $this->hasOne(Product::className(), [ 'id' => 'product_id' ]);
        }
    }
