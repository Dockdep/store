<?php
    
    use yii\db\Migration;
    
    /**
     * Handles the creation of table `order_product_log`.
     */
    class m170126_143229_create_order_product_log_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $this->createTable(
                'order_product_log',
                [
                    'id' => $this->primaryKey(),
                    'order_product_id' => $this->integer(),
                    'created_at' => $this->integer(),
                    'user_id' => $this->integer(),
                    'order_id' => $this->integer(),
                    'data' => 'json',
                ]
            );
            
            $this->addForeignKey(
                'order_product_fk',
                'order_product_log',
                'order_product_id',
                'order_product',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey('user_fk', 'order_product_log', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
            $this->addForeignKey('order_fk', 'order_product_log', 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
        }
        
        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('order_fk', 'order_product_log');
            $this->dropForeignKey('user_fk', 'order_product_log');
            $this->dropForeignKey('order_product_fk', 'order_product_log');
            $this->dropTable('order_product_log');
        }
    }
