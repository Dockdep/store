<?php
    
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\OrderLabelLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View              $this
     * @var Label             $model
     * @var orderLabelLang[] $modelLangs
     */
    
    $this->title = 'Update Label: ' . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => 'Labels',
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = 'Update';
?>
<div class="label-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
