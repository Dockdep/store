<?php
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \frontend\models\ResetPasswordForm */
    
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    
    $this->title = \Yii::t('app', 'restorepass');
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
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
        <div class="col-xs-12 col-sm-12 title_card"><?= Html::encode($this->title) ?></div>
    </div>
    
    
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="forms_ form-register">
                <?php $form = ActiveForm::begin([ 'id' => 'reset-password-form' ]); ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                        <?= $form->field($model, 'password')
                                 ->passwordInput([ 'autofocus' => true ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 email-remind-txt"><?= \Yii::t('app', 'enternewpass') ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 input-wr">
                        <?= Html::submitButton(\Yii::t('app', 'send')) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
