<?php

use yii\db\Migration;

class m161101_143734_blog_tag extends Migration
{
    public function up()
    {
        /**
         * Create table for tags
         */
        $this->createTable(
            'blog_tag',
            [
                'id' => $this->primaryKey(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('blog_tag');
    }
}
