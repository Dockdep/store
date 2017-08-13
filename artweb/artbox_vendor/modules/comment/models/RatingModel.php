<?php
    
    namespace artweb\artbox\modules\comment\models;
    
    use artweb\artbox\models\User;
    use Yii;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "artbox_comment_rating".
     *
     * @property integer $artbox_comment_rating_id
     * @property string  $created_at
     * @property string  $updated_at
     * @property integer $user_id
     * @property integer $value
     * @property string  $model
     * @property integer $model_id
     * @property User    $user
     */
    class RatingModel extends ActiveRecord
    {
        
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'artbox_comment_rating';
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'value' ],
                    'required',
                ],
                [
                    [ 'value' ],
                    'number',
                    'min' => 0.5,
                    'max' => 5,
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
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'rating_id'  => Yii::t('app', 'Rating ID'),
                'date_add'   => Yii::t('app', 'Date Add'),
                'updated_at' => Yii::t('app', 'Date Update'),
                'user_id'    => Yii::t('app', 'User ID'),
                'entity'     => Yii::t('app', 'Entity'),
                'value'      => Yii::t('app', 'Value'),
            ];
        }
        
        /**
         * @return \yii\db\ActiveQuery
         */
        public function getUser()
        {
            return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
        }
        
        public function getModel()
        {
            $model = $this->model;
            return $this->hasOne($model, [ $model::primaryKey() => 'model_id' ]);
        }
    }
