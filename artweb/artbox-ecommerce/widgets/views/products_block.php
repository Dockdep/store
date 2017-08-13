<?php
if (!empty($products)) {
    ?>
    <div class="style slider-wr">
        <div class="container">
            <div class="row">
                <div class="col-xs-12" style="padding: 0;">
                    <div class="style title_slider">
                        <span><?= \Yii::t('app', $title) ?></span>
                        <div class="customNavigation">
                            <div class="prev_btn"></div>
                            <div class="next_btn"></div>
                        </div>
                    </div>
                    <div class="style slider_">
                        <div class="owl-carousel">
                            <?php
                            foreach ($products as $item) {
                                echo \yii\helpers\Html::tag(
                                    'div',
                                    $this->render(
                                        'product_smart',
                                        [
                                            'model' => $item,
                                        ]
                                    ),
                                    [
                                        'class' => 'catalog-wr',
                                    ]
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>