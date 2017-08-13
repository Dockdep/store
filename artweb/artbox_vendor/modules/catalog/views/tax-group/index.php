<?php
    use artweb\artbox\modules\catalog\models\TaxGroup;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var integer            $level
     * @var ActiveDataProvider $dataProvider
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
                    '/rubrication/tax-group/create',
                    'level' => $level,
                ]
            ),
            [ 'class' => 'btn btn-success' ]
        ) ?>
    </p>
    
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                'id',
                'is_filter:boolean',
                'lang.title',
                [
                    'label' => \Yii::t('rubrication', 'Options count'),
                    'value' => function ($model) {
                        /**
                         * @var TaxGroup $model
                         */
                        return count($model->options);
                    },
                ],
                [
                    'label' => \Yii::t('rubrication', 'Categories count'),
                    'value' => function ($model) {
                        /**
                         * @var TaxGroup $model
                         */
                        return count($model->categories);
                    },
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
                            $url = '/admin/rubrication/tax-option?group=' . $model->id;
                            return $url;
                        } elseif ($action === 'update') {
                            $url = Url::to(
                                [
                                    '/rubrication/tax-group/update',
                                    'level' => $level,
                                    'id'    => $model->id,
                                ]
                            );
                            return $url;
                        } elseif ($action === 'delete') {
                            $url = Url::to(
                                [
                                    '/rubrication/tax-group/delete',
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

