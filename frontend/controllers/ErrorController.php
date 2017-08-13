<?php
    namespace frontend\controllers;
    
    use yii\web\Controller;
    
    class ErrorController extends Controller
    {
        
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
            ];
        }
        
    }