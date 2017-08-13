<?php

use yii\db\Migration;

/**
 * Handles adding sort to table `category`.
 */
class m161204_214538_add_sort_column_to_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('category', 'sort', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('category', 'sort');
    }
}
