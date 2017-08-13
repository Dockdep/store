<?php
    
    namespace artweb\artbox\models;
    
    use Yii;
    use yii\behaviors\AttributeBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "feedback".
     *
     * @property integer $id
     * @property string  $name
     * @property string  $phone
     * @property integer $created_at
     * @property string  $ip
     */
    class Feedback extends ActiveRecord
    {
        
        const SCENARIO_FEEDBACK = 'feedback';
        const SCENARIO_CALLBACK = 'callback';
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'feedback';
        }
        
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            $scenarios = parent::scenarios();
            $scenarios = array_merge(
                $scenarios,
                [
                    self::SCENARIO_FEEDBACK => [
                        'name',
                        'phone',
                    ],
                    self::SCENARIO_CALLBACK => [ 'phone' ],
                ]
            );
            return $scenarios;
        }
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                [
                    'class'              => TimestampBehavior::className(),
                    'updatedAtAttribute' => false,
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
                        'phone',
                        'name',
                    ],
                    'required',
                ],
                [
                    [ 'phone' ],
                    'match',
                    'pattern' => '/^\+38\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
                ],
                [
                    [
                        'name',
                        'phone',
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
                'id'         => Yii::t('app', 'id'),
                'name'       => Yii::t('app', 'name'),
                'phone'      => Yii::t('app', 'phone'),
                'created_at' => Yii::t('app', 'created_at'),
                'ip'         => Yii::t('app', 'ip'),
            ];
        }
    }
