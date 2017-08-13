<?php
    use artweb\artbox\ecommerce\models\Basket;
    use artweb\artbox\ecommerce\models\Delivery;
    use frontend\models\OrderFrontend;
    use artweb\artbox\ecommerce\models\ProductVariant;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View             $this
     * @var Basket           $basket
     * @var array            $data
     * @var ProductVariant[] $models
     * @var OrderFrontend    $order
     * @var Delivery[]       $deliveries
     */
    
    $this->title = \Yii::t('app', 'basket');
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title_card"><?= \Yii::t('app','your_basket')?></div>
    </div>
    <div class="row delivery-row">
        <!-- если корзина пустая-->
        <?php if (empty( $models )) {
            ?>
            <div class="col-xs-12 col-sm-12 title_empty_basket">
                <?php echo \Yii::t('app', 'basket_empty'); ?><br/>
<!--                <span>Вы можете выбрать товар в <a href="--><?php //echo Url::to([ 'catalog/index' ]); ?><!--">нашем каталоге</a></span>-->
            </div>
            <?php
        } else {
            ?>
            <div class="col-xs-12 col-sm-12 basket_page">
                <?php echo $this->render(
                    '@frontend/views/basket/basket_table',
                    [
                        'models' => $models,
                        'basket' => $basket,
                        'data'   => $data,
                    ]
                );
                ?>
            </div>
            <div class="col-xs-12 col-sm-12">
                <?php
                    echo $this->render(
                        ( \Yii::$app->user->isGuest ? '_form_guest' : '_form' ),
                        [
                            'order'      => $order,
                            'deliveries' => $deliveries,
                        ]
                    );
                ?>
            </div>
            <?php
        }
        ?>
        <!------------------------>
    </div>
    
    <div id="terms_of_use" class="forms_reg">
        <span id="modal_close"></span>
        <div style="font-size: 22px;font-weight: 700" class="style"><?= \Yii::t('app','basket1')?></div>
        <div style="font-size: 18px;font-weight: 700;margin-top: 10px;" class="style"><?= \Yii::t('app','basket2')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','basket3')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','basket4')?></div>
        <div style="font-size: 18px;font-weight: 700;margin-top: 10px;" class="style"><?= \Yii::t('app','basket5')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','basket6')?>
            <a href="/">extremstyle.ua</a><?= \Yii::t('app','basket7')?>
            <a href="/">extremstyle.ua</a> <?= \Yii::t('app','basket8')?>
        </div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','basket9')?>
            <a href="/">extremstyle.ua</a> <?= \Yii::t('app','basket10')?>
            <a href="/">extremstyle.ua</a> <?= \Yii::t('app','basket11')?>
        </div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','basket12')?>
            <a href="/">extremstyle.ua</a><?= \Yii::t('app','basket13')?>
        </div>
    </div>
    
    <div id="rules_of" class="forms_reg">
        <span id="modal_close"></span>
        <div style="font-size: 20px;font-weight: 700" class="style"><?= \Yii::t('app','basket14')?></div>
        <div style="font-size: 18px;font-weight: 700" class="style"><?= \Yii::t('app','basket15')?></div>
        <div class="style">
            <?= \Yii::t('app','basket16')?><br/>
            <?= \Yii::t('app','basket17')?>
        </div>
        <div style="font-weight: 700" class="style"><?= \Yii::t('app','basket18')?></div>
        <div class="style"><?= \Yii::t('app','basket19')?></div>
        <div class="style"><?= \Yii::t('app','basket20')?></div>
        <div class="style"><?= \Yii::t('app','basket21')?></div>
        <div class="style"><?= \Yii::t('app','basket22')?></div>
        <div class="style"><?= \Yii::t('app','basket23')?></div>
        <div class="style">
            <span style="color: #f26522;"><?= \Yii::t('app','basket24')?></span></span> <?= \Yii::t('app','basket25')?>
        </div>
        <div class="style"><?= \Yii::t('app','basket26')?></div>
        <div class="style"><?= \Yii::t('app','basket27')?><br/><?= \Yii::t('app','basket28')?>
        </div>
        <div style="font-weight: 700" class="style"><?= \Yii::t('app','basket29')?></div>
        <div class="style"><?= \Yii::t('app','basket30')?></div>
        <div class="style"><?= \Yii::t('app','basket31')?><br/><?= \Yii::t('app','basket32')?>
        </div>
        <div class="style"><?= \Yii::t('app','basket33')?></div>
        <div class="style"><?= \Yii::t('app','basket34')?><br/><?= \Yii::t('app','basket86')?><br/><?= \Yii::t('app','basket35')?>
            <a href="mailto:shop@eltrade.com.ua">shop@eltrade.com.ua</a> <?= \Yii::t('app','basket36')?><br/>
        </div>
        <div style="font-weight: 700" class="style"><?= \Yii::t('app','basket37')?></div>
        <div class="style"><?= \Yii::t('app','basket38')?><br/><?= \Yii::t('app','basket39')?>
        </div>
        <div class="style tb_rules">
            <table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td><span style="font-weight: 700;"><?= \Yii::t('app','basket40')?></span></td>
                    <td><span style="font-weight: 700;"><?= \Yii::t('app','basket41')?></span></td>
                </tr>
                <tr>
                    <td><?= \Yii::t('app','basket42')?></td>
                    <td>30 грн.</td>
                </tr>
                <tr>
                    <td><?= \Yii::t('app','basket43')?><span style="color: #f26522">*</span> <?= \Yii::t('app','basket44')?>
                    </td>
                    <td>50 грн.</td>
                </tr>
                <tr>
                    <td><?= \Yii::t('app','basket45')?><span style="color: #f26522">**</span></td>
                    <td>70 грн.</td>
                </tr>
                <tr>
                    <td><?= \Yii::t('app','basket46')?></td>
                    <td><?= \Yii::t('app','basket47')?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="style">
            <span style="color: #959595"><span style="color: #f26522">*</span> <?= \Yii::t('app','basket48')?><br/> <span style="color: #f26522">**</span> <?= \Yii::t('app','basket49')?></span>
        </div>
        <div class="style"><?= \Yii::t('app','basket50')?></div>
        <div class="style">
            <span style="color: #f26522;"><?= \Yii::t('app','basket51')?></span> <?= \Yii::t('app','basket52')?>
        </div>
        <div class="style"><?= \Yii::t('app','basket53')?></div>
        <div class="style"><?= \Yii::t('app','basket54')?>
            <?= \Yii::t('app','basket55')?>
        </div>
        <div class="style">
            <span style="color: #f26522;"><?= \Yii::t('app','basket51')?></span> <?= \Yii::t('app','basket56')?>
        </div>
        <div class="style"><?= \Yii::t('app','basket57')?></div>
        <div style="font-weight: 700" class="style"><?= \Yii::t('app','basket58')?></div>
        <div class="style">
            <?= \Yii::t('app','basket59')?><br/><span style="font-weight: 700"><?= \Yii::t('app','basket60')?></span><br/><?= \Yii::t('app','basket61')?><br/><span style="font-weight: 700"><?= \Yii::t('app','basket62')?></span><br/><?= \Yii::t('app','basket63')?><br/><?= \Yii::t('app','basket64')?><br/><span style="font-weight: 700"><?= \Yii::t('app','basket65')?></span><br/><?= \Yii::t('app','basket66')?><br/><span style="font-weight: 700"><?= \Yii::t('app','basket67')?></span><br/><?= \Yii::t('app','basket68')?><br/><span style="font-weight: 700"><?= \Yii::t('app','basket69')?></span><br/><?= \Yii::t('app','basket70')?>
        </div>
        <div class="style">
            <?= \Yii::t('app','basket71')?><br/><?= \Yii::t('app','basket72')?><br/><?= \Yii::t('app','basket73')?>
        </div>
        <div class="style">
            <span style="color: #f26522;"><?= \Yii::t('app','basket51')?></span> <?= \Yii::t('app','basket74')?>
            <span style="font-weight: 700"><?= \Yii::t('app','basket75')?></span>
        </div>
        <div class="style"><?= \Yii::t('app','basket76')?></div>
        <div class="style"><?= \Yii::t('app','basket77')?></div>
        <div class="style"><?= \Yii::t('app','basket78')?></div>
        <div class="style"><?= \Yii::t('app','basket79')?></div>
        <div class="style"><?= \Yii::t('app','basket80')?></div>
        <div class="style"><?= \Yii::t('app','basket81')?></div>
        <div class="style"><span style="font-weight: 700;"><?= \Yii::t('app','basket82')?>
                <br/><span style="color: #f26522;"><?= \Yii::t('app','basket51')?></span> <?= \Yii::t('app','basket83')?></span>
        </div>
        <div class="style"><?= \Yii::t('app','basket84')?><br/><?= \Yii::t('app','basket85')?>
        </div>
        <div class="style"><?= \Yii::t('app','basket87')?></div>
        <div class="style">
            <?= \Yii::t('app','basket88')?><br/>
            <?= \Yii::t('app','basket89')?><br/>
            <?= \Yii::t('app','basket90')?>
        </div>
        <div class="style">
            <?= \Yii::t('app','basket91')?><br/>
            <?= \Yii::t('app','basket92')?><br/>
            <?= \Yii::t('app','basket93')?><br/>
            <?= \Yii::t('app','basket94')?><br/>
            <?= \Yii::t('app','basket95')?>
        </div>
        <div class="style" style="font-weight: 700"><?= \Yii::t('app','basket96')?></div>
        <div class="style">
            <?= \Yii::t('app','basket97')?><br/>
            <?= \Yii::t('app','basket98')?>
        </div>
        <div class="style" style="position: relative;"><span id="modal_close" class="btn-modal-close"><?= \Yii::t('app','close')?></span>
        </div>
        <div class="style"></div>
        <div class="style"></div>
        <div class="style"></div>
        <div class="style"></div>
        <div class="style"></div>
        <div class="style"></div>
        <div class="style"></div>
        <div class="style"></div>
        <div class="style"></div>
    </div>
</div>
