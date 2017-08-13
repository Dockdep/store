<?php

use yii\db\Migration;

class m161108_120225_product_variant_status extends Migration
{
    public function up()
    {
        $this->addColumn('product_variant', 'status', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('product_variant', 'status');
    }
}
