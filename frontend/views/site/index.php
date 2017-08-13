<?php
    /**
     * @var View           $this
     * @var Product[]      $top_items
     * @var Product[]      $new_items
     * @var Product[]      $discount_items
     * @var BlogArticle[]  $news
     * @var Brand[]        $brands
     * @var Category[]     $icon_categories
     * @var ProductVideo[] $videos
     * @var BlogArticle[]  $articles
     * @var Page[]         $pages
     * @var BlogCategory[] $blogs
     */
    use artweb\artbox\blog\models\BlogArticle;
    use artweb\artbox\blog\models\BlogCategory;
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVideo;
    use artweb\artbox\models\Page;
    use frontend\widgets\Slider;
    use yii\bootstrap\Html;
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
    use yii\web\View;
    
    $conversions = <<< JS
    var google_tag_params = {
    dynx_pagetype: "home"
};
/* <![CDATA[ */
    var google_conversion_id = 881201405;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
JS;
    
    $this->registerJs($conversions, View::POS_BEGIN);
?>
<div class="container">
    <div class="row">
        <div class="hidden-xs col-xs-1 col-sm-4 col-md-4 col-lg-3">
        
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
            <?php // Query count: 6 ?>
            <?= Slider::widget([ "title" => "Main-slider" ]); ?>
            
            
            <div class="row">
                <?php
                    if (!empty( $icon_categories )) {
                        ?>
                        <div class="col-xs-12 col-sm-8">
                            <div class="style buy_now-wr">
                                <div class="title_buy_now hidden-xs"><?= \Yii::t('app', 'index1') ?></div>
                                <div class="style buy_now_bl-wr">
                                    <table cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>
                                                <?php
                                                    foreach ($icon_categories as $icon_category) {
                                                        ?>
                                                        <td>
                                                            <a href="<?php echo Url::to(
                                                                [
                                                                    'catalog/category',
                                                                    'category' => $icon_category->lang->alias,
                                                                ]
                                                            ); ?>">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <?php
                                                                                echo ArtboxImageHelper::getImage(
                                                                                    $icon_category->getImageUrl(1, false),
                                                                                    'icon_category',
                                                                                    [
                                                                                        'alt'   => $icon_category->lang->title,
                                                                                        'title' => $icon_category->lang->title,
                                                                                    ],
                                                                                    90,
                                                                                    true
                                                                                );
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <p><?php echo $icon_category->lang->title; ?></p>
                                                            </a>
                                                        </td>
                                                        <?php
                                                    }
                                                ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                
                                
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php if ($this->beginCache(
    'brand_list',
    [
        'duration' => 3600 * 24,
    ]
)
) { ?>
    <?php
    if (!empty($brands)) {
        ?>
        <div class="style slider-partners-wr">
            <div class="container">
                <div class="row">
                    <div class="style slider-partners">
                        <div class="owl-carousel">
                            <?php
                                foreach ($brands as $brand) {
                                    echo Html::tag(
                                        'div',
                                        Html::a(
                                            ArtboxImageHelper::getImage(
                                                $brand->imageUrl,
                                                'brand_main',
                                                [
                                                    'alt'   => $brand->lang->title,
                                                    'title' => $brand->lang->title,
                                                ],
                                                90,
                                                true
                                            ),
                                            [
                                                'brand/view',
                                                'slug' => $brand->lang->alias,
                                            ]
                                        ),
                                        [
                                            'class' => 'img-wrapp',
                                        ]
                                    );
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php $this->endCache();
} ?>
<div class="style city_select-wr">
    <div class="container">
        <div class="row">
            <div class="style bg_city-sel"></div>
            <div class="col-xs-12 title_city-sel">
                <p><?= \Yii::t('app', 'index2') ?></p>
                <div class="city-sel">
                    <span class="addCity"><?= \Yii::t('app', 'inetmag') ?></span>
                    <div id="hidden_shops" class="_off">
                        <ul>
                            <li class="active">
                                <span class="s_"><?= \Yii::t('app', 'inetmag') ?></span>
                                <div class="phones_content">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>(050) 382-03-00</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-4.png" width="16" height="16" alt=""><span>(067) 385-10-55</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 303-90-10</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 428-65-38</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>пн-пт <?= \Yii::t('app', 'from') ?> 10.00 - 19.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>сб <?= \Yii::t('app', 'from') ?> 10.00 - 17.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?= \Yii::t('app', 'snd') ?>: <?= \Yii::t('app', 'weekend') ?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <span class="s_"><?= \Yii::t('app', 'kiev') ?></span>
                                <div class="phones_content">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="style shop_street"><?= \Yii::t('app', 'kiev') ?>, <?= \Yii::t(
                                                        'app',
                                                        'adress1'
                                                    ) ?></div>
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 237-71-06</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 237-71-09</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>пн-сб <?= \Yii::t('app', 'from') ?> 10.00 - 21.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?= \Yii::t('app', 'snd') ?> <?= \Yii::t('app', 'from') ?> 10.00 - 19.00</td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="style shop_street"><?= \Yii::t('app', 'kiev') ?>, <?= \Yii::t(
                                                        'app',
                                                        'adress2'
                                                    ) ?></div>
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 251-71-11</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 428-65-00</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>пн-сб <?= \Yii::t('app', 'from') ?> 10.00 - 21.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?= \Yii::t('app', 'snd') ?> <?= \Yii::t('app', 'from') ?> 10.00 - 19.00</td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <span class="s_"><?= \Yii::t('app', 'kharkov') ?></span>
                                <div class="phones_content">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="style shop_street"><?= \Yii::t(
                                                        'app',
                                                        'kharkov'
                                                    ) ?>, <?= \Yii::t('app', 'adress3') ?></div>
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(057) 773-04-55</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>(050) 381-01-69</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>пн-сб <?= \Yii::t('app', 'from') ?> 10.00-20.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?= \Yii::t('app', 'snd') ?> <?= \Yii::t('app', 'from') ?> 11.00-19.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <span class="s_"><?= \Yii::t('app', 'odessa') ?></span>
                                <div class="phones_content">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="style shop_street"><?= \Yii::t('app', 'odessa') ?>, <?= \Yii::t(
                                                        'app',
                                                        'adress4'
                                                    ) ?></div>
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>(050) 448-42-19</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(048) 777-1-666</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>пн-сб <?= \Yii::t('app', 'from') ?> 10.00 - 20.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?= \Yii::t('app', 'snd') ?> <?= \Yii::t('app', 'from') ?> 11.00 - 19.00</td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <span class="s_"><?= \Yii::t('app', 'dnepr') ?></span>
                                <div class="phones_content">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="style shop_street"><?= \Yii::t('app', 'dnepr') ?>, <?= \Yii::t(
                                                        'app',
                                                        'adress5'
                                                    ) ?></div>
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(0562) 36-93-73</span>
                                                        </div>
                                                    </div>
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-4.png" width="16" height="16" alt=""><span>(067) 562-41-41</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td>пн-пт <?= \Yii::t('app', 'from') ?> 10.00 - 19.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>сб <?= \Yii::t('app', 'from') ?> 10.00 - 18.00</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>вс <?= \Yii::t('app', 'from') ?> 10.00 - 17.00</td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <span class="s_"><?= \Yii::t('app', 'bukovel') ?></span>
                                <div class="phones_content">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="style shop_street"><?= \Yii::t(
                                                        'app',
                                                        'bukovel'
                                                    ) ?>, <?= \Yii::t('app', 'adress6') ?></div>
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>(050) 385-79-79</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td><?= \Yii::t('app', 'from') ?> 8.00 - 19.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="style city_sel_bl">
                                                <div class="style shop_street"><?= \Yii::t(
                                                        'app',
                                                        'bukovel'
                                                    ) ?>, <?= \Yii::t('app', 'adress7') ?>
                                                </div>
                                                <div class="shop_tell-all">
                                                    <div class="style inet_tel">
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>(066) 500-88-45</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="shop_time">
                                                    <table cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><img src="images/icons/ico-48.png" alt=""></td>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td><?= \Yii::t('app', 'from') ?> 8.00 - 19.00</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!--вывод города по умолчанию-->
            <div id="shops_phones" class="col-xs-12 col-sm-12 col-md-10">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="style city_sel_bl">
                            <div class="shop_tell-all">
                                <div class="style inet_tel">
                                    <div>
                                        <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>(050) 382-03-00</span>
                                    </div>
                                </div>
                                <div class="style inet_tel">
                                    <div>
                                        <img src="/images/icons/ico-4.png" width="16" height="16" alt=""><span>(067) 385-10-55</span>
                                    </div>
                                </div>
                                <div class="style inet_tel">
                                    <div>
                                        <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 303-90-10</span>
                                    </div>
                                </div>
                                <div class="style inet_tel">
                                    <div>
                                        <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>(044) 428-65-38</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="shop_time">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td><img src="images/icons/ico-48.png" alt=""></td>
                                        <td>
                                            <table cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td>пн-пт <?= \Yii::t('app', 'from') ?> 10.00 - 19.00</td>
                                                </tr>
                                                <tr>
                                                    <td>сб <?= \Yii::t('app', 'from') ?> 10.00 - 17.00</td>
                                                </tr>
                                                <tr>
                                                    <td><?= \Yii::t('app', 'snd') ?>: <?= \Yii::t('app', 'weekend') ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----------------------------->
            
            <div class="col-xs-12 col-sm-12 col-md-2">
                <table class="shops_tb" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <?php
                            echo Html::tag(
                                'td',
                                Html::a(
                                    \Yii::t('app', 'Доставка и оплата'),
                                    [
                                        'site/page',
                                        'slug' => ( isset($pages[ 4 ]) ) ? $pages[ 4 ]->lang->alias : NULL,
                                    ]
                                ),
                                [
                                    'class' => 'city-set-1',
                                ]
                            );
                        ?>
                    </tr>
                    <tr>
                        <?php
                            echo Html::tag(
                                'td',
                                Html::a(
                                    \Yii::t('app', 'Все магазины'),
                                    [
                                        'site/shops',
                                    ]
                                ),
                                [
                                    'class' => 'city-set-2',
                                ]
                            );
                        ?>
                    </tr>
                    <tr>
                        <?php
                            echo Html::tag(
                                'td',
                                Html::a(
                                    \Yii::t('app', 'Услуги сервиса'),
                                    [
                                        'blog/category',
                                        'slug' => ( isset($blogs[ 13 ]) ) ? $blogs[ 13 ]->lang->alias : NULL,
                                    ]
                                ),
                                [
                                    'class' => 'city-set-3',
                                ]
                            );
                        ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php if ($this->beginCache(
    'discount_items_list',
    [
        'variations' => [ \Yii::$app->language ],
        'duration'   => 3600 * 24,
    ]
)
) { ?>
    <?php
    if (!empty($discount_items)) {
        ?>
        <div class="style slider-wr">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12" style="padding: 0;">
                        <div class="style title_slider">
                            <span><?= \Yii::t('app', 'Акции') ?></span>
                            <div class="customNavigation">
                                <div class="prev_btn"></div>
                                <div class="next_btn"></div>
                            </div>
                        </div>
                        <div class="style slider_">
                            <div class="owl-carousel">
                                <?php
                                    foreach ($discount_items as $discount_item) {
                                        echo Html::tag(
                                            'div',
                                            $this->render(
                                                '_product_item',
                                                [
                                                    'model' => $discount_item,
                                                ]
                                            ),
                                            [
                                                'class' => 'catalog-wr',
                                            ]
                                        );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php $this->endCache();
} ?>



<?php if ($this->beginCache(
    'new_items_list',
    [
        'variations' => [ \Yii::$app->language ],
        'duration'   => 3600 * 24,
    ]
)
) { ?>
    <?php
    if (!empty($new_items)) {
        ?>
        <div class="style slider-wr">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12" style="padding: 0;">
                        <div class="style title_slider">
                            <span><?= \Yii::t('app', 'Новинки') ?></span>
                            <div class="customNavigation">
                                <div class="prev_btn"></div>
                                <div class="next_btn"></div>
                            </div>
                        </div>
                        <div class="style slider_">
                            <div class="owl-carousel">
                                <?php
                                    foreach ($new_items as $new_item) {
                                        echo Html::tag(
                                            'div',
                                            $this->render(
                                                '_product_item',
                                                [
                                                    'model' => $new_item,
                                                ]
                                            ),
                                            [
                                                'class' => 'catalog-wr',
                                            ]
                                        );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php $this->endCache();
} ?>




<?php if ($this->beginCache(
    'top_items_list',
    [
        'variations' => [ \Yii::$app->language ],
        'duration'   => 3600 * 24,
    ]
)
) { ?>
    <?php
    if (!empty($top_items)) {
        ?>
        <div class="style slider-wr">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12" style="padding: 0;">
                        <div class="style title_slider">
                            <span><?= \Yii::t('app', 'Топ товары') ?></span>
                            <div class="customNavigation">
                                <div class="prev_btn"></div>
                                <div class="next_btn"></div>
                            </div>
                        </div>
                        <div class="style slider_">
                            <div class="owl-carousel">
                                <?php
                                    foreach ($top_items as $top_item) {
                                        echo Html::tag(
                                            'div',
                                            $this->render(
                                                '_product_item',
                                                [
                                                    'model' => $top_item,
                                                ]
                                            ),
                                            [
                                                'class' => 'catalog-wr',
                                            ]
                                        );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php $this->endCache();
} ?>
