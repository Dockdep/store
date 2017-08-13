<?php
    /**
     * @var View $this
     */
    use frontend\models\OrderFrontend;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;

?>
<?php
    $orderFast = new OrderFrontend(
        [
            'scenario' => OrderFrontend::SCENARIO_FAST,
        ]
    );
?>
<div id="buy_in_click" class="forms_" style="display: none;">
    <span id="modal_close"></span>
    <div class="style model-name-test">Купить в один клик</div>
    <div class="style inform_form-wr">
        <?php
            $formFast = ActiveForm::begin(
                [
                    'id' => 'one-click-form',
                    'action' => ['order/fast-buy'],
                ]
            );
        ?>
        <div class="style new_wrapp_in">
            <div class="style">
                <?php
                    echo $formFast->field(
                        $orderFast,
                        'variant_id'
                    )->label(false)->hiddenInput();
                    echo $formFast->field(
                        $orderFast,
                        'name',
                        [
                            'options' => [
                                'class' => 'input-wr',
                            ],
                        ]
                    )->textInput();
                    echo $formFast->field(
                        $orderFast,
                        'phone',
                        [
                            'options' => [
                                'class' => 'input-wr',
                            ],
                        ]
                    )->widget(MaskedInput::className(), [
                        'mask' => '+38(999)999-99-99',
                    ]);
                ?>
            </div>
            <div class="style">
                <?php
                    echo Html::submitButton(\Yii::t('app', 'Купить'));
                ?>
            </div>
        </div>
        <?php
            $formFast::end();
        ?>
    </div>
</div>
