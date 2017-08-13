<?php
/**
 * @var      $productProvider \yii\data\ArrayDataProvider
 * @var View $this
 */
use artweb\artbox\seo\widgets\Seo;
use frontend\widgets\FilterWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\LinkSorter;
use yii\widgets\ListView;
use yii\widgets\Pjax;
    
    $conversions = <<< JS
var google_tag_params = {
    dynx_pagetype: "searchresults"
};
/* <![CDATA[ */
    var google_conversion_id = 881201405;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
JS;
    
    $this->registerJs($conversions, View::POS_BEGIN);

$this->params[ 'seo' ][ 'title' ] = \Yii::t('app', 'search');

$this->params[ 'seo' ][ 'h1' ] = \Yii::t('app', 'search');

$this->params[ 'breadcrumbs' ][] = [
    'label'    => Html::tag(
        'span',
        \Yii::t('app', 'search'),
        [
            'itemprop' => 'name',
        ]
    ),
    'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
];
?>

<?php
Pjax::begin(
    [
        'timeout'  => 20000,
        'id'       => 'list-container',
        'scrollTo' => false,
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
    <div class="block-75" itemscope itemtype="http://schema.org/Product">
        <div class="catalog_product_list view_table">

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- Верстка -->
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 title_cat-main">
                    <h1 class="title"><?= Seo::widget([ 'row' => 'h1' ]) ?></h1>
                </div>


                <?= ListView::widget(
                    [
                        'dataProvider' => $productProvider,
                        'summary' => false,
                        'options'      => [
                            'class' => 'list-view style catalog-wrapp-all',
                        ],
                        'itemOptions'  => [
                            'class' => 'col-sm-3 catalog-wr',
                        ],
                        'itemView'     => '../search/_product_item',
                    ]
                ) ?>
                <?php
                    echo \yii\widgets\LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                ?>
            </div>
        </div>
    </div>
</div>


<?php Pjax::end() ?>


