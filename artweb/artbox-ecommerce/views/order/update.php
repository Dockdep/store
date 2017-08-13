<?php
    /**
     * @var Order              $model
     * @var View               $this
     * @var ActiveDataProvider $dataProvider
     */
    
    use artweb\artbox\ecommerce\models\Order;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\web\View;
    
    $this->title = 'Обновить заказ #' . $model->id;
    $this->params[ 'breadcrumbs' ][] = [
        'url'   => yii\helpers\Url::to([ '/ecommerce/order/index' ]),
        'label' => \Yii::t('app', 'Заказы'),
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'url'   => yii\helpers\Url::to([ '/ecommerce/order/view', 'id' => $model->id, ]),
        'label' => \Yii::t('app', 'Заказ #') . $model->id,
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
    
    $js = '
$.ajax({
  type: "POST",
  url: "/admin/ecommerce/order/block-order",
  data: {
    id: ' . $model->id . '
  },
  success: function(data) {
  var message = data.time;
  if (data.user != "") {
    message += "  " + data.user;
  }
  $.notify({
	message:  message
        },{
	type: "info"
    });
  }
});';
    
    $this->registerJs($js, View::POS_READY);
    
    $js = '
    window.onbeforeunload = function(e) {
        $.ajax({
            type: "POST",
            url: "/admin/ecommerce/order/exit-order",
            data: {
                id: ' . $model->id . ',
            },
            success: function() {
            }
        });
};
';
    
    $this->registerJs($js, View::POS_READY);
  
    if (!empty(\Yii::$app->session->getFlash('label_update'))) {
      $js = '
$.notify({
    message: "Статус заказа обновлен"
}, {
    type : "warning"
})
';
      $this->registerJs($js, View::POS_READY);
    }
    
?>
<div class="order-update">
  <div class="container callout bg-olive">
    <h1><?php echo Html::encode($this->title) ?> | <?php echo date(
            'd-m-Y G:i',
            $model->created_at
        ); ?> | <?php echo $model->manager->username; ?></h1>
  </div>
    
    <?= $this->render(
        '_form',
        [
            'model'        => $model,
            'dataProvider' => $dataProvider,
        ]
    ) ?>
</div>