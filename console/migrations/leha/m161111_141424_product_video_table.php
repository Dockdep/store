<?php
    
    use yii\db\Migration;
    
    class m161111_141424_product_video_table extends Migration
    {
        public function up()
        {
            $this->createTable(
                'product_video',
                [
                    'id' => $this->primaryKey(),
                    'product_id' => $this->integer(),
                    'url' => $this->string(),
                ]
            );
            
            $this->addForeignKey(
                'product_video_fk',
                'product_video',
                'product_id',
                'product',
                'id',
                'CASCADE',
                'CASCADE'
            );
        }
        
        public function down()
        {
            $this->dropForeignKey('product_video_fk', 'product_video');
            
            $this->dropTable('product_video');
        }
    }
