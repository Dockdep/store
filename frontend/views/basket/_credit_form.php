<?php
    /**
     * @var View        $this
     * @var OrderCredit $order
     * @var ActiveForm  $form
     */
    use artweb\artbox\ecommerce\models\Basket;
    use common\components\CreditHelper;
    use frontend\models\OrderCredit;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var Basket $basket
     */
    $basket = \Yii::$app->basket;
    $basketSumFull = $basketSum = $basket->getSum();
    $startPayment = 0;
    if ($basketSum > CreditHelper::MAX_CREDIT_SUM) {
        $startPayment = $basketSum - CreditHelper::MAX_CREDIT_SUM;
        $basketSum = CreditHelper::MAX_CREDIT_SUM;
    }
?>
<div class="row credits-blocks-basked">
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="credit_txt-card">
            <p> <span style="font-weight:700; color:#f26522;">Внимание:</span><span style="font-weight: 700;"> максимальная сумма кредита 25000 грн.</span></p>
            <p style="padding-left: 85px; position: relative;"><img style="position: absolute;left: 0;top: 50%;margin-top: -35px;" class="logo-bank1" src="/images/otp_1.png" alt=""><span style="color: #f26522;">*</span>Для оформления кредита в Оtpbank клиент, возраст которого от 21 до 69 лет,  должен получить счет-фактуру в магазине розничной сети и предъявить ее в ближайшем отделении Оtpbank, предоставить паспорт и идентификационный номер. При получение гарантийного письма от банка вы получаете товар.</p>
        </div>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2">
        <div class="style txt-credits-basked">Сумма кредита</div>
        <div class="price style">
            <?php
            echo Html::tag('span', $basketSum, [
                'class' => 'modal_size_value',
            ]);
            ?>
            <span><?php echo \Yii::t('app', 'грн.'); ?></span>
        </div>

    </div>
    <div class="col-xs-12 col-sm-3 col-md-2 first-pay-credit">
        <?php
        $minVal = $basketSumFull - CreditHelper::MAX_CREDIT_SUM;
        if($minVal < 0) {
            $minVal = 0;
        }
        echo $form->field($order, 'credit_sum')
            ->input(
                'number',
                [
                    'max'          => $basketSumFull - CreditHelper::MIN_CREDIT_SUM,
                    'min'          => $minVal,
                    'value'        => $order->credit_sum ? : $startPayment,
                    'class'        => 'form-control modal_sum_input modal_input',
                    'data-default' => $order->credit_sum ? : $startPayment,
                    'data-sum' => $basketSumFull,
                ]
            );
        ?>
    </div>

    <div class="col-xs-12 col-sm-3 col-md-2 mounts-credit">
        <?php
        echo $form->field($order, 'credit_month')
            ->input(
                'number',
                [
                    'max' => 36,
                    'min' => 3,
                    'value'        => $order->credit_month ? : 36,
                    'class'        => 'form-control modal_month_input modal_input',
                    'data-default' => $order->credit_month ? : 36,
                ]
            );
        ?>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2">
        <div class="style txt-credits-basked">Ежемесячный платеж</div>
        <div class="price style">
           <span class="modal_value">
                <?php
                echo CreditHelper::getCredit($order->credit_sum ? : $basketSum, $order->credit_month ? : 36);
                ?>
            </span>
            <span><?php echo \Yii::t('app', 'грн./мес'); ?></span>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12">
        <div class="style border-credit-basket"></div>
    </div>
</div>
