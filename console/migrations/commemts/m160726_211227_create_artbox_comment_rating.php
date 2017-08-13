<?php
    
    use yii\db\Migration;
    
    class m160726_211227_create_artbox_comment_rating extends Migration
    {
        
        public function up()
        {
            $this->createTable(
                '{{%artbox_comment_rating}}',
                [
                    'artbox_comment_rating_id' => $this->primaryKey(),
                    'created_at'               => $this->integer()
                                                       ->notNull(),
                    'updated_at'               => $this->integer()
                                                       ->notNull(),
                    'user_id'                  => $this->integer(),
                    'value'                    => $this->float(),
                    'model'                    => $this->string()
                                                       ->notNull(),
                    'model_id'                 => $this->integer()
                                                       ->notNull(),
                ]
            );
            $this->addForeignKey(
                'user_id_user',
                '{{%artbox_comment_rating}}',
                'user_id',
                'customer',
                'id',
                'CASCADE',
                'CASCADE'
            );
        }
        
        public function down()
        {
            $this->dropForeignKey('user_id_user', '{{%artbox_comment_rating}}');
            $this->dropTable('{{%artbox_comment_rating}}');
        }
    }
