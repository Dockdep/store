<?php
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\event\models\Event;
    use yii\bootstrap\Html;
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
    use yii\web\View;
    
    /**
     * @var Event $model
     */
?>
<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
    <div class="style banners-wr">
        
        <a class="banner-pg-link" href="<?= Url::to(
            [
                'event/show',
                'alias' => $model->lang->alias,
                'type'  => 'sale',
            ]
        ) ?>">
            <div class="style img-banner-pg">
                <?= ArtboxImageHelper::getImage(
                    $model->imageUrl,
                    'event_list',
                    [
                        'alt' => $model->lang->title,
                        'title' => $model->lang->title,
                    ],
                    90,
                    true
                ) ?>
            </div>
            <div class="style timmer-all-wr">
                <div class="style banner-extreme-title"><?= StringHelper::truncate($model->lang->title, 50) ?></div>
                <div class="banner-time">
                    <?php if (!empty( $model->end_at ) && strtotime($model->end_at) > strtotime(date("Y-m-d"))) { ?>
                        <div class="title_timer"><?= \Yii::t('app','toendbuy')?></div>
                        <div id="timerid-<?= $model->id ?>" class="clock_timer clock_timer-1"></div>
                        <?php
                        $js = "
                            var clock;

                            clock = $('#timerid-$model->id').FlipClock({
                                clockFace: 'DailyCounter',
                                language: 'ru',
                                classes: {
                                    active: 'flip-clock-active',
                                    before: 'flip-clock-before',
                                    divider: 'flip-clock-divider',
                                    dot: 'flip-clock-dot',
                                    label: 'flip-clock-label',
                                    flip: 'flip',
                                    play: 'play',
                                    wrapper: 'flip-clock-wrapper'
                                },
                            });

                            clock.setTime(" . ( strtotime($model->end_at) - strtotime(date('Y-m-d H:i:s')) ) . ");
                            clock.setCountdown(true);
                            clock.start();";
                        $this->registerJs($js, View::POS_READY);
                        ?>
                    <?php } ?>
                </div>
            </div>
        </a>
    </div>
</div>