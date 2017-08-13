<?php

use yii\db\Migration;

/**
 * Handles adding short to table `order_payment`.
 */
class m161129_171901_add_short_column_to_order_payment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order_payment', 'short', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order_payment', 'short');
    }
}
