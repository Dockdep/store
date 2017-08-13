<?php
/**
 * Created by PhpStorm.
 * User: vitaliy
 * Date: 05.10.15
 * Time: 16:18
 */

namespace artweb\artbox\modules\file\widgets;
use yii\base\Widget;


class ImageUploader extends Widget
{
    public $height = 0;
    public $width = 0;
    public $field;
    public $file;
    public $model;
    public $multi = false;
    public $gallery;
    public $size;
    public $name = 'Add file...';
    public $remover = 0;

    public function init(){

        parent::init();

    }


    public function run()
    {

        return $this->render('image_sizer',
            [
                'model'=>$this->model,
                'size' => $this->size,
                'field' => $this->field,
                'height' => $this->height,
                'width' => $this->width,
                'multi'  => $this->multi,
                'name'  => $this->name,
                'remover' => $this->remover
            ]);

    }

    public function getGallery(){
        if($this->gallery){
            $array = explode(",", $this->gallery);
            if(count($array) > 1){
                array_pop($array);
            }
            return $array;
        } else {
            return array();
        }

    }

}