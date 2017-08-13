<?php
    
    namespace artweb\artbox\components\artboximage;
    
    use yii\base\Component;
    use yii\base\ErrorException;
    use yii\image\drivers\Image;
    
    class ArtboxImage extends Component
    {
        /**
         * @var string $driver GD, Imagick ...
         */
        public $driver;
        
        public $presets = [];
        
        /**
         * File path to image locations
         *
         * @var string $rootPath
         */
        public $rootPath;
        
        /**
         * Web path to image locations
         *
         * @var string $rootUrl
         */
        public $rootUrl;
        
        public $extensions = [
            'jpg'  => 'jpeg',
            'jpeg' => 'jpeg',
            'png'  => 'png',
            'gif'  => 'gif',
            'bmp'  => 'bmp',
        ];
        
        /**
         * Try to load image and prepare it to manipulation.
         *
         * @param null|string $file
         * @param null|string $driver
         *
         * @return \yii\image\drivers\Image
         * @throws \yii\base\ErrorException
         */
        public function load($file = null, $driver = null)
        {
            if (empty( $file ) || !realpath($file)) {
                throw new ErrorException('File name can not be empty and exists');
            }
            return Image::factory($file, $driver ? $driver : $this->driver);
        }
    }