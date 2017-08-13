<?php
    /**
     * @var $model  Product
     */
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\Product;
    use yii\bootstrap\Html;
    use yii\helpers\ArrayHelper;
    


if($this->beginCache('_item_list'.$model->id,[
    'variations' => [\Yii::$app->language],
    'id' => $model->id,
    'duration' => 3600 *24
])){


    $fullname = $model->fullName;
    if(!empty($model->enabledVariants)) {
    $variant = $model->enabledVariants[ 0 ];
    } else {
        $variant = $model->variant;
    }
    $imageMap = ArrayHelper::map($model->enabledVariants, 'sku', 'images', 'lang.title');
    $firstImage = [
        'sku'   => $variant->sku,
        'image' => '',
    ];
    
    $firstIteration = true;
    foreach ($imageMap as $key => $item) {
        $imageMap[ $key ] = array_filter($item);
        foreach ($imageMap[ $key ] as $subKey => $subValue) {
            foreach ($subValue as $subSubValue) {
                if (!empty( $subSubValue )) {
                    $imageMap[ $key ][ $subKey ] = $subSubValue->imageUrl;
                    if ($firstIteration) {
                        $firstImage = [
                            'sku'   => $subKey,
                            'image' => $subSubValue->imageUrl,
                        ];
                    }
                    $firstIteration = false;
                    break;
                }
            }
        }
    }
?>
<div class="item_catalog">
    <div class="bg-status">
        <?php
            if ($model->is_discount) {
                echo Html::tag(
                    'div',
                    Html::tag('span', \Yii::t('app', 'акция')),
                    [
                        'class' => 'sale_bg',
                    ]
                );
            }
            if ($model->is_top) {
                echo Html::tag(
                    'div',
                    Html::tag('span', \Yii::t('app', 'top')),
                    [
                        'class' => 'top_bg',
                    ]
                );
            }
            if ($model->is_new) {
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
                    $firstImage[ 'image' ],
                    'category_item',
                    [
                        'alt'   => $fullname,
                        'title' => $fullname,
                    ],
                    90,
                    true
                ),
                [
                    'catalog/product',
                    'product' => $model->lang->alias,
                    'variant' => $firstImage[ 'sku' ],
                ],
                [
                    'data-pjax' => 0,
                    'title'     => $fullname,
                ]
            );
        ?>
    </div>
    <?php
        echo Html::a(
            yii\helpers\StringHelper::truncate($fullname, 41),
            [
                'catalog/product',
                'product' => $model->lang->alias,
                'variant' => $variant->sku,
            ],
            [
                'class'     => 'style title_cat',
                'data-pjax' => 0,
                'title'     => $model->lang->title,
            ]
        );
    ?>
    <p class="style category_cat">
        <?php
            echo !empty($model->brand) ?$model->brand->lang->title:'';
        ?>
    </p>
    <div class="style price_cat">
        <?php
            if (!empty( $variant->price_old )) {
                ?>
                <p>
                    <span class="price_cat-sale"><?php echo $variant->price_old; ?> <?= \Yii::t('app', 'hrn') ?></span>
                    <?php
                        echo $variant->price;
                    ?>
                    <span> грн.</span>
                </p>
                <div class="price_cat-sale_">
                    -<?php echo round(100 - ( $variant->price * 100 / $variant->price_old )); ?> %
                </div>
                <?php
            } else {
                ?>
                <p>
                    <?php
                        echo $variant->price;
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
                        'product' => $model->lang->alias,
                        'variant' => $variant->sku,
                    ],
                    [
                        'class'     => 'btn_view_cat',
                        'data-pjax' => 0,
                    ]
                );
            ?>
            <a class="btn_buy_cat<?php echo ($variant->stock?'':' disabled'); ?>" href="#" data-variant="<?php echo $variant->id; ?>">
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
                            'product' => $model->lang->alias,
                            'variant' => $variant->sku,
                            '#'       => 'video',
                        ],
                        [
                            'class' => 'videos_btn' . ( empty( $model->videos ) ? ' disabled' : '' ),
                        ]
                    )
                ?></div>
            <div class="addit_bl">
                <div class="slider_cat-wr">
                    <ul style="top:0" class="<?php
                        if (count($imageMap) > 3) {
                            echo "jcarousel jcarousel-skin-tango";
                        } else {
                            echo "not-carousel";
                        }
                    ?>">
                        <?php
                            foreach ($imageMap as $item) {
                                if (!empty( $item )) {
                                    echo Html::tag(
                                        'li',
                                        Html::a(
                                            ArtboxImageHelper::getImage(
                                                '/storage/white.jpg',
                                                'category_thumb',
                                                [
                                                    'alt'   => $model->lang->title,
                                                    'title' => $model->lang->title,
                                                    'class' => 'artbox-lazy-event',
                                                    'data-original' => ArtboxImageHelper::getImageSrc(current($item), 'category_thumb', null, 90),
                                                ],
                                                10
                                            ),
                                            [
                                                'catalog/product',
                                                'product' => $model->lang->alias,
                                                'variant' => key($item),
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
<?php $this->endCache(); }?>