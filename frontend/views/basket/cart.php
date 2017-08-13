<?php
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View  $this
     * @var int   $count
     */
?>
<a href="<?php echo Url::to(['order/basket']); ?>" class="basket_">
    <span class="ico_b_"><span><?php echo $count; ?></span></span>
    <p><span class="name_bas"><?= \Yii::t('app','basket')?></span><span class="separator_bas"><i></i></span></p>
</a>
