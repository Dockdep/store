<?php
    
    namespace artweb\artbox\design\controllers;
    
    use developeruz\db_rbac\behaviors\AccessBehavior;
    use artweb\artbox\design\models\Slider;
    use Yii;
    use artweb\artbox\design\models\SliderImage;
    use artweb\artbox\design\models\SliderImageSearch;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    
    /**
     * SliderImageController implements the CRUD actions for SliderImage model.
     */
    class SliderImageController extends Controller
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
        
        /**
         * Lists all SliderImage models.
         *
         * @param $slider_id Slider id
         *
         * @return mixed
         */
        public function actionIndex($slider_id)
        {
            $searchModel = new SliderImageSearch();
            $dataProvider = $searchModel->search($slider_id, Yii::$app->request->queryParams);
            
            return $this->render(
                'index',
                [
                    'slider_id'    => $slider_id,
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                ]
            );
        }
        
        /**
         * Displays a single SliderImage model.
         *
         * @param integer $id
         * @param         $slider_id Slider id
         *
         * @return mixed
         */
        public function actionView($slider_id, $id)
        {
            return $this->render(
                'view',
                [
                    'slider_id' => $slider_id,
                    'model'     => $this->findModel($slider_id, $id),
                ]
            );
        }
        
        /**
         * Creates a new SliderImage model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         *
         * @param $slider_id Slider id
         *
         * @return mixed
         */
        public function actionCreate($slider_id)
        {
            $model = new SliderImage();
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                $model->slider_id = $slider_id;
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'view',
                            'slider_id' => $slider_id,
                            'id'        => $model->id,
                        ]
                    );
                }
            }
            $slider = Slider::findOne($slider_id);
            return $this->render(
                'create',
                [
                    'slider_id'  => $slider_id,
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'slider'     => $slider,
                ]
            );
        }
        
        /**
         * Updates an existing SliderImage model.
         * If update is successful, the browser will be redirected to the 'view' page.
         *
         * @param         $slider_id Slider id
         * @param integer $id
         *
         * @return mixed
         */
        public function actionUpdate($slider_id, $id)
        {
            $model = $this->findModel($slider_id, $id);
            $model->generateLangs();
            if ($model->load(Yii::$app->request->post())) {
                $model->loadLangs(\Yii::$app->request);
                if ($model->save() && $model->transactionStatus) {
                    return $this->redirect(
                        [
                            'view',
                            'slider_id' => $slider_id,
                            'id'        => $model->id,
                        ]
                    );
                }
            }
            $slider = Slider::findOne($slider_id);
            return $this->render(
                'update',
                [
                    'model'      => $model,
                    'modelLangs' => $model->modelLangs,
                    'slider_id'  => $slider_id,
                    'slider'     => $slider,
                ]
            );
        }
        
        /**
         * Deletes an existing SliderImage model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         *
         * @param         $slider_id Slider id
         * @param integer $id
         *
         * @return mixed
         */
        public function actionDelete($slider_id, $id)
        {
            $this->findModel($slider_id, $id)
                 ->delete();
            
            return $this->redirect(
                [
                    'index',
                    'slider_id' => $slider_id,
                ]
            );
        }
    
        public function actionDeleteImage($id)
        {
            $model = SliderImage::findOne($id);
            if(empty($model)) {
                throw new NotFoundHttpException();
            }
            $model->image = null;
            $model->updateAttributes(['image']);
            return true;
        }
        
        /**
         * Finds the SliderImage model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         *
         * @param         $slider_id Slider id
         * @param integer $id
         *
         * @return SliderImage the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($slider_id, $id)
        {
            /**
             * @var SliderImage $model
             */
            if (( $model = SliderImage::find()
                                      ->where(
                                          [
                                              'id'        => $id,
                                              'slider_id' => $slider_id,
                                          ]
                                      )
                                      ->one() ) !== null
            ) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
