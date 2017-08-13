<?php

use yii\db\Migration;

class m161111_131221_position_column extends Migration
{
    public function up()
    {
        $this->addColumn('tax_group', 'position', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('tax_group', 'position');
    }
}
