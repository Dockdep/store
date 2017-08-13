<?php

use yii\db\Migration;

class m161101_143939_blog_tag_lang extends Migration
{
    public function up()
    {
        /**
         * Tags can be in different languages
         */
        $this->createTable(
            'blog_tag_lang',
            [
                'id'          => $this->primaryKey(),
                'blog_tag_id' => $this->integer()
                                      ->notNull(),
                'language_id' => $this->integer()
                                      ->notNull(),
                'label'       => $this->string(255),
            ]
        );
    
        /**
         * Creating indexes and foreign keys for language table
         */
        $this->createIndex(
            'blog_tag_lang_uk',
            'blog_tag_lang',
            [
                'blog_tag_id',
                'language_id',
            ],
            true
        );
    
        $this->addForeignKey(
            'blog_tag_lang_fk',
            'blog_tag_lang',
            'language_id',
            'language',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'blog_tag_fk',
            'blog_tag_lang',
            'blog_tag_id',
            'blog_tag',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('blog_tag_fk', 'blog_tag_lang');
        $this->dropForeignKey('blog_tag_lang_fk', 'blog_tag_lang');
        $this->dropIndex('blog_tag_lang_uk', 'blog_tag_lang');
        $this->dropTable('blog_tag_lang');
    }
}
