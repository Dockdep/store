<?php

use yii\db\Migration;

class m161101_142334_blog_article extends Migration
{
    public function up()
    {
        /**
         * Create main table with blog's articles
         */
        $this->createTable(
            'blog_article',
            [
                'id'         => $this->primaryKey(),
                'image'      => $this->string(255),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'deleted_at' => $this->integer(),
                'sort'       => $this->integer(),
                'status'     => $this->boolean(),
                'author_id'  => $this->integer(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('blog_article');
    }
}
