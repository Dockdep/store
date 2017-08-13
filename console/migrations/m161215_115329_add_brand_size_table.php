<?php
    
    use yii\db\Migration;
    
    class m161215_115329_add_brand_size_table extends Migration
    {
        public function up()
        {
            $this->createTable(
                'brand_size',
                [
                    'id'       => $this->primaryKey(),
                    'brand_id' => $this->integer(),
                    'image'    => $this->string(255),
                ]
            );
            
            $this->addForeignKey('brand_size_fk', 'brand_size', 'brand_id', 'brand', 'id', 'CASCADE', 'CASCADE');
        }
        
        public function down()
        {
            $this->dropForeignKey('brand_size_fk', 'brand_size');
            
            $this->dropTable('brand_size');
        }
    }
