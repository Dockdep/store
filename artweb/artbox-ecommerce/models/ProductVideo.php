<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "product_video".
     *
     * @property integer $id
     * @property integer $product_id
     * @property string  $url
     * @property Product $product
     * @property string  $title
     * @property boolean $is_main
     * @property boolean $is_display
     */
    class ProductVideo extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'product_video';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'product_id' ],
                    'integer',
                ],
                [
                    [
                        'url',
                        'title',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'url',
                        'title',
                    ],
                    'required',
                ],
                [
                    [
                        'is_main',
                        'is_display',
                    ],
                    'boolean',
                ],
                [
                    [
                        'url',
                    ],
                    'match',
                    'pattern' => '/^.*<iframe.*https:\/\/.*$/',
                    'message' => \Yii::t(
                        'app',
                        'Ссылка обязательно должна включать тег iframe и протокол должен быть https://'
                    ),
                ],
                [
                    [ 'product_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Product::className(),
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
                'id'         => \Yii::t('app', 'ID'),
                'product_id' => \Yii::t('app', 'Product ID'),
                'url'        => \Yii::t('app', 'Url'),
                'title'      => \Yii::t('app', 'Title'),
                'is_main'    => \Yii::t('app', 'Is main'),
                'is_display' => \Yii::t('app', 'Is display'),
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
