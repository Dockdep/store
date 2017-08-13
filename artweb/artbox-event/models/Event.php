<?php

namespace artweb\artbox\event\models;

use artweb\artbox\behaviors\SaveImgBehavior;
use artweb\artbox\ecommerce\models\Product;
use artweb\artbox\ecommerce\models\ProductVariant;
use artweb\artbox\language\behaviors\LanguageBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\web\Request;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $end_at
 * @property integer $status
 * @property integer $is_sale
 * @property integer $is_event
 * @property integer $percent
 * @property integer $banner
 * @property integer $type
 * * From language behavior *
 * @property EventLang   $lang
 * @property EventLang[] $langs
 * @property EventLang   $objectLang
 * @property string      $ownerKey
 * @property string      $langKey
 * @property EventLang[] $modelLangs
 * @property bool        $transactionStatus
 * @method string           getOwnerKey()
 * @method void             setOwnerKey( string $value )
 * @method string           getLangKey()
 * @method void             setLangKey( string $value )
 * @method ActiveQuery      getLangs()
 * @method ActiveQuery      getLang( integer $language_id )
 * @method EventLang[]    generateLangs()
 * @method void             loadLangs( Request $request )
 * @method bool             linkLangs()
 * @method bool             saveLangs()
 * @method bool             getTransactionStatus()
 * * End language behavior *
 * * From SaveImgBehavior
 * @property string|null $imageFile
 * @property string|null $imageUrl
 * @method string|null getImageFile( int $field )
 * @method string|null getImageUrl( int $field )
 * * End SaveImgBehavior
 *
 */
class Event extends \yii\db\ActiveRecord
{
    public $imageUpload;
    public $products_file;
    const ACTIVE = 1;
    const INACTIVE = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }




    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'img'=>[
                'class'     => SaveImgBehavior::className(),
                'fields' => [
                    ['name'=>'image','directory' => 'event' ],
                    ['name'=>'banner','directory' => 'event' ],
                ]
            ],
            TimestampBehavior::className(),
            'language' => [
                'class' => LanguageBehavior::className(),
                'objectLang' => EventLang::className()
            ],
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->end_at = !empty($this->end_at) ? (string)strtotime($this->end_at) : '';
            return true;
        }
        return false;

    }

    public function afterFind(){
        $this->end_at = !empty($this->end_at) ? date("Y-m-d", $this->end_at) : '';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at','percent','status','is_sale','is_event','percent' ], 'integer'],
            [['image', 'end_at','banner'], 'string', 'max' => 255],
            [['imageUpload','is_sale','is_event'], 'safe'],
            [['imageUpload'], 'file', 'extensions' => 'jpg, gif, png'],
            [['products_file'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID акции'),
            'name' => Yii::t('app', 'name'),
            'alias' => Yii::t('app', 'alias'),
            'body' => Yii::t('app', 'body'),
            'image' => Yii::t('app', 'image'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'description' => Yii::t('app', 'description'),
            'h1' => Yii::t('app', 'h1'),
            'seo_text' => Yii::t('app', 'seo_text'),
            'created_at' => Yii::t('app', 'created_at'),
            'updated_at' => Yii::t('app', 'updated_at'),
            'end_at' => Yii::t('app', 'end_at'),
            'status' => Yii::t('app', 'Статус акции'),
            'products_file' => Yii::t('app', 'Загрузка файла'),
            'is_sale' => Yii::t('app', 'Распродажа'),
            'percent' => Yii::t('app', 'Процент'),
            'is_event' => Yii::t('app', 'Акция'),
        ];
    }


    public function isActive(){
        if($this->status){

            if(!empty($this->end_at) && (strtotime($this->end_at) <= strtotime(date("Y-m-d")))){
                return false;
            }
            return true;
        }
        return false;
    }


    public function getType(){
        if($this->is_event){
            return "promo";
        } else if($this->is_sale){
            return "sale";
        } else {
            return "promo";
        }
    }


    public function goEvent($file) {

        set_time_limit(0);


        $handle =  fopen($file, 'r');


        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if(isset($data[0])){
                $product = ProductVariant::find()->where(['sku' => $data[0]])->joinWith('product')->one();
                if($product instanceof  ProductVariant){
                    $model= EventsToProducts::find()->where(['event_id' =>$this->id, 'product_id' => $product->product->id ])->one();
                    if(!$model instanceof EventsToProducts){
                        $model =  new EventsToProducts;
                        $model->event_id = $this->id;
                        $model->product_id = $product->product->id;
                        $model->save();
                    }
                }
            }

        }
        fclose($handle);
        unlink($file);

    }

    public function getProducts(){
        return $this->hasMany(Product::className(),['id' => 'product_id'] )->viaTable('events_to_products', ['event_id' => 'id']);
    }

    public static function getSaleEvents(){
        return ArrayHelper::toArray(self::find()->select('percent')->distinct('percent')->where('is_sale=true AND percent IS NOT NULL')->orderBy('percent')->all());
    }

}
