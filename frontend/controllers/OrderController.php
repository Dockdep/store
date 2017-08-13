<?php
    namespace frontend\controllers;
    
    use artweb\artbox\components\SmsSender;
    use artweb\artbox\ecommerce\models\Basket;
    use artweb\artbox\ecommerce\models\Delivery;
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\OrderProduct;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\models\Customer;
    use common\components\CreditHelper;
    use frontend\models\OrderCredit;
    use frontend\models\OrderFrontend;
    use frontend\models\SignupForm;
    use yii\db\Query;
    use yii\validators\NumberValidator;
    use yii\web\Controller;
    
    class OrderController extends Controller
    {
        public function actionBasket()
        {
            /**
             * @var Basket $basket
             */
            $basket = \Yii::$app->basket;
            $basket_sum = $basket->getSum();
            $data = $basket->getData();
            $models = $basket->findModels(array_keys($data));
            $order = new OrderFrontend(
                [
                    'scenario' => ( \Yii::$app->user->isGuest ? OrderFrontend::SCENARIO_GUEST : OrderFrontend::SCENARIO_CUSTOMER ),
                ]
            );
            $order->validators->append(
                new NumberValidator(
                    [
                        'attributes' => 'credit_sum',
                        'max'        => $basket_sum - CreditHelper::MIN_CREDIT_SUM,
                        'min'        => $basket_sum - CreditHelper::MAX_CREDIT_SUM,
                    ]
                )
            );
            $deliveries = Delivery::find()
                                  ->with('children.lang', 'lang')
                                  ->where([ 'parent_id' => null ])
                                  ->orderBy([ 'sort' => SORT_ASC ])
                                  ->all();
            if ($order->scenario == OrderFrontend::SCENARIO_CUSTOMER) {
                /**
                 * @var Customer $user
                 */
                $user = \Yii::$app->user->identity;
                $order->user_id = $user->id;
                $order->name = $user->username;
                $order->phone = $user->phone;
                $order->email = $user->email;
                $order->city = $user->city;
                $order->adress = $user->address;
            }
            if ($order->load(\Yii::$app->request->post(), 'OrderCredit')) {
                $order->payment = 10;
            }
            if (!empty( $models ) && $order->load(\Yii::$app->request->post()) && $order->validate()) {
                if (\Yii::$app->user->isGuest && !empty( $order->email )) {
                    $password = \Yii::$app->security->generateRandomString(6);
                    $signup = new SignupForm(
                        [
                            'username'        => $order->name,
                            'email'           => $order->email,
                            'password'        => $password,
                            'password_repeat' => $password,
                            'phone'           => $order->phone,
                            'city'            => $order->city,
                            'address'         => $order->adress,
                        ]
                    );
                    $customer = $signup->signup();
                    if ($customer) {
                        $order->user_id = $customer->id;
                        \Yii::$app->mailer->compose(
                            [ 'html' => 'order_register' ],
                            [
                                'model'    => $customer,
                                'password' => $password,
                            ]
                        )
                                          ->setFrom(
                                              [ \Yii::$app->params[ 'supportEmail' ] => \Yii::$app->name . ' robot' ]
                                          )
                                          ->setTo($order->email)
                                          ->setSubject(
                                              \Yii::t('app', 'Вы успешно зарегистрированы, пройдите активацию!')
                                          )
                                          ->send();
                    } else {
                        \Yii::$app->session->addFlash('error', \Yii::t('app', 'Ошибка регистрации'));
                    }
                }
                $order->save(false);
                $order_products = [];
                $total = 0;
                foreach ($models as $model) {
                    $sum_cost = $model->price * $data[ $model->id ][ 'count' ];
                    $total += $sum_cost;
                    $order_product = new OrderProduct(
                        [
                            'order_id'           => $order->id,
                            'product_variant_id' => $model->id,
                            'name'               => $model->lang->title,
                            'product_name'       => $model->product->lang->title,
                            'sku'                => $model->sku,
                            'price'              => $model->price,
                            'count'              => $data[ $model->id ][ 'count' ],
                            'sum_cost'           => $sum_cost,
                        ]
                    );
                    $order_product->save();
                    $order_products[] = $order_product;
                    unset( $sum_cost );
                }
                $basket->clear();
                $order->total = $total;
                $order->update(true, [ 'total' ]);
                \Yii::$app->session->setFlash(
                    'success',
                    $this->renderPartial(
                        '_order_success',
                        [
                            'model' => $order,
                        ]
                    )
                );
                if (!empty( $order->email )) {
                    \Yii::$app->mailer->compose(
                        [ 'html' => 'order' ],
                        [
                            'order'          => $order,
                            'order_products' => $order_products,
                            'models'         => $models,
                        ]
                    )
                                      ->setFrom([ \Yii::$app->params[ 'supportEmail' ] => \Yii::$app->name . ' robot' ])
                                      ->setTo($order->email)
                                      ->setSubject(
                                          \Yii::t(
                                              'app',
                                              'Заказ №{order_id}!',
                                              [
                                                  'order_id' => $order->id,
                                              ]
                                          )
                                      )
                                      ->send();
                }
                /**
                 * @var SmsSender $sender
                 */
                $sender = \Yii::$app->sender;
                $sender->send(
                    $order->phone,
                    $this->renderPartial(
                        '@common/mail/smsorder',
                        [
                            'order_id' => $order->id,
                        ]
                    )
                );
                return $this->redirect([ 'site/index' ]);
            }
            return $this->render(
                'basket',
                [
                    'basket'     => $basket,
                    'data'       => $data,
                    'models'     => $models,
                    'order'      => $order,
                    'deliveries' => $deliveries,
                ]
            );
        }
        
        public function actionStatus()
        {
            $labels = Label::find()
                           ->with('lang')
                           ->limit(9)
                           ->orderBy([ 'label' => SORT_ASC ])
                           ->all();
            $model = new OrderFrontend(
                [
                    'scenario' => OrderFrontend::SCENARIO_INFO,
                ]
            );
            $result = null;
            if ($model->load(\Yii::$app->request->get()) && $model->validate()) {
                $result = OrderFrontend::find()
                                       ->where([ 'id' => $model->id ])
                                       ->andWhere(
                                           [
                                               'not',
                                               [ 'label' => null ],
                                           ]
                                       )
                                       ->with('labelModel.lang')
                                       ->one();
            }
            return $this->render(
                'status',
                [
                    'model'  => $model,
                    'result' => $result,
                    'labels' => $labels,
                ]
            );
        }
        
        public function actionFastBuy()
        {
            $response = \Yii::$app->response;
            $response->format = $response::FORMAT_JSON;
            $order = new OrderFrontend(
                [
                    'scenario' => OrderFrontend::SCENARIO_FAST,
                ]
            );
            if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
                $variant = ProductVariant::findOne($order->variant_id);
                $order->total = $variant->price;
                $order->comment = 'Быстрый заказ';
                if ($order->save()) {
                    $order_product = new OrderProduct(
                        [
                            'order_id'           => $order->id,
                            'product_variant_id' => $variant->id,
                            'name'               => $variant->lang->title,
                            'sku'                => $variant->sku,
                            'price'              => $variant->price,
                            'count'              => 1,
                            'sum_cost'           => $variant->price,
                        ]
                    );
                    $order_product->save();
                    \Yii::$app->session->setFlash(
                        'success',
                        $this->renderPartial(
                            '_order_success',
                            [
                                'model' => $order,
                            ]
                        )
                    );
                    \Yii::$app->mailer->compose(
                        [ 'html' => 'order' ],
                        [
                            'order'          => $order,
                            'order_products' => [ $order_product ],
                            'models'         => [ $variant ],
                        ]
                    )
                                      ->setFrom([ \Yii::$app->params[ 'supportEmail' ] => \Yii::$app->name . ' robot' ])
                                      ->setTo($order->email)
                                      ->setSubject(
                                          \Yii::t(
                                              'app',
                                              'Заказ №{order_id}!',
                                              [
                                                  'order_id' => $order->id,
                                              ]
                                          )
                                      )
                                      ->send();
                    /**
                     * @var SmsSender $sender
                     */
                    $sender = \Yii::$app->sender;
                    $sender->send(
                        $order->phone,
                        $this->renderPartial(
                            '@common/mail/smsorder',
                            [
                                'order_id' => $order->id,
                            ]
                        )
                    );
                    return $this->redirect([ 'site/index' ]);
                } else {
                    return [
                        'success' => false,
                        'error'   => \Yii::t('app', 'Невозможно оформить заказ. Попробуйте позже'),
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'error'   => current($order->getFirstErrors()),
                ];
            }
        }
    }
    