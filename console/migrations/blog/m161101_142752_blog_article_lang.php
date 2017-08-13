<?php

use yii\db\Migration;

class m161101_142752_blog_article_lang extends Migration
{
    public function up()
    {
        /**
         * Create table with language fields of blog articles
         */
        $this->createTable(
            'blog_article_lang',
            [
                'id'               => $this->primaryKey(),
                'blog_article_id'  => $this->integer()
                                           ->notNull(),
                'language_id'      => $this->integer()
                                           ->notNull(),
                'title'            => $this->string(255),
                'body'             => $this->text(),
                'body_preview'     => $this->text(),
                'alias'            => $this->string(255),
                'meta_title'       => $this->string(255),
                'meta_description' => $this->string(255),
                'h1'               => $this->string(255),
                'seo_text'         => $this->string(255),
            ]
        );
    
        /**
         * Creating indexes for unique fields (field pairs)
         */
        $this->createIndex(
            'blog_article_lang_uk',
            'blog_article_lang',
            [
                'blog_article_id',
                'language_id',
            ],
            true
        );
    
        $this->createIndex(
            'blog_article_alias_uk',
            'blog_article_lang',
            'alias',
            true
        );
    
        /**
         * Add foreign keys in blog_articles and language tables
         */
        $this->addForeignKey(
            'blog_article_fk',
            'blog_article_lang',
            'blog_article_id',
            'blog_article',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'blog_article_lang_fk',
            'blog_article_lang',
            'language_id',
            'language',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('blog_article_lang_fk', 'blog_article_lang');
        $this->dropForeignKey('blog_article_fk', 'blog_article_lang');
        $this->dropIndex('blog_article_alias_uk', 'blog_article_lang');
        $this->dropIndex('blog_article_lang_uk', 'blog_article_lang');
        $this->dropTable('blog_article_lang');
    }
}
