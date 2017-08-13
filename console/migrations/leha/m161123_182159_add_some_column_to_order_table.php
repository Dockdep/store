<?php
    
    use yii\db\Migration;
    
    /**
     * Handles adding some to table `order`.
     */
    class m161123_182159_add_some_column_to_order_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $this->addColumn('order', 'sms', $this->string(255));
            $this->addColumn('order', 'check', $this->string(255));
        }
        
        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropColumn('order', 'sms');
            $this->dropColumn('order', 'check');
        }
    }
