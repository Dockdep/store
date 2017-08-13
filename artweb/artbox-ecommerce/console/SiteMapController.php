<?php
    
    namespace artweb\artbox\ecommerce\console;
    
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\language\components\LanguageUrlManager;
    use artweb\artbox\language\models\Language;
    use artweb\artbox\seo\models\Seo;
    use artweb\artbox\ecommerce\models\Category;
    use Yii;
    use artweb\artbox\models\Page;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Console;
    use yii\helpers\Url;
    use yii\console\Controller;
    
    class SiteMapController extends Controller
    {
        
        private $urlList = [ 'https://extremstyle.ua/ru' ];
        private $count = 1;
        public $fileName;
        public $handle;
        public $mapNumber = 1;
        public $mainMap = '';
        public $content = '';
        
        public function getAddStatic()
        {
            return [
                Yii::$app->urlManager->baseUrl.'/ru',
            ];
        }


        public function getHost(){
            return Yii::$app->urlManager->baseUrl.'/ru';
        }
        
        public function getVariants()
        {
            return ProductVariant::find()
                                 ->with('lang')
                                 ->with('product.lang');
            
        }
        
        public function getSeoLinks()
        {
            return Seo::find()
                      ->where(['!=', 'meta', 'noindex,nofollow' ])
                      ->all();
            
        }
        
        public function getStaticPages()
        {
            return Page::find()
                       ->with('lang')
                       ->all();
        }
        
        public function getCategories()
        {
            return Category::find()
                           ->with('lang')
                            ->where(['!=', 'parent_id', 0 ])
                           ->all();
        }
        
        public function getCategoriesWithFilters()
        {
            return Category::find()
                           ->with('lang')
                           ->joinWith('taxGroups.lang')
                           ->with('taxGroups.taxOptions.lang')
                            ->where(['!=', 'parent_id', 0 ])
                           ->andWhere(['!=', 'tax_group.meta_robots', 'noindex,nofollow' ])
                           ->andWhere(['!=', 'tax_group.meta_robots', 'noindex, nofollow' ])
                           ->andWhere(['tax_group.is_filter'=>true ])
                           ->all();
        }
        
        public function getBrands()
        {
            return Brand::find()
                        ->joinWith('lang')
                        ->all();
        }
        
        public function checkUrl($url)
        {
            if (!in_array($url, $this->urlList)) {
                $this->urlList[] = $url;
                return true;
            } else {
                return false;
            }
        }
        
        public function createRow($url, $priority)
        {
//            if ($this->checkUrl($url)) {
            if($this->count % 500 == 0) {
                $this->stdout($this->count . " : ", Console::BOLD);
                $this->stdout($url . "\n", Console::FG_YELLOW);
            }
                $this->content .= '<url>' . '<loc>' . $url . '</loc>' . '<lastmod>' . date(
                        'Y-m-d'
                    ) . '</lastmod>' . '<changefreq>Weekly</changefreq>' . '<priority>' . $priority . '</priority>' . '</url>';
                $this->count++;
                if ($this->count % 10000 == 0) {
                    $this->content .= '</urlset>';
                    $this->stdout('Added unset' . "\n", Console::FG_CYAN);
                    fwrite($this->handle, $this->content);
                    fclose($this->handle);
                    $this->mapNumber++;
                    
                    $this->mainMap .= '<sitemap>'.
                        '<loc>https://extremstyle.ua/' . $this->fileName . '</loc>'.
                        '<lastmod>' . date('Y-m-d') . '</lastmod>'.
                        '</sitemap>';
                    
                    $this->fileName = 'sitemap' . $this->mapNumber . '.xml';
                    $this->handle = fopen(Yii::getAlias('@frontend') . '/web' . '/' . $this->fileName, "w");
                    
                    $this->content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                }
//            }
        }
        
        public function actionProcess()
        {

            Language::setCurrent('ru');


            $config = ArrayHelper::merge(
                require( \Yii::getAlias('@frontend/config/') . 'main.php' ),
                require( \Yii::getAlias('@common/config/') . 'main.php' ),
                ['components'=>['urlManager'=>['hostInfo'=>'https://extremstyle.ua']]]
            );

            if(isset($config['components']['urlManager']['class'])){
                unset($config['components']['urlManager']['class']);
            }
            //Yii::$app->urlManager = new LanguageUrlManager($config['components']['urlManager']);

            $urlManager =  new LanguageUrlManager($config['components']['urlManager']);

            $this->mainMap = '<?xml version="1.0" encoding="UTF-8"?>';
            $this->mainMap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $this->fileName = 'sitemap' . $this->mapNumber . '.xml';
            setlocale(LC_ALL, 'ru_RU.CP1251');
            $this->handle = fopen(Yii::getAlias('@frontend') . '/web' . '/' . $this->fileName, "w");



            $this->content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            
            foreach ($this->getAddStatic() as $page) {
                $this->createRow($page, 1);
            }
            
            foreach ($this->getStaticPages() as $page) {
                $url = $urlManager->createAbsoluteUrl(
                    [
                        'site/page',
                        'slug' => $page->lang->alias,
                    ]
                );
                $this->createRow($url, 1);
            }
            
            foreach ($this->getCategories() as $category) {
                $url =  $urlManager->createAbsoluteUrl(
                    [
                        'catalog/category',
                        'category' => $category->lang->alias,
                    ]
                );
                $this->createRow($url, 0.8);
            }
            
            foreach ($this->getVariants()->batch(1000) as $rows) {
                foreach ($rows as $row) {
                    if(!preg_match("@^[a-zA-Z\d]+$@i", $row->sku)) {
                        continue;
                    }
                    $url =  $urlManager->createAbsoluteUrl(
                        [
                            'catalog/product',
                            'product' => $row->product->lang->alias,
                            'variant' => $row->sku,
                        ]
                    );
                    $this->createRow($url, 0.9);
                }
            }
            
            foreach ($this->getBrands() as $brand) {
                
                $url = $urlManager->createAbsoluteUrl(
                    [
                        'brand/view',
                        'slug' => $brand->lang->alias,
                    ]
                );
                $this->createRow($url, 0.7);
                
            }

            foreach ($this->getCategoriesWithFilters() as $category) {
                foreach ($category->taxGroups as $group) {
                    if($group->meta_robots == 'noindex,nofollow') {
                        continue;
                    }
                    if($group->is_filter){
                        foreach ($group->options as $option) {
                            $url =  $urlManager->createAbsoluteUrl(
                                [
                                    'catalog/category',
                                    'category' => $category,
                                    'filters'  => [ $group->lang->alias => [ $option->lang->alias ] ],
                                ]
                            );
                            $this->createRow($url, 0.8);
                        }
                    }



                    
                }
            }
            
            foreach ($this->getSeoLinks() as $link) {
                $url = Yii::$app->urlManager->baseUrl . $link->url;
                $this->createRow($url, 0.7);
                
            }
            
            $this->content .= '</urlset>';
            
            fwrite($this->handle, $this->content);
            fclose($this->handle);
    
            $this->mainMap .= '<sitemap>'.
                '<loc>'.'https://extremstyle.ua/'. $this->fileName . '</loc>'.
                '<lastmod>' . date('Y-m-d') . '</lastmod>'.
                '</sitemap>'.
                '</sitemapindex>';
            
            $mainHandle = fopen(Yii::getAlias('@frontend') . '/web/sitemap.xml', "w");
            fwrite($mainHandle, $this->mainMap);
            fclose($mainHandle);
            
            $this->stdout(Yii::getAlias('@frontend') . '/web' . '/' . $this->fileName . "\n", Console::FG_GREEN);
        }
        
    }
