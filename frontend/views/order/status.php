<?php
    /**
     * @var View               $this
     * @var OrderFrontend      $model
     * @var OrderFrontend|null $result
     * @var Label[]            $labels
     */
    use artweb\artbox\ecommerce\models\Label;
    use frontend\models\OrderFrontend;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    $this->title = \Yii::t('app', 'Статус заказа');
    $this->params['breadcrumbs'][] = [
        'label' => Html::tag(
            'span',
            $this->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title_card"><?php echo $this->title; ?></div>
    </div>
    <div class="row status_row">
        <div class="col-xs-12 col-sm-12 status_txt"><?= \Yii::t('app','status1')?></div>
        <div class="col-xs-12 col-sm-12">
            <div class="style form_status">
                <?php
                    $form = ActiveForm::begin(
                        [
                            'method' => 'GET',
                            'action' => [ 'order/status' ],
                        ]
                    );
                    echo $form->field($model, 'id')
                              ->label(false)
                              ->textInput();
                    echo Html::submitButton(\Yii::t('app', 'more1'));
                    $form::end();
                ?>
            </div>
            <?php
                if (!empty( $model->id ) && empty( $result )) {
                    echo Html::tag(
                        'div',
                        \Yii::t('app', 'status2'),
                        [
                            'class' => 'style not_found_status',
                        ]
                    );
                } elseif (!empty( $model->id )) {
                    ?>
                    <div class="style status_ hidden-sm hidden-md hidden-lg">
                        <span>Статус: </span>
                        <?php
                            echo $result->labelModel->lang->title;
                        ?>
                    </div>
                    
                    <div class="style status_scheme hidden-xs">
                        <ul>
                            <?php
                                $activated = false;
                                $count = 0;
                                foreach ($labels as $label) {
                                    echo Html::tag(
                                        'li',
                                        $label->lang->title . Html::tag('span') . Html::tag('div'),
                                        [
                                            'class' => ( !$activated ? 'active' : '' ) . ( ( ++$count % 2 ) ? ' up' : '' ),
                                        ]
                                    );
                                    if($result->label == $label->id) {
                                        $activated = true;
                                    }
                                }
                            ?>
<!--                            <li class="active up">Обрабатывается Менеджером<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="active">Комплектуется<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="up">Перемещается<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="">Ожидает получения оплаты<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="up">Ожидает клиента в пункте самовывоза<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="">Оплата получена, заказ готовится к отправке<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="up">Передан в службу доставки<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="">Ожидаем получения Товара, чтобы получить возврат или обмен<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
<!--                            <li class="up">Заказ Выполнен<span></span>-->
<!--                                <div></div>-->
<!--                            </li>-->
                        </ul>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>

</div>



