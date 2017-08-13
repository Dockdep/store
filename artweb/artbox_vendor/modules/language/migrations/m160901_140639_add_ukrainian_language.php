<?php
    
    use yii\db\Migration;
    
    class m160901_140639_add_ukrainian_language extends Migration
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
                        3,
                        'ua',
                        'ua-UA',
                        'Українська',
                        0,
                        time(),
                        time(),
                    ],
                ]
            );
        }
        
        public function down()
        {
            $this->delete('{{%language}}', [ 'id' => [ 3 ] ]);
        }
    }
