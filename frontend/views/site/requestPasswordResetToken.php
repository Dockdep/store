<?php
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \frontend\models\PasswordResetRequestForm */
    
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
        <div class="col-xs-12 col-sm-12 title_card"><?= \Yii::t('app', 'forgpass') ?></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="forms_ form-register">
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-3 input-wr medium-label">
                        <?= $form->field($model, 'email')
                                 ->textInput([ 'autofocus' => true ]) ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 email-remind-txt"><?= \Yii::t('app', 'forgpass2') ?></div>
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