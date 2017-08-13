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
         * @param int          $quality
         * @param bool         $lazy
         *
         * @return string
         * @see Html::img()
         */
        public static function getImage($file, $preset, $imgOptions = [], $quality = 90, $lazy = false)
        {
            $preset_alias = is_array($preset) ? array_keys($preset)[ 0 ] : null;
            $src = self::getImageSrc($file, $preset, $preset_alias, $quality);
            if($lazy) {
                $square = self::getImageSrc('/storage/white.jpg', $preset, $preset_alias, 10);
                if(isset($imgOptions['class'])) {
                    $imgOptions['class'] = $imgOptions['class'].' artbox-lazy';
                } else {
                    $imgOptions['class'] = 'artbox-lazy';
                }
                $imgOptions['data-original'] = $src;
                return Html::img($square, $imgOptions);
            } else {
                return Html::img($src, $imgOptions);
            }
        }
    
        /**
         * Get src for image
         *
         * @param string      $file
         * @param string      $preset
         * @param null|string $preset_alias
         * @param int         $quality
         *
         * @return bool|string
         */
        public static function getImageSrc($file, $preset, $preset_alias = null, $quality = 90)
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
            return self::getPresetUrl($filePath, $preset, $preset_alias, $quality);
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
         * @param int    $quality
         *
         * @return bool|string
         */
        private static function getPresetUrl($filePath, $preset, $preset_alias, $quality = 90)
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
            $output = self::createPresetImage($filePath, $preset, $preset_alias, $quality);
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
         * @param string  $filePath
         * @param array   $preset
         * @param string  $preset_alias
         * @param integer $quality
         *
         * @return string
         */
        private static function createPresetImage($filePath, $preset, $preset_alias, $quality = 90)
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
            return $image->render(null, $quality);
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