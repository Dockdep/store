<?php
    
    namespace frontend\widgets;
    
    use yii\base\InvalidConfigException;
    use yii\base\Widget;
    use yii\helpers\Html;
    use yii\web\AssetBundle;

    class AssetWidget extends Widget
    {
        public $assets = [];
        
        public function init()
        {
            parent::init();
            if(!empty($this->assets) && !is_array($this->assets)) {
                throw new InvalidConfigException('Assets must be an array');
            }
        }
    
        public function run()
        {
            if(!empty($this->assets)) {
                $assets = $this->assets;
                $bundles = $this->view->assetBundles;
                foreach ($assets as $asset) {
                    if(array_key_exists($asset, $bundles)) {
                        /**
                         * @var AssetBundle $bundle
                         */
                        $bundle = $bundles[$asset];
                        foreach ($bundle->css as $item) {
                            echo Html::cssFile($item);
                        }
                        $bundle->css = [];
                    }
                }
            }
        }
    }
    