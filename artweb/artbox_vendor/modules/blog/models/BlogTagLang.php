<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "blog_tag_lang".
     *
     * @property integer  $id
     * @property integer  $blog_tag_id
     * @property integer  $language_id
     * @property string   $label
     * @property BlogTag  $blogTag
     * @property Language $language
     */
    class BlogTagLang extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'blog_tag_lang';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'blog_tag_id',
                        'language_id',
                    ],
                    'required',
                ],
                [
                    [
                        'blog_tag_id',
                        'language_id',
                    ],
                    'integer',
                ],
                [
                    [ 'label' ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'blog_tag_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'blog_tag_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Blog Tag ID and Language ID has already been taken.',
                ],
                [
                    [ 'blog_tag_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => BlogTag::className(),
                    'targetAttribute' => [ 'blog_tag_id' => 'id' ],
                ],
                [
                    [ 'language_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Language::className(),
                    'targetAttribute' => [ 'language_id' => 'id' ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'          => 'ID',
                'blog_tag_id' => 'Blog Tag ID',
                'language_id' => 'Language ID',
                'label'       => 'Label',
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getBlogTag()
        {
            return $this->hasOne(BlogTag::className(), [ 'id' => 'blog_tag_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
