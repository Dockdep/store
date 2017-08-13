<?php
    
    namespace artweb\artbox\ecommerce\controllers;
    
    use artweb\artbox\components\SmsSender;
    use artweb\artbox\ecommerce\models\OrderLabelHistory;
    use artweb\artbox\ecommerce\models\OrderSearch;
    use common\components\CreditHelper;
    use common\models\User;
    use Yii;
    use yii\data\ArrayDataProvider;
    use yii\db\ActiveQuery;
    use yii\helpers\Json;
    use yii\helpers\VarDumper;
    use yii\validators\NumberValidator;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use yii\data\ActiveDataProvider;
    use yii\web\ForbiddenHttpException;
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\OrderProduct;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\web\NotFoundHttpException;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    use yii\web\Response;
    
    class OrderController extends Controller
    {
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessBehavior::className(),
                    'rules' => [
                        'site' => [
                            [
                                'actions' => [
                                    'login',
                                    'error',
                                ],
                                'allow'   => true,
                            ],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => [ 'POST' ],
                    ],
                ],
            ];
        }
        
        public function actionIndex()
        {
            $searchModel = new OrderSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render(
                'index',
                [
                    'dataProvider' => $dataProvider,
                    'searchModel'  => $searchModel,
                ]
            );
        }
        
        public function actionShow($id)
        {
            
            $model = $this->findModel((int) $id);
            $dataProvider = new ActiveDataProvider(
                [
                    'query'      => OrderProduct::find()
                                                ->where([ 'order_id' => (int) $id ]),
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ]
            );
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect([ 'index' ]);
            } else {
                $model_orderproduct = new OrderProduct();
                
                return $this->renderAjax(
                    'show',
                    [
                        'model'              => $model,
                        'model_orderproduct' => $model_orderproduct,
                        'dataProvider'       => $dataProvider,
                    ]
                );
            }
        }
        
        public function actionLabelUpdate()
        {
            $model = Order::findOne($_POST[ 'order_id' ]);
            $model->label = $_POST[ 'label_id' ];
            $model->save();
        }
        
        public function actionView($id)
        {
            $model = $this->findModel($id);
            
            $historyData = new ActiveDataProvider(
                [
                    'query' => $model->getLabelsHistory()
                                     ->with('order', 'label', 'user'),
                ]
            );
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $model->getProducts(),
                ]
            );
            return $this->render(
                'view',
                [
                    'model'       => $model,
                    'products'    => $dataProvider,
                    'historyData' => $historyData,
                ]
            );
        }
        
        public function actionPayUpdate()
        {
            $model = Order::findOne($_POST[ 'order_id' ]);
            $model->pay = $_POST[ 'pay_id' ];
            $model->save();
        }
        
        public function actionLog($id)
        {
            $model = Order::findOne($id);
            
            $logData = new ActiveDataProvider(
                [
                    'query' => $model->getLogs(),
                ]
            );
            
            $productLogData = new ActiveDataProvider(
                [
                    'query' => $model->getProducts()
                                     ->with([
                                         'logs' => function(ActiveQuery $query) {
                                            $query->with('user');
                                         },
                                         'productVariant' => function(ActiveQuery $query) {
                                            $query->with([
                                                'lang',
                                                'product' => function(ActiveQuery $query) {
                                                    $query->with([
                                                        'lang',
                                                        'category.lang',
                                                        'brand.lang',
                                                                 ]);
                                                }
                                                         ]);
                                         },
                                            ]),
                ]
            );
            
            return $this->render(
                'log',
                [
                    'model'          => $model,
                    'logData'        => $logData,
                    'productLogData' => $productLogData,
                ]
            );
        }
        
        public function actionDelete($id)
        {
            if (\Yii::$app->user->identity->isAdmin()) {
                $this->findModel($id)
                     ->delete();
            }
            
            return $this->redirect([ 'index' ]);
        }
        
        public function actionAdd()
        {
            if (!empty(\Yii::$app->request->post())) {
                $id = \Yii::$app->request->post('OrderProduct')[ 'id' ];
                $order_id = \Yii::$app->request->post('OrderProduct')[ 'order_id' ];
                if (!empty(\Yii::$app->request->post('OrderProduct')[ 'count' ])) {
                    $count = \Yii::$app->request->post('OrderProduct')[ 'count' ];
                } else {
                    $count = 1;
                }
                $productVariant = ProductVariant::findOne($id);
                
                $model = OrderProduct::find()
                                     ->where(
                                         [
                                             'order_id' => $order_id,
                                         ]
                                     )
                                     ->andWhere(
                                         [
                                             'product_variant_id' => $id,
                                         ]
                                     )
                                     ->one();
                
                if (!empty($model)) {
                    $model->count += $count;
                    $model->removed = false;
                } else {
                    $model = new OrderProduct();
                    
                    $model->order_id = $order_id;
                    $model->product_variant_id = $productVariant->id;
                    $model->product_name = $productVariant->product->lang->title;
                    $model->name = $productVariant->lang->title;
                    $model->sku = $productVariant->sku;
                    $model->price = $productVariant->price;
                    $model->count = $count;
                    $model->removed = false;
                }
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                
                if ($model->save()) {
                    $model->order->totalRecount();
                    return [ 'status' => 'success' ];
                } else {
                    return [ 'status' => 'fail' ];
                }
                
            } else {
                throw new NotFoundHttpException();
            }
        }
        
        public function actionCreate()
        {
            if (\Yii::$app->request->post('hasEditable')) {
                $orderProductId = \Yii::$app->request->post('editableKey');
                $orderProduct = OrderProduct::findOne($orderProductId);
                $out = Json::encode(
                    [
                        'output'  => '',
                        'message' => '',
                    ]
                );
                
                $posted = current(\Yii::$app->request->post('OrderProduct'));
                $post = [ 'OrderProduct' => $posted ];
                
                if ($orderProduct->load($post)) {
                    $orderProduct->save();
                    $output = '';
                    if (isset($posted[ 'count' ])) {
                        $output = Yii::$app->formatter->asDecimal($orderProduct->count, 0);
                    }
                    $out = Json::encode(
                        [
                            'output'  => $output,
                            'message' => '',
                        ]
                    );
                }
                
                return $out;
            }
            
            $model = new Order();
            $model->phone = '+38(000)000-00-00';
            $model->name = \Yii::t('app', 'Новый заказ');
            $model->published = false;
            $model->save();
            
            return $this->redirect(
                [
                    'update',
                    'id' => $model->id,
                ]
            );
            
            //            $dataProvider = new ActiveDataProvider(
            //                [
            //                    'query' => $model->getProducts()
            //                                     ->joinWith('productVariant'),
            //                ]
            //            );
            //
            //            if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //                $model->published = true;
            //                $model->save();
            //                return $this->redirect([ 'index' ]);
            //            } else {
            //                return $this->render(
            //                    'create',
            //                    [
            //                        'model'        => $model,
            //                        'dataProvider' => $dataProvider,
            //                    ]
            //                );
            //            }
        }
        
        public function actionPrint($order_id)
        {
            $order = $this->findModel($order_id);
            $dataProvider = new ArrayDataProvider(
                [
                    'allModels'  => $order->products,
                    'pagination' => false,
                    'sort'       => false,
                ]
            );
            return $this->renderPartial(
                'print',
                [
                    'order'        => $order,
                    'dataProvider' => $dataProvider,
                ]
            );
            
        }
        
        public function actionUpdate($id)
        {
            if (\Yii::$app->request->post('hasEditable')) {
                $orderProductId = \Yii::$app->request->post('editableKey');
                $orderProduct = OrderProduct::findOne($orderProductId);
                $out = Json::encode(
                    [
                        'output'  => '',
                        'message' => '',
                    ]
                );
                
                $posted = current(\Yii::$app->request->post('OrderProduct'));
                $post = [ 'OrderProduct' => $posted ];
                
                if ($orderProduct->load($post)) {
                    $orderProduct->save();
                    $orderProduct->order->totalRecount();
                    $output = '';
                    if (isset($posted[ 'count' ])) {
                        $output = Yii::$app->formatter->asDecimal($orderProduct->count, 0);
                    }
                    $out = Json::encode(
                        [
                            'output'  => $output,
                            'message' => '',
                        ]
                    );
                }
                
                return $out;
            }
            
            $model = $this->findModel($id);
            
            if ($model->payment == 10) {
                $model->validators->append(
                    new NumberValidator(
                        [
                            'attributes' => 'credit_sum',
                            'max'        => $model->total - CreditHelper::MIN_CREDIT_SUM,
                            'min'        => $model->total - CreditHelper::MAX_CREDIT_SUM,
                        ]
                    )
                );
            }
            
            /**
             * @var User $user
             */
            $user = \Yii::$app->user->identity;
            if ($model->isBlocked() && $model->edit_id !== \Yii::$app->user->id) {
                if (!$user->isAdmin()) {
                    throw new ForbiddenHttpException();
                }
            }
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $model->getProducts()
                                     ->joinWith('productVariant.product.brand')
                                     ->with('productVariant.variantStocks'),
                    'sort'  => [ 'defaultOrder' => [ 'id' => SORT_ASC ] ],
                ]
            );
            
            if (empty($model->manager_id)) {
                $model->manager_id = \Yii::$app->user->id;
            }
            
            $headers = \Yii::$app->response->headers;
            $headers->set('Access-Control-Allow-Origin', '*');
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                if ($model->published != true) {
                    $model->published = true;
                    $model->save();
                    /**
                     * @var SmsSender $sender
                     */
                    
                    $sender = \Yii::$app->sender;
                    $sender->send(
                        $model->phone,
                        $this->renderPartial(
                            '@common/mail/smsorder',
                            [
                                'order_id' => $model->id,
                            ]
                        )
                    );
                }
                
                $this->unblockOrder($model->id);
                return $this->render(
                    'update',
                    [
                        'model'        => $model,
                        'dataProvider' => $dataProvider,
                    ]
                );
            } else {
                return $this->render(
                    'update',
                    [
                        'model'        => $model,
                        'dataProvider' => $dataProvider,
                    ]
                );
            }
        }
        
        public function actionFindProduct($q = NULL, $id = NULL)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $out = [
                'results' => [
                    'id'  => '',
                    'sku' => '',
                ],
            ];
            if (!is_null($q)) {
                $result = ProductVariant::find()
                                        ->joinWith('product.lang')
                                        ->where(
                                            [
                                                'like',
                                                'sku',
                                                $q,
                                            ]
                                        )
                                        ->limit(20)
                                        ->asArray()
                                        ->all();
                
                $out[ 'results' ] = $result;
            }
            return $out;
        }
        
        public function actionSendSms()
        {
            $phone = \Yii::$app->request->post('phone');
            $content = \Yii::$app->request->post('content');
            $sender = \Yii::$app->sender;
            $result = $sender->send($phone, $content);
            return $phone . $content . $result;
        }
        
        public function actionDeleteProduct($id, $order_id)
        {
            $model = OrderProduct::findOne($id);
            $model->removed = true;
            $model->count = 0;
            $model->save();
            $order = Order::findOne($order_id);
            $order->totalRecount();
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => 'success',
                'id'     => $id,
            ];
        }
        
        protected function findModel($id)
        {
            if (( $model = Order::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        public function actionExitOrder()
        {
            try {
                $model = Order::findOne(\Yii::$app->request->post('id'));
            } catch (NotFoundHttpException $e) {
                return $this->redirect('index');
            }
            if ($model->edit_id == \Yii::$app->user->id) {
                $this->unblockOrder(\Yii::$app->request->post('id'));
            }
            
            if (!$model->published) {
                $model->deleteUnpublished();
            }
        }
        
        public function actionCloseOrder($id)
        {
            try {
                $model = Order::findOne($id);
            } catch (NotFoundHttpException $e) {
                return $this->redirect('index');
            }
            if ($model->edit_id == \Yii::$app->user->id) {
                $this->unblockOrder($id);
            }
            
            if (!$model->published) {
                $model->deleteUnpublished();
            }
            return $this->redirect('index');
        }
        
        public function actionBlockOrder()
        {
            if (!empty(\Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
                $model = $this->findModel(\Yii::$app->request->post('id'));
                
                $user = User::find()
                            ->where([ 'id' => $model->edit_id ])
                            ->one();
                $model->edit_time = time();
                $model->edit_id = \Yii::$app->user->id;
                
                //$date = new \DateTime("NOW"/*date('D, d M Y H:i:s', $model->edit_time)*/, new \DateTimeZone('Europe/Kiev'));
                $date = \Yii::$app->formatter->asDatetime($model->edit_time + 7200, 'php:G : i');
                
                if ($model->save()) {
                    return [
                        'time' => $date,
                        'user' => !empty($user) ? $user->username : '',
                    ];
                } else {
                    return [
                        'success' => false,
                        'errors'  => $model->errors,
                    ];
                }
            }
        }
        
        protected function unblockOrder($id)
        {
            $model = $this->findModel($id);
            
            $model->edit_time = 0;
            $model->edit_id = 0;
            $model->save();
        }
        
        public function actionPublishOrder($id, $phone)
        {
            $model = Order::findOne($id);
            if ($model->published == true) {
                exit;
            }
            $model->published = true;
            $model->save();
            
            /**
             * Add order to history
             */
            $history = new OrderLabelHistory();
            
            $history->label_id = (integer) $model->label;
            $history->order_id = (integer) $model->id;
            $history->user_id = (integer) \Yii::$app->user->identity->id;
            
            $history->save();
            
            /**
             * @var SmsSender $sender
             */
            $sender = \Yii::$app->sender;
            if (!empty($phone)) {
                $sender->send(
                    $phone,
                    $this->renderPartial(
                        '@common/mail/smsorder',
                        [
                            'order_id' => $model->id,
                        ]
                    )
                );
            }
            
        }
    }
