<?php
    /**
     * @var Product          $product
     * @var ProductVariant   $variant
     * @var Category         $category
     * @var ProductVariant[] $colorVariants
     * @var array            $tabVariants
     * @var array            $listVariants
     * @var TaxGroup[]       $characteristics
     * @var View             $this
     * @var CommentModel[]   $comments
     * @var Page[]           $pages
     * @var string           $specialOption
     * @var BrandSize        $size
     */
    
    use artweb\artbox\comment\models\CommentModel;
    use artweb\artbox\comment\widgets\CommentWidget;
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\BrandSize;
    use artweb\artbox\ecommerce\models\Category;
    use artweb\artbox\ecommerce\models\Product;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use artweb\artbox\ecommerce\models\TaxOption;
    use artweb\artbox\ecommerce\models\TaxGroup;
    use artweb\artbox\models\Page;
    use artweb\artbox\seo\widgets\Seo;
    use common\components\CreditHelper;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    
    $conversions = <<< JS
var google_tag_params = {
    dynx_itemid: "{$variant->id}",
    dynx_pagetype: "offerdetail",
    dynx_totalvalue: {$variant->price}
};
/* <![CDATA[ */
    var google_conversion_id = 881201405;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
JS;
    
    $this->registerJs($conversions, View::POS_BEGIN);
    
    $this->registerJsFile(
        '/js/myGallery.js',
        [
            'position' => View::POS_END,
            'depends'  => [ 'yii\web\JqueryAsset' ],
        ]
    );
    
    $js = <<< JS
if(window.location.hash === '#video')
    {
    var buttons = document.getElementsByClassName('desk_name');
    var tabs = document.getElementsByClassName('desk_list-wr');
    buttons[0].parentNode.className = '';
    buttons[2].parentNode.className = 'active';
    tabs[0].className = 'desk_list-wr';
    tabs[2].className = 'desk_list-wr active';
    buttons[0].scrollIntoView();
    }
JS;
    
    $this->registerJs($js, View::POS_READY);
    
    $this->title = $product->lang->title;
    
    $fullname = $product->fullName;
    
    $this->params[ 'seo' ][ 'fields' ][ 'name' ] = $fullname . $specialOption;
    $this->params[ 'seo' ][ Seo::TITLE ] = $product->lang->meta_title;
    $this->params[ 'seo' ][ Seo::H1 ] = $fullname;
    
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
            'span',
            !empty( $product->brand ) ? $product->brand->lang->title : '',
            [
                'itemprop' => 'name',
            ]
        ),
        'url'      => [
            'brand/view',
            'slug' => !empty( $product->brand ) ? $product->brand->lang->alias : '',
        ],
        'itemprop' => 'item',
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];
    
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
            'span',
            $category->lang->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'url'      => [
            'catalog/category',
            'category' => $category->lang->alias,
        ],
        'itemprop' => 'item',
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='3' /></li>\n",
    ];
    
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
            'span',
            $fullname,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='4' /></li>\n",
    ];

