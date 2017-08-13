<?php
    
    use artweb\artbox\ecommerce\models\TaxGroup;
    use artweb\artbox\ecommerce\models\TaxOptionLang;
    use yii\helpers\Html;
    
    /**
     * @var yii\web\View                                $this
     * @var artweb\artbox\ecommerce\models\TaxOption $model
     * @var TaxGroup                                    $group
     * @var TaxOptionLang[]                             $modelLangs
     */
    $this->title = Yii::t('rubrication', 'Update {modelClass}: ', [
            'modelClass' => 'Tax Option',
        ]) . ' ' . $model->lang->value;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $group->level ? Yii::t('rubrication', 'Modification Groups') : Yii::t('rubrication', 'Product Groups'),
        'url'   => [
            'tax-group/index',
            'level' => $group->level,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $group->lang->title,
        'url'   => [
            'tax-group/update',
            'id'    => $group->id,
            'level' => $group->level,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('rubrication', 'Options for group {group}', [ 'group' => $group->lang->title ]),
        'url'   => [
            'index',
            'group' => $group->id,
            'level' => $group->level,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="tax-option-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
        'group'       => $group,
    ]) ?>

</div>
