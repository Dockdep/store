<?php
    
    use artweb\artbox\seo\models\SeoCategory;
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    
    /**
     * @var yii\web\View             $this
     * @var artweb\artbox\seo\models\SeoDynamic $model
     * @var SeoCategory              $seo_category
     */
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Seo Categories'),
        'url'   => [ '/seo-category/index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $seo_category->lang->title,
        'url'   => [
            'index',
            'seo_category_id' => $seo_category->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="seo-dynamic-view">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a(
            Yii::t('app', 'Update'),
            [
                'update',
                'id'              => $model->id,
                'seo_category_id' => $seo_category->id,
            ],
            [ 'class' => 'btn btn-primary' ]
        ) ?>
        <?= Html::a(
            Yii::t('app', 'Delete'),
            [
                'delete',
                'id'              => $model->id,
                'seo_category_id' => $seo_category->id,
            ],
            [
                'class' => 'btn btn-danger',
                'data'  => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method'  => 'post',
                ],
            ]
        ) ?>
    </p>
    
    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                'id',
                [
                    'label'  => \Yii::t('app', 'Seo Category'),
                    'value'  => Html::a(
                        $seo_category->lang->title,
                        [
                            'index',
                            'seo_category_id' => $seo_category->id,
                        ]
                    ),
                    'format' => 'html',
                ],
                'lang.title',
                'action',
                'fields',
                'param',
                'status',
                'lang.meta_title',
                'lang.h1',
                'lang.key',
                'lang.meta',
                'lang.description',
                'lang.seo_text',
            ],
        ]
    ) ?>

</div>
