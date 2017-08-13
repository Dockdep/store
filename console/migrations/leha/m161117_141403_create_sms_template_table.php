<?php
    
    use yii\db\Migration;
    
    /**
     * Handles the creation of table `sms_template`.
     */
    class m161117_141403_create_sms_template_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $this->createTable(
                'sms_template',
                [
                    'id'    => $this->primaryKey(),
                    'text'  => $this->text(),
                    'title' => $this->string(255),
                ]
            );
        }
        
        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('sms_template');
        }
    }
