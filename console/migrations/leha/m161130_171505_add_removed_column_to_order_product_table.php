<?php

use yii\db\Migration;

/**
 * Handles adding removed to table `order_product`.
 */
class m161130_171505_add_removed_column_to_order_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order_product', 'removed', $this->boolean()->defaultValue(false));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order_product', 'removed');
    }
}
