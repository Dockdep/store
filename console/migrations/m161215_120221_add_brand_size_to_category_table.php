<?php
    
    use yii\db\Migration;
    
    class m161215_120221_add_brand_size_to_category_table extends Migration
    {
        public function up()
        {
            $this->createTable(
                'brand_size_to_category',
                [
                    'id'            => $this->primaryKey(),
                    'brand_size_id' => $this->integer(),
                    'category_id'   => $this->integer(),
                ]
            );
            
            $this->addForeignKey(
                'brand_size_fk',
                'brand_size_to_category',
                'brand_size_id',
                'brand_size',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey(
                'category_fk',
                'brand_size_to_category',
                'category_id',
                'category',
                'id',
                'CASCADE',
                'CASCADE'
            );
            
            $this->createIndex(
                'brand_size_uk',
                'brand_size_to_category',
                [
                    'brand_size_id',
                    'category_id',
                ],
                true
            );
        }
        
        public function down()
        {
            $this->dropIndex('brand_size_uk', 'brand_size_to_category');
            $this->dropForeignKey('category_fk', 'brand_size_to_category');
            $this->dropForeignKey('brand_size_fk', 'brand_size_to_category');
            $this->dropTable('brand_size_to_category');
        }
    }
