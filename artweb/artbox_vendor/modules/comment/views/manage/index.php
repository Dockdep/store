<?php
    use artweb\artbox\modules\comment\models\CommentModelSearch;
    use yii\data\ActiveDataProvider;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    
    /**
     * @var ActiveDataProvider $dataProvider
     * @var CommentModelSearch $searchModel
     * @var string             $commentModel
     */
    $statuses = [
        $searchModel::STATUS_ACTIVE  => 'Активный',
        $searchModel::STATUS_HIDDEN  => 'Скрытый',
        $searchModel::STATUS_DELETED => 'Удаленный',
    ];
    Pjax::begin();
    if(( $success = \Yii::$app->session->getFlash('artbox_comment_success') ) != NULL) {
        echo Html::tag('p', $success);
    }
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
            [
                'attribute' => 'artbox_comment_id',
                'label'     => 'Идентификатор',
            ],
            [
                'attribute' => 'created_at',
                'format'    => [
                    'date',
                    'php:d.m.Y',
                ],
                'filter'    => false,
            ],
            'text:text',
            [
                'attribute' => 'user_id',
                'value'     => function($model) {
                    if(!empty( $model->user_id )) {
                        return $model->user->username . ' (id:' . $model->user->id . ')';
                    } else {
                        return $model->username . ' ' . $model->email . ' (Гость)';
                    }
                },
            ],
            [
                'attribute' => 'status',
                'filter'    => $statuses,
                'value'     => function($model) use ($statuses) {
                    return $statuses[ $model->status ];
                },
            ],
            [
                'attribute' => 'ratingValue',
                'value'     => function($model) {
                    if(!empty( $model->rating )) {
                        return $model->rating->value;
                    }
                    return NULL;
                },
            ],
            'entity',
            'entity_id',
            [
                'attribute' => 'childrenCount',
                'value'     => function($model) {
                    return count($model->children);
                },
            ],
        ],
    ]);
    Pjax::end();