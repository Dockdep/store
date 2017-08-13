<?php
    namespace artweb\artbox\controllers;
    
    use Yii;
    use yii\helpers\Url;
    use yii\web\Controller;
    use artweb\artbox\models\LoginForm;
    use yii\filters\VerbFilter;
    
    /**
     * Site controller
     */
    class SiteController extends Controller
    {
        
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
            ];
        }
        
        public function actionIndex()
        {
            return $this->render('index');
        }
        
        public function actionLogin()
        {
            $this->layout = '/none';
            
            if (!\Yii::$app->user->isGuest) {
                return $this->goHome();
            }
            
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect(Url::to('/admin/site/index'));
            } else {
                return $this->render(
                    'login',
                    [
                        'model' => $model,
                    ]
                );
            }
        }
        
        public function actionLogout()
        {
            Yii::$app->user->logout();
            
            return $this->goHome();
        }
        
    }
