<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\catalog\models\Category;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "tax_group_to_category".
     *
     * @property integer  $tax_group_to_category_id
     * @property integer  $tax_group_id
     * @property integer  $category_id
     * @property Category $category
     * @property TaxGroup $taxGroup
     */
    class TaxGroupToCategory extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'tax_group_to_category';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'tax_group_id',
                        'category_id',
                    ],
                    'required',
                ],
                [
                    [
                        'tax_group_id',
                        'category_id',
                    ],
                    'integer',
                ],
                [
                    [ 'category_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Category::className(),
                    'targetAttribute' => [ 'category_id' => 'id' ],
                ],
                [
                    [ 'tax_group_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => TaxGroup::className(),
                    'targetAttribute' => [ 'tax_group_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'tax_group_to_category_id' => 'Tax Group To Category ID',
                'tax_group_id'             => 'Tax Group ID',
                'category_id'              => 'Category ID',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCategory()
        {
            return $this->hasOne(Category::className(), [ 'id' => 'category_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTaxGroup()
        {
            return $this->hasOne(TaxGroup::className(), [ 'id' => 'tax_group_id' ]);
        }
    }
