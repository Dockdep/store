<?php

use yii\db\Migration;

/**
 * Handles adding time to table `order`.
 */
class m161123_094442_add_time_columns_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order', 'created_at', $this->integer());
        $this->addColumn('order', 'updated_at', $this->integer());
        $this->addColumn('order', 'deleted_at', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order', 'deleted_at');
        $this->dropColumn('order', 'updated_at');
        $this->dropColumn('order', 'created_at');
    }
}
