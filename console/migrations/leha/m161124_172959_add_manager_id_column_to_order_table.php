<?php

use yii\db\Migration;

/**
 * Handles adding manager_id to table `order`.
 */
class m161124_172959_add_manager_id_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order', 'manager_id', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order', 'manager_id');
    }
}
