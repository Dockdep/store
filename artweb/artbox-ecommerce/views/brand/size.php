<?php
    
    use artweb\artbox\ecommerce\models\Brand;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\web\View;
    
    /**
     * @var View               $this
     * @var Brand              $model
     * @var ActiveDataProvider $dataProvider
     */
    
    $this->title = Yii::t('app', 'Brand Sizes');
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="brand-size-index">

  <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  <p>
      <?= Html::a(Yii::t('app', 'Create Brand Size'), [ 'brand-size/create' ], [ 'class' => 'btn btn-success' ]) ?>
  </p>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'columns'      => [
                [ 'class' => 'yii\grid\SerialColumn' ],
                
                'id',
                'brand_id',
                'image',
                
                [
                  'class' => 'yii\grid\ActionColumn',
                  'controller' => 'brand-size',
                ],
            ],
        ]
    ); ?>
</div>
