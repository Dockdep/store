<?php

use yii\db\Migration;

class m161101_144312_blog_article_to_article extends Migration
{
    public function up()
    {
        /**
         * Create table and all relations for related articles functionality
         */
        $this->createTable(
            'blog_article_to_article',
            [
                'id'                      => $this->primaryKey(),
                'blog_article_id'         => $this->integer()
                                                  ->notNull(),
                'related_blog_article_id' => $this->integer()
                                                  ->notNull(),
            ]
        );
    
        $this->createIndex(
            'blog_article_to_article_uk',
            'blog_article_to_article',
            [
                'blog_article_id',
                'related_blog_article_id',
            ],
            true
        );
    
        $this->addForeignKey(
            'blog_article_to_article_art_fk',
            'blog_article_to_article',
            'blog_article_id',
            'blog_article',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'blog_article_to_article_rel_fk',
            'blog_article_to_article',
            'related_blog_article_id',
            'blog_article',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('blog_article_to_article_rel_fk', 'blog_article_to_article');
        $this->dropForeignKey('blog_article_to_article_art_fk', 'blog_article_to_article');
        $this->dropIndex('blog_article_to_article_uk', 'blog_article_to_article');
        $this->dropTable('blog_article_to_article');
    }
}
