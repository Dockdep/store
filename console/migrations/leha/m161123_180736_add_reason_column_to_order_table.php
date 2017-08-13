<?php

use yii\db\Migration;

/**
 * Handles adding reason to table `order`.
 */
class m161123_180736_add_reason_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order', 'reason', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order', 'reason');
    }
}
