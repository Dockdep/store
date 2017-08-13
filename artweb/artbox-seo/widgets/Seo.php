<?php
namespace artweb\artbox\seo\widgets;

use artweb\artbox\seo\models\SeoDynamic;
use artweb\artbox\ecommerce\models\Brand;
use artweb\artbox\ecommerce\models\TaxGroup;
use artweb\artbox\ecommerce\models\TaxOption;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class Seo extends Widget
{
    private $url;
    public $row;
    public $own_attr;
    public $fields;
    public $category_name;
    public $description;
    public $title;
    public $meta;
    public $seo_text;
    public $h1;
    public $key;
    public $name;
    public $project_name;
    public static $optionsList;
    protected static $check_url;
    protected static $check_url_bool;


    const SEO_TEXT = 'seo_text';
    const DESCRIPTION = 'description';
    const META = 'meta';
    const H1 = 'h1';
    const TITLE = 'title';

    public function init()
    {
        $this->url = \Yii::$app->request->url;
        $this->project_name = \Yii::$app->name;
        if(empty(self::$optionsList)){
            self::$optionsList = ArrayHelper::getColumn(TaxGroup::find()->joinWith('lang')->where(['is_filter' => 'TRUE'])->asArray()->all(),'lang.alias');
        }

        parent::init();

    }

    /**
     * @return mixed
     */
    public function run()
    {

        $seoData = $this->getViewData();
        foreach ($seoData as $key => $value) {
            $this->$key = $value;
        }


        switch ($this->row) {
            case self::SEO_TEXT:


                $filter = \Yii::$app->request->get('filters', []);
                $sort = \Yii::$app->request->get('sort', []);
                $paginate = \Yii::$app->request->get('page', []);

                if(empty($filter) && empty($sort) && empty($paginate) ){

                    return $this->prepareString($this->selectSeoData(self::SEO_TEXT,$filter,$priority));

                } else {

                    $widgetData = static::findSeoByUrl($this->url);

                    $result = '';

                    if ($widgetData instanceof \artweb\artbox\seo\models\Seo) {

                        $result = $widgetData->{self::SEO_TEXT};

                    } else {

                        $widgetData = $this->findSeoByDynamic();

                        if ($widgetData instanceof SeoDynamic) {

                            $result = $widgetData->lang->{self::SEO_TEXT};

                        }

                    }

                    return $this->prepareString($this->replaceData($result));
                }


                break;
            case self::H1:

                $filter = \Yii::$app->request->get('filters', []);

                $default = $this->selectSeoData(self::H1, $filter,$priority);
                if ($default != $this->{self::H1} && $priority != 3) {

                    return $this->prepareString($default);


                } else if(!empty($filter) && !$this->checkFilter($filter)){
                    $array = $this->arrayBuilder($filter);
                    return $this->prepareString($this->getNameString($array,$default));
                }
                else {
                    return $this->prepareString($default);
                }
                break;
            case self::TITLE:

                $filter = \Yii::$app->request->get('filters', []);


                $title = $this->selectSeoData(self::TITLE, $filter, $priority);


                if(!empty($filter) &&  $priority==3 || !empty($filter) && empty($this->{Seo::TITLE})) {

                    $array = $this->arrayBuilder($filter);

                    $title_string = $this->getTitleString($array, $title);

                    if($title_string){
                        return $this->prepareString($title_string);
                    }

                }


                if (!empty($title)) {
                    return $this->prepareString($title);
                } else  {
                    return $this->prepareString($this->project_name);
                }
                break;
            case self::DESCRIPTION:

                $filter = \Yii::$app->request->get('filters', []);

                $description = $this->selectSeoData(self::DESCRIPTION, $filter,$priority);

                if (!empty($description) && $priority!=3 ) {

                    $this->getView()->registerMetaTag([
                        'name' => 'description',
                        'content' => $this->prepareString($description)
                    ]);

                } else {

                    if(!empty($filter)){
                        $array = $this->arrayBuilder($filter);
                        $this->getView()->registerMetaTag([
                            'name' => 'description',
                            'content' => $this->prepareString($this->getDescriptionString($array,$description))
                        ]);
                    }

                }

                break;
            case self::META:


                $filter = \Yii::$app->request->get('filters', []);
                $sort = \Yii::$app->request->get('sort', []);
                $paginate = \Yii::$app->request->get('page', []);

                $meta = $this->selectSeoData(self::META, $filter,$priority);

                if(!empty($filter) && !$this->checkFilter($filter) && count($filter, COUNT_RECURSIVE) == 2 && $priority==3){
                    $key = array_keys ( $filter);
                    if(isset($key[0])){
                        $metaGroup = TaxGroup::find()->joinWith('lang')->where(['alias'=>$key[0]])->one();
                        if($metaGroup != null && !empty($metaGroup->meta_robots)){
                            $this->getView()->registerMetaTag([
                                'name' => 'robots',
                                'content' => $metaGroup->meta_robots
                            ]);
                            break;
                        }
                    }
                }


                if (!empty($meta) && empty($sort) &&  empty($paginate) && !isset($filter['prices']) ) {

                    $this->getView()->registerMetaTag([
                        'name' => 'robots',
                        'content' => $meta
                    ]);

                } else if(!empty($filter['special'])){

                    $this->getView()->registerMetaTag([
                        'name' => 'robots',
                        'content' => 'noindex,nofollow'
                    ]);

                } else if (
                    isset($filter['brands']) && count($filter['brands']) > 1
                    || isset($filter)  && $this->checkFilter($filter)

                ) {

                    $this->getView()->registerMetaTag([
                        'name' => 'robots',
                        'content' => 'noindex,nofollow'
                    ]);

                } else if (
                    isset($filter['brands']) && count($filter['brands']) > 1 && isset($filter) && count($filter, COUNT_RECURSIVE) >= 2
                    || isset($filter) && count($filter, COUNT_RECURSIVE) > 2
                    || !empty($sort) ||  !empty($paginate) || isset($filter['prices'])
                ) {

                    $this->getView()->registerMetaTag([
                        'name' => 'robots',
                        'content' => 'noindex,nofollow'
                    ]);
                } else if(!empty($this->{Seo::META})){

                    $this->getView()->registerMetaTag([
                        'name' => 'robots',
                        'content' => $this->{Seo::META}
                    ]);
                } else {

                    $this->getView()->registerMetaTag([
                        'name' => 'robots',
                        'content' => 'index,follow'
                    ]);
                }




                break;
        }


    }

    /**
     * @param $str
     * @return mixed
     */
    protected function replaceData($str)
    {

        if (!empty($this->fields)) {
            foreach ($this->fields as $field_name => $field_value) {
                $str = str_replace('{' . $field_name . '}', $field_value, $str);
            }
        }
        $str = str_replace('{project_name}', $this->project_name, $str);
        return $str;
    }

    /**
     * @param $url
     * @return static
     */
    protected static function findSeoByUrl($url)
    {
        if(empty(self::$check_url_bool)){
            self::$check_url = \artweb\artbox\seo\models\Seo::findOne(['url' => $url]);
            self::$check_url_bool = true;
        }
        return self::$check_url;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    protected function findSeoByDynamic()
    {

//        print_r(\Yii::$app->controller->id);
//        print_r(\Yii::$app->controller->action->id);
//        die();
        if(!empty($this->key)){

            $query = SeoDynamic::find()->joinWith('seoCategory')->where(['controller' => \Yii::$app->controller->id, 'action' => \Yii::$app->controller->action->id, 'key' => $this->key]);
        } else {


            $query = SeoDynamic::find()->joinWith('seoCategory')->where(['controller' => \Yii::$app->controller->id, 'action' => \Yii::$app->controller->action->id]);
        }

        return $query->one();
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    protected function findSeoByDynamicForFilters(){
        return SeoDynamic::find()->joinWith('seoCategory')->where(['param' =>'filters'])->one();
    }

    /**
     * @return array
     */
    protected function getViewData()
    {
        $params = $this->getView()->params;
        if (isset($params['seo'])) {
            return $params['seo'];
        } else {
            return [];
        }
    }


    /**
     * @param $param
     * @param $filter
     * @param $priority
     * @return mixed
     */
    protected function selectSeoData($param, $filter, &$priority)
    {
        $result = '';

        $widgetData = static::findSeoByUrl($this->url);

        $widgetDynamicData = $this->findSeoByDynamic();

        if ($widgetData instanceof \artweb\artbox\seo\models\Seo) {
            $priority = 1;
            $result = $widgetData->$param;

        }else if(!empty($this->$param) && empty($filter)) {
            $priority = 2;
            $result = $this->$param;

        } else if ($widgetDynamicData instanceof SeoDynamic) {
            $priority = 3;
            $result = $widgetDynamicData->lang->$param;

        }

        return $this->replaceData($result);

    }

    /**
     * @param $array
     * @param $title
     * @return mixed
     */
    public function getTitleString($array, $title){

        $row = '';
        foreach($array as $name => $field){
            $row .= $field['value'].' '  ;
        }

        $template =  preg_replace('/{filter}/',$row,$title);

        return $template;

    }

    /**
     * @param $array
     * @param $description
     * @return mixed
     */
    public function getDescriptionString($array, $description){

        $row = '';
        foreach($array as $name => $field){
            $row .= $field['value'].' '  ;
        }
        $template =  preg_replace('/{filter}/',$row, $description);

        return $template;

    }


    /**
     * @param $array
     * @param $h1
     * @return mixed
     */
    public function getNameString($array, $h1){

        $row = ' ';
        foreach($array as $name => $field){
            $row .= $field['value'].' '  ;
        }

        $template =  preg_replace('/{filter}/',$row, $h1 );

        return $template;

    }

    /**
     * @param $filter
     * @return mixed
     */
    public function arrayBuilder($filter)
    {
        $array = [];
            if (isset($filter['brands']) && count($filter['brands']) == 1) {
                $model = Brand::find()->joinWith('lang')->where(['alias' => $filter['brands'][0]])->one();
                if (!$model instanceof Brand) {

                    \Yii::$app->response->redirect(['/site/error'], 404);
                } else {
                    $array['brand']['name'] = 'Бренд';
                    $array['brand']['value'] = $model->lang->title;
                }

            }


            $optionsList = ArrayHelper::map(TaxGroup::find()->joinWith('lang')->where(['is_filter' => 'TRUE'])->asArray()->all(), 'lang.alias', 'name');


            foreach ($optionsList as $optionList => $name) {


                if (isset($filter[$optionList]) && count($filter[$optionList]) == 1) {

                    $model = TaxOption::find()->joinWith('lang')->where(['alias' => $filter[$optionList]])->one();
                    if (!$model instanceof TaxOption) {

                        \Yii::$app->response->redirect(['site/error'], 404);
                    } else {
                        $array[$optionList]['value'] = $model->lang->value;
                        $array[$optionList]['name'] = $name;
                    }


                }


            }

        return $array;

    }

    /**
     * @param $filter
     * @return bool
     */
    protected function checkFilter($filter){
        foreach(self::$optionsList as $optionList){

            if(isset($filter[$optionList]) && count($filter[$optionList])  > 1){
                return true;
            }

        }
        return false;
    }

    /**
     * @param $string
     * @return mixed
     */
    public function prepareString($string){
       return preg_replace('/\{.[^\}]*\}/','',$string);
    }

}