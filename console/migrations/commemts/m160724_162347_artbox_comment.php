<?php
    
    use yii\db\Migration;
    
    class m160724_162347_artbox_comment extends Migration
    {
        
        public function up()
        {
            $this->createTable(
                '{{%artbox_comment}}',
                [
                    'artbox_comment_id'  => $this->primaryKey(),
                    'text'               => $this->text()
                                                 ->notNull(),
                    'user_id'            => $this->integer(),
                    'username'           => $this->string(),
                    'email'              => $this->string(),
                    'created_at'         => $this->integer()
                                                 ->notNull(),
                    'updated_at'         => $this->integer()
                                                 ->notNull(),
                    'deleted_at'         => $this->integer(),
                    'status'             => $this->integer()
                                                 ->notNull()
                                                 ->defaultValue(1),
                    'artbox_comment_pid' => $this->integer(),
                    'related_id'         => $this->integer(),
                    'ip'                 => $this->string()
                                                 ->notNull(),
                    'info'               => $this->text(),
                ]
            );
            
            $this->addForeignKey(
                'user_id_user',
                '{{%artbox_comment}}',
                'user_id',
                'customer',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey(
                'artbox_comment_pid_artbox_comment',
                '{{%artbox_comment}}',
                'artbox_comment_pid',
                'artbox_comment',
                'artbox_comment_id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey(
                'related_id_artbox_comment',
                '{{%artbox_comment}}',
                'related_id',
                'artbox_comment',
                'artbox_comment_id',
                'CASCADE',
                'CASCADE'
            );
            
            $this->createTable(
                '{{%artbox_like}}',
                [
                    'artbox_like_id'    => $this->primaryKey(),
                    'artbox_comment_id' => $this->integer()
                                                ->notNull(),
                    'user_id'           => $this->integer(),
                    'created_at'        => $this->integer()
                                                ->notNull(),
                    'is_like'           => $this->integer()
                                                ->notNull()
                                                ->defaultValue(1),
                ]
            );
            
            $this->addForeignKey(
                'artbox_comment_id_artbox_comment',
                '{{%artbox_like}}',
                'artbox_comment_id',
                'artbox_comment',
                'artbox_comment_id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey('user_id_user', '{{%artbox_like}}', 'user_id', 'customer', 'id', 'CASCADE', 'CASCADE');
            $this->createIndex(
                'artbox_like_unique',
                '{{%artbox_like}}',
                [
                    'artbox_comment_id',
                    'user_id',
                    'is_like',
                ],
                true
            );
            
        }
        
        public function down()
        {
            $this->dropForeignKey('user_id_user', '{{%artbox_comment}}');
            $this->dropForeignKey('artbox_comment_pid_artbox_comment', '{{%artbox_comment}}');
            $this->dropForeignKey('related_id_artbox_comment', '{{%artbox_comment}}');
            $this->dropForeignKey('artbox_comment_id_artbox_comment', '{{%artbox_like}}');
            $this->dropForeignKey('user_id_user', '{{%artbox_like}}');
            $this->dropIndex('artbox_like_unique', '{{%artbox_like}}');
            $this->dropTable('{{%artbox_comment}}');
            $this->dropTable('{{%artbox_like}}');
        }
    }
