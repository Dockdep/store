<?php
    use artweb\artbox\ecommerce\models\TaxGroup;
    use artweb\artbox\ecommerce\models\TaxGroupSearch;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var integer            $level
     * @var ActiveDataProvider $dataProvider
     * @var TaxGroupSearch     $searchModel
     * @var TaxGroup           $model
     */
    
    $this->title = $level ? Yii::t('rubrication', 'Modification Groups') : Yii::t('rubrication', 'Product Groups');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="tax-group-index">
    
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(
            Yii::t('rubrication', 'Create Group'),
            Url::to(
                [
                    'tax-group/create',
                    'level' => $level,
                ]
            ),
            [ 'class' => 'btn btn-success' ]
        ) ?>
    </p>
    
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                'sort',
                [
                    'attribute' => 'is_filter',
                    'format'    => 'boolean',
                    'filter'    => \Yii::$app->formatter->booleanFormat,
                ],
                [
                    'attribute' => 'groupName',
                    'value'     => 'lang.title',
                ],
                [
                    'attribute' => 'alias',
                    'value'     => 'lang.alias'
                ],
                [
                    'attribute' => 'description',
                    'value'     => 'lang.description'
                ],
                [
                    'class'      => 'yii\grid\ActionColumn',
                    'template'   => '{update} {options} {delete}',
                    'buttons'    => [
                        'options' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-th-list"></span>',
                                $url,
                                [
                                    'title' => Yii::t('rubrication', 'Options'),
                                ]
                            );
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) use ($level) {
                        if ($action === 'options') {
                            $url = Url::to(
                                [
                                    'tax-option/index',
                                    'group' => $model->id,
                                ]
                            );
                            return $url;
                        } elseif ($action === 'update') {
                            $url = Url::to(
                                [
                                    'tax-group/update',
                                    'level' => $level,
                                    'id'    => $model->id,
                                ]
                            );
                            return $url;
                        } elseif ($action === 'delete') {
                            $url = Url::to(
                                [
                                    'tax-group/delete',
                                    'level' => $level,
                                    'id'    => $model->id,
                                ]
                            );
                            return $url;
                        }
                        return '';
                    },
                ],
            ],
        ]
    ); ?>
</div>

