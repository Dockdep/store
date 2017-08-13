<?php
    
    namespace artweb\artbox\modules\comment;
    
    use artweb\artbox\modules\comment\models\CommentModel;
    use artweb\artbox\modules\comment\models\RatingModel;
    use Yii;
    use yii\console\Application;

    /**
     * Class Module
     * @package artweb\artbox\modules\comment
     */
    class Module extends \yii\base\Module
    {
        
        /**
         * @var string module name
         */
        public static $name = 'artbox-comment';
        
        /**
         * User identity class, default to artweb\artbox\models\User
         * @var string|null
         */
        public $userIdentityClass = NULL;
        
        /**
         * Comment model class, default to artweb\artbox\modules\models\CommentModel
         * @var string comment model class
         */
        public $commentModelClass = NULL;
        
        public $ratingModelClass = NULL;
        
        /**
         * This namespace will be used to load controller classes by prepending it to the controller
         * class name.
         * @var string the namespace that controller classes are in.
         */
        public $controllerNamespace = 'artweb\artbox\modules\comment\controllers';
        
        /**
         * @var \yii\db\Connection DB connection, default to \Yii::$app->db
         */
        public $db = NULL;
        
        /**
         * Key, used to encrypt and decrypt comment service data.
         * @var string Encryption key
         */
        public static $encryptionKey = 'artbox-comment';
        
        /**
         * Whether to enable comment rating or not.
         * @var bool
         */
        public static $enableRating = true;
        
        /**
         * Initializes the module.
         * This method is called after the module is created and initialized with property values
         * given in configuration. The default implementation will initialize
         * [[controllerNamespace]] if it is not set. If you override this method, please make sure
         * you call the parent implementation.
         */
        public function init()
        {
            if($this->userIdentityClass === NULL) {
                $this->userIdentityClass = Yii::$app->getUser()->identityClass;
            }
            if($this->commentModelClass === NULL) {
                $this->commentModelClass = CommentModel::className();
            }
            if(self::$enableRating && $this->ratingModelClass === NULL) {
                $this->ratingModelClass = RatingModel::className();
            }
            if(\Yii::$app instanceof Application) {
                $this->controllerNamespace = 'artweb\artbox\modules\comment\commands';
            }
            if($this->db === NULL) {
                $this->db = \Yii::$app->db;
            }
            Yii::setAlias('@artbox-comment', __DIR__);
            parent::init();
        }
        
    }
