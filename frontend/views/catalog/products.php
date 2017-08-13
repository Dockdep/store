<?php
    /**
     * @var            $productProvider \yii\data\ActiveDataProvider
     * @var View       $this
     * @var Category   $category
     * @var Category[] $siblings
     */
    use artweb\artbox\ecommerce\models\Category;
    use frontend\widgets\FilterWidget;
    use artweb\artbox\seo\widgets\Seo;
    use yii\bootstrap\Html;
    use yii\web\View;
    use yii\widgets\LinkSorter;
    use yii\widgets\ListView;
    use yii\widgets\Pjax;
    
    $this->registerCssFile(Yii::getAlias('@web/css/ion.rangeSlider.css'));
    $this->registerCssFile(Yii::getAlias('@web/css/ion.rangeSlider.skinHTML5.css'));
    $this->registerJsFile(
        Yii::getAlias('@web/js/ion.rangeSlider.js'),
        [
            'position' => View::POS_END,
            'depends'  => [ 'yii\web\JqueryAsset' ],
        ]
    );
    $this->title = !empty( $category->lang->meta_title ) ? $category->lang->meta_title : $category->lang->title;

    $this->params[ 'seo' ][ 'fields' ]['name'] = $category->lang->title;
    $this->params[ 'seo' ][ 'h1' ] = !empty( $category->lang->h1 ) ? $category->lang->h1 : $category->lang->title;
    $this->params[ 'seo' ][ 'title' ] = !empty( $category->lang->meta_title ) ? $category->lang->meta_title : '';
    $this->params[ 'seo' ][ 'seo_text' ] = $category->lang->seo_text;
    $this->params[ 'seo' ][ 'description' ] = $category->lang->meta_description;
    $this->params[ 'seo' ][ 'meta' ] = $category->lang->meta_robots;
    $this->params[ 'seo' ][ 'category_name' ] = $category->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Html::tag(
            'span',
            $category->lang->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];

    if(empty($productProvider->models)){
        $this->params['seo']['meta'] = 'noindex,nofollow';
    }else{
        $this->params['seo']['meta'] = $category->lang->meta_robots;
    }


    $this->registerCssFile(Yii::getAlias('@web/css/ion.rangeSlider.css'));
    $this->registerCssFile(Yii::getAlias('@web/css/ion.rangeSlider.skinHTML5.css'));
    $this->registerJsFile(
        Yii::getAlias('@web/js/ion.rangeSlider.js'),
        [
            'position' => View::POS_END,
            'depends'  => [ 'yii\web\JqueryAsset' ],
        ]
    );
    $this->registerJsFile(
        Yii::getAlias('@web/js/filters.js'),
        [
            'position' => View::POS_END,
            'depends'  => [ 'yii\web\JqueryAsset' ],
        ]
    );

?>
<?php
    Pjax::begin(
        [
            'timeout' => 4000,
            'id'      => 'list-container',
            'scrollTo' => 0
        ]
    )
?>
<div class="container">
    <div class="block-25" style="position: relative;">
        
        <div class="clearfix"></div>
        <div class="columnLeftInfo">
            <!-- del_columnLeftInfo -->
            <!-- del_columnLeftInfo_end -->
        </div>
    </div>
    <div class="block-75">
        <div class="catalog_product_list view_table">
            
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- Верстка -->
<div class="container">
    <div class="row">
        <?= FilterWidget::widget(
            [
                'category'    => $category,
                'groups'      => $groups,
                'filter'      => $filter,
                'priceLimits' => $priceLimits,
                'productProvider' => $productProvider
            ]
        ) ?>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9" itemscope itemtype="http://schema.org/Product">
            <div class="row">
                <div class="col-xs-12 title_cat-main">
                    <h1 class="title" itemprop="name"><?= Seo::widget([ 'row' => 'h1' ]) ?></h1>
                </div>
                <div itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
                    <meta itemprop="priceCurrency" content="UAH" />
                    <meta itemprop="lowPrice" content="<?=$priceLimits['min']?>"/>
                    <meta itemprop="highPrice" content="<?=$priceLimits['max']?>"/>
                </div>
                <?php
                    /* Вывод братьев/сестер */
                ?>
                <div class="col-xs-12 tags-cat-wr">
                    <?php
                        echo Html::a($category->lang->title, ['', 'category' => $category->lang->alias], [
                            'class' => 'disabled',
                        ]);
                        foreach ($siblings as $sibling) {
                            echo Html::a($sibling->lang->title, [
                                'catalog/category',
                                'category' => $sibling->lang->alias,
                            ]);
                        }
                    ?>
                </div>
                
                <div class="col-xs-12 sort-cat-wr">
                    <p>Cортировка: </p>
                    <div class="sort-cat">
                        <?php
                            $order = array_keys($productProvider->sort->attributes)[ 0 ];
                            $order_url = \Yii::$app->request->get($productProvider->sort->sortParam, $order);
                            if (strpos($order_url, '-') === 0) {
                                $order_url = substr($order_url, 1);
                            }
                            if(in_array($order_url, array_keys($productProvider->sort->attributes))) {
                                $order = $order_url;
                            }
                            echo Html::a(
                                $productProvider->sort->attributes[ $order ][ 'label' ],
                                '#',
                                [
                                    'id' => 'category-sort',
                                ]
                            );
                        ?>
                        <?= LinkSorter::widget(
                            [
                                'sort'       => $productProvider->sort,
                                'attributes' => [
                                    'price_desc',
                                    'price_asc',
                                    'name_asc',
                                    'name_desc',
                                ],
                            ]
                        ); ?>
                    </div>
                </div>
                <?= ListView::widget(
                    [
                        'dataProvider' => $productProvider,
                        'layout'       => "{items} " . $this->render('_load_more') . " {pager}",
                        'options'      => [
                            'class' => 'list-view style catalog-wrapp-all',
                        ],
                        'itemOptions'  => [
                            'class' => 'col-sm-4 catalog-wr',
                        ],
                        'itemView'     => '_product_item',
                        'pager' => [
                            'maxButtonCount' => 5,
                        ],
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end() ?>
<!--<div class="banners-forms" style="display: none">-->
<!--    <div class="banners">-->
<!--        <div class="col-xs-12 col-sm-4 banner_">-->
<!--            <!--размер баннера - (223x370)-->
<!--            <div>-->
<!--                <!--                <a href="#"><img src="images/uploads/img-4.jpg" alt=""></a>-->
<!--                -->
<!--                -->
<!--                <!--размер баннера (223x370)-->
<!--                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="223" height="370" id="wanka" align="middle">-->
<!--                    <param name="allowScriptAccess" value="sameDomain"/>-->
<!--                    <param name="allowFullScreen" value="false"/>-->
<!--                    <param name="movie" value="http://extremstyle.ua/uploaded/pic/banners/1426606529.swf"/>-->
<!--                    <param name="quality" value="high"/>-->
<!--                    <param name="autoStart" value="true"/>-->
<!--                    <param name="bgcolor" value="#ffffff"/>-->
<!--                    <embed src="http://extremstyle.ua/uploaded/pic/banners/1426606529.swf" quality="high" wmode="transparent" bgcolor="#ffffff" width="223" height="370" name="wanka" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"/>-->
<!--                    <param name="wmode" value="transparent">-->
<!--                </object>-->
<!--            -->
<!--            -->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->