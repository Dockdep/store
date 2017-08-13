<?php
    /**
     * @var Order              $model
     * @var ActiveDataProvider $dataProvider
     * @var View               $this
     */
    use artweb\artbox\ecommerce\models\Order;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\web\View;
    
    $this->title = 'Добавить товар в заказ';
    $this->params[ 'breadcrumbs' ][] = [
        'label' => \Yii::t('app', 'Заказы'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;
    
    $js = '
    window.onbeforeunload = function(e) {
        $.ajax({
            type: "GET",
            url: "/admin/ecommerce/order/exit-order",
            data: {
                id: ' . $model->id . ',
            },
            success: function() {
                console.log("Exit order");
            }
        });
};
';
    
    $this->registerJs($js, View::POS_READY);
    
?>

<div class="order-create">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?= $this->render(
        '_form',
        [
            'model'        => $model,
            'dataProvider' => $dataProvider,
            'pjax'         => true,
        ]
    ) ?>
</div>

