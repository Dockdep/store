<?php
    
    namespace artweb\artbox\components\artboximage;
    
    use Yii;
    use yii\base\Object;
    use yii\helpers\Html;
    
    class ArtboxImageHelper extends Object
    {
        
        /**
         * @var  ArtboxImage $imageDriver
         */
        private static $imageDriver;
        
        /**
         * @var array $presets
         */
        private static $presets;
        
        /**
         * Get image manipulation driver
         *
         * @return \artweb\artbox\components\artboximage\ArtboxImage
         */
        public static function getDriver()
        {
            if (empty( self::$imageDriver )) {
                self::$imageDriver = Yii::$app->get('artboximage');
            }
            return self::$imageDriver;
        }
        
        /**
         * Get named preset from driver preset list.
         *
         * @param string $preset
         *
         * @return array|null
         */
        public static function getPreset($preset)
        {
            if (empty( self::$presets )) {
                self::$presets = self::getDriver()->presets;
            }
            return empty( self::$presets[ $preset ] ) ? null : self::$presets[ $preset ];
        }
        
        /**
         * Get image HTML for image
         *
         * @param string       $file
         * @param array|string $preset
         * @param array        $imgOptions
         *
         * @see Html::img()
         * @return string
         */
        public static function getImage($file, $preset, $imgOptions = [])
        {
            $preset_alias = is_array($preset) ? array_keys($preset)[ 0 ] : null;
            return Html::img(self::getImageSrc($file, $preset, $preset_alias), $imgOptions);
        }
        
        /**
         * Get src for image
         *
         * @param string      $file
         * @param string      $preset
         * @param null|string $preset_alias
         *
         * @return bool|string
         */
        public static function getImageSrc($file, $preset, $preset_alias = null)
        {
            if (is_string($preset)) {
                $preset_alias = $preset;
                $preset = self::getPreset($preset);
            }
            if (empty( $preset ) || empty( $preset_alias )) {
                return $file;
            }
            
            $filePath = self::getPathFromUrl($file);
            if (!file_exists($filePath) || !preg_match(
                    '#^(.*)\.(' . self::getExtensionsRegexp() . ')$#',
                    $file,
                    $matches
                )
            ) {
                return $file;
            }
            return self::getPresetUrl($filePath, $preset, $preset_alias);
        }
        
        /**
         * Replace web path with file path
         *
         * @param string $url
         *
         * @return string
         */
        private static function getPathFromUrl($url)
        {
            return substr_replace($url, self::getDriver()->rootPath, 0, strlen(self::getDriver()->rootUrl));
        }
        
        /**
         * Replace file path with web path
         *
         * @param string $path
         *
         * @return string
         */
        private static function getUrlFromPath($path)
        {
            return substr_replace($path, self::getDriver()->rootUrl, 0, strlen(self::getDriver()->rootPath));
        }
        
        /**
         * Get formatted file url or create it if not exist
         *
         * @param string $filePath
         * @param array  $preset
         * @param string $preset_alias
         *
         * @return bool|string
         */
        private static function getPresetUrl($filePath, $preset, $preset_alias)
        {
            $pathinfo = pathinfo($filePath);
            $presetPath = $pathinfo[ 'dirname' ] . '/styles/' . strtolower($preset_alias);
            $presetFilePath = $presetPath . '/' . $pathinfo[ 'basename' ];
            $presetUrl = self::getUrlFromPath($presetFilePath);
            if (file_exists($presetFilePath)) {
                return $presetUrl;
            }
            if (!file_exists($presetPath)) {
                @mkdir($presetPath, 0777, true);
            }
            $output = self::createPresetImage($filePath, $preset, $preset_alias);
            if (!empty( $output )) {
                $f = fopen($presetFilePath, 'w');
                fwrite($f, $output);
                fclose($f);
                return $presetUrl;
            }
            return false;
        }
        
        /**
         * Create formatted image.
         * Available manipulations:
         * * resize
         * * flip
         *
         * @param string $filePath
         * @param array  $preset
         * @param string $preset_alias
         *
         * @return string
         */
        private static function createPresetImage($filePath, $preset, $preset_alias)
        {
            $image = self::getDriver()
                         ->load($filePath);
            foreach ($preset as $action => $data) {
                switch ($action) {
                    case 'resize':
                        $width = empty( $data[ 'width' ] ) ? null : $data[ 'width' ];
                        $height = empty( $data[ 'height' ] ) ? null : $data[ 'height' ];
                        $master = empty( $data[ 'master' ] ) ? null : $data[ 'master' ];
                        $image->resize($width, $height, $master);
                        break;
                    case 'flip':
                        $image->flip(@$data[ 'direction' ]);
                        break;
                    default:
                        break;
                }
            }
            return $image->render();
        }
        
        /**
         * Get extensions regexp
         *
         * @return string regexp
         */
        private static function getExtensionsRegexp()
        {
            $keys = array_keys(self::getDriver()->extensions);
            return '(?i)' . join('|', $keys);
        }
    }