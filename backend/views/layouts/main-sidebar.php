<?php
use yii\helpers\Url;
use yii\widgets\Menu;
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <?php
        $items = [
            [
                'label' => 'Заказы',
                'url' => ['/ecommerce/order'],
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-shopping-cart"></i> <span>{label}</span></a>',
                'options' => ['class'=>\Yii::$app->user->can('order') ? '' :'hide'],
                'active' => preg_match('/^order.*$/', $this->context->id) ||
                            preg_match('/^label.*$/', $this->context->id) ||
                            preg_match('/^statistics.*$/', $this->context->id) ||
                            preg_match('/^delivery.*$/', $this->context->id) ||
                            preg_match('/^order-payment.*$/', $this->context->id) ||
                            preg_match('/^sms-template.*$/', $this->context->id) ? true : false,
                'items' => [
                    [
                        'label' => 'Список заказов',
                        'url' => ['/ecommerce/order'],
                        'options' => ['class'=>\Yii::$app->user->can('order') ? '' :'hide'],
                        'active' => preg_match('/^order.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Статистика заказов',
                        'url' => ['/ecommerce/statistics'],
                        'options' => ['class'=>\Yii::$app->user->can('order') ? '' :'hide'],
                        'active' => preg_match('/^statistics.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Шаблоны смс',
                        'url' => ['/sms-template/index'],
                        'options' => ['class'=>\Yii::$app->user->can('sms-template') ? '' :'hide'],
                        'active' => preg_match('/^sms-template.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Доставка',
                        'url' => ['/ecommerce/delivery'],
                        'options' => ['class'=>\Yii::$app->user->can('delivery') ? '' :'hide'],
                        'active' => preg_match('/^delivery.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Способы оплаты',
                        'url' => ['/ecommerce/order-payment'],
                        'options' => ['class'=>\Yii::$app->user->can('delivery') ? '' :'hide'],
                        'active' => preg_match('/^order-payment.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Статус товара',
                        'url' => ['/ecommerce/label'],
                        'options' => ['class'=>\Yii::$app->user->can('label') ? '' :'hide'],
                        'active' => preg_match('/^label.*$/', $this->context->id),
                    ],
                ],
            ],
            [
                'label' => 'eCommerce',
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-barcode"></i> <span>{label}</span></a>',
                'url' => ['/product/manage'],
                'active' => preg_match('/^manage.*$/', $this->context->id) ||
                            preg_match('/^category.*$/', $this->context->id) ||


                            preg_match('/^brand.*$/', $this->context->id) ||
                            preg_match('/^product-unit.*$/', $this->context->id) ||
                            preg_match('/^import.*$/', $this->context->id) ||
                            preg_match('/^tax-group.*$/', $this->context->id) ||
                            preg_match('/^export.*$/', $this->context->id) ? true : false,
                'options' => ['class'=>\Yii::$app->user->can('product') || \Yii::$app->user->can('category') || \Yii::$app->user->can('brand') || \Yii::$app->user->can('rubrication')  ? '' :'hide'],
                'items' => [
                    [
                        'label' => 'Товары',
                        'url' => ['/ecommerce/manage'],
                        'options' => ['class'=>\Yii::$app->user->can('product') ? '' :'hide'],
                        'active' => preg_match('/^manage.*$/', $this->context->id),
                    ],

                    [
                        'label' => 'Категории',
                        'url' => ['/ecommerce/category'],
                        'options' => ['class'=>\Yii::$app->user->can('category') ? '' :'hide'],
                        'active' => preg_match('/^category.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Бренды',
                        'url' => ['/ecommerce/brand'],
                        'options' => ['class'=>\Yii::$app->user->can('brand') ? '' :'hide'],
                        'active' => preg_match('/^brand[^-]*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Размеры',
                        'url' => ['/ecommerce/brand-size'],
                        'options' => ['class'=>\Yii::$app->user->can('brand') ? '' :'hide'],
                        'active' => preg_match('/^brand-size.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Единицы измерения',
                        'url' => ['/ecommerce/product-unit'],
                        'options' => ['class'=>\Yii::$app->user->can('product') ? '' :'hide'],
                        'active' => preg_match('/^product-unit.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Импорт товаров',
                        'url' => ['/import/import'],
                        'options' => ['class'=>\Yii::$app->user->can('product') ? '' :'hide'],
                        'active' => preg_match('/^import.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Экспорт товаров',
                        'url' => ['/export/export'],
                        'options' => ['class'=>\Yii::$app->user->can('product') ? '' :'hide'],
                        'active' => preg_match('/^export.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Характеристики Товаров',
                        'url' => Url::toRoute(['/ecommerce/tax-group', 'level'=> '0']),
                        'options' => ['class'=>\Yii::$app->user->can('rubrication') ? '' :'hide'],
                        'active' => preg_match('/^tax-group.*$/', $this->context->id) && (\Yii::$app->request->getQueryParam('level') == 0),
                    ],
                    [
                        'label' => 'Характеристики Модификаций',
                        'url' => Url::toRoute(['/ecommerce/tax-group', 'level'=> '1']),
                        'options' => ['class'=>\Yii::$app->user->can('rubrication') ? '' :'hide'],
                        'active' => preg_match('/^tax-group.*$/', $this->context->id) && (\Yii::$app->request->getQueryParam('level') == 1),
                    ]
                ]
            ],
            [
                'label' => 'Акции',
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-piggy-bank"></i> <span>{label}</span></a>',
                'url' => ['/event/event/index'],
                'options' => ['class'=>\Yii::$app->user->can('event') ? '' :'hide'],
            ],
            [
                'label' => 'Слайдер/Банеры',
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-picture"></i> <span>{label}</span></a>',
                'options' => ['class'=>\Yii::$app->user->can('banner') || \Yii::$app->user->can('slider') ? '' :'hide'],
                'active' => preg_match('/^slider.*$/', $this->context->id) || preg_match('/^banner.*$/', $this->context->id) ? true : false,
                'items' => [
                    [
                        'label' => 'Слайдер',
                        'url' => ['/design/slider/index'],
                        'options' => ['class'=>\Yii::$app->user->can('slider') ? '' :'hide'],
                    ],
                    [
                        'label' => 'Банер',
                        'url' => ['/design/banner/index'],
                        'options' => ['class'=>\Yii::$app->user->can('banner') ? '' :'hide'],
                    ],
                    [
                        'label' => 'Видео',
                        'url' => ['/video/index'],
                        'options' => ['class'=>\Yii::$app->user->can('video') ? '' :'hide'],
                    ],
                ]
            ],
            [
                'label' => 'Текстовые страницы',
                 'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-duplicate"></i> <span>{label}</span></a>',
                'url' => ['/page/index'],
                'options' => ['class'=>\Yii::$app->user->can('page') ? '' :'hide'],
            ],
            [
                'label' => 'Настройки кеша',
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-duplicate"></i> <span>{label}</span></a>',
                'url' => ['/setting/index'],
                'options' => ['class'=>\Yii::$app->user->can('setting') ? '' :'hide'],
            ],
            [
                'label' => 'Блог',
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-edit"></i> <span>{label}</span></a>',
                'options' => ['class'=>\Yii::$app->user->can('blog') ? '' :'hide'],
                'active' => preg_match('/^blog.*$/', $this->context->id) ? true : false,
                'items' => [
                    [
                        'label' => 'Статьи',
                        'url' => ['/blog/blog-article'],
                        'options' => ['class'=>\Yii::$app->user->can('blog') ? '' :'hide'],
                        'active' => preg_match('/.*blog-article.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Рубрики',
                        'url' => ['/blog/blog-category'],
                        'options' => ['class'=>\Yii::$app->user->can('blog') ? '' :'hide'],
                        'active' => preg_match('/.*blog-category.*$/', $this->context->id),
                    ],
                    [
                        'label' => 'Тэги',
                        'url' => ['/blog/blog-tag'],
                        'options' => ['class'=>\Yii::$app->user->can('blog') ? '' :'hide'],
                        'active' => preg_match('/.*blog-tag.*$/', $this->context->id),
                    ],
                ]
            ],
            [
                'label' => 'SEO',
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-search"></i> <span>{label}</span></a>',
                'active' => preg_match('/^seo.*$/', $this->context->id) || preg_match('/^seo-category.*$/', $this->context->id) ? true : false,
                'options' => ['class'=>\Yii::$app->user->can('seo') || \Yii::$app->user->can('seo-category') ? '' :'hide'],
                'items' => [
                    [
                        'label' => 'URL',
                        'url' => ['/seo/seo/index'],
                        'options' => ['class'=>\Yii::$app->user->can('seo') ? '' :'hide'],
                    ],
                    [
                        'label' => 'Шаблоны',
                        'url' => ['/seo/seo-category/index'],
                        'options' => ['class'=>\Yii::$app->user->can('seo-category') ? '' :'hide'],
                    ]
                ]
            ],

            [
                'label' => 'Фон',
                'url' => ['/design/bg/index'],
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-picture"></i> <span>{label}</span></a>',
                'options' => ['class'=>\Yii::$app->user->can('bg')? '' :'hide']
            ],
            [
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-user"></i> <span>{label}</span></a>',
                'label' => 'Пользователи',
                'url' => ['/customer/index'],
                'options' => ['class'=>\Yii::$app->user->can('customer') ? '' :'hide'],
            ],
            [
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-comment"></i> <span>{label}</span></a>',
                'label' => 'Комментарии',
                'url' => ['/artbox-comments'],
                'options' => ['class'=>\Yii::$app->user->can('artbox-comments') ? '' :'hide'],
            ],
            [
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-comment"></i> <span>{label}</span></a>',
                'label' => 'Обратная связь',
                'url' => ['/feedback'],
                'options' => ['class'=>\Yii::$app->user->can('feedback') ? '' :'hide'],
            ],
            [
                'label' => 'Настройка ролей',
                'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-cog"></i> <span>{label}</span></a>',
                'active' => preg_match('/^user.*$/', $this->context->id)
                            || preg_match('/^access.*$/', $this->context->id) ? true : false,
                'options' => ['class'=>\Yii::$app->user->can('user') || \Yii::$app->user->can('user') || \Yii::$app->user->can('permit')  ? '' :'hide'],
                'items' => [
                    [
                        'label' => 'Администраторы',
                        'url' => ['/user/index'],
                        'options' => ['class'=>\Yii::$app->user->can('user') ? '' :'hide'],
                    ],
                    [
                        'label' => 'управление ролями',
                        'url' => ['/permit/access/role'],
                        'options' => ['class'=>\Yii::$app->user->can('permit') ? '' :'hide'],
                    ],
                    [
                        'label' => 'управление правами доступа',
                        'url' => ['/permit/access/permission'],
                        'options' => ['class'=>\Yii::$app->user->can('permit') ? '' :'hide'],
                    ]
                ]
            ],



        ];


        if (Yii::$app->user->isGuest) {
            array_push($items,
                ['label' => 'Signup', 'url' => ['/admin/site/signup']], ['label' => 'Login', 'url' => ['/admin/site/login']]
            );
        } else {
            array_push($items,
                [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url'=>'/admin/site/logout',
                    'template'=>'<a href="{url}"> <i class="glyphicon glyphicon-log-out"></i> <span>{label}</span></a>',
                ]
            );
        }
        echo Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
            'items' =>$items,

        ]);
        ?>
        <!-- sidebar menu: : style can be found in sidebar.less -->

    </section>
    <!-- /.sidebar -->
</aside>