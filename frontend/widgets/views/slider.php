<?php
    /* @var $slider Slider */
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\design\models\Slider;
    use yii\helpers\Html;
    use yii\web\View;

?>
<div class="style slider_time-wr">
    <div id="<?= $title ?>" class="owl-carousel">
        <?php if ($slider instanceof Slider) {
            foreach ($slider->sliderImages as $image) {
                
                ?>
                <div class="sl-timer">
                    <?php
                        if (!empty( $image->end_at ) && strtotime($image->end_at) > strtotime(date("Y-m-d"))) {
                            ?>
                            <div class="banner-time">
                                <div class="style banner-extreme-title"><span>СУПЕРТЕМа ОТ ЭКТРЕМА</span>Вкусная цена
                                </div>
                                <div class="title_timer">до конца акции осталось</div>
                                <div class="clock_centered">
                                    <div class="clock_style clock_<?= $image->primaryKey ?>"></div>
                                </div>
                                <div class="style period_sales">
                                    Период проведения акции:<br/>
                                    с 10 июля по 24 июля 2015 года
                                </div>
                            </div>
                            
                            
                            <?php $js = "var clock;
                clock = $('.clock_" . $image->primaryKey . "').FlipClock({              
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

                    clock.setTime(" . ( strtotime($image->end_at) - strtotime(date('Y-m-d H:i:s')) ) . ");
                    clock.setCountdown(true);
                    clock.start();";
                            $this->registerJs($js, View::POS_LOAD) ?>
                            
                            
                            <?php
                        } ?>
                    <noindex>
                        <?= Html::a(
                            ArtboxImageHelper::getImage($image->imageUrl, 'slider_img', [], 40),
                            $image->url,
                            [
                                'rel' => 'nofollow',
                            ]
                        ) ?>
                    </noindex>
                </div>
            
            <?php };
        }
        ?>
    
    </div>
</div>

<?php
    $dur = $slider->duration ? $slider->duration : 5000;
    $speed = $slider->speed ? $slider->speed : 500;
    $js = "

            var owlTimer = $('#$title')
            owlTimer.owlCarousel(
                {
                    responsiveClass: true,
                    loop: false,
                    items: 1,
                    navSpeed: 400,
                    smartSpeed: 400,
                    nav: true,
                    navText: [],
                    autoplay: true,
                    autoplayTimeout: $dur,
                    paginationSpeed : $speed,
                    autoplayHoverPause: true,
                    dots: true
                }
            )
            var dots = $('.slider_time-wr .owl-dots')
            $('.slider_time-wr .owl-prev')
            .after(dots)
     ";
    
    $this->registerJs($js, View::POS_READY);

?>
