<?php

use yii\db\Migration;

class m161101_143541_blog_article_to_category extends Migration
{
    public function up()
    {
        /**
         * Create junction table to connect articles with categories
         */
        $this->createTable(
            'blog_article_to_category',
            [
                'id'               => $this->primaryKey(),
                'blog_article_id'  => $this->integer()
                                           ->notNull(),
                'blog_category_id' => $this->integer()
                                           ->notNull(),
            ]
        );
    
        /**
         * Add foreign keys and indexes for junction table
         */
        $this->createIndex(
            'blog_article_to_category_uk',
            'blog_article_to_category',
            [
                'blog_article_id',
                'blog_category_id',
            ],
            true
        );
    
        $this->addForeignKey(
            'blog_article_to_category_art_fk',
            'blog_article_to_category',
            'blog_article_id',
            'blog_article',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'blog_article_to_category_cat_fk',
            'blog_article_to_category',
            'blog_category_id',
            'blog_category',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('blog_article_to_category_cat_fk', 'blog_article_to_category');
        $this->dropForeignKey('blog_article_to_category_art_fk', 'blog_article_to_category');
        $this->dropIndex('blog_article_to_category_uk', 'blog_article_to_category');
        $this->dropTable('blog_article_to_category');
    }
}
