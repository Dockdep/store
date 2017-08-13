<?php
    use frontend\models\SignupForm;
    use yii\web\View;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    /**
     *@var SignupForm $model
     */
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title_card"><?= \Yii::t('app','reg2')?></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="forms_ form-register register-page">
                <div class="style txt_reg_page">
                    <p>
                        <?= \Yii::t('app','signup1')?>
                    </p>
                </div>
                <?php $form = ActiveForm::begin([
                    "action" =>  Url::to(['/site/signup'])
                ]); ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 input-wr medium-label">
                            <?= $form->field($model, 'username')->textInput() ?>
                        </div>
                        <div class="hidden-xs col-sm-5 col-md-6 input-wr required_fields"><span>*</span> <?= \Yii::t('app','signup2')?></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label select-arrow">
                            <?= $form->field($model, 'gender')->dropDownList([
                                '0' => 'Выберите пол...',
                                'Мужской' => 'Мужской',
                                'Женский' => 'Женский',
                            ],['options' => [0 => ['disabled' => true, 'selected' => true]
                            ]]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                            <?= $form->field($model, 'birthday')->textInput([
                                'class' => '_datepicer'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                            <?= $form->field($model, 'email')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 input-wr emails_txt">
                            <?= \Yii::t('app','signup3')?><br />
                            <?= \Yii::t('app','signup4')?><a class="terms_of_use" href="#"><?= \Yii::t('app','signup5')?></a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        </div>
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                            <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                            <?= $form->field($model, 'phone')->textInput([
                                "placeholder" => "+38(_ _ _) _ _ _- _ _ - _ _"
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                            <?= $form->field($model, 'city')->textInput() ?>
                        </div>
                        <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                            <?= $form->field($model, 'address')->textInput() ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 input-wr">
                            <?= Html::submitButton('Зарегистрироваться') ?>
                        </div>
                    </div>
                <?php
                ActiveForm::end();
                ?>
            </div>
        </div>
    </div>

    <div id="terms_of_use" class="forms_reg">
        <span id="modal_close"></span>
        <div style="font-size: 22px;font-weight: 700" class="style"><?= \Yii::t('app','terms1')?></div>
        <div style="font-size: 18px;font-weight: 700;margin-top: 10px;" class="style"><?= \Yii::t('app','terms2')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','terms3')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','terms4')?></div>
        <div style="font-size: 18px;font-weight: 700;margin-top: 10px;" class="style"><?= \Yii::t('app','terms5')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','terms6')?><a href="/">extremstyle.ua</a><?= \Yii::t('app','terms7')?><a href="/">extremstyle.ua</a><?= \Yii::t('app','terms8')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','terms9')?><a href="/">extremstyle.ua</a><?= \Yii::t('app','terms10')?><a href="/">extremstyle.ua</a><?= \Yii::t('app','terms11')?></div>
        <div style="margin-top: 10px;" class="style"><?= \Yii::t('app','terms12')?><a href="/">extremstyle.ua</a><?= \Yii::t('app','terms13')?></div>
    </div>

</div>

<?php
$js = "

$('#signupform-phone').mask('+38(000)000-00-00');
$( '._datepicer' ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd.mm.yy',
    closeText: 'Закрыть',
    prevText: 'Пред',
    nextText: 'След',
    monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
    monthNamesShort: ['Январь','Февраль','Март','Апрель','Май','Июнь', 'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
    dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
    dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
    dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
    firstDay: 1,
    defaultDate:'01.01.1990'
});";
$this->registerJs($js,View::POS_READY);

?>
