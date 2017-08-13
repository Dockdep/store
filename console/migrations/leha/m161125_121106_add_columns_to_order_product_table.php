<?php
    
    use yii\db\Migration;
    
    class m161125_121106_add_columns_to_order_product_table extends Migration
    {
        public function up()
        {
            $this->addColumn('order_product', 'status', $this->string(255));
            $this->addColumn('order_product', 'booking', $this->string(255));
            $this->addColumn('order_product', 'return', $this->boolean());
        }
        
        public function down()
        {
            $this->dropColumn('order_product', 'return');
            $this->dropColumn('order_product', 'booking');
            $this->dropColumn('order_product', 'status');
        }
    }
