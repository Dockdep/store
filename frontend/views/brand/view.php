<?php
    /**
     * @var Brand $model
     * @var array $categoryList
     * @var View  $this
     */
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\Brand;
use artweb\artbox\seo\widgets\Seo;
use yii\helpers\Html;
    use yii\web\View;
    
    $this->title = \Yii::t(
        'app',
        'Компания {name}',
        [
            'name' => $model->lang->title,
        ]
    );
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Html::tag(
            'span',
            \Yii::t('app', 'brands'),
            [
                'itemprop' => 'name',
            ]
        ),
        'url'   => [ 'index' ],
        'itemprop' => 'item',
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Html::tag(
            'span',
            $this->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='3' /></li>\n",
    ];

    $this->params['seo']['fields']['name'] = $model->lang->title;
    $this->params['seo']['title'] = $model->lang->meta_title;
    $this->params['seo']['h1'] = $model->lang->title;
    $this->params['seo']['seo_text'] = $model->lang->seo_text;
    $this->params['seo']['description'] = $model->lang->meta_description;
    $this->params['seo']['meta'] = $model->lang->meta_robots;

?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title_card">
            <h1><?php echo Seo::widget([ 'row' => Seo::H1 ]) ?></h1>
        </div>
    </div>
    <div class="row delivery-row">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9 brands_v">
            <div class="style description_list-wrapper hidden-xs">
                <ul class="description_list">
                    <li class="active">
                        <a class="desk_name" href="#"><?php echo \Yii::t('app', 'history'); ?></a>
                    </li>
                    <?php
                        /*
                        ?>
                        <li>
                            <a class="desk_name" href="#"><?php echo \Yii::t('app', 'techs'); ?></a>
                        </li>
                        */
                    ?>
                </ul>
            </div>
            <div class="style desk_blocks-wr">
                <div class="active desk_list-wr">
                    <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#"><?php echo \Yii::t(
                            'app',
                            'history'
                        ); ?></a>
                    <div class="style desk_delivery brands_history">
                        <div class="logo_brand">
                            <div>
                                <?php
                                    echo ArtboxImageHelper::getImage(
                                        $model->imageUrl,
                                        'brand_list',
                                        [
                                            'alt'   => $model->lang->title,
                                            'title' => $model->lang->title,
                                        ],
                                        90,
                                        true
                                    );
                                ?>
                            </div>
                        </div>
                        <?php
                            echo( $model->lang->history ? : \Yii::t('app', 'no_info_company') );
                        ?>
                    </div>
                </div>
                <?php
                    /*
                    ?>
                    <div class="desk_list-wr">
                        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#"><?php echo \Yii::t('app', 'Технологии'); ?></a>
                        <div class="style desk_delivery brands_history">
                            <a href="#">Посмотреть все товары бренда</a>
                        </div>
                    </div>
                    */
                ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
            <div class="style brands_txt"><?php echo \Yii::t(
                    'app',
                    ( !empty( $categoryList ) ? 'brands_view_1' : 'brands_view_2' )
                ); ?>
            </div>
            <?php
                if (!empty( $categoryList )) {
                    ?>
                    <div class="sidebar style brands_sidebar">
                        <div class="style">
                            <?php
                                foreach ($categoryList as $parentCategory) {
                                    ?>
                                    <div class="brands_cat">
                                        <?php
                                            if (empty( $parentCategory[ 'children' ] )) {
                                                ?>
                                                <div class="brands_cat-title"><?php echo Html::a(
                                                        $parentCategory[ 'name' ] . '(' . $parentCategory[ 'count' ] . ')',
                                                        [
                                                            'catalog/category',
                                                            'filters'  => [
                                                                'brands' => [
                                                                    $model->lang->alias,
                                                                ],
                                                            ],
                                                            'category' => $parentCategory[ 'alias' ],
                                                        ]
                                                    ); ?></div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="brands_cat-title"><?php echo $parentCategory[ 'name' ]; ?></div>
                                                <ul>
                                                    <?php
                                                        foreach ($parentCategory[ 'children' ] as $category) {
                                                            echo Html::tag(
                                                                'li',
                                                                Html::a(
                                                                    $category[ 'name' ] . '(' . $category[ 'count' ] . ')',
                                                                    [
                                                                        'catalog/category',
                                                                        'filters'  => [
                                                                            'brands' => [
                                                                                $model->lang->alias,
                                                                            ],
                                                                        ],
                                                                        'category' => $category[ 'alias' ],
                                                                    ]
                                                                )
                                                            );
                                                        }
                                                    ?>
                                                </ul>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                        <div class="style border_brands"></div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>

</div>
