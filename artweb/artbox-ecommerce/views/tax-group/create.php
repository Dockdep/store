<?php
    
    use artweb\artbox\ecommerce\models\TaxGroup;
    use artweb\artbox\ecommerce\models\TaxGroupLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View           $this
     * @var TaxGroup       $model
     * @var TaxGroupLang[] $modelLangs
     * @var int            $level
     */
    $this->title = Yii::t('rubrication', 'Create Tax Group');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('rubrication', 'Tax Groups'),
        'url'   => [
            'index',
            'level' => $level,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="tax-group-create">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
        ]
    ) ?>

</div>
