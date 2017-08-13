<?php
    /**
     * @var OrderProduct $model
     * @var Order        $order
     */
    use artweb\artbox\ecommerce\models\Order;
    use artweb\artbox\ecommerce\models\OrderProduct;
    use artweb\artbox\ecommerce\models\OrderProductLog;
    use yii\helpers\Html;
    use yii\helpers\Json;
    use yii\helpers\StringHelper;
    use yii\helpers\Url;

?>
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">История товара <a href="<?= Url::to(
            [
                '/ecommerce/variant/view',
                'product_id' => $model->productVariant->product->id,
                'id'         => $model->productVariant->id,
            ]
        ) ?>" target="_blank" class="text-green"><?php echo $model->sku; ?></a></h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="box-body row">

    <div class="col-md-4 col-sm-12 col-xs-12">
        <?php
            echo Html::tag('h3', $model->product_name);
            if (empty($model->productVariant)) {
                echo '';
            } else {
                echo Html::img(
                    $model->productVariant->imageUrl,
                    [
                        'class' => 'img-thumbnail',
                    ]
                );
            }
            echo Html::beginTag('br');
        ?>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-shopping-basket "></i></span>
        <div class="info-box-content">
          <span style="text-decoration: line-through" class="info-box-text"><?= !empty($model->productVariant->price_old) ? $model->productVariant->price_old : '' ?></span>
          <span class="info-box-number"><?= $model->productVariant->price ?> грн.</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
          <li><a target="_blank" href="<?= Url::to(
                  [
                      '/ecommerce/manage/view',
                      'id' => $model->productVariant->product->id,
                  ]
              ) ?>">Название: <span class="text-orange pull-right"><?php echo StringHelper::truncate(
                          $model->productVariant->product->lang->title,
                          35,
                          '...'
                      ); ?></span></a></li>
          <li><a target="_blank" href="<?= Url::to(
                  [
                      '/ecommerce/category/view',
                      'id' => $model->productVariant->category->id,
                  ]
              ) ?>">Категория:
              <span class="pull-right text-orange"><?php echo $model->productVariant->category->lang->title; ?></span></a>
          </li>
          <li><a target="_blank" href="<?= Url::to(
                  [
                      '/ecommerce/brand/view',
                      'id' => $model->productVariant->product->brand->id,
                  ]
              ) ?>">Брэнд:
              <span class="pull-right text-orange"><?php echo $model->productVariant->product->brand->lang->title; ?></span></a>
          </li>
          <li><a target="_blank" href="#">Метки:
                  <?php
                      if ($model->productVariant->product->is_discount) {
                          echo Html::tag(
                              'span',
                              'Акция',
                              [
                                  'class' => 'pull-right badge bg-red',
                              ]
                          );
                      }
                      if ($model->productVariant->product->is_top) {
                          echo Html::tag(
                              'span',
                              'Топ',
                              [
                                  'class' => 'pull-right badge bg-purple',
                              ]
                          );
                      }
                      if ($model->productVariant->product->is_new) {
                          echo Html::tag(
                              'span',
                              'Новый',
                              [
                                  'class' => 'pull-right badge bg-blue',
                              ]
                          );
                      }
                  ?>

            </a></li>
        </ul>
      </div>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-12">
        <?php if (!empty($model->logs)) {
            echo Html::beginTag(
                'ul',
                [
                    'class' => 'timeline',
                ]
            );
            foreach ($model->logs as $log) {
                /**
                 * @var OrderProductLog $log
                 */
                $data = Json::decode($log->data); ?>

              <li>
                <i class="fa fa-pencil bg-blue"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-calendar"></i> <?= \Yii::$app->formatter->asDatetime(
                          $log->created_at
                      ) ?></span>

                  <h3 class="timeline-header"><?php
                          echo empty($log->user) ? Html::tag(
                              'span',
                              'Добавлен с сайта',
                              [ 'class' => 'text-orange' ]
                          ) : 'Пользователь: ' . Html::tag('span', $log->user->username, [ 'class' => 'text-blue' ]) ?>
                  </h3>
                    <?php
                        if (isset($data[ 'removed' ])) {
                            ?>
                          <div class="timeline-body">
                            <span class="text-red">Товар удален</span>
                          </div>
                            <?php
                        } elseif (isset($data[ 'id' ])) {
                            ?>
                          <div class="timeline-body">
                            <span class="text-green">Товар добавлен</span>
                          </div>
                            <?php
                        } else {
                            ?>
                          <div class="timeline-body">
                            <ul class="products-list product-list-in-box">
                                <?php foreach ($data as $key => $item) {
                                    echo Html::tag(
                                        'li',
                                        $model->attributeLabels()[ $key ] . ' > ' . Html::tag(
                                            'span',
                                            \Yii::$app->formatter->asText($item[ 'old' ]),
                                            [ 'class' => 'text-red' ]
                                        ) . ' > ' . Html::tag(
                                            'span',
                                            \Yii::$app->formatter->asText($item[ 'new' ]),
                                            [ 'class' => 'text-green' ]
                                        ),
                                        [ 'class' => 'item' ]
                                    );
                                } ?>
                            </ul>
                          </div>
                            <?php
                        }
                    ?>
                </div>
              </li>
                <?php
            }
            
            echo Html::tag('li', Html::tag('i', '', [ 'class' => 'fa fa-clock-o bg-gray' ])) . Html::endTag('ul');
        }
        ?>
    </div>

  </div><!-- /.box-body -->
</div><!-- /.box -->