?>
    
    <div class="container" itemscope itemtype="http://schema.org/Product">
        <div class="row">
            <h1 class="col-xs-12 col-sm-12 title_card" itemprop="name"><?php echo Seo::widget(
                    [ 'row' => Seo::H1 ]
                ) ?></h1>
        </div>
        <div class="row card-wrapper">
            <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                <div class="bg-status" style="left: 15px;">
                    <?php
                        if ($product->is_discount) {
                            echo Html::tag(
                                'div',
                                Html::tag('span', \Yii::t('app', 'акция')),
                                [
                                    'class' => 'sale_bg',
                                ]
                            );
                        }
                        if ($product->is_top) {
                            echo Html::tag(
                                'div',
                                Html::tag('span', \Yii::t('app', 'top')),
                                [
                                    'class' => 'new_bg',
                                ]
                            );
                        }
                        if ($product->is_new) {
                            echo Html::tag(
                                'div',
                                Html::tag('span', \Yii::t('app', 'new')),
                                [
                                    'class' => 'top_bg',
                                ]
                            );
                        }
                    ?>
                </div>
                <div class="style img-big-wr">
                    <a href="#" class="gallery-box-min">
                        <?php
                            echo ArtboxImageHelper::getImage(
                                !empty( $variant->image ) ? !empty( $variant->image ) ? $variant->image->getImageUrl(
                                ) : '/images/image-not-found.png' : '/images/image-not-found.png',
                                'card_big',
                                [
                                    'alt'      => $fullname,
                                    'title'    => $fullname,
                                    'itemprop' => 'image',
                                ],
                                90,
                                true
                            );
                        ?>
                    </a>
                    <div class="gallery-box-hidden">
                        <div class="gallery-box-preview">
                        <span data-link="<?php
                            echo ArtboxImageHelper::getImageSrc(
                                !empty( $variant->image ) ? !empty( $variant->image ) ? $variant->image->getImageUrl(
                                ) : '/images/image-not-found.png' : '/images/image-not-found.png',
                                'card_thumb'
                            );
                        ?>"></span>
                            <?php foreach ($product->images as $image) { ?>
                                <span data-link="<?php
                                    echo ArtboxImageHelper::getImageSrc(
                                        !empty( $image ) ? $image->getImageUrl() : '/images/image-not-found.png',
                                        'card_thumb'
                                    );
                                ?>"></span>
                            <?php } ?>
                        </div>
                        <div class="gallery-box-big">
                        <span data-link="<?php echo !empty( $variant->image ) ? !empty( $variant->image ) ? $variant->image->getImageUrl(
                        ) : '/images/image-not-found.png' : '/images/image-not-found.png'; ?>"></span>
                            <?php foreach ($product->images as $image) { ?>
                                <span data-link="<?php echo $image->getImageUrl(); ?>"></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <div class="style img-small-wr">
                    <ul>
                        <li class="active">
                        <span>
                            <img data-big-img="<?php
                                echo ArtboxImageHelper::getImageSrc(
                                    !empty( $variant->image ) ? $variant->image->getImageUrl(
                                    ) : '/images/image-not-found.png',
                                    'card_big',
                                    null,
                                    100
                                );
                            ?>" src="<?php
                                echo ArtboxImageHelper::getImageSrc(
                                    !empty( $variant->image ) ? $variant->image->getImageUrl(
                                    ) : '/images/image-not-found.png',
                                    'card_thumb',
                                    null,
                                    90
                                );
                            ?>" alt="<?php echo $variant->lang->title; ?>" title="<?php echo $variant->lang->title; ?>">
                        </span>
                        </li>
                        <?php foreach ($product->images as $image) { ?>
                            <li>
                            <span>
                                <img data-big-img="<?php
                                    echo ArtboxImageHelper::getImageSrc($image->getImageUrl(), 'card_big', null, 100);
                                ?>" src="<?php
                                    echo ArtboxImageHelper::getImageSrc($image->getImageUrl(), 'card_thumb', null, 90);
                                ?>" alt="<?php echo $product->lang->title; ?>" title="<?php echo $product->lang->title; ?>">
                            </span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                
                <?php
                    if ($variant->stock) {
                        ?>
                        <div class="style new_can_buy">
                            <div class="style _title_can_buy"><?= \Yii::t('app', 'card1') ?></div>
                            <div class="style _txt_can_buy"><?= \Yii::t('app', 'card2') ?></div>
                            <ul>
                                <?php foreach ($variant->variantStocks as $stock) {
                                    if ($stock->quantity > 0) { ?>
                                        <li>
                                            <?php
                                                echo Html::a(
                                                    $stock->stock->title,
                                                    [
                                                        '/site/shops',
                                                        '#' => $stock->stock->id,
                                                    ]
                                                );
                                            ?>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                        <?php
                    }
                ?>
            
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <div class="style product_code"><span><?= \Yii::t('app', 'Код товара') ?> <?php echo $variant->sku; ?></span></div>
                <div class="style">
                    <div class="style options_bl">
                        <div class="style color_title"><?= \Yii::t('app', 'Цветовые решения') ?></div>
                        <div class="style colors_list"><?php
                                if (!preg_match('~.+\.(jpg|gif|png)~i', $variant->lang->title)) {
                                    echo $variant->lang->title, ' ';
                                }
                                //                            foreach ($colorVariants as $colorVariant) {
                                //                                if (preg_match('~.+\.(jpg|gif|png)~i', $colorVariant->lang->title)) {
                                //                                    continue;
                                //                                }
                                //                                echo $colorVariant->lang->title, ' ';
                                //                            }
                            ?></div>
                        <div class="style colors-img">
                            <ul>
                                <li>
                                    <a class="active" href="<?php
                                        echo Url::to(
                                            [
                                                'catalog/product',
                                                'product' => $product->lang->alias,
                                                'variant' => $variant->sku,
                                            ]
                                        );
                                    ?>"><img src="<?php
                                            echo ArtboxImageHelper::getImageSrc(
                                                !empty( $variant->image ) ? $variant->image->getImageUrl(
                                                ) : '/images/image-not-found.png',
                                                'card_thumb',
                                                null,
                                                90
                                            );
                                        ?>" alt="<?php echo $variant->lang->title; ?>" title="<?php echo $variant->lang->title; ?>"></a>
                                </li>
                                <!--размер миниатюр 80х52-->
                                <?php foreach ($colorVariants as $productVariant) { ?>
                                    <li>
                                        <a href="<?php
                                            echo Url::to(
                                                [
                                                    'catalog/product',
                                                    'product' => $product->lang->alias,
                                                    'variant' => $productVariant->sku,
                                                ]
                                            );
                                        ?>"><img src="<?php
                                                echo ArtboxImageHelper::getImageSrc(
                                                    !empty( $productVariant->image ) ? $productVariant->image->getImageUrl(
                                                    ) : '/images/image-not-found.png',
                                                    'card_thumb',
                                                    null,
                                                    90
                                                );
                                            ?>" alt="<?php echo $productVariant->lang->title; ?>" title="<?php echo $productVariant->lang->title; ?>"></a>
                                    </li>
                                <?php } ?>
                                <!--кнопка "еще" выводиться если цветовых решений больше 5-->
                                <?php if (count($colorVariants) > 5) { ?>
                                    <div class="more_card color_more"><span><?= \Yii::t('app', 'еще') ?></span></div>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="shadow_bl">
                            <div></div>
                        </div>
                    </div>
                </div>
                <div class="style">
                    <div class="style options_bl">
                        <div class="style">
                            <ul class="size_growth">
                                <?php foreach ($tabVariants as $tabGroup) { ?>
                                    <li><a <?php
                                            if (reset($tabVariants) === $tabGroup) {
                                                echo 'class="active"';
                                            }
                                        ?> href="#"><span><?php echo $tabGroup[ 'title' ] ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        
                        <div class="style">
                            <?php foreach ($tabVariants as $property => $tabGroup) { ?>
                                <ul class="size_growth-list <?php if (reset($tabVariants) === $tabGroup) {
                                    echo 'active';
                                } ?>">
                                    <?php
                                        foreach ($tabGroup as $tabVariant) {
                                            if (!is_object($tabVariant)) {
                                                continue;
                                            }
                                            ?>
                                            <li<?php if ($tabVariant->id === $variant->id) {
                                                echo ' class="active"';
                                            } ?>><a href="<?php
                                                    echo Url::to(
                                                        [
                                                            'catalog/product',
                                                            'product' => $product->lang->alias,
                                                            'variant' => $tabVariant->sku,
                                                        ]
                                                    );
                                                ?>"><?php
                                                        echo $tabVariant->customOption[ $property ]->lang->value;
                                                    ?></a></li>
                                        <?php } ?>
                                    <!--кнопка "еще" выводиться если размеров больше 4-->
                                    <?php if (count($tabGroup) > 5) { ?>
                                        <div class="more_card size_more"><span><?= \Yii::t('app', 'еще') ?></span></div>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            
                            <div class="shadow_bl">
                                <div></div>
                            </div>
                        </div>
                        <?php if (!empty( $size )) { ?>
                            <a data-link="<?php echo $size->imageUrl; ?>" class="size_table" href="#"><?= \Yii::t('app', 'card3') ?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="style">
                    <?php foreach ($listVariants as $property => $tabGroup) { ?>
                        <div class="style options_bl">
                            <div class="style color_title"><?php echo $tabGroup[ 'title' ]; ?></div>
                            <div class="style weather_list">
                                <ul>
                                    <?php
                                        foreach ($tabGroup as $tabVariant) {
                                            if (!is_object($tabVariant)) {
                                                continue;
                                            }
                                            ?>
                                            <li<?php if ($tabVariant->id === $variant->id) {
                                                echo ' class="active"';
                                            } ?>><a href="<?php
                                                    echo Url::to(
                                                        [
                                                            'catalog/product',
                                                            'product' => $product->lang->alias,
                                                            'variant' => $tabVariant->sku,
                                                        ]
                                                    );
                                                ?>"><?php
                                                        echo $tabVariant->customOption[ $property ]->lang->value;
                                                    ?></a></li>
                                        <?php } ?>
                                    <!--кнопка "еще" выводиться если погодных условий больше 1-->
                                    <?php if (count($tabGroup) > 2) { ?>
                                        <div class="more_card weather_more"><span><?= \Yii::t('app', 'еще') ?></span></div>
                                    <?php } ?>
                                </ul>
                            </div>
                            
                            <div class="shadow_bl">
                                <div></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="price-wr col-xs-6 col-sm-12">
                    <div class="style price_bl" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <?php if ($variant->price_old != 0) { ?>
                                <tr>
                                    <td>
                                        <p class="old_price-card"><?php echo $variant->price_old; ?><span> грн.</span>
                                        </p>
                                        <meta itemprop="priceCurrency" content="UAH">
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>
                                    <p itemprop="price">
                                        <?php echo $variant->price; ?>
                                        <span> грн.</span>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <?php if ($variant->price_old != 0) { ?>
                            <div class="hidden-xs price_sale_card">-<?php echo round(
                                    ( 1 - $variant->price / $variant->price_old ) * 100
                                ); ?> %
                            </div>
                        <?php } ?>
                    </div>


                    <div class="style price_txt_new">
                        <a class="btn_buy_cat buy_card<?php echo( $variant->stock ? '' : ' disabled' ); ?>" href="#" data-variant="<?= $variant->id; ?>">
                            <?php
                                if ($variant->stock) {
                                    ?>
                                    <p><?= \Yii::t('app', 'buy') ?></p>
                                    <?= \Yii::t('app', 'span_buy') ?>
                                    <?php
                                } else {
                                    echo \Yii::t('app', 'Нет в наличии');
                                }
                            ?>
                        </a>
                        <div class="buy_in_one_click">
                            <?php
                            echo Html::a(\Yii::t('app', 'buy_in_click'), '#', [
                                'class' => 'one_click_link',
                                'data-variant' => $variant->id,
                            ]);
                            ?>
                        </div>
                    </div>

                    <div class="style hidden-xs price_txt_new-tb">
                        <?= \Yii::t('app', 'nalich_price') ?>
                    </div>

                    <div class="style buy_in_credit_ buy_in_credit_new-">
                        <p class="old_price-card">
                            <?php
                            $creditSum = ($variant->price > 25000)?25000:$variant->price;
                            echo \Yii::t('app', 'from {creditSum} hrn/month', [
                                'creditSum' => CreditHelper::getCredit($creditSum),
                            ]);
                            ?>
                        </p>
                        <a class="buy_card_credit<?php echo( $variant->stock ? '' : ' disabled' ); ?>" href="#" data-variant="<?= $variant->id; ?>"><?php echo \Yii::t('app', 'Купить в кредит'); ?></a>
                    </div>

                </div>
                <div class="price_links col-xs-6 col-sm-12">
                    <!--                <a class="btns_card_sets btn_sets hidden-sm hidden-md hidden-lg" href="#">отложить</a>-->
                    <ul>
                        <li><a href="<?php
                                if (isset( $pages[ 8 ] )) {
                                    echo Url::to(
                                        [
                                            'site/page',
                                            'slug' => $pages[ 8 ]->lang->alias,
                                        ]
                                    );
                                }
                            ?>" target="_blank"><img src="/images/icons/ico-53.png" width="8" height="12" alt=""><?= \Yii::t('app', 'creditbuy') ?></a>
                        </li>
                        <!-- <li>
                            <a href="#"><img src="/images/icons/ico-54.png" width="9" height="12" alt="">Забронировать в магазине</a>
                        </li> -->
                        <!--            --><?php
                            //                if ($variant->stock) {
                            //                    ?>
                        <!--                  <li>-->
                        <!--                    <a class="where_can_buy" href="#"><img src="/images/icons/ico-55.png" width="8" height="12" alt="">Где купить?</a>-->
                        <!--                    <div class="where_buy_hidden">-->
                        <!--                      <div class="close_where"></div>-->
                        <!--                      <div class="style title-where_buy">Где купить?</div>-->
                        <!--                      <div class="style where_buy-there">Данный товар есть в наличии в наших магазинах</div>-->
                        <!--                      <ul>-->
                        <!--                          --><?php //foreach ($variant->variantStocks as $stock) {
                            //                              if ($stock->quantity > 0) { ?>
                        <!--                                <li>-->
                        <!--                                    --><?php
                            //                                        echo Html::a(
                            //                                            $stock->stock->title,
                            //                                            [
                            //                                                '/site/shops',
                            //                                                '#' => $stock->stock->id,
                            //                                            ]
                            //                                        );
                            //                                    ?>
                        <!--                                </li>-->
                        <!--                              --><?php //}
                            //                          } ?>
                        <!--                      </ul>-->
                        <!--                    </div>-->
                        <!--                  </li>-->
                        <!--                    --><?php
                            //                }
                            //            ?>
                    </ul>
                </div>
            </div>
            
            <div class="hidden-xs col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <?php foreach ($product->events as $event) { ?>
                    
                    <div class="style banner-time-wr">
                        <div class="style banner-time-title"><?= Html::a(
                                $event->lang->title,
                                Url::to(
                                    [
                                        'event/show',
                                        'alias' => $event->lang->alias,
                                        'type'  => $event->type,
                                    ]
                                )
                            ) ?></div>
                        <div class="style banner-img">
                            <?= Html::a(
                                ArtboxImageHelper::getImage(
                                    $event->getImageUrl(1),
                                    'event_list',
                                    [
                                        'alt'   => $event->lang->title,
                                        'title' => $event->lang->title,
                                    ],
                                    90,
                                    true
                                ),
                                [
                                    'event/show',
                                    'alias' => $event->lang->alias,
                                    'type'  => $event->type,
                                ]
                            ) ?>
                        </div>
                        <div class="banner-time">
                            <?php if (!empty( $event->end_at ) && $event->isActive()) { ?>
                                <div class="clock_timer clock_timer-<?= $event->primaryKey ?>"></div>
                                <?php
                                $js = "
                                var clock;

                                clock = $('.clock_timer-$event->primaryKey')
                                .FlipClock(
                                    {
                                        clockFace : 'DailyCounter',
                                        language : 'ru',
                                        classes : {
                                            active : 'flip-clock-active',
                                            before : 'flip-clock-before',
                                            divider : 'flip-clock-divider',
                                            dot : 'flip-clock-dot',
                                            label : 'flip-clock-label',
                                            flip : 'flip',
                                            play : 'play',
                                            wrapper : 'flip-clock-wrapper'
                                        },
                                    }
                                );

                                clock.setTime(" . ( strtotime($event->end_at) - strtotime(date('Y-m-d H:i:s')) ) . ");
                                clock.setCountdown(true);
                                clock.start();";
                                
                                $this->registerJs($js, View::POS_LOAD);
                                
                                ?>
                            <?php } ?>
                        </div>
                    </div>
                
                
                <?php } ?>
                
                
                <div class="style delivery-card">
                    <?php
                        if ($variant->price >= 1000 && empty( $variant->price_old ) && !$product->is_discount) {
                            echo Html::tag(
                                'div',
                                \Yii::t('app', 'бесплатная') . Html::tag('br') . \Yii::t('app', 'доставка'),
                                [ 'class' => 'style delivery-card-title', ]
                            );
                        }
                    ?>
                    
                    <div class="style delivery-card_ payment_guarantee">
                        <?php
                            /*
                            ?>
                            <div class="city-sel-deliv">
                                <a href="#">Киев</a>
                                <ul>
                                    <li style="display: none;"><a href="/">Киев</a></li>
                                    <li><a href="#">Харьков</a></li>
                                    <li><a href="#">Одесса</a></li>
                                    <li><a href="#">Днепр</a></li>
                                    <li><a href="#">Буковель</a></li>
                                </ul>
                            </div>
                            */
                        ?>
                        <!--                    <div class="title_pay">Доставка</div>-->
                        <div class="style">
                            <div class="title_pay"><?= \Yii::t('app', 'САМОВЫВОЗ') ?></div>
                            <div class="pay-txt">
                                <div><?= \Yii::t('app', 'Вы можете забрать этот товар самостоятельно') ?></div>
                                <a class="card_delivery_link" href="#delivery-self"><?= \Yii::t('app', 'в пунктах выдачи') ?></a>
                                <p class="delivery-price"><?= \Yii::t('app', 'бесплатно') ?></p>
                            </div>
                        </div>
                        <div class="style">
                            <div class="title_pay title_pay-two"></div>
                            <div class="pay-txt">
                                <div><?= \Yii::t('app', 'Вы можете воспользоваться') ?></div>
                                <a class="card_delivery_link" href="#delivery-kiev-courier"><?= \Yii::t('app', 'курьерской доставкой') ?></a>
                                <p class="delivery-price">
                                    <?php
                                        if ($variant->price >= 1000 && empty( $variant->price_old ) && !$product->is_discount) {
                                            echo \Yii::t('app', 'бесплатно');
                                        } else {
                                            echo \Yii::t('app', '30 или 50 грн. в зависимости от габаритов');
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div class="style">
                            <div class="title_pay title_pay-two"><?= \Yii::t('app', 'ДОСТАВКА В РЕГИОНЫ УКРАИНЫ') ?></div>
                            <div class="pay-txt">
                                <div><?= \Yii::t('app', 'card13') ?></div>
                                <a class="card_delivery_link" href="#delivery-ukraine"><?= \Yii::t('app', 'card4') ?></a>
                                <p class="delivery-price long_txt-delivery">
                                    <?php
                                        if ($variant->price >= 1000 && empty( $variant->price_old ) && !$product->is_discount) {
                                            echo \Yii::t('app', 'card5');
                                        } else {
                                            echo \Yii::t('app', 'card6');
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="style payment_guarantee">
                        <div class="title_pay">Оплата</div>
                        <div class="pay-txt"><?= \Yii::t('app', 'Доступные') ?>
                            <a href="<?php echo Url::to(
                                [
                                    'site/page',
                                    'slug' => $pages[ 4 ]->lang->alias,
                                    '#'    => 'payment-page',
                                ]
                            ); ?>" target="_blank"><?= \Yii::t('app', 'card7') ?></a>
                        </div>
                    </div>
                    <div class="style payment_guarantee">
                        <div class="title_pay"><?= \Yii::t('app', 'garanty') ?></div>
                        <div class="pay-txt"><?= \Yii::t('app', 'card8') ?></div>
                        <div class="pay-txt"><?= \Yii::t('app', 'card10') ?>
                            <a href="<?php echo Url::to(
                                [
                                    'site/page',
                                    'slug' => $pages[ 4 ]->lang->alias,
                                    '#'    => 'guarantee-page',
                                ]
                            ); ?>" target="_blank"><?= \Yii::t('app', 'card9') ?></a>
                        </div>
                    </div>
                
                </div>
                
                <div class="style stars_raiting" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <div class="rateit" data-rateit-value="<?php echo ( !empty( $product->averageRating ) ) ? $product->averageRating->value : 0; ?>" data-rateit-readonly="true"></div>
                    <div class="number_of_votes"> <?= \Yii::t('app', 'card11') ?> <span itemprop="ratingCount"><?php echo count(
                                $comments
                            ); ?></span> <?= \Yii::t('app', 'card12') ?>
                    </div>
                    <meta itemprop="ratingValue" content="<?php echo ( !empty( $product->averageRating ) ) ? $product->averageRating->value : 0; ?>">
                    <meta itemprop="bestRating" content="5">
                    <meta itemprop="worstRating" content="5">
                </div>
                <?php
                    /*
                    ?>
                    <div class="style buttons_card-act">
                        <a class="btns_card_sets btns_card_sets_l btn_sets" href="#"><span>отложить</span></a>
                        <a class="btns_card_compare btn_sets" href="#"><span>сравнить</span></a>
                        <a class="btns_card_follow btn_sets" href="#"><span>следить за ценой</span></a>
                    </div>
                    */
                ?>
            </div>
            
            <div class="col-xs-12 col-sm-12">
                <div class="style description_list-wrapper hidden-xs">
                    <ul class="description_list">
                        <li class="active">
                            <a class="desk_name" href="#"><?= \Yii::t('app', 'card-desc1') ?></a>
                        </li>
                        <li>
                            <a class="desk_name" href="#">Характеристики</a>
                        </li>
                        <li>
                            <a class="desk_name" href="#"><?= \Yii::t('app', 'card-desc2') ?> (<?php echo count($product->videos); ?>)</a>
                        </li>
                        <li>
                            <a class="desk_name" href="#"><?= \Yii::t('app', 'card-desc3') ?> (<?php echo count($product->comments); ?>)</a>
                        </li>
                        <li>
                            <a class="desk_name" id="card_deliveries" href="#">Доставка</a>
                        </li>
                    </ul>
                </div>
                
                <div class="style desk_blocks-wr">
                    <div class="active desk_list-wr">
                        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#"><?= \Yii::t('app', 'card-desc1') ?></a>
                        <div class="style desk_txt_" itemprop="description">
                            <?php echo $product->lang->description; ?>
                        </div>
                    </div>
                    <div class="desk_list-wr">
                        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#">Характеристики</a>
                        <div class="desk_specifications">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <?php foreach ($characteristics as $group) { ?>
                                            <tr>
                                                <td><?php echo $group->lang->title; ?></td>
                                                <td><?php echo implode(
                                                        ', ',
                                                        ArrayHelper::getColumn(
                                                            $group->customOptions,
                                                            'lang.value'
                                                        )
                                                    ); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="desk_list-wr">
                        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#"><?= \Yii::t('app', 'card-desc2') ?><?php echo " (" . count(
                                    $product->videos
                                ) . ")"; ?></a>
                        <div class="style desk_videos">
                            <div class="row">
                                <?php
                                    if (!empty( $product->videos )) {
                                        foreach ($product->videos as $video) {
                                            ?>
                                            <div class="col-xs-12 col-sm-6">
                                                <?php echo $video->url; ?>
                                            </div>
                                        <?php }
                                    } ?>
                            </div>
                        </div>
                    </div>
                    <div class="desk_list-wr">
                        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#"><?= \Yii::t('app', 'card-desc3') ?> (<?php echo count(
                                $product->comments
                            ); ?>)</a>
                        <div class="style desk_comments">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <a href="#" class="btn_scroll_to_comment"><?= \Yii::t('app', 'card-desc4') ?></a></div>
                            </div>
                            <?php
                                echo CommentWidget::widget(
                                    [
                                        'model'       => $product,
                                        'options'     => [
                                            'class' => 'artbox_comment_container row comments-start',
                                            'id'    => 'artbox-comment',
                                        ],
                                        'layout'      => "<div class='col-xs-12'>{list}</div><div class='col-xs-12'><div class='row'>{form} {reply_form}</div></div>",
                                        'formOptions' => [ 'class' => 'col-xs-12 col-sm-12 col-md-8 col-lg-6 artbox_form_container', ],
                                        'itemView'    => '@frontend/views/site/_custom_comment_item',
                                        'formView'    => '@frontend/views/site/_custom_comment_form',
                                    ]
                                );
                            ?>
                        </div>
                    </div>
                    <div class="desk_list-wr">
                        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#">Доставка</a>
                        <div class="style desk_delivery">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    $this->registerJs(
        "$('.desk_delivery').html('" . preg_replace('/[\r\n]/', '', $this->render('_card_delivery')) . "');"
    );
?>
<?= \artweb\artbox\ecommerce\widgets\lastProducts::widget() ?>
<?= \artweb\artbox\ecommerce\widgets\similarProducts::widget([ 'product' => $product ]) ?>
<?php
    $this->beginBlock('buy_in_click');
    echo  $this->render('_buy_in_click');
    $this->endBlock();
?>
