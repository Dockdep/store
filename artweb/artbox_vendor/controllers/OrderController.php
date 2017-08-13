<?php
    
    namespace artweb\artbox\controllers;
    
    use artweb\artbox\models\OrderSearch;
    use Yii;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use yii\data\ActiveDataProvider;
    use yii\web\HttpException;
    use artweb\artbox\models\Order;
    use artweb\artbox\models\OrderProduct;
    use artweb\artbox\modules\catalog\models\ProductVariant;
    use yii\web\NotFoundHttpException;
    use developeruz\db_rbac\behaviors\AccessBehavior;
    
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
        
        public function actionPayUpdate()
        {
            $model = Order::findOne($_POST[ 'order_id' ]);
            $model->pay = $_POST[ 'pay_id' ];
            $model->save();
        }
        
        public function actionDelete()
        {
            $model = Order::findOne($_GET[ 'id' ]);
            $model->delete();
            return Yii::$app->response->redirect([ '/order/index' ]);
        }
        
        public function actionAdd()
        {
            $model = new OrderProduct();
            if ($model->load(Yii::$app->request->post())) {
                /**
                 * @var ProductVariant $modelMod
                 */
                if (!$modelMod = ProductVariant::find()
                                               ->with('product.lang')
                                               ->with('lang')
                                               ->where([ 'sku' => $model->sku ])
                                               ->one()
                ) {
                    throw new HttpException(404, 'Данного артикля не существует!');
                }
                $model->product_name = $modelMod->product->lang->title;
                $model->name = $modelMod->lang->title;
                $model->sku = $modelMod->sku;
                $model->price = $modelMod->price;
                $model->sum_cost = $model->count * $modelMod->price;
                $model->product_variant_id = $modelMod->id;
                $model->save();
                //return Yii::$app->response->redirect(['/admin/order/show','id'=>$_GET['order_id']]);
            }
            
            //return $this->render('add',['model'=>$model]);
        }
        
        public function actionCreate()
        {
            $model = new Order();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect([ 'index' ]);
            } else {
                return $this->render(
                    'create',
                    [
                        'model' => $model,
                    ]
                );
            }
        }
        
        public function actionDeleteProduct()
        {
            $model = OrderProduct::findOne($_GET[ 'id' ]);
            $model->delete();
            return Yii::$app->response->redirect(
                [
                    '/admin/order/show',
                    'id' => $_GET[ 'order_id' ],
                ]
            );
        }
        
        protected function findModel($id)
        {
            if (( $model = Order::findOne($id) ) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
