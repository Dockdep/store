<?php

use yii\db\Migration;

class m161101_144434_blog_article_to_product extends Migration
{
    public function up()
    {
        /**
         * Creates junction table and all stuff for adding related products to articles
         */
        $this->createTable(
            'blog_article_to_product',
            [
                'id'              => $this->primaryKey(),
                'blog_article_id' => $this->integer()
                                          ->notNull(),
                'product_id'      => $this->integer()
                                          ->notNull(),
            ]
        );
    
        $this->createIndex(
            'blog_article_to_product_uk',
            'blog_article_to_product',
            [
                'blog_article_id',
                'product_id',
            ],
            true
        );
    
        $this->addForeignKey(
            'blog_article_to_product_art_fk',
            'blog_article_to_product',
            'blog_article_id',
            'blog_article',
            'id',
            'CASCADE',
            'CASCADE'
        );
    
        $this->addForeignKey(
            'blog_article_to_product_prod_fk',
            'blog_article_to_product',
            'product_id',
            'product',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('blog_article_to_product_prod_fk', 'blog_article_to_product');
        $this->dropForeignKey('blog_article_to_product_art_fk', 'blog_article_to_product');
        $this->dropIndex('blog_article_to_product_uk', 'blog_article_to_product');
        $this->dropTable('blog_article_to_product');
    }
}
