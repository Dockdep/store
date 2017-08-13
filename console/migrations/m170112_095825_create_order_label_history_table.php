<?php
    
    use yii\db\Migration;
    
    /**
     * Handles the creation of table `order_label_history`.
     */
    class m170112_095825_create_order_label_history_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $this->createTable(
                'order_label_history',
                [
                    'id'         => $this->primaryKey(),
                    'label_id'   => $this->integer(),
                    'order_id'   => $this->integer(),
                    'user_id'    => $this->integer(),
                    'created_at' => $this->integer(),
                ]
            );
            
            $this->addForeignKey(
                'label_fk',
                'order_label_history',
                'label_id',
                'order_label',
                'id',
                'CASCADE',
                'CASCADE'
            );
    
            $this->addForeignKey(
                'order_fk',
                'order_label_history',
                'order_id',
                'order',
                'id',
                'CASCADE',
                'CASCADE'
            );
    
            $this->addForeignKey(
                'user_fk',
                'order_label_history',
                'user_id',
                'user',
                'id',
                'CASCADE',
                'CASCADE'
            );
        }
        
        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('user_fk', 'order_label_history');
            $this->dropForeignKey('order_fk', 'order_label_history');
            $this->dropForeignKey('label_fk', 'order_label_history');
    
            $this->dropTable('order_label_history');
        }
    }
