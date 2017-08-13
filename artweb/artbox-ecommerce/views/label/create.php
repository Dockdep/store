<?php
    
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\OrderLabelLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View $this
     * @var Label $model
     * @var orderLabelLang[] $modelLangs
     */

$this->title = 'Create Label';
$this->params['breadcrumbs'][] = ['label' => 'Labels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="label-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
