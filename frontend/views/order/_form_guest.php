<?php
    /**
     * @var OrderFrontend $order
     * @var View          $this
     * @var Delivery[]    $deliveries
     */
    use artweb\artbox\ecommerce\models\Delivery;
    use frontend\models\LoginForm;
    use frontend\models\OrderFrontend;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;

?>
<div class="style description_list-wrapper hidden-xs">
    <ul class="description_list">
        <li class="active">
            <a class="desk_name" href="#"><?= \Yii::t('app','newbuyer')?></a>
        </li>
        
        <li>
            <a class="desk_name" href="#"><?= \Yii::t('app','registed')?></a>
        </li>
    
    </ul>
</div>
<div class="style desk_blocks-wr">
    <div class="active desk_list-wr active-mobile">
        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#"><?= \Yii::t('app','newbuyer')?></a>
        <?php
            echo $this->render(
                '_form',
                [
                    'order' => $order,
                ]
            );
        ?>
    </div>
    <div class="desk_list-wr">
        <a class="btn_mobil_show_desk style hidden-sm hidden-md hidden-lg" href="#"><?= \Yii::t('app','registed')?></a>
        <div class="style desk_delivery">
            <div class="forms_ already-registered">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-3">
                        <?php $form = ActiveForm::begin([
                            "action" =>  Url::to(['site/login', 'referer' => Url::current([], true)]),
                        ]); ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 input-wr medium-label">
                                <?= $form->field(new LoginForm(), 'email')->textInput(['autofocus' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 input-wr medium-label">
                                <?= $form->field(new LoginForm(), 'password')->passwordInput() ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 input-wr check-box-form">
                                <?= $form->field(new LoginForm(), 'rememberMe', [ 'template' => "{input}\n{label}\n{error}" ])
                                    ->label('<span></span>'.\Yii::t('app','rememberme'))
                                    ->checkbox([
                                        'class'    => 'custom-check',
                                        'checked' => 'checked',
                                    ], false) ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 input-wr">
                                <?= Html::submitButton(\Yii::t('app','enter')) ?>
                            </div>
                        </div>
                        <?php
                        ActiveForm::end();
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-5 col-md-3">
                        <div class="btns_reg">
                            <?= Html::a(\Yii::t('app','restore'), [ 'site/request-password-reset' ],
                                [
                                    'class'=>'restore_password_'
                                ]) ?>
                            <span class="no_account_yet"><?= \Yii::t('app', 'noaccs') ?></span>
                            <?= Html::a(\Yii::t('app','reg'), [ 'site/signup' ],[
                                'class'=>'to-register-link'
                            ]) ?>
                        </div>
                        <div class="btns_reg btns_social-login">
                            <span class="no_account_yet"><?= \Yii::t('app', 'socbutreg') ?></span>
                            <div class="style icon-soc-login-wr">
                                <span class="social_login vk_login"></span>
                                <span class="social_login fb_login"></span>
                                <span class="social_login gpluse_login"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
