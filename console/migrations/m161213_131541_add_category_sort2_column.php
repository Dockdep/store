<?php

use yii\db\Migration;

class m161213_131541_add_category_sort2_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('category', 'sort2', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('category', 'sort2');
    }
}
