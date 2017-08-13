<?php
    
    namespace artweb\artbox\modules\catalog\behaviors;
    
    use artweb\artbox\modules\catalog\models\ProductOption;
    use artweb\artbox\modules\catalog\models\TaxOption;
    use yii\base\Behavior;
    use yii\db\ActiveRecord;
    
    class FilterBehavior extends Behavior
    {
        
        public function getFilters()
        {
            
            /**
             * @var ActiveRecord $owner
             */
            $owner = $this->owner;
            return $owner->hasMany(TaxOption::className(), [ 'tax_option_id' => 'option_id' ])
                         ->viaTable(ProductOption::tableName(), [ 'product_id' => $owner->getTableSchema()->primaryKey[ 0 ] ])
                         ->joinWith('taxGroup')
                         ->all();
        }
        
    }