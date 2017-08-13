<?php
/**
 * Created by PhpStorm.
 * User: Cibermag
 * Date: 31.08.2015
 * Time: 9:58
 */
namespace artweb\artbox\modules\file\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use artweb\artbox\modules\file\models\ImageSizerForm;
use yii\web\Controller;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use yii\imagine\Image;

class UploaderController extends Controller {

    public  $enableCsrfValidation = false;

    public function isBigger($width,$height,$w,$h)
    {
        if($width>$w){
            return true;
        }else if($height >$h) {
            return true;
        }
        return false;
    }


    private function getUserPath(){
        if(isset(Yii::$app->user->id)){
            return 'user_'.Yii::$app->user->id;
        }else {
            return 'guest';
        }
    }

    private function resizeImg($w, $h, $imageAlias,$imageAliasSave){
        $img = Image::getImagine()->open(Yii::getAlias($imageAlias));

        $size = $img->getSize();

        $width = $size->getWidth();
        $height = $size->getHeight();

        $e_width = $w/$h;
        $e_height = $h/$w;

        $e1_width = $width/$height;
        $e1_height = $height/$width;



            if($e_width<$e1_width){

                $new_width = $width*($e_width/$e1_width);

                $y = 0;
                $x = $width/ 2-($new_width/2);
                $width = $new_width;

            }else {

                $new_height = $height*($e_height/$e1_height);
                $x = 0;
                $y = $height/2-($new_height/2);
                $height = $new_height;
            }




        Image::crop($imageAlias, $width, $height,[$x,$y])
            ->save(Yii::getAlias($imageAliasSave), ['quality' =>
                100]);


        $imagine = new Imagine();
        $imagine->open($imageAliasSave)
            ->resize(new Box($w, $h))
            ->save($imageAliasSave, array('flatten' => false));



    }

    private function deleteImages($old_img){

        if(!empty($old_img) && file_exists($_SERVER['DOCUMENT_ROOT'].$old_img)){

            $rootDir = explode("/", $old_img);

            $row = $_SERVER['DOCUMENT_ROOT'].'/'.$rootDir[1].'/'.$rootDir[2].'/'.$rootDir[3].'/';

            $allFiles = scandir($row);

            $allFiles = array_slice($allFiles, 2);

            foreach($allFiles as $oldFile){

                unlink($row.$oldFile);

            }

        }
    }

    public function actionDeleteImage(){

        $this->enableCsrfValidation = false;

        $request = Yii::$app->request->post();

        if($request){
            if ($request['old_img']) {
                $this->deleteImages($request['old_img']);
            }
            if(isset($request['action']) && $request['action']=='save'){
                $object = str_replace('-', '\\',$request['model']);
                $model = new $object;
                $model = $model->findOne($request['id']);
                $model->$request['field'] = $request['new_url'];
                $model->save();
            }
        }

    }


    public function actionDownloadPhoto()
    {

        $model = new ImageSizerForm();

        $request = Yii::$app->request->post();

        if ($request) {

            $model->multi = isset($request['multi'])? 1 : 0;

            $model->file = UploadedFile::getInstance($model, 'file');

            $md5_file = md5_file($model->file->tempName).rand(1, 1000);

            $imgDir = Yii::getAlias('@storage/'.$this->getUserPath().'/'.$md5_file.'/');

            $imageOrigAlias = Yii::getAlias($imgDir.'original'.'.'.$model->file->extension);

            if(!is_dir($imgDir)) {
                mkdir($imgDir, 0755, true);
            }

            $model->file->saveAs($imageOrigAlias);


            if(isset($request['size'] )){

                $request['size'] = ArrayHelper::toArray(json_decode($request['size']));

                foreach($request['size'] as $size){
                    if($size['width'] && $size['height']){

                        $imageAlias = Yii::getAlias($imgDir.$size['width'].'x'.$size['height'].'.'.$model->file->extension);

                        $imageLink = '/storage/'.$this->getUserPath().'/'.$md5_file.'/'.$size['width'].'x'.$size['height'].'.'.$model->file->extension;

                        $this->resizeImg($size['width'], $size['height'], $imageOrigAlias,$imageAlias);

                    }
                }

            } else {

                $imageLink = '/storage/'.$this->getUserPath().'/'.$md5_file.'/'.'original'.'.'.$model->file->extension;

            }


            if($model->multi){
                $view = $this->renderPartial('/_gallery_item', [
                    'item' => ['image'=>$imageLink],
                    'field'=>$request['field']
                ]);
                return json_encode(['link'=>$imageLink,
                    'view' =>$view,

                ]);


            } else {
                $view = $this->renderPartial('/_one_item', [
                    'item' => ['image'=>$imageLink],
                    'field'=>$request['field']
                ]);
                return json_encode(['link'=>$imageLink,
                    'view' =>$view,
                ]);
            }


        }
    }


    public function getex($filename) {
        $array = explode(".", $filename);
        return array_pop($array);
    }


    public function actionImagesUpload(){

        if($_FILES['upload'])
        {
            if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name'])) )
            {
                $message = "Вы не выбрали файл";
            }
            else if ($_FILES['upload']["size"] == 0 OR $_FILES['upload']["size"] > 2050000)
            {
                $message = "Размер файла не соответствует нормам";
            }
            else if (($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png") AND ($_FILES['upload']['type'] != 'image/gif'))
            {
                $message = "Допускается загрузка только картинок JPG и PNG.";
            }
            else if (!is_uploaded_file($_FILES['upload']["tmp_name"]))
            {
                $message = "Что-то пошло не так. Попытайтесь загрузить файл ещё раз.";
            }
            else{
                $filename = $_FILES['upload']['name'];
                $name =$_FILES['upload']['name'].'.'.$this->getex($filename);

                $path = "../../storage/".$this->getUserPath()."/images/";
                if(!is_dir($path)) {
                    mkdir($path, 0755, true);
                }



                move_uploaded_file($_FILES['upload']['tmp_name'], $path.$name);

                $full_path = '/storage/'.$this->getUserPath().'/images/'.$name;

                $message = "Файл ".$_FILES['upload']['name']." загружен";


            }
            $callback = $_REQUEST['CKEditorFuncNum'];
            echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$callback.'", "'.$full_path.'", "'.$message.'" );</script>';
        }
    }


}