<?php
    
    namespace artweb\artbox\behaviors;
    
    use yii\base\ActionFilter;
    use yii\web\BadRequestHttpException;

    class AjaxFilter extends ActionFilter
    {
        /**
         * @param \yii\base\Action $action
         *
         * @return bool
         * @throws \yii\web\BadRequestHttpException
         */
        public function beforeAction($action)
        {
            if(\Yii::$app->request->isAjax) {
                return parent::beforeAction($action);
            }
            
            throw new BadRequestHttpException('Allowed only via AJAX');
        }
    }