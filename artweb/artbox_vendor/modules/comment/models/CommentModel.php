<?php
    namespace artweb\artbox\modules\comment\models;
    
    use artweb\artbox\behaviors\RatingBehavior;
    use artweb\artbox\modules\comment\behaviors\ParentBehavior;
    use artweb\artbox\modules\comment\models\interfaces\CommentInterface;
    use yii\behaviors\AttributeBehavior;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\data\ActiveDataProvider;
    use yii\db\ActiveRecord;
    
    /**
     * Class CommentModel
     *
     * @property int    $artbox_comment_id
     * @property string $text
     * @property int    $user_id
     * @property string $username
     * @property string $email
     * @property int    $created_at
     * @property int    $updated_at
     * @property int    $deleted_at
     * @property int    $status
     * @property int    $artbox_comment_pid
     * @property int    $related_id
     * @property string $ip
     * @property string $info
     * @property string $entity
     * @property int    $entity_id
     * @package artweb\artbox\modules\comment\models
     */
    class CommentModel extends ActiveRecord implements CommentInterface
    {
        
        const STATUS_ACTIVE = 1;
        const STATUS_HIDDEN = 0;
        const STATUS_DELETED = 2;
        
        const SCENARIO_USER = 'user';
        const SCENARIO_GUEST = 'guest';
        
        public $encryptedEntity;
        
        public $entityId;
        
        public function scenarios()
        {
            $scenarios = parent::scenarios();
            $scenarios[ self::SCENARIO_USER ] = [
                'text',
                'entity',
                'entity_id',
                'artbox_comment_pid',
                'status',
            ];
            $scenarios[ self::SCENARIO_GUEST ] = [
                'text',
                'entity',
                'entity_id',
                'username',
                'email',
                'status',
            ];
            return $scenarios;
        }
        
        public static function tableName()
        {
            return '{{%artbox_comment}}';
        }
        
        public function rules()
        {
            return [
                [
                    [
                        'text',
                        'entity',
                        'entity_id',
                    ],
                    'required',
                ],
                [
                    [
                        'username',
                        'email',
                    ],
                    'required',
                    'on' => self::SCENARIO_GUEST,
                ],
                [
                    [
                        'text',
                        'entity',
                        'username',
                    ],
                    'string',
                ],
                [
                    [
                        'email',
                    ],
                    'email',
                ],
                [
                    [
                        'entity_id',
                        'artbox_comment_pid',
                    ],
                    'integer',
                ],
                [
                    [ 'status' ],
                    'default',
                    'value' => 0,
                ],
                [
                    [ 'artbox_comment_pid' ],
                    'exist',
                    'targetAttribute' => 'artbox_comment_id',
                    'skipOnError'     => true,
                ],
            ];
        }
        
        public function behaviors()
        {
            return [
                [
                    'class' => TimestampBehavior::className(),
                ],
                [
                    'class'              => BlameableBehavior::className(),
                    'createdByAttribute' => 'user_id',
                    'updatedByAttribute' => false,
                ],
                [
                    'class'      => AttributeBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => 'ip',
                    ],
                    'value'      => function ($event) {
                        return \Yii::$app->request->userIP;
                    },
                ],
                [
                    'class' => ParentBehavior::className(),
                ],
                [
                    'class' => RatingBehavior::className(),
                ],
                /* Notification: uncomment to enable notifications.
                [
                    'class' => NotifyBehavior::className(),
                ],
                */
            ];
        }
        
        public function attributeLabels()
        {
            return [
                'artbox_comment_id'  => \Yii::t('artbox-comment', 'ID'),
                'text'               => \Yii::t('artbox-comment', 'Text'),
                'user_id'            => \Yii::t('artbox-comment', 'User'),
                'username'           => \Yii::t('artbox-comment', 'Username'),
                'email'              => 'Email',
                'date_add'           => \Yii::t('artbox-comment', 'Date add'),
                'updated_at'         => \Yii::t('artbox-comment', 'Date update'),
                'deleted_at'         => \Yii::t('artbox-comment', 'Date delete'),
                'status'             => \Yii::t('artbox-comment', 'Status'),
                'artbox_comment_pid' => \Yii::t('artbox-comment', 'Comment parent'),
                'related_id'         => \Yii::t('artbox-comment', 'Comment related'),
                'ip'                 => 'IP',
                'entity'             => \Yii::t('artbox-comment', 'Entity'),
                'info'               => \Yii::t('artbox-comment', 'Info'),
                'entity_id'          => \Yii::t('artbox-comment', 'Entity ID'),
            ];
        }
        
        public function setEntity(string $entity)
        {
            $this->entity = $entity;
        }
        
        public function getEntity(): string
        {
            return $this->entity;
        }
        
        public static function getTree(string $entity, int $entityId): ActiveDataProvider
        {
            return new ActiveDataProvider(
                [
                    'query'      => self::find()
                                        ->with(
                                            [
                                                'children',
                                                'user',
                                                'children.user',
                                            ]
                                        )
                                        ->where(
                                            [
                                                'entity'             => $entity,
                                                'entity_id'          => $entityId,
                                                'status'             => 1,
                                                'artbox_comment_pid' => null,
                                            ]
                                        ),
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                    'sort'       => [
                        'defaultOrder' => [
                            'created_at' => SORT_DESC,
                        ],
                    ],
                ]
            );
        }
        
        public function deleteComment(): bool
        {
            if (\Yii::$app->user->id != null && \Yii::$app->user->id == $this->user_id) {
                if ($this->delete()) {
                    return true;
                }
            }
            return false;
        }
        
        public function setEntityId(int $entityId)
        {
            $this->entityId = $entityId;
        }
        
        public function getEntityId(): int
        {
            return $this->entityId;
        }
        
        public function getChildren()
        {
            return $this->hasMany(self::className(), [ 'artbox_comment_pid' => 'artbox_comment_id' ])
                        ->andFilterWhere([ 'status' => self::STATUS_ACTIVE ])
                        ->inverseOf('parent');
        }
        
        public function getParent()
        {
            return $this->hasOne(self::className(), [ 'artbox_comment_id' => 'artbox_comment_pid' ])
                        ->inverseOf('children');
        }
        
        public function getUser()
        {
            $module = \Yii::$app->getModule('artbox-comment');
            return $this->hasOne($module->userIdentityClass, [ 'id' => 'user_id' ]);
        }
        
        public function getRating()
        {
            return $this->hasOne(RatingModel::className(), [ 'model_id' => 'artbox_comment_id' ])
                        ->andWhere(
                            [
                                'or',
                                [ 'artbox_comment_rating.model' => null ],
                                [ 'artbox_comment_rating.model' => self::className() ],
                            ]
                        );
        }
    }