<?php

use yii\db\Migration;

/**
 * Handles adding deadline to table `order`.
 */
class m161123_150528_add_deadline_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order', 'deadline', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order', 'deadline');
    }
}
