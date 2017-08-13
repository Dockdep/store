<?php
    
    use artweb\artbox\design\models\Bg;
    use artweb\artbox\design\models\BgLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View     $this
     * @var Bg       $model
     * @var BgLang[] $modelLangs
     */
    
    $this->title = \Yii::t('app', 'Update Bg: ') . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Bgs'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = \Yii::t('app', 'Update');
?>
<div class="bg-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
