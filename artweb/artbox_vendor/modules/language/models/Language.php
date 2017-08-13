<?php
    
    namespace artweb\artbox\modules\language\models;
    
    use Yii;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "language".
     *
     * @property integer $id
     * @property string  $url
     * @property string  $local
     * @property string  $name
     * @property boolean $default
     * @property integer $created_at
     * @property integer $updated_at
     */
    class Language extends ActiveRecord
    {
        
        /**
         * @var null|self
         */
        public static $current = null;
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'language';
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'timestamp' => [
                    'class'      => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => [
                            'created_at',
                            'updated_at',
                        ],
                        ActiveRecord::EVENT_BEFORE_UPDATE => [
                            'updated_at',
                        ],
                    ],
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
                    [
                        'url',
                        'local',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'required',
                ],
                [
                    [ 'default' ],
                    'boolean',
                ],
                [
                    [
                        'created_at',
                        'updated_at',
                    ],
                    'integer',
                ],
                [
                    [
                        'url',
                        'local',
                        'name',
                    ],
                    'string',
                    'max' => 255,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id' => Yii::t('app', 'Language ID'),
                'url'         => Yii::t('app', 'Url'),
                'local'       => Yii::t('app', 'Local'),
                'name'        => Yii::t('app', 'Name'),
                'default'     => Yii::t('app', 'Default'),
                'created_at'  => Yii::t('app', 'Date Create'),
                'updated_at'  => Yii::t('app', 'Date Update'),
            ];
        }
        
        /**
         * Get current language
         *
         * @return null|Language
         */
        public static function getCurrent()
        {
            if (self::$current === null) {
                self::$current = self::getDefaultLanguage();
            }
            return self::$current;
        }
        
        /**
         * Set current language by Url param
         *
         * @param null|string $url Language url param
         */
        public static function setCurrent($url = null)
        {
            $language = self::getLanguageByUrl($url);
            self::$current = ( $language === null ) ? self::getDefaultLanguage() : $language;
            Yii::$app->language = self::$current->local;
        }
        
        /**
         * Get default language
         *
         * @return null|Language
         */
        public static function getDefaultLanguage()
        {
            /**
             * @var null|Language $language
             */
            $language = self::find()
                            ->where([ 'default' => true ])
                            ->one();
            return $language;
        }
        
        /**
         * Get language by Url param
         *
         * @param null|string $url Language url param
         *
         * @return null|Language
         */
        public static function getLanguageByUrl($url = null)
        {
            if ($url === null) {
                return null;
            } else {
                /**
                 * @var null|Language $language
                 */
                $language = self::find()
                                ->where([ 'url' => $url ])
                                ->one();
                if ($language === null) {
                    return null;
                } else {
                    return $language;
                }
            }
        }
    }
