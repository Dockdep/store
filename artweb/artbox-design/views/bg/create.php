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
    
    $this->title = \Yii::t('app', 'create_item',['item'=>'Bg']);
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Bgs'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="bg-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
