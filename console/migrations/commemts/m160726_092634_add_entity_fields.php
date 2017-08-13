<?php
    
    use yii\db\Migration;
    
    class m160726_092634_add_entity_fields extends Migration
    {
        
        public function up()
        {
            $this->addColumn('{{%artbox_comment}}', 'entity', $this->string()
                                                                   ->notNull()
                                                                   ->defaultValue(''));
            $this->addColumn('{{%artbox_comment}}', 'entity_id', $this->integer()
                                                                      ->notNull()
                                                                      ->defaultValue(1));
        }
        
        public function down()
        {
            $this->dropColumn('{{%artbox_comment}}', 'entity');
            $this->dropColumn('{{%artbox_comment}}', 'entity_id');
        }
    }
