<?php
    
    use yii\db\Migration;
    
    class m160829_105345_add_default_languages extends Migration
    {
        
        public function up()
        {
            $this->batchInsert(
                '{{%language}}',
                [
                    'id',
                    'url',
                    'local',
                    'name',
                    'default',
                    'created_at',
                    'updated_at',
                ],
                [
                    [
                        1,
                        'en',
                        'en-EN',
                        'English',
                        0,
                        time(),
                        time(),
                    ],
                    [
                        2,
                        'ru',
                        'ru-RU',
                        'Русский',
                        1,
                        time(),
                        time(),
                    ],
                ]
            );
        }
        
        public function down()
        {
            $this->delete(
                '{{%language}}',
                [
                    'id' => [
                        1,
                        2,
                    ],
                ]
            );
        }
    }
