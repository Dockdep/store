<?php
    
    use yii\db\Migration;
    
    /**
     * Handles the creation of table `order_log`.
     */
    class m170116_110159_create_order_log_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $this->createTable(
                'order_log',
                [
                    'id'         => $this->primaryKey(),
                    'order_id'   => $this->integer(),
                    'created_at' => $this->integer(),
                    'user_id'    => $this->integer(),
                    'data'       => 'json',
                ]
            );
            
            $this->addForeignKey('order_fk', 'order_log', 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
            $this->addForeignKey('user_fk', 'order_log', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        }
        
        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('order_log');
        }
    }
