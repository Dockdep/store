<?php
    
    use yii\db\Migration;
    
    class m160927_124151_add_status_column extends Migration
    {
        
        public function up()
        {
            $this->addColumn('language', 'status', $this->boolean()
                                                        ->notNull()
                                                        ->defaultValue(false));
        }
        
        public function down()
        {
            $this->dropColumn('language', 'status');
        }
    }
