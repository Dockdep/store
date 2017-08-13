<?php
namespace frontend\widgets;

use yii\base\Widget;

class FilterWidget extends Widget
{

    public $category;
    public $groups;
    public $filter;
    public $priceLimits;
    public $filterWhitoutPrice;
    public $productProvider;


    public function init(){

        parent::init();

    }


    public function run()
    {

        return $this->render('_filter_view',[
            'category'=>$this->category,
            'groups'=>$this->groups,
            'filter'=>$this->filter,
            'priceLimits'=>$this->priceLimits,
            'productProvider' => $this->productProvider
        ]);

    }

}
?>
