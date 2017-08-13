<?php
    /**
     * @var View   $this
     * @var string $content
     */
    use artweb\artbox\blog\models\BlogCategory;
    use artweb\artbox\models\Feedback;
    use artweb\artbox\models\Page;
    use artweb\artbox\seo\widgets\Seo;
    use frontend\models\LoginForm;
    use frontend\models\OrderFrontend;
    use frontend\models\PaymentInform;
    use frontend\widgets\AssetWidget;
    use frontend\widgets\LinkerWidget;
    use frontend\widgets\MenuWidget;
    use yii\helpers\Html;
    use frontend\assets\AppAsset;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use yii\widgets\Breadcrumbs;
    use yii\widgets\MaskedInput;
    use yii\widgets\Menu;
    
    AppAsset::register($this);
    
    $this->registerMetaTag(
        [
            'name'    => 'viewport',
            'content' => 'width=device-width',
        ]
    );
    
    $cacheKey = [
        'PagesBlock',
        'variations' => [ \Yii::$app->language ],
    ];
    if (!$pages = Yii::$app->cache->get($cacheKey)) {
        $pages = Page::find()
                     ->with('lang')
                     ->where(
                         [
                             'id' => [
                                 2,
                                 3,
                                 4,
                                 5,
                                 6,
                                 8,
                             ],
                         ]
                     )
                     ->indexBy('id')
                     ->all();
        Yii::$app->cache->set($cacheKey, $pages, 3600 * 24);
    }
    
    $cacheKey = [
        'BlogsBlock',
        'variations' => [ \Yii::$app->language ],
    ];
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?php echo \Yii::$app->language; ?>">
    <head>
        <meta charset="<?php echo \Yii::$app->charset; ?>">
        <link rel="shortcut icon" href="https://extremstyle.ua/images/favicon.ico" type="image/x-icon">
        <?= Html::csrfMetaTags() ?>
        <title><?= Seo::widget([ 'row' => 'title' ]) ?></title>
        <?= Seo::widget([ 'row' => Seo::DESCRIPTION ]) ?>
        <style>
            .preloader-main {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: block;
                z-index: 9999999;
                background: white url(/images/preload-2.gif) 50% 50% no-repeat;
            }
        </style>
        <?php
            if (\Yii::$app->language == 'ua-UA') {
                echo '<meta name="robots" content="noindex,nofollow">';
            } else {
                echo Seo::widget([ 'row' => 'meta' ]);
            }
        ?>
        <meta name="google-site-verification" content="RBRKmKXIVNwSMbRe849aw_zn0X9I1wmU63EoVc4dHrQ"/>
        <?php $this->head() ?>
    </head>
    <body>
        <script>
            (function(i, s, o, g, r, a, m) {
                i[ 'GoogleAnalyticsObject' ] = r;
                i[ r ] = i[ r ] || function() {
                        (i[ r ].q = i[ r ].q || []).push(arguments)
                    }, i[ r ].l = 1 * new Date();
                a = s.createElement(o), m = s.getElementsByTagName(o)[ 0 ];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-15799063-3', 'auto');
            ga('send', 'pageview');

        </script>
        <?php $this->beginBody(); ?>
        <div class="preloader-main"></div>
        <div class="section-box-header ">
            <!-----полная версия------>
            <div class="header-full hidden visible_940">
                <div class="style first-menu-wr">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-5">
                                <ul class="first-menu_1">
                                    <?php
                                        echo Html::tag(
                                            'li',
                                            Html::a(
                                                \Yii::t('app', 'contacts'),
                                                [
                                                    'site/page',
                                                    'slug' => isset( $pages[ 2 ] ) ? $pages[ 2 ]->lang->alias : null,
                                                ]
                                            )
                                        );
                                        echo Html::tag(
                                            'li',
                                            Html::a(
                                                \Yii::t('app', 'Доставка и оплата'),
                                                [
                                                    'site/page',
                                                    'slug' => isset( $pages[ 4 ] ) ? $pages[ 4 ]->lang->alias : null,
                                                ]
                                            )
                                        );
                                        echo Html::tag(
                                            'li',
                                            Html::a(
                                                Html::img(
                                                    '/images/icons/ico-21.png',
                                                    [
                                                        'width'  => 12,
                                                        'height' => 12,
                                                    ]
                                                ) . Html::tag('span', \Yii::t('app', 'Помощь')),
                                                [
                                                    'site/page',
                                                    'slug' => isset( $pages[ 5 ] ) ? $pages[ 5 ]->lang->alias : null,
                                                ]
                                            )
                                        );
                                        echo Html::tag('li', Html::a(\Yii::t('app', 'brands'), [ 'brand/index' ]));
                                    ?>
                                </ul>
                            </div>
                            <div class="col-xs-5 col-sm-5 col-md-4 col-lg-5">
                                <ul class="first-menu_2">
                                    <?php
                                        echo Html::tag(
                                            'li',
                                            Html::a(
                                                Html::img(
                                                    '/images/icons/ico-22.png',
                                                    [
                                                        'width'  => 11,
                                                        'height' => 12,
                                                    ]
                                                ) . Html::tag('span', \Yii::t('app', 'Статус заказа')),
                                                [
                                                    'order/status',
                                                ]
                                            )
                                        );
                                    ?>
                                    <li>
                                        <a class="inform_pay" href="#"><img src="/images/icons/ico-23.png" width="12" height="12" alt=""><span><?= \Yii::t(
                                                    'app',
                                                    'tellpay'
                                                ) ?></span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <ul class="first-menu_3">
                                    <?php if (Yii::$app->user->isGuest): ?>
                                        <li>
                                            <?= Html::a(
                                                Html::img(
                                                    "/images/icons/ico-24.png",
                                                    [
                                                        'width'  => '13',
                                                        "height" => "12",
                                                        "title"  => \Yii::t("app", "lcab"),

                                                    ]
                                                ) . "<span>" . \Yii::t("app", "lcab") . "</span>",
                                                Url::to([ "site/login" ]),
                                                [
                                                    "class" => "login_link",
                                                ]
                                            ); ?>

                                        </li>
                                    <?php else: ?>
                                        <li class="is_logged">
                                            <?= Html::a(
                                                Html::img(
                                                    "/images/icons/ico-24.png",
                                                    [
                                                        'width'  => '13',
                                                        "height" => "12",
                                                        "title"  => \Yii::t("app", "lcab"),

                                                    ]
                                                ) . "<span>" . Yii::$app->user->identity->email . "</span>",
                                                Url::to([ "cabinet/main" ]),
                                                [
                                                    "class" => "is_logged",
                                                ]
                                            ); ?>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="style header-content-wr">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">
                                <a class="logo" href="<?php echo Url::to([ 'site/index' ]); ?>"></a>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="style slogan-wr">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td valign="top">Магазини якісного спорядження</td>
                                            <!--                                    <td valign="top">Найбільший <span>вибір найкращого</span> спорядження</td>-->
                                        </tr>
                                        <tr>
                                            <td class="lang_header">
                                                <?php
                                                    echo LinkerWidget::widget(
                                                        [
                                                            'languageId' => 3,
                                                        ]
                                                    );
                                                ?>
                                                <span class="lang_sep"></span>
                                                <?php
                                                    echo LinkerWidget::widget(
                                                        [
                                                            'languageId' => 2,
                                                        ]
                                                    );
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-4 col-md-4 col-lg-4">
                                <div class="style phones-header-wr">
                                    <div class="phones-header">
                                        <div class="phones_h">
                                            <div class="phones-h_list">
                                                <div style="background-image: url('/images/icons/ico-25.png')"></div>
                                                <div style="background-image: url('/images/icons/ico-31.png')"></div>
                                            </div>
                                            <div class="phones-h-num"><span>044</span> 303-90-10</div>
                                            <div class="hidden-phones-h_list">
                                                <ul>
                                                    <li class="hidden_">
                                                        <div style="background-image: url('/images/icons/ico-25.png')"></div>
                                                        <span><?= \Yii::t('app', 'incity') ?></span>
                                                        <p><span>044</span> 303-90-10</p>
                                                    </li>
                                                    <li>
                                                        <div style="background-image: url('/images/icons/ico-25.png')"></div>
                                                        <span><?= \Yii::t('app', 'incity') ?></span>
                                                        <p><span>044</span> 428-65-38</p>
                                                    </li>
                                                    <li>
                                                        <div style="background-image: url('/images/icons/ico-3.png')"></div>
                                                        <span><?= \Yii::t('app', 'mts') ?></span>
                                                        <p><span>050</span> 382-03-00</p>
                                                    </li>
                                                    <li>
                                                        <div style="background-image: url('/images/icons/ico-4.png')"></div>
                                                        <span><?= \Yii::t('app', 'kyivstar') ?></span>
                                                        <p><span>067</span> 385-10-55</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="callback_h">
                                            <?php
                                                $feedback = new Feedback(
                                                    [
                                                        'scenario' => Feedback::SCENARIO_CALLBACK,
                                                    ]
                                                );
                                                $form = ActiveForm::begin(
                                                    [
                                                        'id' => 'feedback-header',
                                                    ]
                                                );
                                                echo $form->field(
                                                    $feedback,
                                                    'phone',
                                                    [
                                                        'template' => '{input}',
                                                    ]
                                                )
                                                          ->widget(
                                                              MaskedInput::className(),
                                                              [
                                                                  'mask'    => '+38(099)999-99-99',
                                                                  'options' => [
                                                                      'placeholder' => '+38(0XX)XXX-XX-XX',
                                                                  ],
                                                              ]
                                                          );
                                                echo Html::submitButton(\Yii::t('app', 'callback'));
                                                $form::end();
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="style buttons_head_link-wr">
                                    <div class="buttons_head_link">
                                        <a class="hot_line" href="#"><span><?= \Yii::t(
                                                    'app',
                                                    'hotline'
                                                ) ?></span><img src="/images/icons/ico-28.png" width="12" height="12" alt=""></a>
                                        <?php
                                            echo Html::a(
                                                Html::tag(
                                                    'span',
                                                    \Yii::t('app', 'creditbuy')
                                                ) . Html::img(
                                                    '/images/icons/ico-29.png',
                                                    [
                                                        'width'  => 12,
                                                        'height' => 12,
                                                    ]
                                                ),
                                                [
                                                    'site/page',
                                                    'slug' => isset( $pages[ 8 ] ) ? $pages[ 8 ]->lang->alias : null,
                                                ]
                                            )
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                <div class="style basket-wrapper">
                                    <?php
                                        echo $this->render('@frontend/views/basket/cart', [ 'count' => 0, ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="style main-menu-wr">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-9 col-sm-8 col-md-8 col-lg-8">
                                <ul class="main-menu">
                                    <li class="catalog_ <?php echo isset( $this->params[ 'isHome' ] ) ? 'homepage_notclick' : '' ?>">
                                        <a href="#">
                                            <div></div>
                                            <div></div>
                                            <p><?= \Yii::t('app', 'catalog') ?></p></a></li>
                                    <li>
                                        <?= Html::a(
                                            '<span>' . Html::img(
                                                '/images/icons/ico-32.png',
                                                [
                                                    'width'  => '11',
                                                    'height' => '16',
                                                ]
                                            ) . '</span>' . '<p>' . \Yii::t('app', 'Магазины') . '</p>',
                                            [ 'site/shops', ]
                                        ); ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            '<span>' . Html::img(
                                                '/images/icons/ico-33.png',
                                                [
                                                    'width'  => '18',
                                                    'height' => '15',
                                                ]
                                            ) . '</span>' . '<p>' . \Yii::t('app', 'Акции') . '</p>',
                                            [ 'event/promo' ]
                                        ); ?>
                                    </li>
                                    <!--                            <li class="outlet_pc">-->
                                    <!--                                --><?php //echo Html::a('<p>' . \Yii::t('app', 'OUTLET') . '</p>', [ 'event/sale' ]); ?>
                                    <!--                            </li>-->
                                </ul>
                            </div>
                            <div class="col-xs-3 col-sm-4 col-md-4 col-lg-4">
                                <div class="style search-wrapper">
                                    <div class="search">
                                        <?php $form = ActiveForm::begin(
                                            [
                                                'method' => 'GET',
                                                'action' => Url::to([ 'search/main' ]),
                                            ]
                                        ); ?>
                                        <?= $form->field(new \frontend\models\SearchForm(), 'word')
                                                 ->textInput(
                                                     [
                                                         'name'        => 'word',
                                                         'placeholder' => Yii::t('app', 'search_tov'),
                                                     ]
                                                 ) ?>
                                        <button type="submit"></button>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----мобильная версия---->
            <div class="hidden visible-mobile">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="style header-mobile-wr">
                                <div class="row">
                                    <div class="col-xs-7">
                                        <div class="menu_mob">
                                            <div></div>
                                            <div></div>
                                        </div>
                                        <div id="menu-mob-hidden">
                                            <div class="style close-menu-mob">
                                                <div class="close_mob"></div>
                                                <div class="mob_lang">
                                                    <?php
                                                        echo LinkerWidget::widget(
                                                            [
                                                                'languageId' => 3,
                                                            ]
                                                        );
                                                    ?>
                                                    <span></span>
                                                    <?php
                                                        echo LinkerWidget::widget(
                                                            [
                                                                'languageId' => 2,
                                                            ]
                                                        );
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                                echo Menu::widget(
                                                    [
                                                        'encodeLabels' => false,
                                                        'items'        => [
                                                            [
                                                                'label'   => \Yii::t('app', 'OUTLET'),
                                                                'url'     => [ 'event/sale' ],
                                                                'options' => [
                                                                    'class' => 'outlet_mob',
                                                                ],
                                                                'visible' => false,
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-1.png',
                                                                            [
                                                                                'width'  => 15,
                                                                                'height' => 15,
                                                                            ]
                                                                        )
                                                                    ) . \Yii::t('app', 'Home'),
                                                                'url'     => [ 'site/index' ],
                                                                'options' => [
                                                                    'class' => 'bg-mob-menu',
                                                                ],
                                                            ],
                                                            [
                                                                'label' => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-2.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'Акции'),
                                                                'url'   => [ 'event/promo' ],
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-3.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'Магазины'),
                                                                'url'     => [
                                                                    'site/shops',
                                                                ],
                                                                'visible' => isset( $pages[ 3 ] ),
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-4.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'lcab'),
                                                                'url'     => [ 'cabinet/main' ],
                                                                'options' => [
                                                                    'class' => 'bg-mob-menu',
                                                                ],
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-5.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'Войти'),
                                                                'url'     => [ 'site/login' ],
                                                                'visible' => \Yii::$app->user->isGuest,
                                                                'options' => [
                                                                    'class' => 'bg-mob_link',
                                                                ],
                                                            ],
                                                            [
                                                                'label' => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-6.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'basket'),
                                                                'url'   => [ 'order/basket' ],
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-7.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'Сравнения'),
                                                                'url'     => [ '' ],
                                                                'visible' => false,
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-8.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'bookmarks'),
                                                                'url'     => [ '' ],
                                                                'visible' => false,
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-9.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'tellpay'),
                                                                'url'     => '#',
                                                                'options' => [
                                                                    'class' => 'mob_inform_pay',
                                                                ],
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-10.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'myorders'),
                                                                'url'     => [ 'cabinet/my-orders' ],
                                                                'visible' => !\Yii::$app->user->isGuest,
                                                            ],
                                                            [
                                                                'label' => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-11.png',
                                                                            [
                                                                                'width'  => 11,
                                                                                'height' => 12,
                                                                            ]
                                                                        )
                                                                    ) . \Yii::t('app', 'Статус заказа'),
                                                                'url'   => [ 'order/status' ],
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-12.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'help'),
                                                                'url'     => [
                                                                    'site/page',
                                                                    'slug' => ( isset( $pages[ 5 ] ) ? $pages[ 5 ]->lang->alias : false ),
                                                                ],
                                                                'visible' => isset( $pages[ 5 ] ),
                                                                'options' => [
                                                                    'class' => 'bg-mob-menu',
                                                                ],
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-13.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'Доставка и оплата'),
                                                                'url'     => [
                                                                    'site/page',
                                                                    'slug' => ( isset( $pages[ 4 ] ) ? $pages[ 4 ]->lang->alias : false ),
                                                                ],
                                                                'visible' => isset( $pages[ 4 ] ),
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-14.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'contacts'),
                                                                'url'     => [
                                                                    'site/page',
                                                                    'slug' => ( isset( $pages[ 2 ] ) ? $pages[ 2 ]->lang->alias : false ),
                                                                ],
                                                                'visible' => isset( $pages[ 2 ] ),
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-15.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'hotline'),
                                                                'url'     => '#',
                                                                'options' => [
                                                                    'class' => 'mob_hot_line',
                                                                ],
                                                            ],
                                                            [
                                                                'label'   => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-16.png'
                                                                        )
                                                                    ) . \Yii::t('app', 'creditbuy'),
                                                                'url'     => [
                                                                    'site/page',
                                                                    'slug' => ( isset( $pages[ 8 ] ) ? $pages[ 8 ]->lang->alias : false ),
                                                                ],
                                                                'visible' => isset( $pages[ 8 ] ),
                                                            ],
                                                            [
                                                                'label' => Html::tag(
                                                                        'span',
                                                                        Html::img(
                                                                            '/images/icons/ico-menu/ico-17.png'
                                                                        )
                                                                    ) . Html::tag(
                                                                        'span',
                                                                        \Yii::t('app', 'callback'),
                                                                        [
                                                                            'class' => 'border_c',
                                                                        ]
                                                                    ),
                                                                'url'   => '#',
                                                            ],
                                                        ],
                                                    ]
                                                );
                                            ?>
                                        </div>
                                        <?php
                                            echo Html::a(
                                                '',
                                                Url::home(),
                                                [
                                                    'class' => 'logo',
                                                ]
                                            );
                                        ?>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="mobile_call_header">
                                            <img src="/images/icons/icon_phone_head_01.png" alt="">
                                        </div>
                                    </div>
                                    <div class="col-xs-3 mob-basket_ basket-wrapper2">
                                        <?php
                                            echo $this->render('@frontend/views/basket/cart', [ 'count' => 0, ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="style catalog-search-mob">
                                <div class="catalog_mob-wr">
                                    <a href="#" class="catalog_mob tablet hidden visible-sm <?php echo isset( $this->params[ 'isHome' ] ) ? 'active homepage_notclick' : '' ?>"><?= \Yii::t(
                                            'app',
                                            'catalog2'
                                        ) ?></a>
                                    <a href="#" class="catalog_mob mob hidden visible-xs"><?= \Yii::t(
                                            'app',
                                            'catalog2'
                                        ) ?></a>
                                </div>
                                <div class="search-mob-wr">
                                    <div class="search-mob">
                                        <?php $form = ActiveForm::begin(
                                            [
                                                'method' => 'GET',
                                                'action' => Url::to([ 'search/main' ]),
                                            ]
                                        ); ?>
                                        <?= $form->field(new \frontend\models\SearchForm(), 'word')
                                                 ->textInput(
                                                     [
                                                         'name'        => 'word',
                                                         'placeholder' => \Yii::t('app', 'searchitem'),
                                                     ]
                                                 ) ?>
                                        <button type="submit"></button>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!----катол товаров меню--->
        <!--<div id="catalog-hidden-width" class="container hidden visible_all">-->
        <!--    <div class="row">-->
        <!--        <div id="catalog-menu-width" class="col-xs-12 col-sm-4 col-md-3 col-lg-3">-->
        <!--            <div class="style" style="background: none; opacity: 0; height: 1px;-ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)';"></div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!----полная версия---->

        <div class="style hidden-xs catalog-menu-wr <?php echo isset( $this->params[ 'isHome' ] ) ? 'opens homepage_notclose' : '' ?>">
            <?php if ($this->beginCache(
                'menu_list',
                [
                    'variations' => [ \Yii::$app->language ],
                    'duration'   => 3600 * 24,
                ]
            )
            ) { ?>
                <?php echo MenuWidget::widget(); ?>

                <?php $this->endCache();
            } ?>
        </div>
        <div class="section-box-content">

            <div class="container" style="<?php echo isset( $this->params[ 'isHome' ] ) ? 'display:none;' : '' ?>">
                <div class="row">
                    <div class="col-xs-12">
                        <?php

                            echo Breadcrumbs::widget(
                                [
                                    'encodeLabels'       => false,
                                    'homeLink'           => [
                                        'label'    => Html::tag(
                                            'span',
                                            \Yii::t('app', 'Home'),
                                            [
                                                'itemprop' => 'name',
                                            ]
                                        ),
                                        'url'      => [ 'site/index' ],
                                        'itemprop' => 'item',
                                        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='1' /></li>\n",
                                    ],
                                    'links'              => isset( $this->params[ 'breadcrumbs' ] ) ? $this->params[ 'breadcrumbs' ] : [],
                                    'options'            => [
                                        'class'     => 'breadcrumb',
                                        'itemscope' => true,
                                        'itemtype'  => 'http://schema.org/BreadcrumbList',
                                    ],
                                    'itemTemplate'       => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}</li>\n",
                                    'activeItemTemplate' => "<li class='active' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>{link}</li>\n",
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
            <?php echo $content; ?>
        </div>
        <?php
            if (!empty( Seo::widget([ 'row' => Seo::SEO_TEXT ]) )) {
                ?>
                <div class="section-box-seo">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <div class="style seo_txt-wrapp hidden-seo-txt">
                                    <div class="style seo_txt">
                                        <?= Seo::widget([ 'row' => Seo::SEO_TEXT ]) ?>
                                    </div>
                                    <a data-text-read="<?= \Yii::t('app', 'readfull') ?>" data-text-hide="<?= \Yii::t(
                                        'app',
                                        'hide'
                                    ) ?>" href="#" class="read_more_seo"><?= \Yii::t('app', 'readfull') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        <div class="section-box-footer">
            <div class="section-box causes_wr <?php echo isset( $this->params[ 'isHome' ] ) ? '' : 'all_causes_' ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="style causes_title"><?= \Yii::t('app', 'layout1') ?></div>
                        </div>

                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="style causes-bl">
                                <div class="causes_1"></div>
                                <p><?= \Yii::t('app', 'layout2') ?></p>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="style causes-bl">
                                <div class="causes_2"></div>
                                <p><?= \Yii::t('app', 'layout3') ?></p>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="style causes-bl">
                                <div class="causes_3"></div>
                                <p><?= \Yii::t('app', 'layout4') ?></p>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="style causes-bl">
                                <div class="causes_4"></div>
                                <p><?= \Yii::t('app', 'layout5') ?></p>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="style causes-bl">
                                <div class="causes_5"></div>
                                <p><?= \Yii::t('app', 'layout6') ?></p>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="style causes-bl">
                                <div class="causes_6"></div>
                                <p><?= \Yii::t('app', 'layout7') ?></p>
                            </div>

                        </div>


                    </div>
                </div>


            </div>
            <div class="section-box footer-all hidden visible_all">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                            <div class="style footer-title"><?= \Yii::t('app', 'catalog') ?></div>
                            <div class="style shops-title">
                                <p><?= \Yii::t('app', 'Магазины') ?></p>
                                <div class="city-sel">
                                    <span class="addCity"><?= \Yii::t('app', 'inetmag') ?></span>
                                    <div id="hidden_shops" class="_off">
                                        <ul>
                                            <li class="active">
                                                <span class="s_"><?= \Yii::t('app', 'inetmag') ?></span>
                                                <div class="phones_content">
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 303-90-10</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 428-65-38</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>050 382-03-00</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-4.png" width="16" height="16" alt=""><span>067 385-10-55</span>
                                                        </div>
                                                    </div>
                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph2'
                                                            ) ?></div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 105px;">
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf;width: 83px;">
                                                                        <span>сб</span>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t(
                                                                            'app',
                                                                            'snd'
                                                                        ) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 10 до 21</p></td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'from'
                                                                            ) ?> 10 - 17</p>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'weekend'
                                                                            ) ?></p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="s_"><?= \Yii::t('app', 'kiev') ?></span>
                                                <div class="phones_content">
                                                    <div class="style shops-wr">
                                                        <div class="style shops-str"><?= \Yii::t(
                                                                'app',
                                                                'kiev'
                                                            ) ?>, <?= \Yii::t(
                                                                'app',
                                                                'adress11'
                                                            ) ?></div>
                                                    </div>
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 237-71-06</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 237-71-09</span>
                                                        </div>
                                                    </div>
                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph1'
                                                            ) ?></div>

                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 142px;">
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span><span>сб</span>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t(
                                                                            'app',
                                                                            'snd'
                                                                        ) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 10 до 21</p></td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'from'
                                                                            ) ?> 10 до 19</p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="style shops-wr">
                                                        <div class="style shops-str"><?= \Yii::t(
                                                                'app',
                                                                'kiev'
                                                            ) ?>, <?= \Yii::t(
                                                                'app',
                                                                'adress22'
                                                            ) ?></div>
                                                    </div>
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 251-71-11</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 428-65-00</span>
                                                        </div>
                                                    </div>

                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph1'
                                                            ) ?></div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 142px;">
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span><span>сб</span>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t(
                                                                            'app',
                                                                            'snd'
                                                                        ) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 10 до 21</p></td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'from'
                                                                            ) ?> 10 до 19</p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <span class="s_"><?= \Yii::t('app', 'kharkov') ?></span>
                                                <div class="phones_content">
                                                    <div class="style shops-wr">
                                                        <div class="style shops-str"><?= \Yii::t(
                                                                'app',
                                                                'kharkov'
                                                            ) ?>, <?= \Yii::t('app', 'adress3') ?></div>
                                                    </div>
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>050 381-01-69</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>057 773-04-55 </span>
                                                        </div>
                                                    </div>
                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph1'
                                                            ) ?></div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 142px;">
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span><span>сб</span>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t(
                                                                            'app',
                                                                            'snd'
                                                                        ) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 10.00 до 20.00</p></td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'from'
                                                                            ) ?> 11 до 19</p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <span class="s_"><?= \Yii::t('app', 'odessa') ?></span>
                                                <div class="phones_content">
                                                    <div class="style shops-wr">
                                                        <div class="style shops-str"><?= \Yii::t(
                                                                'app',
                                                                'odessa'
                                                            ) ?>, <?= \Yii::t('app', 'adress4') ?></div>
                                                    </div>
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>048 777-1-666</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>050 448-42-19</span>
                                                        </div>
                                                    </div>
                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph1'
                                                            ) ?></div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 142px;">
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span><span>сб</span>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t(
                                                                            'app',
                                                                            'snd'
                                                                        ) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 10.00 до 20.00</p></td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'from'
                                                                            ) ?> 11.00 до 19.00</p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <span class="s_"><?= \Yii::t('app', 'dnepr') ?></span>
                                                <div class="phones_content">
                                                    <div class="style shops-wr">
                                                        <div class="style shops-str"><?= \Yii::t(
                                                                'app',
                                                                'dnepr'
                                                            ) ?>, <?= \Yii::t('app', 'adress5') ?></div>
                                                    </div>
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>0562 36-93-73</span>
                                                        </div>
                                                        <div>
                                                            <img src="/images/icons/ico-4.png" width="16" height="16" alt=""><span>067 562-41-41</span>
                                                        </div>
                                                    </div>
                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph1'
                                                            ) ?></div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf">сб</td>
                                                                    <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t(
                                                                            'app',
                                                                            'snd'
                                                                        ) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 10.00 до 19.00</p></td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'from'
                                                                            ) ?> 10.00 до 18.00</p>
                                                                    </td>
                                                                    <td style="border-left: 1px solid #dfdfdf">
                                                                        <p><?= \Yii::t(
                                                                                'app',
                                                                                'from'
                                                                            ) ?> 10.00 до 17.00</p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <span class="s_"><?= \Yii::t('app', 'bukovel') ?></span>
                                                <div class="phones_content">
                                                    <div class="style shops-wr">
                                                        <div class="style shops-str"><?= \Yii::t(
                                                                'app',
                                                                'bukovel'
                                                            ) ?>, <?= \Yii::t('app', 'adress6') ?></div>
                                                    </div>
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>050 385-79-79</span>
                                                        </div>
                                                    </div>
                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph1'
                                                            ) ?></div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span><span>сб</span><span><?= \Yii::t(
                                                                                'app',
                                                                                'snd'
                                                                            ) ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 8.00 до 19.00</p></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="style shops-wr">
                                                        <div class="style shops-str"><?= \Yii::t(
                                                                'app',
                                                                'bukovel'
                                                            ) ?>, <?= \Yii::t('app', 'adress33') ?></div>
                                                    </div>
                                                    <div class="style phone-wr-footer">
                                                        <div>
                                                            <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>066 500-88-45</span>
                                                        </div>
                                                    </div>
                                                    <div class="style graph-footer">
                                                        <div class="style graph-footer-title"><?= \Yii::t(
                                                                'app',
                                                                'graph1'
                                                            ) ?></div>
                                                        <table cellpadding="0" cellspacing="0" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span><span>сб</span><span><?= \Yii::t(
                                                                                'app',
                                                                                'snd'
                                                                            ) ?></span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><p><?= \Yii::t('app', 'from') ?> 8.00 до 19.00</p></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="style footer_add">

                                <div class="style phone-wr-footer">
                                    <div>
                                        <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 303-90-10</span>
                                    </div>
                                    <div>
                                        <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 428-65-38</span>
                                    </div>
                                    <div>
                                        <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>050 382-03-00</span>
                                    </div>
                                    <div>
                                        <img src="/images/icons/ico-4.png" width="16" height="16" alt=""><span>067 385-10-55</span>
                                    </div>

                                </div>
                                <div class="style graph-footer">
                                    <div class="style graph-footer-title"><?= \Yii::t('app', 'graph2') ?></div>
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td style="width: 105px;">
                                                    <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span>
                                                </td>
                                                <td style="border-left: 1px solid #dfdfdf;width: 83px;">
                                                    <span>сб</span>
                                                </td>
                                                <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t(
                                                        'app',
                                                        'snd'
                                                    ) ?></td>
                                            </tr>
                                            <tr>
                                                <td><p><?= \Yii::t('app', 'from') ?> 10 до 21</p></td>
                                                <td style="border-left: 1px solid #dfdfdf"><p><?= \Yii::t(
                                                            'app',
                                                            'from'
                                                        ) ?> 10 - 17</p>
                                                </td>
                                                <td style="border-left: 1px solid #dfdfdf"><p><?= \Yii::t(
                                                            'app',
                                                            'weekend'
                                                        ) ?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="style skype-mail-footer">
                                <div>
                                    <img src="/images/icons/ico-6.png" width="16" height="16" alt=""><a class="skype_f" href="skype:www.extremstyle.com.ua?call">www.extremstyle.com.ua</a>
                                </div>
                                <div>
                                    <img src="/images/icons/ico-7.png" width="16" height="16" alt=""><a class="mail_f" href="mailto:vopros@eltrade.com.ua">vopros@eltrade.com.ua</a>
                                </div>
                            </div>
                            <div class="style phones_help">
                                <span>Телефон для</span><img src="/images/icons/ico-8.png" width="16" height="16" alt=""><span><?= \Yii::t(
                                        'app',
                                        'phonefor1'
                                    ) ?></span><img src="/images/icons/ico-9.png" width="16" height="16" alt=""><span><?= \Yii::t(
                                        'app',
                                        'phonefor2'
                                    ) ?></span>
                            </div>
                            <div class="style phone-tb-f">
                                <table cellspacing="0" cellpadding="0" border="0" style="color:#fff;">
                                    <tr>
                                        <td width="140" style="font-size: 18px;font-family: Helvetica;">0-800-50-09-10</td>
                                        <td style="font-size: 11px;"><?= \Yii::t('app', 'noweekends') ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-3 col-lg-4">
                            <div class="style blog-social-wr hidden visible-md visible-lg">
                                <ul class="style blog-social-ico">
                                    <!--                                <li class="active"><span style="background-position: 11px 8px"></span></li>-->
                                    <li><span class="active" style="background-position: -34px 8px;"></span></li>
                                </ul>
                                <div class="style blog-social">
                                    <div class="style blog-social-navi-arrow"></div>
                                    <ul class="style blog-social-hide">
                                        <!--                                    <li>-->
                                        <!--                                        <div id="vk_groups"></div>-->
                                        <!--                                        --><?php
                                            ////                                        $this->registerJs('VK.Widgets.Group(
                                            ////                                                "vk_groups", {
                                            ////                                                    mode: 0,
                                            ////                                                    width: "200",
                                            ////                                                    height: "333",
                                            ////                                                    color1: \'FFFFFF\',
                                            ////                                                    color2: \'333333\',
                                            ////                                                    color3: \'50ae34\'
                                            ////                                                }, 57946563
                                            ////                                            );');
                                            //                                        ?>
                                        <!--                                    </li>-->
                                        <li>
                                            <div id="fb-root"></div>
                                            <div id="fb-root"></div>
                                            <script>(function(d, s, id) {
                                                    var js, fjs = d.getElementsByTagName(s)[ 0 ];
                                                    if (d.getElementById(id)) {
                                                        return;
                                                    }
                                                    js = d.createElement(s);
                                                    js.id = id;
                                                    js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.6";
                                                    fjs.parentNode.insertBefore(js, fjs);
                                                }(document, 'script', 'facebook-jssdk'));</script>
                                            <div data-width="200" data-height="333" class="fb-page" data-href="https://www.facebook.com/extremstyle.ua/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                                                <blockquote cite="https://www.facebook.com/extremstyle.ua/" class="fb-xfbml-parse-ignore">
                                                    <a href="https://www.facebook.com/extremstyle.ua/"><?= \Yii::t(
                                                            'app',
                                                            'network'
                                                        ) ?></a>
                                                </blockquote>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="style hidden visible-sm network-copy">
                                <div><a target="_blank" href="http://vk.com/extremstyle_ua"></a></div>
                                <div><a target="_blank" href="https://www.facebook.com/extremstyle.ua/"></a></div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
                            <div class="style footer-menu">
                                <ul class="style menu-list-footer">
                                    <li>
                                        <?php
                                            echo Html::a(\Yii::t('app', 'Акции'), [ 'event/promo' ]);
                                        ?>
                                    </li>
                                    <?php
                                        if (isset( $pages[ 4 ] )) {
                                            echo Html::tag(
                                                'li',
                                                Html::a(
                                                    \Yii::t('app', 'Доставка и оплата'),
                                                    [
                                                        'site/page',
                                                        'slug' => $pages[ 4 ]->lang->alias,
                                                    ]
                                                )
                                            );
                                        }
                                    ?>
                                    <li><?php echo Html::a(\Yii::t('app', 'brands'), [ 'brand/index' ]); ?></li>
                                    <li><?php echo Html::a(
                                            \Yii::t('app', 'vacancy'),
                                            [
                                                'site/page',
                                                'slug' => isset( $pages[ 6 ] ) ? $pages[ 6 ]->lang->alias : null,
                                            ]
                                        ); ?></li>
                                    <?php
                                        if (isset( $blogs[ 14 ] )) {
                                            echo Html::tag(
                                                'li',
                                                Html::a(
                                                    \Yii::t('app', 'learn'),
                                                    [
                                                        'blog/category',
                                                        'slug' => $blogs[ 14 ]->lang->alias,
                                                    ]
                                                )
                                            );
                                        }
                                    ?>
                                </ul>
                                <ul class="style menu-list-second-footer">
                                    <li>
                                        <?php
                                            echo Html::a(
                                                Html::tag(
                                                    'div',
                                                    Html::tag(
                                                        'span',
                                                        Html::img(
                                                            '/images/icons/ico-15.png',
                                                            [
                                                                'width'  => 26,
                                                                'height' => 32,
                                                            ]
                                                        )
                                                    )
                                                ) . Html::tag('p', Html::tag('span', \Yii::t('app', 'serviceinv'))),
                                                [
                                                    'blog/category',
                                                    'slug' => isset( $blogs[ 13 ] ) ? $blogs[ 13 ]->lang->alias : null,
                                                ]
                                            );
                                        ?>
                                    </li>
                                    <?php
                                        /*
                                        ?>
                                        <li><a href="#">
                                                <div><span><img src="/images/icons/ico-16.png" width="70" height="42" alt=""></span>
                                                </div>
                                                <p><span>Клубная карта</span></p></a></li>
                                        */
                                    ?>
                                    <!--                            <li class="version-button-mobile"><a href="#"><div><span><img src="/images/icons/ico-17.png" width="21" height="36" alt=""></span></div><p><span>Мобильная версия</span></p></a></li>-->
                                </ul>
                                <?php
                                    echo Html::a(
                                        \Yii::t('app', 'watchvideos'),
                                        [ 'video/list' ],
                                        [
                                            'class' => 'style videos-footer',
                                        ]
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-box footer-mob visible-xs">
                <div class="style blocks_stores">
                    <div class="container">
                        <div class="row">
                            <?php
                                if (isset( $pages[ 3 ] )) {
                                    ?>
                                    <div class="col-xs-4">
                                        <a href="<?php echo Url::to(
                                            [
                                                'site/page',
                                                'slug' => $pages[ 3 ]->lang->alias,
                                            ]
                                        ); ?>">
                                            <div class="style bl-store">
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td><img src="/images/icons/ico-1.png" alt=""></td>
                                                    </tr>
                                                </table>
                                                <p><?= \Yii::t('app', 'adreses') ?></p>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                                if (isset( $pages[ 4 ] )) {
                                    ?>
                                    <div class="col-xs-4">
                                        <a href="<?php echo Url::to(
                                            [
                                                'site/page',
                                                'slug' => $pages[ 4 ]->lang->alias,
                                            ]
                                        ); ?>">
                                            <div class="style bl-store">
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td><img src="/images/icons/ico-13.png" alt=""></td>
                                                    </tr>
                                                </table>
                                                <p><?= \Yii::t('app', 'oplatai') ?></p>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                                if (isset( $blogs[ 13 ] )) {
                                    ?>
                                    <div class="col-xs-4">
                                        <a href="<?php echo Url::to(
                                            [
                                                'blog/category',
                                                'slug' => $blogs[ 13 ]->lang->alias,
                                            ]
                                        ); ?>">
                                            <div class="style bl-store">
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td><img src="/images/icons/ico-14.png" alt=""></td>
                                                    </tr>
                                                </table>
                                                <p><?= \Yii::t('app', 'uslugi') ?></p>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="style footer-min-wr">
                    <div class="footer-min-center">
                        <div class="footer-min">
                            <div class="style graph-footer">
                                <div class="style graph-footer-title"><?= \Yii::t('app', 'graph1') ?></div>
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="width: 135px;">
                                            <span>пн</span><span>вт</span><span>ср</span><span>чт</span><span>пт</span><span>сб</span>
                                        </td>
                                        <td style="border-left: 1px solid #dfdfdf"><?= \Yii::t('app', 'snd') ?></td>
                                    </tr>
                                    <tr>
                                        <td><p>с 10 до 21</p></td>
                                        <td style="border-left: 1px solid #dfdfdf"><p><?= \Yii::t(
                                                    'app',
                                                    'graph5'
                                                ) ?></p></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="style phone-wr-footer">
                                <div>
                                    <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 303-90-10</span>
                                </div>
                                <div>
                                    <img src="/images/icons/ico-2.png" width="16" height="16" alt=""><span>044 428-65-38</span>
                                </div>
                                <div>
                                    <img src="/images/icons/ico-3.png" width="16" height="16" alt=""><span>050 382-03-00</span>
                                </div>
                                <div>
                                    <img src="/images/icons/ico-4.png" width="16" height="16" alt=""><span>067 385-10-55</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="version-button-full-wr">
                    <div class="style" style="margin-top: 20px;">
                        <div class="style version-button-full">
                            <img src="/images/icons/ico-19.png" width="36" height="35" alt="">
                            <span><?= \Yii::t('app', 'fullver') ?></span>
                        </div>
                    </div>
                </div>
                <div class="videos-footer-mobile">
                    <div class="style" style="margin-top: 20px;">
                        <div style="float: left">
                            <?php
                                echo Html::a(
                                    \Yii::t('app', 'смотреть Видеообзоры'),
                                    [
                                        'video/list',
                                    ],
                                    [
                                        'class' => 'style videos-footer',
                                    ]
                                );
                            ?>
                        </div>
                        <div class="network-copy-mobile">
                            <a target="_blank" href="http://vk.com/extremstyle_ua"></a>
                            <a target="_blank" href="https://www.facebook.com/extremstyle.ua/"></a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="section-box studio_footer">
                <div class="hidden call-button-mobile"></div>
                <div class="call-mobile-wr">
                    <div class="call-mobile">
                        <!--                <div class="call-mobile-scroll">-->
                        <!--                    -->
                        <!--                </div>-->
                        <!--                <div class="button-call-mob">-->
                        <!--                    <div class="callback-footer"><span></span>Обратный звонок</div>-->
                        <!--                </div>-->
                        <div class="button-call-mob">
                            <a class="button-call_" href="tel:044 303-90-10">
                                <p><?= \Yii::t('app', 'makecall') ?></p>
                                <div><span><img src="/images/icons/ico-2.png" width="16" height="16"></span></div>
                                <span>044 303-90-10</span>
                            </a>
                        </div>
                        <div class="button-call-mob">
                            <a class="button-call_" href="tel:044 428-65-38">
                                <p><?= \Yii::t('app', 'makecall') ?></p>
                                <div><span><img src="/images/icons/ico-2.png" width="16" height="16"></span></div>
                                <span>044 428-65-38</span>
                            </a>
                        </div>
                        <div class="button-call-mob">
                            <a class="button-call_" href="tel:050 382-03-00">
                                <p><?= \Yii::t('app', 'makecall') ?></p>
                                <div><span><img src="/images/icons/ico-3.png" width="16" height="16"></span></div>
                                <span>050 382-03-00</span>
                            </a>
                        </div>
                        <!--new_phone-->
                        <div class="button-call-mob">
                            <a class="button-call_" href="tel:067 385-10-55">
                                <p><?= \Yii::t('app', 'makecall') ?></p>
                                <div><span><img src="/images/icons/ico-4.png" width="16" height="16"></span></div>
                                <span>067 385-10-55</span>
                            </a>
                        </div>
                        <div class="button-call-mob"><a class="call-sms" href="sms:050 382-03-00">Отправить SMS</a>
                        </div>
                        <div class="button-call-mob">
                            <?php echo Html::a(
                                \Yii::t('app', 'contadd'),
                                '/contacts/extremstyle.vcf',
                                [ 'class' => 'call-contact' ]
                            ); ?>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="hidden visible_all col-xs-12 col-sm-6">
                                    <div class="copyright">© Extremstyle, <?php echo date('Y'); ?>. <?= \Yii::t(
                                            'app',
                                            'copyright1'
                                        ) ?></div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="artweb-wr">
                                        <a target="_blank" href="http://artweb.ua/"><?= \Yii::t(
                                                'app',
                                                'copyright2'
                                            ) ?></a>
                                        <div class="artweb-img">
                                            <a target="_blank" href="http://artweb.ua/"><img src="/images/artweb-logo.png"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn_scroll" style="display:none;"></div>

        <div id="overlay"></div>
        <div id="overlay_s"></div>

        <!--<script src="https://www.google.com/recaptcha/api.js"></script>-->
        <?php
            if(isset($this->blocks['buy_in_click'])) {
                echo $this->blocks['buy_in_click'];
            }
        ?>
        <div id="inform" class="forms_">
            <span id="modal_close"></span>
            <?php
                echo $this->render('@frontend/views/site/_payment_inform', [ 'model' => new PaymentInform(), ]);
            ?>
        </div>
        <div id="hot_line" class="forms_" style="display: none">
            <span id="modal_close"></span>
            <?php
                /**
                 * @var Page $hotline
                 */
                $hotline = Page::find()
                               ->with('lang')
                               ->where([ 'id' => 7 ])
                               ->one();
                if (!empty( $hotline )) {
                    echo $hotline->lang->body;
                }
            ?>
        </div>

        <div class="basket_modal">
        </div>

        <div class="modal_login forms_" style="display: none">
            <span id="modal_close"></span>
            <div class="already-registered">
                <?= $this->render('../partial/login_form', [ 'model' => new LoginForm(), ]) ?>
            </div>
        </div>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div id="success_form">
                <span id="modal_close"></span>
                <div class="txt-success"><?= Yii::$app->session->getFlash('success'); ?></div>
            </div>


            <?php
            $js = "$('#overlay_s').css({display:'block'});
      setTimeout(function () {
            $('#success_form').css('display', 'block').animate({opacity: 1}, 700);
        },400)";
            $this->registerJs($js, View::POS_READY); ?>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div id="success_form">
                <span id="modal_close"></span>
                <div class="txt-success"><?= Yii::$app->session->getFlash('error'); ?></div>
            </div>


            <?php
            $js = "$('#overlay_s').css({display:'block'});
      setTimeout(function () {
            $('#success_form').css('display', 'block').animate({opacity: 1}, 700);
        },400)";
            $this->registerJs($js, View::POS_READY); ?>
        <?php endif; ?>
        <?php
            echo AssetWidget::widget(
                [
                    'assets' => [
                        'all',
                        'frontend\assets\CdnAsset',
                    ],
                ]
            );
        ?>
        <?php
            //    $this->registerJsFile('https://vk.com/js/api/openapi.js?136', [
            //        'async' => true,
            //    ]);
        ?>
        <?php $this->endBody(); ?>

        <!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter11051485 = new Ya.Metrika({ id:11051485, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/11051485" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    </body>
</html>
<?php
    //    $js = '
    //         function deviceCheck() {
    //            var meta = document.createElement("meta");
    //            meta.name = "viewport";
    //            meta.content = "width=device-width";
    //            document.getElementsByTagName(\'head\')[ 0 ].appendChild(meta);
    //        }
    //        if (device.mobile()) {
    //            deviceCheck();
    //        } else if (device.tablet()) {
    //            deviceCheck();
    //        } else {
    //        }';
    //    $this->registerJs($js, View::POS_READY);
    
    $js = '
            $(\'.jcarousel\')
            .jcarousel(
                {
                    vertical: true,
                    scroll: 1,
                    animation: 250
                }
            );
        ';
    
    $this->registerJs($js, View::POS_READY);
    
    $js = '

            var owlPartners = $(".slider-partners .owl-carousel")
            owlPartners.owlCarousel(
                {
                    responsiveClass: true,
                    loop: true,
                    responsive: {
                        0: {
                            items: 2,
                            slideBy: 1
                        },
                        340: {
                            items: 3,
                            slideBy: 1
                        },
                        530: {
                            items: 5,
                            slideBy: 1
                        },
                        768: {
                            items: 7,
                            slideBy: 1
                        },
                        873: {
                            items: 8,
                            slideBy: 1
                        },
                        1200: {
                            items: 9,
                            slideBy: 1
                        }
                    },
                    navSpeed: 150,
                    nav: true,
                    navText: []

                }
            )

            var owl = $(".owl-carousel")
            owl.owlCarousel(
                {
                    mouseDrag: false,
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: 1,
                            slideBy: 1
                        },
                        768: {
                            items: 3,
                            slideBy: 3
                        },
                        1200: {
                            items: 4,
                            slideBy: 4
                        }
                    },
                    navSpeed: 200,
                }
            )

            $(".next_btn")
            .click(
                function() {
                    $(this)
                    .parents(\'.slider-wr\')
                    .find(owl)
                    .trigger(\'next.owl.carousel\');
                }
            )

            $(".prev_btn")
            .click(
                function() {
                    $(this)
                    .parents(\'.slider-wr\')
                    .find(owl)
                    .trigger(\'prev.owl.carousel\');
                }
            )

            var owlCardImg = $(".slider-partners .owl-carousel")
            owlCardImg.owlCarousel(
                {
                    responsiveClass: true,
                    loop: true,
                    responsive: {
                        0: {
                            items: 1,
                            slideBy: 1
                        },
                        340: {
                            items: 1,
                            slideBy: 1
                        },
                        530: {
                            items: 2,
                            slideBy: 1
                        },
                        768: {
                            items: 3,
                            slideBy: 1
                        },
                        873: {
                            items: 3,
                            slideBy: 1
                        },
                        1200: {
                            items: 3,
                            slideBy: 1
                        }
                    },
                    navSpeed: 150,
                    nav: true,
                    navText: []

                }
            )
        ';
    
    $this->registerJs($js, View::POS_READY);
?>
<?php $this->endPage(); ?>

