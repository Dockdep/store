<?php

namespace console\controllers;

use artweb\artbox\models\Article;
use artweb\artbox\models\Seo;
use artweb\artbox\modules\catalog\models\Category;
use artweb\artbox\modules\catalog\models\Product;
use frontend\models\ProductFrontendSearch;
use Yii;
use artweb\artbox\models\Page;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\console\Controller;
/**
 * PageController implements the CRUD actions for Page model.
 */
class SiteMapController extends Controller
{

    private $urlList = ['http://www.rukzachok.com.ua/'];
    private $count = 1;



    public function checkFilter($category, $filter){
        $productModel = new ProductFrontendSearch();
        $productProvider = $productModel->search($category, $filter);
       if(!empty($productProvider->models)){
           return true;
       } else {
           return false;
       }
    }



    public function getAddStatic(){
        return [
            'http://www.rukzachok.com.ua',
            'http://www.rukzachok.com.ua/catalog'
        ];
    }


    public function getProducts() {
        return Product::find()->all();

    }


    public function getSeoLinks() {
        return Seo::find()->where(['meta' => ''])->all();

    }

    public function getStaticPages(){
        return Page::find()->all();
    }


    public function getCategories(){
        return Category::find()->all();
    }


    public function getArticles(){
        return Article::find()->all();
    }

    public function getBrands($category){

        return $category->brands;
    }

    /**
     * @param $category Category;
     * @return mixed
     */

    public function getFilters($category){

        return $category->getActiveFilters()->all();

    }


    public function checkUrl($url){
        if(!in_array($url, $this->urlList)){
            $this->urlList[] = $url;
            return true;
        } else {
            return false;
        }
    }


    public function createRow( $url, $priority, &$content ){
        if($this->checkUrl($url)){
            print $this->count++ . "\n";
            $content .= '<url>' .
                '<loc>' . $url . '</loc>' .
                '<lastmod>' . date('Y-m-d') . '</lastmod>' .
                '<changefreq>Daily</changefreq>' .
                '<priority>' . $priority .'</priority>' .
                '</url>';
        }
    }


    public function actionProcess() {

        $config = ArrayHelper::merge(
            require(__DIR__ . '/../../frontend/config/main.php'),
            require(__DIR__ . '/../../common/config/main.php')

        );

        Yii::$app->urlManager->addRules($config['components']['urlManager']['rules']);



        $dirName = Yii::getAlias('@frontend').'/web';

        $filename = 'sitemap.xml';

        setlocale(LC_ALL, 'ru_RU.CP1251');
        $handle = fopen($dirName .'/'. $filename, "w");

        $content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->getAddStatic() as $page) {
            $this->createRow($page , 1,$content);
        }

        foreach ($this->getStaticPages() as $page) {
            $url = Url::to(['text/index','translit' => $page->translit]);
            $this->createRow($url , 1,$content);
        }

        foreach ($this->getCategories() as $category) {
            $url = Url::to(['catalog/category', 'category' => $category]);
            $this->createRow($url , 1,$content);
        }


        foreach ($this->getProducts() as $product) {

            $url = Url::to(['catalog/product', 'product' => $product]);
            $this->createRow($url , 0.9, $content);
        }


        foreach ($this->getArticles() as $article) {

            $url = Url::to(['articles/show', 'translit' => $article->translit, 'id' => $article->id,]);
            $this->createRow($url , 0.8,$content);

        }


        foreach($this->getCategories() as $category){
            foreach ($this->getBrands($category) as $brand) {
                if($this->checkFilter($category, ['brands' => [$brand->id]])){
                    $url = Url::to(['catalog/category', 'category' => $category, 'filters' => ['brands' => [$brand->alias]]]) ;
                    $this->createRow($url , 0.8, $content);
                }
            }
        }


        foreach($this->getCategories() as $category){
            foreach ($this->getFilters($category) as $filter) {
                if($this->checkFilter($category, [$filter['group_alias'] => [$filter['option_alias']]])){
                    $url = Url::to(['catalog/category', 'category' => $category, 'filters' => [$filter['group_alias'] => [$filter['option_alias']]] ]);
                    $this->createRow($url , 0.8, $content);
                }

            }
        }

        foreach($this->getSeoLinks() as $link){
            $url = Yii::$app->urlManager->baseUrl.$link->url;
            $this->createRow($url , 0.7, $content);

        }



//        foreach($this->getCategories() as $category){
//            foreach ($this->getFilters($category) as $filter1) {
//                foreach ($this->getFilters($category) as $filter2) {
//                    if($this->checkFilter($category, [$filter1['group_alias'] => [$filter1['option_alias']],$filter2['group_alias'] => [$filter2['option_alias']]] )){
//                        $url = Url::to(['catalog/category', 'category' => $category, 'filters' => [$filter1['group_alias'] => [$filter1['option_alias']],$filter2['group_alias'] => [$filter2['option_alias']]] ]);
//                        $this->createRow($url , 0.7, $content);
//                    }
//
//                }
//
//                foreach ($this->getBrands($category) as $brand) {
//                    if($this->checkFilter($category, ['brands' => [$brand->id], $filter1['group_alias'] =>  [$filter1['option_alias']]] )){
//                        $url = Url::to(['catalog/category', 'category' => $category, 'filters' => ['brands' => [$brand->alias],$filter1['group_alias'] => [$filter1['option_alias']]]]);
//                        $this->createRow($url , 0.7,$content);
//                    }
//
//                }
//            }
//        }



        $content .= '</urlset>';

        fwrite($handle, $content);
        fclose($handle);

        print $dirName .'/'. $filename;
    }

}
