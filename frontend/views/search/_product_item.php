<?php
    /**
     * @var $model  Product
     */
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\Product;
    use yii\bootstrap\Html;
    use yii\helpers\ArrayHelper;

    $data = $model[ '_source' ];
    $countVariant = [];
    foreach ($data[ 'variants' ] as $item) {
        if (!empty( $item[ 'image' ] ) && !in_array($item['title'],$countVariant)) {
            $countVariant[]=$item['title'];
        }
    }
    ArrayHelper::multisort($data[ 'variants' ], 'stock', [ SORT_DESC ]);

?>
<div class="item_catalog">
    <div class="bg-status">
        <?php
            if ($data[ 'is_discount' ]) {
                echo Html::tag(
                    'div',
                    Html::tag('span', \Yii::t('app', 'акция')),
                    [
                        'class' => 'sale_bg',
                    ]
                );
            }
            if ($data[ 'is_top' ]) {
                echo Html::tag(
                    'div',
                    Html::tag('span', \Yii::t('app', 'top')),
                    [
                        'class' => 'top_bg',
                    ]
                );
            }
            if ($data[ 'is_new' ]) {
                echo Html::tag(
                    'div',
                    Html::tag('span', \Yii::t('app', 'new')),
                    [
                        'class' => 'new_bg',
                    ]
                );
            }
        ?>
    </div>
    <div class="img style">
        <?php
            echo Html::a(
                ArtboxImageHelper::getImage(
                    $data[ 'variants' ][ 0 ][ 'image' ],
                    'category_item',
                    [
                        'alt'   => $data[ 'full_name' ],
                        'title' => $data[ 'full_name' ],
                    ],
                    90,
                    true
                ),
                [
                    'catalog/product',
                    'product' => $data[ 'alias' ],
                    'variant' => $data[ 'variants' ][ 0 ][ 'sku' ],
                ],
                [
                    'data-pjax' => 0,
                    'title'     => $data[ 'full_name' ],
                ]
            );
        ?>
    </div>
    <?php
        echo Html::a(
            yii\helpers\StringHelper::truncate($data[ 'full_name' ], 41),
            [
                'catalog/product',
                'product' => $data[ 'alias' ],
                'variant' => $data[ 'variants' ][ 0 ][ 'sku' ],
            ],
            [
                'class'     => 'style title_cat',
                'data-pjax' => 0,
                'title'     => $data[ 'full_name' ],
            ]
        );
    ?>
    <p class="style category_cat">
        <?php
            echo isset( $data[ 'brand' ][ 'title' ] ) ? $data[ 'brand' ][ 'title' ] : '';
        ?>
    </p>
    <div class="style price_cat">
        <?php
            if (!empty( $data[ 'variants' ][ 0 ][ 'old_price' ] )) {
                ?>
                <p>
                    <span class="price_cat-sale"><?php echo $data[ 'variants' ][ 0 ][ 'old_price' ] ?> <?= \Yii::t(
                            'app',
                            'hrn'
                        ) ?></span>
                    <?php
                        echo $data[ 'variants' ][ 0 ][ 'price' ];
                    ?>
                    <span> грн.</span>
                </p>
                <div class="price_cat-sale_">
                    -<?php echo round(
                        100 - ( $data[ 'variants' ][ 0 ][ 'price' ] * 100 / $data[ 'variants' ][ 0 ][ 'old_price' ] )
                    ); ?> %
                </div>
                <?php
            } else {
                ?>
                <p>
                    <?php
                        echo $data[ 'variants' ][ 0 ][ 'price' ];
                    ?>
                    <span> <?= \Yii::t('app', 'hrn') ?></span>
                </p>
                <?php
            }
        ?>
    </div>
    <div class="style buttons_cat-wr">
        <div class="buttons_cat">
            <?php
                
                echo Html::a(
                    \Yii::t('app', 'Подробнее'),
                    [
                        'catalog/product',
                        'product' => $data[ 'alias' ],
                        'variant' => $data[ 'variants' ][ 0 ][ 'sku' ],
                    ],
                    [
                        'class'     => 'btn_view_cat',
                        'data-pjax' => 0,
                    ]
                );
            ?>
            <a class="btn_buy_cat<?php echo( $data[ 'variants' ][ 0 ][ 'stock' ] ? '' : ' disabled' ); ?>" href="#" data-variant="<?php echo $data[ 'variants' ][ 0 ][ 'id' ] ?>">
                <?php
                    if ($data[ 'variants' ][ 0 ][ 'stock' ]) {
                        ?>
                        <p><?= \Yii::t('app', 'buy') ?></p>
                        <?= \Yii::t('app', 'span_buy') ?>
                        <?php
                    } else {
                        echo \Yii::t('app', 'Нет в наличии');
                    }
                ?>
            </a>
        </div>
    </div>
</div>
<div class="additional_wr">
    <div class="additional_bg">
        <div class="addit_wr">
            <div class="addit_bl"><?php
                    echo Html::a(
                        '',
                        [
                            'catalog/product',
                            'product' => $data[ 'alias' ],
                            'variant' => $data[ 'variants' ][ 0 ][ 'sku' ],
                            '#'       => 'video',
                        ],
                        [
                            'class' => 'videos_btn' . ( empty( $data[ 'videos' ] ) ? ' disabled' : '' ),
                        ]
                    )
                ?></div>
            <div class="addit_bl">
                <div class="slider_cat-wr">
                    <ul style="top:0" class="<?php
                        if (count($countVariant) > 3) {
                            echo "jcarousel jcarousel-skin-tango";
                        } else {
                            echo "not-carousel";
                        }
                    ?>">
                        <?php
                            $checkVariant = [];
                            foreach ($data[ 'variants' ] as $item) {
                                if (!empty( $item[ 'image' ] ) && !in_array($item['title'],$checkVariant)) {
                                    $checkVariant[]=$item['title'];
                                    echo Html::tag(
                                        'li',
                                        Html::a(
                                            ArtboxImageHelper::getImage(
                                                '/storage/white.jpg',
                                                'category_thumb',
                                                [
                                                    'alt'           => $data[ 'full_name' ],
                                                    'title'         => $data[ 'full_name' ],
                                                    'class'         => 'artbox-lazy-event',
                                                    'data-original' => $item[ 'image' ],
                                                ],
                                                10
                                            ),
                                            [
                                                'catalog/product',
                                                'product' => $data[ 'alias' ],
                                                'variant' => $item[ 'sku' ],
                                            ],
                                            [
                                                'data-pjax' => 0,
                                            ]
                                        )
                                    );
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="addit_bl">
                <div class="style addit_buttons">
                    <?php
                        /*
                        ?>
                        <div><a class="btn_aside" href="#">отложить</a></div>
                        <div><a class="btn_compare" href="#">сравнить</a></div>
                        */
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
