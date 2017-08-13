<?php

use yii\db\Migration;

class m161101_144140_blog_article_to_tag extends Migration
{
    public function up()
    {
        /**
         * Create junction table to connect articles with tags
         */
        $this->createTable(
            'blog_article_to_tag',
            [
                'id'              => $this->primaryKey(),
                'blog_article_id' => $this->integer()
                                          ->notNull(),
                'blog_tag_id'     => $this->integer()
                                          ->notNull(),
            ]
        );
    
        /**
         * Create indexes and foreign keys for junction table
         */
        $this->createIndex(
            'blog_article_to_tag_uk',
            'blog_article_to_tag',
            [
                'blog_article_id',
                'blog_tag_id',
            ],
            true
        );
    
        $this->addForeignKey(
            'blog_article_to_tag_tag_fk',
            'blog_article_to_tag',
            'blog_tag_id',
            'blog_tag',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'blog_article_to_tag_art_fk',
            'blog_article_to_tag',
            'blog_article_id',
            'blog_article',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('blog_article_to_tag_art_fk', 'blog_article_to_tag');
        $this->dropForeignKey('blog_article_to_tag_tag_fk', 'blog_article_to_tag');
        $this->dropIndex('blog_article_to_tag_uk', 'blog_article_to_tag');
        $this->dropTable('blog_article_to_tag');
    }
}
