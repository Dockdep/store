<?php
    
    namespace artweb\artbox\ecommerce\behaviors;
    
    use artweb\artbox\ecommerce\models\ProductOption;
    use artweb\artbox\ecommerce\models\TaxOption;
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
    