<?php
    /**
     * @var OrderLog $model
     * @var Order    $order
     */
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\OrderLog;
    use yii\helpers\Html;
    use yii\helpers\Json;

?>

<!-- timeline item -->
<li>
  <!-- timeline icon -->
  <i class="fa fa-user bg-orange"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-calendar"></i> <?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>

    <h3 class="timeline-header">Пользователь: <span class="text-orange"><?= $model->user->username ?></span></h3>

    <div class="timeline-body">
      <table class="table table-bordered table-striped">
        <tr>
          <th>Поле</th>
          <th>Старое значение</th>
          <th>Новое значение</th>
        </tr>
          <?php
              foreach (Json::decode($model->data) as $key => $item) {
                  echo Html::tag(
                      'tr',
                      Html::tag('td', $order->attributeLabels()[ $key ]) . Html::tag('td', $item[ 'old' ]) . Html::tag(
                          'td',
                          $item[ 'new' ]
                      )
                  );
              }
          ?>
      </table>
    </div>

  </div>
</li>
