<?php
    
    namespace artweb\artbox\behaviors;
    
    use Yii;
    use yii\base\Action;
    use yii\base\Event;
    use yii\behaviors\AttributeBehavior;
    use yii\di\Instance;
    use yii\base\Module;
    use yii\filters\AccessRule;
    use yii\web\Request;
    use yii\web\User;
    use yii\web\ForbiddenHttpException;
    
    class ArtBoxAccessBehavior extends AttributeBehavior
    {
        
        public $rules = [];
        
        /**
         * @var AccessRule[] $ruleList
         */
        private $ruleList = [];
        
        public function events()
        {
            return [
                Module::EVENT_BEFORE_ACTION => 'interception',
            ];
        }
        
        /**
         * Check whether current user have access to current action.
         *
         * @param Event $event
         *
         * @return void
         * @throws \yii\web\ForbiddenHttpException
         */
        public function interception($event)
        {
            if (!isset( Yii::$app->i18n->translations[ 'db_rbac' ] )) {
                Yii::$app->i18n->translations[ 'db_rbac' ] = [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru-Ru',
                    'basePath'       => '@developeruz/db_rbac/messages',
                ];
            }
            
            $route = Yii::$app->getRequest()
                              ->resolve();
            //Проверяем права по конфигу
            $this->createRule();
            $user = Instance::ensure(Yii::$app->user, User::className());
            $request = Yii::$app->getRequest();
            $action = $event->action;
            
            if (!$this->cheсkByRule($action, $user, $request)) {
                //И по AuthManager
                if (!$this->checkPermission($route)) {
                    if ($user->getIsGuest()) {
                        $user->loginRequired();
                    } else {
                        throw new ForbiddenHttpException(Yii::t('db_rbac', 'Недостаточно прав'));
                    }
                }
                
            }
        }
        
        /**
         * Fill $ruleList with AccessRules
         *
         * @return void
         */
        protected function createRule()
        {
            foreach ($this->rules as $controller => $rule) {
                foreach ($rule as $singleRule) {
                    if (is_array($singleRule)) {
                        $option = [
                            'controllers' => [ $controller ],
                            'class'       => 'yii\filters\AccessRule',
                        ];
                        $this->ruleList[] = Yii::createObject(array_merge($option, $singleRule));
                    }
                }
            }
        }
        
        /**
         * Check whether the User allowed to perform action
         *
         * @param Action  $action
         * @param User    $user
         * @param Request $request
         *
         * @return bool
         */
        protected function cheсkByRule($action, $user, $request)
        {
            
            foreach ($this->ruleList as $rule) {
                
                if ($rule->allows($action, $user, $request)) {
                    return true;
                }
            }
            return false;
        }
    
        /**
         * Check whether the User have permission for current operation
         *
         * @param array $route
         *
         * @return bool
         */
        protected function checkPermission($route)
        {
            //$route[0] - is the route, $route[1] - is the associated parameters
            $routePathTmp = explode('/', $route[ 0 ]);
            $routeVariant = array_shift($routePathTmp);
            if (Yii::$app->user->can($routeVariant, $route[ 1 ])) {
                return true;
            }
            /**
             * @var string $routePart
             */
            foreach ($routePathTmp as $routePart) {
                $routeVariant .= '/' . $routePart;
                if (Yii::$app->user->can($routeVariant, $route[ 1 ])) {
                    return true;
                }
            }
            return false;
        }
        
    }
    