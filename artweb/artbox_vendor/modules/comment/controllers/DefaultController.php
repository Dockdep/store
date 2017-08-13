<?php
    namespace artweb\artbox\modules\comment\controllers;
    
    use artweb\artbox\modules\comment\models\CommentModel;
    use artweb\artbox\modules\comment\models\RatingModel;
    use artweb\artbox\modules\comment\Module;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\helpers\Json;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class DefaultController extends Controller
    {
        
        /**
         * Returns a list of behaviors that this component should behave as.
         * @return array
         */
        public function behaviors()
        {
            return [
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'create' => [ 'post' ],
                        'delete' => [
                            'post',
                            'delete',
                        ],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => [ 'delete' ],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => [ '@' ],
                        ],
                    ],
                ],
            ];
        }
    
        /**
         * Create comment.
         *
         * @param string $entity
         *
         * @return array|null|Response
         */
        public function actionCreate(string $entity)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            /* @var $module Module */
            $module = \Yii::$app->getModule(Module::$name);
            $entity_data_json = \Yii::$app->getSecurity()
                                          ->decryptByKey($entity, $module::$encryptionKey);
            if($entity_data_json != false) {
                $entity_data = Json::decode($entity_data_json);
                $commentModelClass = $module->commentModelClass;
                /**
                 * @var CommentModel $model
                 */
                $model = new $commentModelClass([
                    'scenario' => \Yii::$app->user->getIsGuest() ? $commentModelClass::SCENARIO_GUEST : $commentModelClass::SCENARIO_USER,
                ]);
                if($model->load(\Yii::$app->request->post())) {
                    $model->setAttributes($entity_data);
                    if($model->save()) {
                        if(empty( $model->artbox_comment_pid ) && $module::$enableRating) {
                            $ratingModelClass = $module->ratingModelClass;
                            /**
                             * @var RatingModel $rating
                             */
                            $rating = new $ratingModelClass([
                                'model'    => $model::className(),
                                'model_id' => $model->primaryKey,
                            ]);
                            if($rating->load(\Yii::$app->request->post())) {
                                $rating->save();
                            }
                        }
                        \Yii::$app->session->setFlash('artbox_comment_success', \Yii::t('artbox-comment', 'Comment posted'));
                        return [ 'status' => 'success' ];
                    } else {
                        return [
                            'status' => 'error',
                            'errors' => $model->getFirstErrors(),
                        ];
                    }
                }
            }
            return [
                'status'  => 'error',
                'message' => \Yii::t('artbox-comment', 'Oops, something went wrong. Please try again later.'),
            ];
        }
        
        /**
         * Delete comment.
         *
         * @param integer $id Comment ID
         *
         * @return string Comment text
         */
        public function actionDelete($id)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = $this->findModel($id);
            if($model->deleteComment()) {
                return [
                    'status'  => 'success',
                    'message' => \Yii::t('yii2mod.comments', 'Comment has been deleted.'),
                ];
            } else {
                \Yii::$app->response->setStatusCode(500);
                return \Yii::t('yii2mod.comments', 'Comment has not been deleted. Please try again!');
            }
        }
        
        /**
         * Find model by ID.
         *
         * @param integer|array $id Comment ID
         *
         * @return CommentModel
         * @throws NotFoundHttpException
         */
        protected function findModel(int $id): CommentModel
        {
            /** @var CommentModel $model */
            $commentModelClass = \Yii::$app->getModule(Module::$name)->commentModelClass;
            if(( $model = $commentModelClass::findOne($id) ) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException(\Yii::t('yii2mod.comments', 'The requested page does not exist.'));
            }
        }
    }