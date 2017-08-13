<?php

use yii\db\Migration;

class m161128_150008_add_blocking_to_order_table extends Migration
{
    public function up()
    {
        $this->addColumn('order', 'edit_time', $this->integer()->defaultValue(0));
        $this->addColumn('order', 'edit_id', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('order', 'edit_id');
        $this->dropColumn('order', 'edit_time');
    }
}
