<?php
    use frontend\models\OrderFrontend;
    use yii\web\View;
    
    /**
     * @var View          $this
     * @var OrderFrontend $model
     */
?>
<script>
    ga('require', 'ecommerce');
    ga('ecommerce:addTransaction', {
        'id': '<?php echo $model->id; ?>'
    });
    <?php foreach ($model->products as $product) { ?>
    ga('ecommerce:addItem', {
        'id': '<?php echo $model->id; ?>',
        'name': '<?php echo $product->product_name . $product->name; ?>',
        'price': '<?php echo $product->price; ?>',
        'quantity': '<?php echo $product->count; ?>'
    });
    <?php } ?>
    ga('ecommerce:send');
</script>
<h3><?php echo \Yii::t('app', 'order_success'); ?></h3>
<p>
    <?php
        echo \Yii::t(
            'app',
            'order_success2',
            [
                'order_id' => $model->id,
            ]
        );
    ?>
</p>
