<?php
    namespace artweb\artbox\language\components;
    
    use artweb\artbox\language\models\Language;
    use yii\web\UrlManager;
    
    class LanguageUrlManager extends UrlManager
    {
        
        /**
         * @inheritdoc
         */
        public function createUrl($params)
        {
            if(isset( $params[ 'language_id' ] )) {
                
                $language = Language::findOne($params[ 'language_id' ]);
                if($language === NULL) {
                    $language = Language::getDefaultLanguage();
                }
                unset( $params[ 'language_id' ] );
            } else {
                
                $language = Language::getCurrent();
            }
            
            $url = parent::createUrl($params);
            
            if($url == '/') {
                return '/' . $language->url;
            } else {
                return '/' . $language->url . $url;
            }
        }
    }