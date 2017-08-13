<?php
    
    use artweb\artbox\models\Service;
    use artweb\artbox\models\ServiceLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View          $this
     * @var Service       $model
     * @var ServiceLang[] $modelLangs
     */
    
    $this->title = Yii::t('app', 'Create Service');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Services'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="service-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
