<?php

use yii\db\Migration;

class m161209_130225_add_video_columns extends Migration
{
   
    public function safeUp()
    {
        $this->addColumn('product_video', 'is_main', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('product_video', 'is_display', $this->boolean()->notNull()->defaultValue(false));
    }

    public function safeDown()
    {
        $this->dropColumn('product_video', 'is_main');
        $this->dropColumn('product_video', 'is_display');
    }
}
