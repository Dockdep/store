<?php
    use artweb\artbox\ecommerce\models\Order;
    use yii\data\ActiveDataProvider;
    use yii\web\View;
    use yii\widgets\ListView;
    
    /**
     * @var View               $this
     * @var ActiveDataProvider $logData
     * @var ActiveDataProvider $productLogData
     * @var Order              $model
     */
    
    $this->title = 'История заказа #' . $model->id;
    
    $this->params[ 'breadcrumbs' ][] = [
        'url'   => yii\helpers\Url::to([ '/ecommerce/order/index' ]),
        'label' => \Yii::t('app', 'Заказы'),
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'url'   => yii\helpers\Url::to(
            [
                '/ecommerce/order/view',
                'id' => $model->id,
            ]
        ),
        'label' => \Yii::t('app', 'Заказ #') . $model->id,
    ];
    $this->params[ 'breadcrumbs' ][] = $this->title;

?>

<div class="order-log">

  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">История</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
    </div>
    <div class="box-body">
        
        <?php
            echo ListView::widget(
                [
                    'dataProvider'     => $logData,
                    'layout'           => '{items}',
                    'itemView'         => '_log_item',
                    'itemOptions'      => [
                        'tag' => false,
                    ],
                    'options'          => [
                        'tag'   => $logData->totalCount == 0 ? 'div' : 'ul',
                        'class' => $logData->totalCount == 0 ? 'list-view' : 'list-view timeline',
                    ],
                    'viewParams'       => [
                        'order' => $model,
                    ],
                    'emptyText'        => 'У этого заказа пока нет истории',
                    'emptyTextOptions' => [
                        'class' => 'callout callout-info',
                    ],
                ]
            );
        ?>

    </div><!-- /.box-body -->
  </div><!-- /.box -->
    
    <?php
//        echo ListView::widget(
//            [
//                'dataProvider'     => $productLogData,
//                'layout'           => '{items}',
//                'itemView'         => '_log_product_item',
//                'itemOptions'      => [
//                    'tag' => false,
//                ],
//                'viewParams'       => [
//                    'order' => $model,
//                ],
//                'options' => [
//                  'class' => 'list-view',
//                ],
//                'emptyText'        => 'У этого заказа нет товаров',
//                'emptyTextOptions' => [
//                    'class' => 'callout callout-info',
//                ],
//            ]
//        );
    ?>

</div>
