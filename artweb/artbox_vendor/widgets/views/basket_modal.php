<?php
    /**
     * @var $items array data from session
     * @var $count integer count items in basket
     */
    use yii\helpers\Html;

?>
<div class="order_list">
    <ul>
        <?php
            foreach ($items as $item) {
                ?>
                <li>
                    <div class="order_list_li" data-id="<?= $item[ 'item' ]->id ?>">
                        <div class="delete_item_btn"><i class="fa fa-times"></i></div>
                        <div class="little_img">
                            <?php if (empty( $item[ 'item' ]->product->image )) : ?>
                                <img src="/images/no_photo.png">
                            <?php else : ?>
                                <img src="/images/<?= $item[ 'item' ]->product->image->image ?>" alt="<?= $item[ 'item' ]->product->image->alt ? $item[ 'item' ]->product->image->alt : $item[ 'item' ]->product->title ?>">
                            <?php endif ?>
                        </div>
                        <div class="name_and_code">
                            <span class="name"><?= $item[ 'item' ]->product->title . ' ' . $item[ 'item' ]->title ?></span>
                            <span class="code"><?= Yii::t('app', 'code', '45885-01016049') ?></span>
                        </div>
                        <div class="count_block_wrap">
                            <div class="count_block">
                                <input type="text" name="" class="form-control buy_one_item" value="<?= $item[ 'num' ] ?>">
                                <div class="count_buttons">
                                    <div class="button_plus">+</div>
                                    <div class="button_minus">-</div>
                                </div>
                            </div>
                            <div class="price">
                                <span class="price_val" data-price="<?= $item[ 'item' ]->price ?>"><?= $item[ 'item' ]->price * $item[ 'num' ] ?></span><span class="price_text">грн.</span>
                            </div>
                        </div>
                    </div>
                </li>
            <?php } ?>
    
    </ul>
    <hr>
    <div class="all_price">
        <p><?= Yii::t('app', 'articles') ?>: <span class="all_count"><?= $count ?></span></p>
        <p><?= Yii::t('app', 'sum') ?>:
            <span class="all_price all_price_span"><?= $price ?></span> грн.</p>
    </div>
    <div class="busket_bottom_btn">
        <?= Html::a(Yii::t('app', 'continue_shopping'), '#', [ 'class' => 'close' ]) ?>
        <?= Html::a(Yii::t('app', 'checkout'), [ 'order/first' ], [ 'class' => 'button' ]); ?>
    </div>
</div>
