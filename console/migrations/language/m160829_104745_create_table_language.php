<?php
    
    use yii\db\Migration;
    
    class m160829_104745_create_table_language extends Migration
    {
        
        public function up()
        {
            $this->createTable(
                '{{%language}}',
                [
                    'id' => $this->primaryKey(),
                    'url'         => $this->string()
                                          ->notNull(),
                    'local'       => $this->string()
                                          ->notNull(),
                    'name'        => $this->string()
                                          ->notNull(),
                    'default'     => $this->boolean()
                                          ->notNull()
                                          ->defaultValue(false),
                    'created_at'  => $this->integer()
                                          ->notNull(),
                    'updated_at'  => $this->integer()
                                          ->notNull(),
                ]
            );
        }
        
        public function down()
        {
            $this->dropTable('{{%language}}');
        }
    }
