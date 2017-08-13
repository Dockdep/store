<?php

use yii\db\Migration;

class m170119_145641_setting extends Migration
{
    public function up()
    {
        $this->createTable(
            'setting',
            [
                'id'       => $this->primaryKey(),
                'name' => $this->string(255),
                'value'    => $this->string(255),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('setting');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
