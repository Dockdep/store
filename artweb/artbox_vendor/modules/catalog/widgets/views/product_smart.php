<?php
    /**
     * @var $product  artweb\artbox\modules\catalog\models\Product
     */
    use yii\helpers\Html;
    use yii\helpers\Url;

?>
<div class="catalog_item">
    <div class="wrapper">
        <div class="item_container">
            <input class="prodInfo" type="hidden" value="[]">
            <div class="title">
                <?= Html::a(
                    $product->lang->title,
                    Url::to(
                        [
                            'catalog/product',
                            'product' => $product->lang->alias,
                        ]
                    ),
                    [ 'class' => 'btn-product-details' ]
                ) ?>
            </div>
            <div class="img">
                <a class="btn-product-details" href="<?= Url::to(
                    [
                        'catalog/product',
                        'product' => $product->lang->alias,
                    ]
                ) ?>">
                    <?= \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage(
                        $product->enabledVariants[ 0 ]->imageUrl,
                        'list',
                        [
                            'alt'   => $product->category->lang->title . ' ' . $product->fullname,
                            'title' => $product->category->lang->title . ' ' . $product->fullname,
                            'class' => 'selected',
                        ]
                    ) ?>
                </a>
                <div class="info_icons">
                    <a href="#" class="btn buy_button" data-toggle="modal" data-target="#buyForm" data-id="<?= $product->variant->id; ?>" lang="145">Купить</a>
                    <ul class="ul wishlike_block hidden">
                        <li class="compare  hidden">
                            <a onclick="add2compare(); return false;" class="compare compare_text_link_3631483" href="#">К сравнению</a>
                            <span class="icon"></span>
                        </li>
                        <li class="like hidden">
                            <a class="like like_text_link_3631483" href="#">В избранное</a><span class="icon"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="price">
                <div class="dlexfduinxipi">
                    Цена:
                    <span class="main">
                        <?= $product->variant->price ?>
                        <span class="currency">грн</span>
                    </span>
                </div>
            </div>
            <div class="additional_info params">
                <div class="block_title">Особенности</div>
                <div class="descr">
                    <div class="info">
                        <ul class="sv">
                            
                            <li><span>Бренд:</span> <?= $product->brand->lang->title ?></li>
                            
                            <?php foreach ($product->getProperties() as $group): ?>
                                <li>
                                    <span><?= $group->lang->title ?> <?php foreach ( $group->customOptions as $option ) : ?>&nbsp;</span><?= $option->lang->value ?><?php endforeach ?>
                                </li>
                            <?php endforeach; ?>
                        
                        
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="price" style="display: none;">
                    <div class="dlexfduinxipi">
                        Цена:
                        <span class="main">
                            <?php
    
                                echo '<div class="cost-block" itemprop="offers" itemscope itemtype="http://schema.org/Offer">';
    
                                // есть скидка
                                echo '<p  class="cost">';
                                if ($product->enabledVariants[ 0 ]->price_old != 0 && $product->enabledVariants[ 0 ]->price_old != $product->enabledVariants[ 0 ]->price) {
                                    echo '<strike><span id=\'old_cost\' itemprop="price">' . $product->enabledVariants[ 0 ]->price_old . '</span> грн.</strike>&nbsp;';
                                    echo $product->enabledVariants[ 0 ]->price . ' <span>грн.</span></p>';
                                } else {
                                    echo '<span  itemprop="price">' . $product->enabledVariants[ 0 ]->price . ' </span><span>грн.</span></p>';
                                }
                                echo '<meta itemprop="priceCurrency" content = "UAH">';
                                echo '</div>';

                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="opacity_bg"></div>
        </div>
    </div>
</div>