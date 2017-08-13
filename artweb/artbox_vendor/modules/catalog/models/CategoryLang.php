<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\modules\language\models\Language;
    use Yii;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "category_lang".
     * @property integer  $category_id
     * @property integer  $language_id
     * @property string   $title
     * @property string   $meta_title
     * @property string   $meta_robots
     * @property string   $meta_description
     * @property string   $seo_text
     * @property string   $h1
     * @property Category $category
     * @property Language $language
     */
    class CategoryLang extends ActiveRecord
    {
        
        public static function primaryKey()
        {
            return [
                'category_id',
                'language_id',
            ];
        }
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'category_lang';
        }
        
        public function behaviors()
        {
            return [
                'slug' => [
                    'class'         => 'artweb\artbox\behaviors\Slug',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'title' ],
                    'required',
                ],
                [
                    [
                        'seo_text',
                        'alias',
                    ],
                    'string',
                ],
                [
                    [
                        'title',
                        'meta_title',
                        'meta_robots',
                        'meta_description',
                        'h1',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'category_id',
                        'language_id',
                    ],
                    'unique',
                    'targetAttribute' => [
                        'category_id',
                        'language_id',
                    ],
                    'message'         => 'The combination of Category ID and Language ID has already been taken.',
                ],
                [
                    [ 'category_id' ],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Category::className(),
                    'targetAttribute' => [ 'category_id' => 'id' ],
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
                'category_id' => Yii::t('app', 'Category ID'),
                'language_id' => Yii::t('app', 'Language ID'),
                'title'        => Yii::t('app', 'Name'),
                'meta_title'  => Yii::t('app', 'Meta Title'),
                'meta_robots' => Yii::t('app', 'Meta Robots'),
                'meta_description'   => Yii::t('app', 'Meta Desc'),
                'seo_text'    => Yii::t('app', 'Seo Text'),
                'h1'          => Yii::t('app', 'H1'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCategory()
        {
            return $this->hasOne(Category::className(), [ 'id' => 'category_id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getLanguage()
        {
            return $this->hasOne(Language::className(), [ 'id' => 'language_id' ]);
        }
    }
