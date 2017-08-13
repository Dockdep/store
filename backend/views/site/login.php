<?php
    /**
     * @var yii\web\View $this
     * @var yii\bootstrap\ActiveForm $form
     * @var \common\models\LoginForm $model
     */
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    
    $this->title = 'Login';
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-login">
    <div class="container">
        
        
        <div class="row">
            <div class="col-xs-3"></div>
            <div class="col-lg-6 new_admin_form">
                <div class="artbox_logo-wr">
                    <div class="artbox_logo">ArtBox</div>
                </div>
                <div class="artbox_logo_txt">управление интернет магазином</div>
                <?php $form = ActiveForm::begin([ 'id' => 'login-form' ]); ?>
                
                <?= $form->field($model, 'username')
                         ->textInput([ 'autofocus' => true ]) ?>
                
                <?= $form->field($model, 'password')
                         ->passwordInput() ?>
                
                <?= $form->field($model, 'rememberMe')
                         ->checkbox() ?>
                
                <div class="form-group">
                    <?= Html::submitButton('Войти', [
                        'class' => 'btn btn-primary',
                        'name'  => 'login-button',
                    ]) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-xs-3"></div>
        </div>
    </div>
</div>
