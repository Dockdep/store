<?php
    
    namespace artweb\artbox\modules\language\components;
    
    use artweb\artbox\modules\language\models\Language;
    use yii\base\InvalidConfigException;
    use yii\web\Request;
    
    class LanguageRequest extends Request
    {
        
        private $languageUrl;
        
        public function getLanguageUrl()
        {
            if($this->languageUrl === NULL) {
                $this->languageUrl = $this->getUrl();
                
                $url_list = explode('/', $this->languageUrl);
                
                $language_url = isset( $url_list[ 1 ] ) ? $url_list[ 1 ] : NULL;
                Language::setCurrent($language_url);
                
                if($language_url !== NULL && $language_url === Language::getCurrent()->url && strpos($this->languageUrl, Language::getCurrent()->url) === 1) {
                    $this->languageUrl = substr($this->languageUrl, strlen(Language::getCurrent()->url) + 1);
                }
            }
            
            return $this->languageUrl;
        }
        
        protected function resolvePathInfo()
        {
            $pathInfo = $this->getLanguageUrl();
            
            if(( $pos = strpos($pathInfo, '?') ) !== false) {
                $pathInfo = substr($pathInfo, 0, $pos);
            }
            
            $pathInfo = urldecode($pathInfo);
            
            if(!preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              
            | [\xC2-\xDF][\x80-\xBF]             
            | \xE0[\xA0-\xBF][\x80-\xBF]       
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
            | \xED[\x80-\x9F][\x80-\xBF]       
            | \xF0[\x90-\xBF][\x80-\xBF]{2}    
            | [\xF1-\xF3][\x80-\xBF]{3}        
            | \xF4[\x80-\x8F][\x80-\xBF]{2}    
            )*$%xs', $pathInfo)
            ) {
                $pathInfo = utf8_encode($pathInfo);
            }
            
            $scriptUrl = $this->getScriptUrl();
            $baseUrl = $this->getBaseUrl();
            
            if(strpos($pathInfo, $scriptUrl) === 0) {
                $pathInfo = substr($pathInfo, strlen($scriptUrl));
            } elseif($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0) {
                $pathInfo = substr($pathInfo, strlen($baseUrl));
            } elseif(isset( $_SERVER[ 'PHP_SELF' ] ) && strpos($_SERVER[ 'PHP_SELF' ], $scriptUrl) === 0) {
                $pathInfo = substr($_SERVER[ 'PHP_SELF' ], strlen($scriptUrl));
            } else {
                throw new InvalidConfigException('Unable to determine the path info of the current request.');
            }
            
            if($pathInfo === '/') {
                $pathInfo = substr($pathInfo, 1);
            }
            
            return (string) $pathInfo;
        }
    }