<?php
/**
 * @var $content string
 * @var $this View
 */
use frontend\assets\CabinetAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

CabinetAsset::register($this);

$this->beginContent('@app/views/layouts/main.php');

?>
<div class="container">
    <div class="row cabinet-wrapper">
        <div class="hidden-xs hidden-sm col-md-4 col-lg-3">
            <div class="artbox_cab-sidebar-wr">
                <div class="cab_cab-wr style">
                    <div class="box_side_title style"><?= \Yii::t('app','mycab')?></div>

                    <div class="artbox_cab-sidebar-ava-wr style">
                        <div class="artbox_cab-sidebar-ava">

                        </div>
                    </div>

                    <div class="box_side_name style"><?= Yii::$app->user->identity->username ?></div>
                </div>

                <div style="display: none" class="box_side-notice style">
                    <!--                    <ul>-->
                    <!--                        <li class="notice-today">-->
                    <!--                            <span>Сегодня</span>-->
                    <!--                            <div>-->
                    <!--                                <a href="#">Ваш заказ № 32456 готов к отправке. Менеджер.</a>-->
                    <!--                            </div>-->
                    <!--                        </li>-->
                    <!--                        <li>-->
                    <!--                            <span>вчера</span>-->
                    <!--                            <div>-->
                    <!--                                <a href="#">На вашу почту было отправленно 2 сообщения.</a>-->
                    <!--                            </div>-->
                    <!--                        </li>-->
                    <!--                        <li>-->
                    <!--                            <span>вчера</span>-->
                    <!--                            <div>-->
                    <!--                                <a href="#">У нас новые акции. Лучшие товары по лучшим ценам.</a>-->
                    <!--                            </div>-->
                    <!--                        </li>-->
                    <!---->
                    <!--                        <li>-->
                    <!--                            <span>08.04.2016</span>-->
                    <!--                            <div>-->
                    <!--                                <a href="#">У нас новые акции.</a>-->
                    <!--                            </div>-->
                    <!--                        </li>-->
                    <!---->
                    <!---->
                    <!--                        <li>-->
                    <!--                            <span>08.04.2016</span>-->
                    <!--                            <div>-->
                    <!--                                <a href="#">У нас новые акции.</a>-->
                    <!--                            </div>-->
                    <!--                        </li>-->
                    <!---->
                    <!--                        <li>-->
                    <!--                            <span>08.04.2016</span>-->
                    <!--                            <div>-->
                    <!--                                <a href="#">У нас новые акции.</a>-->
                    <!--                            </div>-->
                    <!--                        </li>-->
                    <!---->
                    <!--                        <li>-->
                    <!--                            <span>08.04.2016</span>-->
                    <!--                            <div>-->
                    <!--                                <a href="#">У нас новые акции. У нас новые акции. У нас новые акции.</a>-->
                    <!--                            </div>-->
                    <!--                        </li>-->
                    <!---->
                    <!---->
                    <!---->
                    <!--                    </ul>-->
                </div>
                <div style="display: none;" class="arrows_up"></div>
                <div style="display: none;" class="arrows_down"></div>
            </div>
            <div class="artbox_cab-menu-wr">
                <ul>
                    <li class="<?= preg_match('/main/', Yii::$app->controller->action->id) ? "active" : ''?>">
                        <?= Html::a("<div class='menu-ico_1'></div><span>".\Yii::t('app','данные')."</span>", Url::to(['cabinet/main']));?>
                    </li>
                    <li class="<?= preg_match('/my-orders/', Yii::$app->controller->action->id) ? "active" : ''?>">
                        <?= Html::a("<div class='menu-ico_5'></div><span>".\Yii::t('app','заказы')."</span>", Url::to(['cabinet/my-orders']));?>
                    </li>
                    <li class="<?= preg_match('/history/', Yii::$app->controller->action->id) ? "active" : ''?>">
                        <?= Html::a("<div class='menu-ico_6'></div><span>".\Yii::t('app','просмотры')."</span>", Url::to(['cabinet/history']));?>
                    </li>
                    <li>
                        <?= Html::a("<div class='menu-ico_9'></div><span>".\Yii::t('app', "выход")."</span>",Url::to(['site/logout']));?>
                    </li>
                </ul>
            </div>
        </div>

            <?= $content ?>
        </div>

</div>
<?php $this->endContent() ?>