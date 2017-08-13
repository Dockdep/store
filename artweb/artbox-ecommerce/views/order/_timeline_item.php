<?php
    /**
     * @var OrderLabelHistory $model
     */
    use artweb\artbox\ecommerce\models\OrderLabelHistory;
    use yii\helpers\Html;

?>

<!-- timeline item -->
<li>
  <!-- timeline icon -->
  <i class="fa fa-tag bg-blue"></i>
  <div class="timeline-item">
    <span class="time"><i class="fa fa-calendar"></i> <?=Yii::$app->formatter->asDatetime($model->created_at)?></span>

    <h3 class="timeline-header"><?=$model->label->lang->title?></h3>

    <div class="timeline-body">
      <?php
      if (empty($model->user)) {
        echo Html::tag('p', 'Поступил с сайта', ['class' => 'text-green']);
      } else {
        echo 'Статус присвоил: ' . Html::tag('p', $model->user->username, [
          'class' => 'text-blue'
            ]);
      }
      ?>
    </div>
    
  </div>
</li>
