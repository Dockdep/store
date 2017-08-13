<?php
    namespace artweb\artbox\file\models;
    
    use yii\base\Model;
    use yii\web\UploadedFile;
    
    /**
     * UploadForm is the model behind the upload form.
     */
    class ImageSizerForm extends Model
    {
        /**
         * @var UploadedFile file attribute
         */
        public $file;
        public $width;
        public $height;
        public $field;
        public $model;
        public $form;
        public $multi;
        public $old_img;
        public $img;
        public $price_list;
        
        /**
         * @return array the validation rules.
         */
        public function rules()
        {
            return [
                [
                    [
                        'width',
                        'height',
                        'multi',
                    ],
                    'integer',
                ],
                [
                    [
                        'field',
                        'multi',
                        'old_img',
                    ],
                    'string',
                    'max' => 255,
                ],
                [
                    [
                        'model',
                        'form',
                    ],
                    'string',
                ],
                [
                    [
                        'file',
                        'img',
                        'price_list',
                    ],
                    'file',
                ],
            ];
        }
    }