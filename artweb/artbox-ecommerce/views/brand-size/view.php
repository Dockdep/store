<?php
    
    use artweb\artbox\ecommerce\models\BrandSize;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /**
     * @var View      $this
     * @var BrandSize $model
     */
    
    $this->title = $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Brand Sizes'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="brand-size-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
      <?= Html::a(
          Yii::t('app', 'Update'),
          [
              'update',
              'id' => $model->id,
          ],
          [ 'class' => 'btn btn-primary' ]
      ) ?>
      <?= Html::a(
          Yii::t('app', 'Delete'),
          [
              'delete',
              'id' => $model->id,
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
                'brand.lang.title',
                'imageUrl:image',
            ],
        ]
    ) ?>

</div>
