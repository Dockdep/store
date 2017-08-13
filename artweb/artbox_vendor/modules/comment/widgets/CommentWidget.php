<?php
    
    namespace artweb\artbox\modules\comment\widgets;
    
    use artweb\artbox\modules\comment\assets\CommentAsset;
    use artweb\artbox\modules\comment\models\interfaces\CommentInterface;
    use artweb\artbox\modules\comment\models\RatingModel;
    use artweb\artbox\modules\comment\Module;
    use Yii;
    use yii\base\InvalidConfigException;
    use yii\base\Model;
    use yii\base\Widget;
    use yii\data\ActiveDataProvider;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Json;
    
    /**
     * Class CommentWidget
     * @property Model $model Model, to which comment attached
     * @package artweb\artbox\modules\comment\widgets
     */
    class CommentWidget extends Widget
    {
        
        /**
         * Model, to which comment attached
         * @var Model Model
         */
        //public $model;
        
        /**
         * Options
         * @var array
         */
        public $options = [
            'class' => 'artbox_comment_container comments-start',
            'id'    => 'artbox-comment',
        ];
        
        /**
         * @var string the view file that will render comment form.
         */
        public $formView = '@artbox-comment/views/artbox_comment_form';
        
        /**
         * Form options
         * @var array
         */
        public $formOptions = [
            'class' => 'artbox_form_container',
        ];
        
        /**
         * Params to be passed to form
         * @var array
         */
        public $formParams = [];
        
        /**
         * @var string the view file that will render comments list.
         */
        public $listView = '@artbox-comment/views/artbox_comment_list';
        
        /**
         * List options
         * @var array
         */
        public $listOptions = [
            'class' => 'artbox_list_container',
        ];
        
        /**
         * List params
         * @var array
         */
        public $listParams = [];
        
        /**
         * Reply options
         * @var array
         */
        public $replyOptions = [
            'style' => 'display: none;',
            'class' => 'artbox_comment_reply_container',
        ];
        
        /**
         * Reply view
         * @var string
         */
        public $replyView = '@artbox-comment/views/artbox_comment_reply';
        
        /**
         * Comment form ID. If you have multiple forms on the same page, please use unique IDs.
         * @var string Form ID
         */
        public $formId = 'artbox-comment-form';
        
        /**
         * Comment list ID. If you have multiple forms on the same page, please use unique IDs.
         * @var string List ID
         */
        public $listId = 'artbox-comment-list';
        
        /**
         * Item view
         * @var string
         */
        public $itemView = '@artbox-comment/views/artbox_comment_item';
        
        /**
         * Item options
         * @var array
         */
        public $itemOptions = [
            'class'     => 'artbox_item_container',
            'itemprop'  => 'review',
            'itemscope' => 'itemscope',
            'itemtype'  => 'http://schema.org/Review',
        ];
        
        /**
         * Entity ID attribute, default to primaryKey() if ActiveRecord and throws exception if not
         * set
         * @var string entity id attribute
         */
        public $entityIdAttribute;
        
        /**
         * Info to be passed to Comment Model
         * @var string $info Additional info
         */
        public $info = NULL;
        
        /**
         * Client options to be passed to JS
         * @var array comment widget client options
         */
        public $clientOptions = [];
        
        /**
         * @todo Check if needed
         * @var string pjax container id
         */
        public $pjaxContainerId;
        
        public $layout = "<div class='comments-border'></div>{form} {reply_form} {list}";
        
        /**
         * Model fully namespaced classname
         * @var string Model namespace
         */
        protected $entity;
        
        /**
         * Entity ID for attached model
         * @var integer Entity ID
         */
        protected $entityId;
        
        /**
         * Encrypted data to be passed to Controller. Consist of:
         * * Model::className()
         * * entityId
         * * info (optional)
         * @var string encrypted entity key
         */
        protected $encryptedEntityKey;
        
        /**
         * Parts for widget
         * @var array $parts
         */
        protected $parts;
        
        /**
         * Initializes the widget params.
         */
        public function init()
        {
            // Module init
            Yii::$app->getModule(Module::$name);
            // Model init
            $model = $this->getModel();
            
            /**
             * @todo Check if needed
             */
            if(empty( $this->pjaxContainerId )) {
                $this->pjaxContainerId = 'comment-pjax-container-' . $this->getId();
            }
            
            $this->entity = $model::className();
            // Entity ID init
            if(!empty( $this->entityIdAttribute ) && $this->model->hasProperty($this->entityIdAttribute)) {
                $this->entityId = $this->model->{$this->entityIdAttribute};
            } else {
                if($this->model instanceof ActiveRecord && !empty( $this->model->getPrimaryKey() )) {
                    $this->entityId = (int) $this->model->getPrimaryKey();
                } else {
                    throw new InvalidConfigException(/*Yii::t('artbox-comment', 'The "entityIdAttribute" value for widget model cannot be empty.')*/);
                }
            }
            
            // Generate encryptedEntityKey
            $this->encryptedEntityKey = $this->generateEntityKey();
            
            $this->registerAssets();
        }
        
        /**
         * Executes the widget.
         * @return string the result of widget execution to be outputted.
         */
        public function run()
        {
            /* @var Module $module */
            $module = Yii::$app->getModule(Module::$name);
            $commentModelClass = $module->commentModelClass;
            $commentModel = $this->createModel($commentModelClass, [
                'entity'          => $this->entity,
                'entityId'        => $this->entityId,
                'encryptedEntity' => $this->encryptedEntityKey,
                'scenario'        => \Yii::$app->user->getIsGuest() ? $commentModelClass::SCENARIO_GUEST : $commentModelClass::SCENARIO_USER,
            ]);
            if($module::$enableRating) {
                $ratingModelClass = $module->ratingModelClass;
                $ratingModel = $this->createRating($ratingModelClass);
            } else {
                $ratingModel = NULL;
            }
            
            $comments = $commentModelClass::getTree($this->entity, $this->entityId);
            
            $this->buildParts($commentModel, $comments, $ratingModel);
            
            return $this->renderWidget();
        }
        
        /**
         * Register assets.
         */
        protected function registerAssets()
        {
            $this->clientOptions[ 'formSelector' ] = '#' . $this->formId;
            $this->clientOptions[ 'listSelector' ] = '#' . $this->listId;
            $options = Json::encode($this->clientOptions);
            $view = $this->getView();
            CommentAsset::register($view);
            $view->registerJs("jQuery('#{$this->formId}').artbox_comment({$options});");
        }
        
        /**
         * Get encrypted entity key
         * @return string
         */
        protected function generateEntityKey()
        {
            return Yii::$app->getSecurity()
                            ->encryptByKey(Json::encode([
                                'entity'    => $this->entity,
                                'entity_id' => $this->entityId,
                                'info'      => $this->info,
                            ]), Module::$encryptionKey);
        }
        
        /**
         * Create comment model
         *
         * @param string $className Full namespaced model
         * @param array  $config    Init config
         *
         * @return CommentInterface Comment model
         * @throws InvalidConfigException If object not instance of \yii\base\Model
         */
        protected function createModel(string $className, array $config = []): CommentInterface
        {
            $options = array_merge($config, [ 'class' => $className ]);
            $object = Yii::createObject($options);
            if($object instanceof CommentInterface) {
                return $object;
            }
            throw new InvalidConfigException(/*Yii::t(\'artbox-comment\', \'Comment model must be instance of CommentInterface.\')*/);
        }
        
        /**
         * Create rating model
         *
         * @param string $className Full namespaced model
         * @param array  $config    Init config
         *
         * @return CommentInterface|RatingModel Comment model
         * @throws InvalidConfigException If object not instance of \yii\base\Model
         */
        protected function createRating(string $className, array $config = []): RatingModel
        {
            $options = array_merge($config, [ 'class' => $className ]);
            $object = Yii::createObject($options);
            if($object instanceof RatingModel) {
                return $object;
            }
            throw new InvalidConfigException(Yii::t('artbox-comment', 'Comment model must be instance of RatingModel.'));
        }
        
        /**
         * Build parts for rendering widget
         *
         * @param CommentInterface   $commentModel
         * @param ActiveDataProvider $comments
         * @param null|RatingModel   $ratingModel
         */
        protected function buildParts(CommentInterface $commentModel, ActiveDataProvider $comments, $ratingModel = NULL)
        {
            $form_options = $this->formOptions;
            $this->parts[ 'form' ] = Html::tag(ArrayHelper::remove($form_options, 'tag', 'div'), $this->render($this->formView, [
                'comment_model' => $commentModel,
                'form_params'   => $this->formParams,
                'model'         => $this->getModel(),
                'formId'        => $this->formId,
                'rating_model'  => $ratingModel,
            ]), $form_options);
            
            if(!\Yii::$app->user->isGuest) {
                $reply_options = $this->replyOptions;
                $this->parts[ 'reply_form' ] = Html::tag(ArrayHelper::remove($reply_options, 'tag', 'div'), $this->render($this->replyView, [
                    'comment_model' => $commentModel,
                    'form_params'   => $this->formParams,
                    'model'         => $this->getModel(),
                    'formId'        => $this->formId,
                ]), $reply_options);
            }
            
            $list_options = array_merge($this->listOptions, [ 'id' => $this->listId ]);
            $this->parts[ 'list' ] = Html::tag(ArrayHelper::remove($list_options, 'tag', 'div'), $this->render($this->listView, [
                'comment_model' => $commentModel,
                'list_params'   => $this->listParams,
                'model'         => $this->getModel(),
                'comments'      => $comments,
                'item_options'  => $this->itemOptions,
                'item_view'     => $this->itemView,
            ]), $list_options);
        }
        
        /**
         * @return string
         */
        protected function renderWidget(): string
        {
            $layout = $this->layout;
            $parts = $this->parts;
            $options = $this->options;
            $layout = preg_replace('/{list}/', ArrayHelper::getValue($parts, 'list', ''), $layout);
            $layout = preg_replace('/{form}/', ArrayHelper::getValue($parts, 'form', ''), $layout);
            $layout = preg_replace('/{reply_form}/', ArrayHelper::getValue($parts, 'reply_form', ''), $layout);
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            return Html::tag($tag, $layout, $options);
        }
        
        public function setModel(Model $model)
        {
            $this->model = $model;
        }
        
        public function getModel(): Model
        {
            if(!empty( $this->model )) {
                return $this->model;
            }
            throw new InvalidConfigException(/*Yii::t(\'artbox-comment\', \'The "model" property must be set.\')*/);
        }
    }