<?php
    /**
     * @var View          $this
     * @var OrderFrontend $order
     * @var ActiveForm    $form
     */
    use artweb\artbox\ecommerce\models\Basket;
    use common\components\CreditHelper;
    use frontend\models\OrderFrontend;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var Basket $basket
     */
    $basket = \Yii::$app->basket;
    $basketSum = $basketSumFull = $basket->getSum();
    $startPayment = 0;
    if ($basketSum > CreditHelper::MAX_CREDIT_SUM) {
        $startPayment = $basketSum - CreditHelper::MAX_CREDIT_SUM;
        $basketSum = CreditHelper::MAX_CREDIT_SUM;
    }
?>
<div class="row credits-blocks-basked">
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="credit_txt-card">
            <p> <span style="font-weight:700; color:#f26522;"><?= \Yii::t('app', 'p_inform1') ?>:</span><span style="font-weight: 700;"> <?= \Yii::t('app', 'credit1') ?></span></p>
            <p style="padding-left: 85px; position: relative;"><img style="position: absolute;left: 0;top: 50%;margin-top: -35px;" class="logo-bank1" src="/images/otp_1.png" alt=""> <span style="color: #f26522;">*</span><?= \Yii::t('app', 'credit2') ?></p>
        </div>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2">
        <div class="style txt-credits-basked"><?= \Yii::t('app', 'credit3') ?></div>
        <div class="price style">
            <?php
            echo Html::tag('span', $basketSumFull - ($order->credit_sum ? : $startPayment), [
                'class' => 'credit_size_value',
            ]);
            ?>
            <span><?php echo \Yii::t('app', 'грн.'); ?></span>
        </div>


    </div>
    <div class="col-xs-12 col-sm-3 col-md-2">
        <?php
        echo $form->field($order, 'credit_sum')
            ->textInput(
                [
                    'value' => $order->credit_sum ? : $startPayment,
                    'class' => 'form-control credit_sum_input credit_input',
                    'data-default' => $order->credit_sum ? : $startPayment,
                    'data-sum' => $basket->getSum(),
                ]
            );
        ?>
    </div>

    <div class="col-xs-12 col-sm-3 col-md-2">
        <?php
        echo $form->field($order, 'credit_month')
            ->textInput(
                [
                    'value' => $order->credit_month ? : 36,
                    'class' => 'form-control credit_month_input credit_input',
                    'data-default' => $order->credit_month ? : 36,
                ]
            );
        ?>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2">
        <div class="style txt-credits-basked"><?= \Yii::t('app', 'credit4') ?></div>
        <div class="price style">
           <span class="modal_value">
               <span class="credit_value">
                <?php
                echo CreditHelper::getCredit($basketSumFull - ($order->credit_sum ? : $startPayment), $order->credit_month?:36);
                ?>
            </span>
            <span><?php echo \Yii::t('app', 'грн./мес'); ?></span>
        </div>



    </div>
</div>
