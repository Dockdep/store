<?php
    
    namespace artweb\artbox\models;
    
    use Yii;
    use yii\web\IdentityInterface;
    
    /**
     * This is the model class for table "customer".
     *
     * @property integer $id
     * @property string  $username
     * @property string  $password_hash
     * @property string  $name
     * @property string  $surname
     * @property string  $phone
     * @property string  $gender
     * @property integer $birth_day
     * @property integer $birth_month
     * @property integer $birth_year
     * @property string  $body
     * @property integer $group_id
     * @property string  $email
     * @property string  $auth_key
     * @property string  $password_reset_token
     * @property integer $status
     * @property integer $created_at
     * @property integer $updated_at
     */
    class Customer extends User implements IdentityInterface
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'customer';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'username',
                        'password_hash',
                    ],
                    'required',
                ],
                [
                    [ 'password' ],
                    'safe',
                ],
                [
                    [
                        'birth_day',
                        'birth_month',
                        'birth_year',
                        'group_id',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                    'integer',
                ],
                [
                    [ 'body' ],
                    'string',
                ],
                [
                    [ 'status' ],
                    'default',
                    'value' => '10',
                ],
                [
                    [
                        'username',
                        'name',
                        'surname',
                        'phone',
                        'email',
                        'password_reset_token',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'gender',
                        'auth_key',
                    ],
                    'string',
                    'max' => 32,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'                   => Yii::t('app', 'id'),
                'username'             => Yii::t('app', 'username'),
                'name'                 => Yii::t('app', 'cname'),
                'surname'              => Yii::t('app', 'surname'),
                'phone'                => Yii::t('app', 'phone'),
                'gender'               => Yii::t('app', 'gender'),
                'birth_day'            => Yii::t('app', 'birth_day'),
                'birth_month'          => Yii::t('app', 'birth_month'),
                'birth_year'           => Yii::t('app', 'birth_year'),
                'body'                 => Yii::t('app', 'body'),
                'group_id'             => Yii::t('app', 'group_id'),
                'email'                => Yii::t('app', 'email'),
                'auth_key'             => Yii::t('app', 'auth_key'),
                'password_reset_token' => Yii::t('app', 'password_reset_token'),
                'status'               => Yii::t('app', 'status'),
                'created_at'           => Yii::t('app', 'created_at'),
                'updated_at'           => Yii::t('app', 'updated_at'),
            ];
        }
        
        /**
         * Finds user by email
         *
         * @param string $email
         *
         * @return static|null
         */
        public static function findByEmail($email)
        {
            return static::findOne(
                [
                    'email'  => $email,
                    'status' => self::STATUS_ACTIVE,
                ]
            );
        }
    
        /**
         * Get full name
         *
         * @return string
         */
        public function getName()
        {
            return $this->username . ' ' . $this->surname;
        }
        
        public function getPassword()
        {
            return false;
        }
        
    }
