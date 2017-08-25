<?php
namespace frontend\controllers;

use artweb\artbox\ecommerce\models\Brand;
use artweb\artbox\ecommerce\models\Category;
use artweb\artbox\ecommerce\models\Product;
use artweb\artbox\ecommerce\models\ProductCategory;
use artweb\artbox\ecommerce\models\ProductImage;
use artweb\artbox\ecommerce\models\ProductVariant;
use yii\base\Controller;
use yii\base\Exception;

class IntegrationController extends Controller{

    public $result = [];
    public function getItemData(){
      return '  [  
      {
    "status": false,
    "jan": "",
    "storage_prices": "",
    "product_id": "",
    "sku": "5.45300",
    "image": "ae8f4465-57df-11e7-8043-305a3a578d98.jpg",
    "category_id": "000009701",
    "category": [
      {
        "status": false,
        "category_id": "000029984",
        "parent_id": "         ",
        "name": "СИСТЕМА ОПАЛЕННЯ / КОНДИЦІОНЕР / КТ.",
        "image": "ae8f4465-57df-11e7-8043-305a3a578d98.jpg"
      },
      {
        "status": false,
        "category_id": "000009701",
        "parent_id": "000029984",
        "name": "РАДИАТОР",
        "image": "ae8f4465-57df-11e7-8043-305a3a578d98.jpg"
      }
    ],
    "price": "10860.72",
    "manufacturer": {
      "manufacturer_id": "000000025",
      "name": "DANIPARTS",
      "image": ""
    },
    "ean": "",
    "upc": "",
    "model": "000051565",
    "quantity": 0,
    "name": "Радіатор 211-DF9550-01"
  },
  {
    "status": false,
    "jan": "",
    "storage_prices": "",
    "product_id": "",
    "sku": "9700519612",
    "image": "42fad13b-57e0-11e7-8043-305a3a578d98.jpg",
    "category_id": "000047029",
    "category": [
      {
        "status": false,
        "category_id": "000000480",
        "parent_id": "         ",
        "name": "ПНЕВМОСИСТЕМА ",
        "image": "42fad13b-57e0-11e7-8043-305a3a578d98.jpg"
      },
      {
        "status": false,
        "category_id": "000047029",
        "parent_id": "000000480",
        "name": "РЕМ. / КТ. КРАНИ",
        "image": "42fad13b-57e0-11e7-8043-305a3a578d98.jpg"
      }
    ],
    "price": "2194.8",
    "manufacturer": {
      "manufacturer_id": "000000078",
      "name": "AIRFREN",
      "image": ""
    },
    "ean": "",
    "upc": "",
    "model": "000051566",
    "quantity": 0,
    "name": "РМК ПГУ 09.R051.414"
  }
  ]';
    }

    public  function actionImportProducts(){
        try{
            $data = $this->getItemData();
            $data = json_decode($data);
            if(is_array($data)){
                foreach ($data as $item){
                    $this->ImportProduct($item);
                }
            } else {
                throw new Exception("Данные о товарах ожидаются в виде массива.");
            }
            die(\GuzzleHttp\json_encode($this->result));

        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n", ' на строке ', $e->getLine(), "\n";
        }
    }

    public function ImportProduct($item){
        if(isset($item->category)){
            $item->category_id = $this->SaveCategories($item->category);
            unset($item->category);
        } else {
            throw new Exception("У товара {$item->model} не указаны категории");
        }

        if(isset($item->manufacturer)){
            $item->brand = $this->SaveBrand($item->manufacturer);
            unset($item->manufacturer);
        } else {
            throw new Exception("У товара {$item->model} не указан бренд");
        }

        $this->SaveItem($item);
    }

    private function SaveCategories($categories){
        $category_id = null;
        foreach ($categories as $category){
            $parent = null;
            if(!empty($category->parent_id)){
                $parent = $model = Category::find()->joinWith('lang')->where(["remote_id" => $category->parent_id])->one();
            }
            $model = Category::find()->joinWith('lang')->where(["remote_id" => $category->category_id])->one();
            if(!$model instanceof Category){
                $model = new Category();
                $model->generateLangs();
                foreach ($model->modelLangs as $category_lang) {
                    $category_lang->title = $category->name;
                }
            } else {
                $model->lang->title = $category->name;
            }
            $model->remote_id = $category->category_id;
            $model->status    = $category->status;
            $model->parent_id =  $parent ? $parent->id : 0;
            $model->image = $category->image;
            if(!$model->validate()){
                throw new Exception(print_r($model->getErrors()));
            }
            $model->save();
            $category_id = $model->id;
        }

        return $category_id;
    }

    private function SaveBrand($brand){
        $model = Brand::find()->joinWith('lang')->where(["remote_id" => $brand->manufacturer_id])->one();
        if(!$model instanceof Brand){
            $model = new Brand();
            $model->generateLangs();
            foreach ($model->modelLangs as $brand_lang) {
                $brand_lang->title = $brand->name;
            }
        } else {
            $model->lang->title = $brand->name;
        }
        $model->remote_id = $brand->manufacturer_id;
        $model->image = $brand->image;
        if(!$model->validate()){
            throw new Exception(print_r($model->getErrors()));
        }
        $model->save();

        return $model->id;
    }


    private function SaveItem($item){

        $model = Product::find()->joinWith('lang')->where(["remote_id" => $item->model])->one();

        $new_product = false;

        if(!$model instanceof Product){
            $new_product = true;
            $model = new Product();
            $model->generateLangs();
            foreach ($model->modelLangs as $lang) {
                $lang->title = $item->name;
            }
        } else {
            $model->lang->title = $item->name;
        }

        $model->remote_id = $item->model;
        $model->brand_id = $item->brand;
        if(!$model->validate()){
            throw new Exception(print_r($model->getErrors()));
        }
        $model->save();



        $variant_id = $this->SaveItemVariant($new_product, $model, $item);

        $this->SaveProductCategory($new_product, $model, $item);

        $this->SaveProductImage($new_product,$model,$item,$variant_id);

        $this->result[$item->model] = $model->id;

    }

    private function SaveProductCategory($new_product, $model, $item){
        if(!$new_product){
            $data = ProductCategory::find()
                ->where(["product_id"=>$model->id,
                    "category_id"=>$item->category_id])
                ->one();
            if($data instanceof ProductCategory){
                $data->delete();
            }
        }

        $category = new ProductCategory();
        $category->product_id = $model->id;
        $category->category_id = $item->category_id;
        if(!$category->validate()){
            throw new Exception(print_r($category->getErrors()));
        }
        $category->save();
    }

    private function SaveProductImage($new_product,$model,$item,$variant_id ){
        if($new_product || !isset($model->image)){
            $image = new ProductImage();
            $image->product_id = $model->id;
            $image->product_variant_id = $variant_id;
        } else {
            $image = $model->image;
        }
        $image->image = $item->image;
        if(!$image->validate()){
            throw new Exception(print_r($image->getErrors()));
        }
        $image->save();
    }

    private function SaveItemVariant($new_product, $model, $item){
        if($new_product){
            $variant = new ProductVariant();
            $variant->product_id = $model->id;
            $variant->generateLangs();
            foreach ($variant->modelLangs as $lang) {
                $lang->title = $item->name;
            }
        } else {
            $variant  = $model->variant;
            $variant->lang->title = $item->name;
        }

        $variant->sku = $item->sku;
        $variant->price = $item->price;
        $variant->remote_id = $item->model;
        $variant->stock = $item->quantity;
        if(!$variant->validate()){
            throw new Exception(print_r($variant->getErrors()));
        }
        $variant->save();
        return $variant->id;
    }
}