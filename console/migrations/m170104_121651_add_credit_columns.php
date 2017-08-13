<?php

use yii\db\Migration;

class m170104_121651_add_credit_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'credit_sum', $this->float());
        $this->addColumn('order', 'credit_month', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('order', 'credit_sum');
        $this->dropColumn('order', 'credit_month');
    }
}
