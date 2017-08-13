<?php
    
    use artweb\artbox\ecommerce\models\TaxGroup;
    use artweb\artbox\ecommerce\models\TaxOption;
    use yii\helpers\Html;
    use yii\grid\GridView;
    
    /**
     * @var yii\web\View                                      $this
     * @var artweb\artbox\ecommerce\models\TaxOptionSearch $searchModel
     * @var yii\data\ActiveDataProvider                       $dataProvider
     * @var TaxGroup                                          $group
     */
    
    $this->title = Yii::t('rubrication', 'Options for group {group}', [ 'group' => $group->lang->title ]);
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $group->level ? Yii::t('rubrication', 'Modification Groups') : Yii::t(
            'rubrication',
            'Product Groups'
        ),
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
    $this->params[ 'breadcrumbs' ][] = $this->title;

?>
<div class="tax-option-index">
    
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(
            Yii::t('rubrication', 'Create Option'),
            [ 'create?group=' . $group->id ],
            [ 'class' => 'btn btn-success' ]
        ) ?>
    </p>

    <?php echo GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                'id',
                [
                    'attribute' => 'value',
                    'value'     => 'lang.value',
                ],
                'imageUrl:image',
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                ],
            ],
        ]
    ); ?>
</div>
