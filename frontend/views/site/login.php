<?php
use frontend\models\LoginForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 *@var LoginForm $model
 */
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title_card"><?= \Yii::t('app','entercab')?></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="forms_ form-register">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-3">
                        <?php $form = ActiveForm::begin([
                            "action" =>  Url::to(['site/login'])
                        ]); ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 input-wr medium-label">
                                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 input-wr medium-label">
                                <?= $form->field($model, 'password')->passwordInput() ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 input-wr check-box-form">
                                <?= $form->field($model, 'rememberMe', [ 'template' => "{input}\n{label}\n{error}" ])
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
                            <span class="no_account_yet"><?= \Yii::t('app','no_account_yet')?></span>
                            <?= Html::a(\Yii::t('app','reg'), [ 'site/signup' ],[
                                'class'=>'to-register-link'
                            ]) ?>
                        </div>
                        <div class="btns_reg btns_social-login">
                            <span class="no_account_yet">Регистрация через соц. сети</span>
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
