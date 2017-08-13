<?php
    use artweb\artbox\ecommerce\models\Basket;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use frontend\models\OrderCredit;
    use yii\bootstrap\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var View             $this
     * @var ProductVariant[] $models
     * @var Basket           $basket
     * @var array            $data
     */
    $data = $basket->getData();
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="style basket-modal-bg">
                <span id="modal_close"></span>
                <div class="style model-name-test"> <?= \Yii::t('app','you_add')?></div>
                <?php
                    echo $this->render(
                        '@frontend/views/basket/basket_table',
                        [
                            'models' => $models,
                            'basket' => $basket,
                            'data'   => $data,
                        ]
                    );
                ?>
                <div class="style">
                    <?php
                        $form = ActiveForm::begin([
                            'id' => 'basket-modal-form',
                            'method' => 'POST',
                            'action' => Url::to(['order/basket']),
                                                  ]);
                        if($basket->isCredit) {
                            echo $this->render('_credit_form', [
                                'order' => new OrderCredit(),
                                'form' => $form,
                            ]);
                            echo Html::submitButton(\Yii::t('app', 'оформить заказ'),
                                [
                                    'class' => 'btn_link_basket',
                                ]
                            );
                        } else {
                            echo Html::a(
                                \Yii::t('app', 'оформить заказ'), [
                                'order/basket',
                            ],
                                [
                                    'class' => 'btn_link_basket',
                                ]
                            );
                        }
                    ?>
                    <span id="modal_close-2"><?= \Yii::t('app','cont_buy')?></span>
                    <?php
                    $form::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
