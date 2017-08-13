<?php
    namespace frontend\controllers;
    

    use artweb\artbox\models\Customer;
    use artweb\artbox\models\Feedback;
    use frontend\models\LoginForm;
    use frontend\models\PaymentInform;
    use frontend\models\ResetPasswordForm;
    use frontend\models\SignupForm;
    use Yii;

    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVideo;
    use artweb\artbox\models\Page;
    use yii\base\InvalidParamException;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\helpers\Url;
    use yii\web\BadRequestHttpException;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use yii\filters\AccessControl;
    use yii\web\NotFoundHttpException;
    use frontend\models\PasswordResetRequestForm;
    use yii\web\UploadedFile;
    use yii\widgets\ActiveForm;
    
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
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => [
                        'logout',
                        'signup',
                    ],
                    'rules' => [
                        [
                            'actions' => [ 'signup' ],
                            'allow'   => true,
                            'roles'   => [ '?' ],
                        ],
                        [
                            'actions' => [ 'logout' ],
                            'allow'   => true,
                            'roles'   => [ '@' ],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'payment-inform' => [ 'post' ],
                        'feedback'       => [ 'post' ],
                    ],
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function actions()
        {
            return [
                'error'   => [
                    'class' => 'yii\web\ErrorAction',
                ],
                'captcha' => [
                    'class'           => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ],
            ];
        }
        
        /**
         * Logs in a user.
         *
         * @return mixed
         */
        public function actionLogin()
        {
            if (!\Yii::$app->user->isGuest) {
                return $this->goHome();
            }
            $referer = \Yii::$app->request->get('referer', \Yii::$app->request->referrer);
            if (empty( $referer )) {
                $referer = Url::to([ 'cabinet/main' ], true);
            }
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return Yii::$app->getResponse()
                                ->redirect($referer);
            } else {
                return $this->render(
                    'login',
                    [
                        'model' => $model,
                    ]
                );
            }
        }
        
        /**
         * Displays homepage.
         *
         * @return mixed
         */
        public function actionIndex()
        {
            $this->view->params[ 'isHome' ] = true;
            $cacheKey = [
                'TopBlock',
                'variations' => [ \Yii::$app->language ],
            ];
            if (!$top_items = Yii::$app->cache->get($cacheKey)) {
                $top_items = Product::find()
                                    ->distinct()
                                    ->innerJoinWith(
                                        [
                                            'enabledVariants' => function ($query) {
                                                /**
                                                 * @var ActiveQuery $query
                                                 */
                                                $query->joinWith('lang')
                                                      ->with('images');
                                            },
                                        ]
                                    )
                                    ->with('options', 'category.lang', 'videos', 'lang', 'brand.lang')
                                    ->where([ 'is_top' => true ])
                                    ->limit(12)
                                    ->all();
                
                Yii::$app->cache->set($cacheKey, $top_items, 3600 * 24);
            }
            
            $cacheKey = [
                'NewsBlock',
                'variations' => [ \Yii::$app->language ],
            ];
            if (!$new_items = Yii::$app->cache->get($cacheKey)) {
                $new_items = Product::find()
                                    ->distinct()
                                    ->innerJoinWith(
                                        [
                                            'enabledVariants' => function ($query) {
                                                /**
                                                 * @var ActiveQuery $query
                                                 */
                                                $query->joinWith('lang')
                                                      ->with('images');
                                            },
                                        ]
                                    )
                                    ->with('options', 'category.lang', 'videos', 'lang', 'brand.lang')
                                    ->where([ 'is_new' => true ])
                                    ->limit(12)
                                    ->all();
                Yii::$app->cache->set($cacheKey, $new_items, 3600 * 24);
            }
            
            $cacheKey = [
                'DiscountBlock',
                'variations' => [ \Yii::$app->language ],
            ];
            
            if (!$discount_items = Yii::$app->cache->get($cacheKey)) {
                $discount_items = Product::find()
                                         ->distinct()
                                         ->innerJoinWith(
                                             [
                                                 'enabledVariants' => function ($query) {
                                                     /**
                                                      * @var ActiveQuery $query
                                                      */
                                                     $query->joinWith('lang')
                                                           ->with('images');
                                                 },
                                             ]
                                         )
                                         ->with('options', 'category.lang', 'videos', 'lang', 'brand.lang')
                                         ->where([ 'is_discount' => true ])
                                         ->limit(12)
                                         ->all();
                
                Yii::$app->cache->set($cacheKey, $discount_items, 3600 * 24);
            }
            

            
            $cacheKey = [
                'BrandBlock',
                'variations' => [ \Yii::$app->language ],
            ];
            if (!$brands = Yii::$app->cache->get($cacheKey)) {
                $brands = Brand::find()
                               ->with('lang')
                               ->where([ 'in_menu' => true ])
                               ->andWhere(
                                   [
                                       'not',
                                       [ 'image' => null ],
                                   ]
                               )
                               ->limit(90)
                               ->all();
                Yii::$app->cache->set($cacheKey, $brands, 3600 * 24);
            }
            $icon_categories = Category::find()
                                       ->with('lang')
                                       ->limit(10)
                                       ->all();
            $cacheKey = [
                'VideoBlock',
                'variations' => [ \Yii::$app->language ],
            ];
            if (!$videos = Yii::$app->cache->get($cacheKey)) {
                $videos = ProductVideo::find()
                                      ->limit(3)
                                      ->where(
                                          [
                                              'product_id' => null,
                                              'is_display' => true,
                                          ]
                                      )
                                      ->orderBy(
                                          [
                                              'is_main'    => SORT_DESC,
                                              'is_display' => SORT_DESC,
                                          ]
                                      )
                                      ->all();
                Yii::$app->cache->set($cacheKey, $videos, 3600 * 24);
            }
            
            $pages = Page::find()
                         ->with('lang')
                         ->where([ 'id' => 4 ])
                         ->indexBy('id')
                         ->all();

            return $this->render(
                'index',
                [
                    'top_items'       => $top_items,
                    'new_items'       => $new_items,
                    'discount_items'  => $discount_items,
                    'brands'          => $brands,
                    'icon_categories' => $icon_categories,
                    'videos'          => $videos,
                    'pages'           => $pages

                ]
            );
        }
        
        public function actionPage($slug)
        {
            $model = $this->findPage($slug);
            return $this->render(
                'page',
                [
                    'model' => $model,
                ]
            );
        }
        
        public function actionShops()
        {
            $model = Page::find()
                         ->with('lang')
                         ->where([ 'id' => 3 ])
                         ->one();
            return $this->render(
                'shops',
                [
                    'model' => $model,
                ]
            );
        }
        
        /**
         * Signs user up.
         *
         * @return mixed
         */
        public function actionSignup()
        {
            $model = new SignupForm();
            
            if (Yii::$app->request->post()) {
                
                $model->load(Yii::$app->request->post());
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()
                                 ->login($user)
                    ) {
                        
                        $email = \Yii::$app->mailer->compose(
                            [ 'html' => 'signupConfirmation-html' ],
                            [ 'user' => $user ]
                        )
                                                   ->setTo($user->email)
                                                   ->setFrom(
                                                       [ \Yii::$app->params[ 'supportEmail' ] => \Yii::$app->name . ' robot' ]
                                                   )
                                                   ->setSubject('Signup Confirmation')
                                                   ->send();
                        if ($email) {
                            Yii::$app->getSession()
                                     ->setFlash(
                                         'success',
                                         \Yii::t(
                                             'app',
                                             'Спасибо за регистрацию! Для активации аккаунта, пройдите по ссылке, которая была отправлена Вам на почту.'
                                         )
                                     );
                        } else {
                            Yii::$app->getSession()
                                     ->setFlash(
                                         'warning',
                                         \Yii::t('app', 'Ошибка регистрации, свяжитесь с администрацией сайта!')
                                     );
                        }
                        return $this->goHome();
                    }
                }
            }
            return $this->render(
                'signup',
                [
                    'model' => $model,
                
                ]
            );
        }
        
        public function actionConfirm($id, $key)
        {
            $user = Customer::find()
                            ->where(
                                [
                                    'id'       => $id,
                                    'auth_key' => $key,
                                    'status'   => 0,
                                ]
                            )
                            ->one();
            if (!empty( $user )) {
                $user->status = 10;
                $user->save();
                Yii::$app->getSession()
                         ->setFlash('success', \Yii::t('app', 'Ваш аккаунт упешно активирован!'));
            } else {
                Yii::$app->getSession()
                         ->setFlash('warning', \Yii::t('app', 'Ошибка при активации акаунта!'));
            }
            return $this->goHome();
        }
        
        /**
         * Logs out the current user.
         *
         * @return mixed
         */
        public function actionLogout()
        {
            Yii::$app->user->logout();
            
            return $this->goHome();
        }
        
        public function actionTest()
        {
            
        }
        
        /**
         * Resets password.
         *
         * @param string $token
         *
         * @return mixed
         * @throws BadRequestHttpException
         */
        public function actionResetPassword($token)
        {
            try {
                $model = new ResetPasswordForm($token);
            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
            
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                Yii::$app->session->setFlash('success', \Yii::t('app', 'Новый пароль был успешно сохранен'));
                
                return $this->goHome();
            }
            
            return $this->render(
                'resetPassword',
                [
                    'model' => $model,
                ]
            );
        }
        
        /**
         * Requests password reset.
         *
         * @return mixed
         */
        public function actionRequestPasswordReset()
        {
            
            $model = new PasswordResetRequestForm();
            
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                
                if ($model->sendEmail()) {
                    
                    Yii::$app->session->setFlash(
                        'success',
                        \Yii::t(
                            'app',
                            'Проверьте свою электронную почту для получения дальнейших инструкций.'
                        )
                    );
                    
                    return $this->goHome();
                } else {
                    
                    Yii::$app->session->setFlash(
                        'error',
                        \Yii::t(
                            'app',
                            'К сожалению, мы не можем сбросить пароль для данной электронной почты.'
                        )
                    );
                }
            }
            
            return $this->render(
                'requestPasswordResetToken',
                [
                    'model' => $model,
                ]
            );
        }
        
        public function actionPaymentInform()
        {
            $request = \Yii::$app->request;
            $response = \Yii::$app->response;
            if (!$request->isAjax) {
                throw new BadRequestHttpException('Available via Ajax only');
            }
            $response->format = $response::FORMAT_JSON;
            $model = new PaymentInform();
            if ($model->load($request->post())) {
                if ($request->post('validation')) {
                    return ActiveForm::validate($model);
                } else {
                    if (empty( ActiveForm::validate($model) )) {
                        //Success
                        $file = UploadedFile::getInstance($model, 'file');
                        $mail = \Yii::$app->mailer->compose(
                            [ 'html' => 'payment_inform' ],
                            [
                                'model' => $model,
                            ]
                        )
                                                  ->setFrom(
                                                      [ \Yii::$app->params[ 'supportEmail' ] => \Yii::$app->name . ' robot' ]
                                                  )
                                                  ->setTo(['bzika@ukr.net', 'shop@eltrade.com.ua']);
                        if (!empty( $model->orderId )) {
                            $mail->setSubject(
                                \Yii::t(
                                    'app',
                                    'Заказ №{order_id}!',
                                    [
                                        'order_id' => $model->orderId,
                                    ]
                                )
                            );
                        } else {
                            $mail->setSubject(
                                \Yii::t(
                                    'app',
                                    'Сообщение об оплате'
                                )
                            );
                        }
                        if ($file) {
                            $mail->attach(
                                $file->tempName,
                                [
                                    'fileName' => $file->baseName . '.' . $file->extension,
                                ]
                            );
                        }
                        if ($mail->send()) {
                            return [ 'success' => \Yii::t('app', 'Сообщение успешно доставлено') ];
                        } else {
                            return [
                                'error'   => true,
                                'message' => \Yii::t('app', 'Ошибка отправки сообщения, попробуйте снова'),
                            ];
                        }
                    } else {
                        return ActiveForm::validate($model);
                    }
                }
            }
            return [
                'error'   => true,
                'message' => \Yii::t('app', 'Форма заполнена неправильно'),
            ];
        }
        
        public function actionFeedback()
        {
            $request = \Yii::$app->request;
            $response = \Yii::$app->response;
            if (!$request->isAjax) {
                throw new BadRequestHttpException('Available via Ajax only');
            }
            $response->format = $response::FORMAT_JSON;
            $model = new Feedback(
                [
                    'scenario' => Feedback::SCENARIO_CALLBACK,
                    'name'     => 'Обратная связь',
                ]
            );
            if ($model->load($request->post()) && $model->save()) {
                \Yii::$app->session->setFlash(
                    'success',
                    \Yii::t(
                        'app',
                        'Спасибо за обращение, мы свяжемся с Вами в ближайшее время'
                    )
                );
                return [
                    'success' => \Yii::t('app', 'Спасибо за обращение, мы свяжемся с Вами в ближайшее время'),
                ];
            }
            \Yii::$app->session->setFlash('error', \Yii::t('app', 'Неправильно введенные данные'));
            return [
                'error' => \Yii::t('app', 'Неправильно введенные данные'),
            ];
        }
        
        /**
         * Method to find text page by slug
         *
         * @param string $slug
         *
         * @return \yii\db\ActiveRecord
         * @throws \yii\web\NotFoundHttpException
         */
        private function findPage(string $slug):ActiveRecord
        {
            $model = Page::find()
                         ->joinWith('lang')
                         ->where([ 'page_lang.alias' => $slug ])
                         ->one();
            if (empty( $model )) {
                throw new NotFoundHttpException('Page not found');
            } else {
                return $model;
            }
        }
        
        /**
         * Build query to find articles by their category ID limited to $max_count
         *
         * @param int $category_id
         * @param int $max_count
         *
         * @return \yii\db\ActiveQuery
         */
        private function findArticles(int $category_id, int $max_count): ActiveQuery
        {
            $query = BlogArticle::find()
                                ->with('lang')
                                ->innerJoin(
                                    'blog_article_to_category',
                                    'blog_article.id = blog_article_to_category.blog_article_id'
                                )
                                ->where(
                                    [
                                        'blog_article.status'                       => true,
                                        'blog_article_to_category.blog_category_id' => $category_id,
                                    ]
                                )
                                ->orderBy([ 'created_at' => SORT_DESC ])
                                ->limit($max_count);
            return $query;
        }
        
        public function actionError()
        {
            return $this->render(
                'error',
                [
                    'code' => '404',
                ]
            );
        }
    }
