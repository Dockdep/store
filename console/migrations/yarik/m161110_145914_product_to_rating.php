<?php
    
    use yii\db\Migration;
    
    class m161110_145914_product_to_rating extends Migration
    {
        public function up()
        {
            $this->createTable(
                'product_to_rating',
                [
                    'id' => $this->primaryKey(),
                    'product_id' => $this->integer()
                                         ->notNull()
                                         ->unique(),
                    'value' => $this->float()
                                    ->notNull()
                                    ->defaultValue(0),
                ]
            );
            
            $this->addForeignKey(
                'product_to_rating_to_product_fkey',
                'product_to_rating',
                'product_id',
                'product',
                'id',
                'CASCADE',
                'CASCADE'
            );
        }
        
        public function down()
        {
            $this->dropForeignKey('product_to_rating_to_product_fkey', 'product_to_rating');
            
            $this->dropTable('product_to_rating');
        }
    }
