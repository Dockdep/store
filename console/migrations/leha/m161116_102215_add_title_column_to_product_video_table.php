<?php

use yii\db\Migration;

/**
 * Handles adding title to table `product_video`.
 */
class m161116_102215_add_title_column_to_product_video_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_video', 'title', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_video', 'title');
    }
}
