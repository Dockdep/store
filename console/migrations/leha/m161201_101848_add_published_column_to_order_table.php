<?php

use yii\db\Migration;

/**
 * Handles adding published to table `order`.
 */
class m161201_101848_add_published_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('order', 'published', $this->boolean());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('order', 'published');
    }
}
