<?php

use yii\db\Migration;

class m161101_143259_blog_category_lang extends Migration
{
    public function up()
    {
        /**
         * Table for category languages
         */
        $this->createTable(
            'blog_category_lang',
            [
                'id'               => $this->primaryKey(),
                'blog_category_id' => $this->integer()
                                           ->notNull(),
                'language_id'      => $this->integer()
                                           ->notNull(),
                'title'            => $this->string(255),
                'alias'            => $this->string(255),
                'description'      => $this->text(),
                'meta_title'       => $this->string(255),
                'meta_description' => $this->string(255),
                'h1'               => $this->string(255),
                'seo_text'         => $this->string(255),
            ]
        );
    
        /**
         * Create unique indexes for language and alias
         */
        $this->createIndex(
            'blog_category_lang_uk',
            'blog_category_lang',
            [
                'blog_category_id',
                'language_id',
            ],
            true
        );
    
        $this->createIndex(
            'blog_category_alias_uk',
            'blog_category_lang',
            'alias',
            true
        );
    
        /**
         * Add foreign keys for language tables
         */
        $this->addForeignKey(
            'blog_category_fk',
            'blog_category_lang',
            'blog_category_id',
            'blog_category',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'blog_category_lang_fk',
            'blog_category_lang',
            'language_id',
            'language',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('blog_category_lang_fk', 'blog_category_lang');
        $this->dropForeignKey('blog_category_fk', 'blog_category_lang');
        $this->dropIndex('blog_category_alias_uk', 'blog_category_lang');
        $this->dropIndex('blog_category_lang_uk', 'blog_category_lang');
        $this->dropTable('blog_category_lang');
    }
}
