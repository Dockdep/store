<?php

use yii\db\Migration;

/**
 * Handles adding size_image to table `product`.
 */
class m161116_160207_add_size_image_column_to_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product', 'size_image', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product', 'size_image');
    }
}
