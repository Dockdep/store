<?php
    
    use artweb\artbox\models\Banner;
    use artweb\artbox\models\BannerLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View         $this
     * @var Banner       $model
     * @var BannerLang[] $modelLangs
     */
    
    $this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Banner',
        ]) . $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Banners'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->id,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="banner-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
