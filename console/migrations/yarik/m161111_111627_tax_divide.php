<?php
    
    use yii\db\Migration;
    
    class m161111_111627_tax_divide extends Migration
    {
        public function safeUp()
        {
            $this->dropColumn('tax_group', 'level');
            $this->createTable(
                'tax_variant_group',
                [
                    'id'        => $this->primaryKey(),
                    'is_filter' => $this->boolean(),
                    'sort'      => $this->integer(),
                    'display'   => $this->boolean(),
                    'is_menu'   => $this->boolean(),
                    'remote_id' => $this->string(),
                    'position'  => $this->integer()
                                        ->defaultValue(0),
                ]
            );
            $this->createTable(
                'tax_variant_group_lang',
                [
                    'id'                   => $this->primaryKey(),
                    'tax_variant_group_id' => $this->integer()
                                                   ->notNull(),
                    'language_id'          => $this->integer()
                                                   ->notNull(),
                    'title'                => $this->string()
                                                   ->notNull(),
                    'description'          => $this->text(),
                    'alias'                => $this->string()
                                                   ->unique(),
                ]
            );
            $this->addForeignKey(
                'tax_variant_group_lang_tax_variant_group_fkey',
                'tax_variant_group_lang',
                'tax_variant_group_id',
                'tax_variant_group',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey(
                'tax_variant_group_lang_language_fkey',
                'tax_variant_group_lang',
                'language_id',
                'language',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->createIndex(
                'tax_variant_group_id_language_id_ukey',
                'tax_variant_group_lang',
                [
                    'tax_variant_group_id',
                    'language_id',
                ],
                true
            );
            $this->createTable(
                'tax_variant_option',
                [
                    'id'                   => $this->primaryKey(),
                    'tax_variant_group_id' => $this->integer()
                                                   ->notNull(),
                    'sort'                 => $this->integer(),
                    'image'                => $this->string(),
                    'remote_id'            => $this->string(),
                ]
            );
            $this->createTable(
                'tax_variant_option_lang',
                [
                    'id'                    => $this->primaryKey(),
                    'tax_variant_option_id' => $this->integer()
                                                    ->notNull(),
                    'language_id'           => $this->integer()
                                                    ->notNull(),
                    'value'                 => $this->string()
                                                    ->notNull(),
                    'alias'                 => $this->string()
                                                    ->unique(),
                ]
            );
            $this->addForeignKey(
                'tax_variant_option_lang_tax_variant_option_fkey',
                'tax_variant_option_lang',
                'tax_variant_option_id',
                'tax_variant_option',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey(
                'tax_variant_option_lang_language_fkey',
                'tax_variant_option_lang',
                'language_id',
                'language',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->createIndex(
                'tax_variant_option_id_language_id_ukey',
                'tax_variant_option_lang',
                [
                    'tax_variant_option_id',
                    'language_id',
                ],
                true
            );
            $this->createTable(
                'tax_variant_group_to_category',
                [
                    'id' => $this->primaryKey(),
                    'tax_variant_group_id'             => $this->integer()
                                                               ->notNull(),
                    'category_id'                      => $this->integer()
                                                               ->notNull(),
                ]
            );
            $this->addForeignKey(
                'tax_variant_group_to_category_tax_variant_group_fkey',
                'tax_variant_group_to_category',
                'tax_variant_group_id',
                'tax_variant_group',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addForeignKey(
                'tax_variant_group_to_category_category_fkey',
                'tax_variant_group_to_category',
                'category_id',
                'category',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->createIndex(
                'tax_variant_group_id_category_id_ukey',
                'tax_variant_group_to_category',
                [
                    'tax_variant_group_id',
                    'category_id',
                ],
                true
            );
            $this->dropPrimaryKey('product_variant_option_pkey', 'product_variant_option');
            $this->dropForeignKey('product_variant_option_tax_option_tax_option_id_fk', 'product_variant_option');
            $this->truncateTable('product_variant_option');
            $this->addForeignKey(
                'product_variant_option_tax_variant_option_tax_variant_option_id_fk',
                'product_variant_option',
                'option_id',
                'tax_variant_option',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addPrimaryKey(
                'product_variant_option_pkey',
                'product_variant_option',
                [
                    'product_variant_id',
                    'option_id',
                ]
            );
        }
        
        public function safeDown()
        {
            $this->dropPrimaryKey('product_variant_option_pkey', 'product_variant_option');
            $this->dropForeignKey(
                'product_variant_option_tax_variant_option_tax_variant_option_id_fk',
                'product_variant_option'
            );
            $this->addForeignKey(
                'product_variant_option_tax_option_tax_option_id_fk',
                'product_variant_option',
                'option_id',
                'tax_option',
                'id',
                'CASCADE',
                'CASCADE'
            );
            $this->addPrimaryKey(
                'product_variant_option_pkey',
                'product_variant_option',
                [
                    'product_variant_id',
                    'option_id',
                ]
            );
            $this->dropIndex(
                'tax_variant_group_id_category_id_ukey',
                'tax_variant_group_to_category'
            );
            $this->dropForeignKey(
                'tax_variant_group_to_category_tax_variant_group_fkey',
                'tax_variant_group_to_category'
            );
            $this->dropForeignKey(
                'tax_variant_group_to_category_category_fkey',
                'tax_variant_group_to_category'
            );
            $this->dropTable(
                'tax_variant_group_to_category'
            );
            $this->dropIndex(
                'tax_variant_option_id_language_id_ukey',
                'tax_variant_option_lang'
            );
            $this->dropForeignKey(
                'tax_variant_option_lang_tax_variant_option_fkey',
                'tax_variant_option_lang'
            );
            $this->dropForeignKey(
                'tax_variant_option_lang_language_fkey',
                'tax_variant_option_lang'
            );
            $this->dropTable(
                'tax_variant_option_lang'
            );
            $this->dropTable(
                'tax_variant_option'
            );
            $this->dropIndex(
                'tax_variant_group_id_language_id_ukey',
                'tax_variant_group_lang'
            );
            $this->dropForeignKey(
                'tax_variant_group_lang_tax_variant_group_fkey',
                'tax_variant_group_lang'
            );
            $this->dropForeignKey(
                'tax_variant_group_lang_language_fkey',
                'tax_variant_group_lang'
            );
            $this->dropTable(
                'tax_variant_group_lang'
            );
            $this->dropTable(
                'tax_variant_group'
            );
            $this->addColumn('tax_group', 'level', $this->integer());
        }
    }
