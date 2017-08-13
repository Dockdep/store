<?php

use yii\db\Migration;

class m161101_143033_blog_category extends Migration
{
    public function up()
    {
        /**
         * Create table for blog's categories
         */
        $this->createTable(
            'blog_category',
            [
                'id'        => $this->primaryKey(),
                'sort'      => $this->integer(),
                'image'     => $this->string(255),
                'parent_id' => $this->integer()
                                    ->defaultValue(0),
                'status'    => $this->boolean(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('blog_category');
    }
}
