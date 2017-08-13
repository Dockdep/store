<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "tax_variant_group_to_category".
     *
     * @property integer         $tax_group_to_category_id
     * @property integer         $tax_group_id
     * @property integer         $category_id
     * @property Category        $category
     * @property TaxVariantGroup $taxGroup
     * @property TaxVariantGroup $taxVariantGroup
     */
    class TaxVariantGroupToCategory extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'tax_variant_group_to_category';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'tax_variant_group_id',
                        'category_id',
                    ],
                    'required',
                ],
                [
                    [
                        'tax_variant_group_id',
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
                    [ 'tax_variant_group_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => TaxVariantGroup::className(),
                    'targetAttribute' => [ 'tax_variant_group_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'                   => 'Tax Variant Group To Category ID',
                'tax_variant_group_id' => 'Tax Variant Group ID',
                'category_id'          => 'Category ID',
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
        public function getVariantTaxGroup()
        {
            return $this->hasOne(TaxVariantGroup::className(), [ 'id' => 'tax_variant_group_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTaxGroup()
        {
            return $this->getVariantTaxGroup();
        }
    }
