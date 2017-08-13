<?php

use yii\db\Migration;

/**
 * Handles adding delivery_cost to table `order`.
 */
class m161130_174748_add_delivery_cost_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order', 'delivery_cost', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order', 'delivery_cost');
    }
}
