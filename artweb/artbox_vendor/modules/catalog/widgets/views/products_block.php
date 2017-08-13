<?php
    use artweb\artbox\modules\catalog\models\Product;
    use yii\web\View;
    
    /**
     * @var Product[] $products
     */
?>
<?php if(!empty( $products )) { ?>
    <div class="_prd_spec-wr">
        <div class="special-products products<?= ( !empty( $class ) ? ' ' . $class : '' ) ?>">
            <span style="text-align: center;
    text-transform: uppercase;
    font-size: 20px;   display: block;
    -webkit-margin-before: 1em;
    -webkit-margin-after: 1em;
    -webkit-margin-start: 0;
    -webkit-margin-end: 0;
    font-weight: bold;"><?= $title ?></span>
            <div id="<?= $class ?>">
                <?php foreach($products as $product) : ?>
                    <?= $this->render('product_smart', [ 'product' => $product ]); ?>
                <?php endforeach ?>
            </div>
            <div class="both"></div>
        </div>
    </div>
    <?php $js = "$('#$class').owlCarousel({
navigation:true,
navigationText: []
})
";
    $this->registerJs($js, View::POS_READY);
    ?>
<?php } ?>