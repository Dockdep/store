<?php
    
    namespace backend\models;
    
    use developeruz\db_rbac\interfaces\UserRbacInterface;
    use common\modules\comment\models\CommentModel;
    use common\modules\comment\models\RatingModel;
    use yii\base\NotSupportedException;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\web\IdentityInterface;
    
    /**
     * This is the model class for table "user".
     * @property integer        $id
     * @property string         $username
     * @property string         $auth_key
     * @property string         $password_hash
     * @property string         $password_reset_token
     * @property string         $email
     * @property integer        $status
     * @property integer        $created_at
     * @property integer        $updated_at
     * @property CommentModel[] $comments
     * @property RatingModel[]  $ratings
     */
    class User extends ActiveRecord implements UserRbacInterface, IdentityInterface
    {
        
        const STATUS_DELETED = 0;
        const STATUS_ACTIVE = 10;
        
        public $password;
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'user';
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
                        'password',
                        'email',
                    ],
                    'required',
                ],
                [
                    [
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                    'integer',
                ],
                [
                    [
                        'username',
                        'password_hash',
                        'password_reset_token',
                        'email',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [ 'auth_key' ],
                    'string',
                    'max' => 32,
                ],
                [
                    [ 'password_reset_token' ],
                    'unique',
                ],
                [
                    'email',
                    'unique',
                    'targetClass' => '\backend\models\User',
                    'message'     => Yii::t('app', 'message', [
                        'field' => 'Email',
                    ]),
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                TimestampBehavior::className(),
            ];
        }
        
        public function beforeSave($insert)
        {
            $this->setPassword($this->password);
            $this->generateAuthKey();
            return parent::beforeSave($insert);
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'                   => 'ID',
                'username'             => 'Username',
                'auth_key'             => 'Auth Key',
                'password_hash'        => 'Password Hash',
                'password_reset_token' => 'Password Reset Token',
                'email'                => 'Email',
                'status'               => 'Status',
                'created_at'           => 'Created At',
                'updated_at'           => 'Updated At',
            ];
        }
        
        /**
         * Generates "remember me" authentication key
         */
        public function generateAuthKey()
        {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }
        
        /**
         * Generates password hash from password and sets it to the model
         *
         * @param string $password
         */
        public function setPassword($password)
        {
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
        
        public function getRole()
        {
            return !empty( $this->id ) ? \Yii::$app->authManager->getRolesByUser($this->id) : "";
        }
        
        /**
         * @inheritdoc
         */
        public function getId()
        {
            return $this->getPrimaryKey();
        }
        
        /**
         * @inheritdoc
         */
        public function getAuthKey()
        {
            return $this->auth_key;
        }
        
        /**
         * @inheritdoc
         */
        public function validateAuthKey($authKey)
        {
            return $this->getAuthKey() === $authKey;
        }
        
        /**
         * @inheritdoc
         */
        public static function findIdentityByAccessToken($token, $type = NULL)
        {
            throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        }
        
        /**
         * @inheritdoc
         */
        public static function findIdentity($id)
        {
            return static::findOne([
                'id'     => $id,
                'status' => self::STATUS_ACTIVE,
            ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getComments()
        {
            return $this->hasMany(CommentModel::className(), [ 'user_id' => 'id' ]);
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getRatings()
        {
            return $this->hasMany(RatingModel::className(), [ 'user_id' => 'id' ]);
        }
        
        public function getUserName()
        {
            return $this->username;
        }
        
    }
