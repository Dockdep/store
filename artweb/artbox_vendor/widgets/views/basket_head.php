<?php
use yii\bootstrap\Html;
?>
<div class="basket_head">
    <img src="/images/ico_basket.png" class="bh_cell img">
    <div class="bh_cell text">
        <div class="basket_head_title"><?= Yii::t('app','basket');?> <?= $count? Html::tag('span',$count,['class'=>'head_basket_count']) :'' ?></div>
    </div>
    <i class="head-down bh_cell"></i>
</div>
