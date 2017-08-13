<?php
    
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\event\models\Event;
    use artweb\artbox\seo\widgets\Seo;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ListView;
    
    /**
     * @var $model Event
     */
    $this->title = $model->lang->title;
    
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Html::tag(
            'span',
            \Yii::t('app', 'Акции'),
            [
                'itemprop' => 'name',
            ]
        ),
        'url'   => [ 'event/promo' ],
        'itemprop' => 'item',
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Html::tag(
            'span',
            $this->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='3' /></li>\n",
    ];
    
    $this->params[ 'seo' ][ 'title' ] = $model->lang->meta_title;
    
    $this->params[ 'seo' ][ 'h1' ] = $this->title;
?>
<div class="container">
    <div class="row contacts_row">
        <div class="style">
            <div class="col-xs-12 col-sm-12"><h1 class="style banner-extreme-title _banner-extreme-title_new"><?= Seo::widget([ 'row' => Seo::H1 ]) ?></h1></div>
            <div class="col-xs-12 col-sm-12">
                <div class="style sale-img-pag-one">
                    <?= ArtboxImageHelper::getImage(
                        $model->imageUrl,
                        'event_show',
                        [
                            'alt'   => $model->lang->title,
                            'title' => $model->lang->title,
                        ],
                        90,
                        true
                    ) ?>
                </div>
                <div class="style timer-wr-page-act timer-wr-page-act_">
                    <div class="style timer_page-s">


                            <?php

                            if (!empty( $model->end_at ) && strtotime($model->end_at) > strtotime(date("Y-m-d"))) { ?>
                        <div class="banner-time">
                                <div class="title_timer"><?= \Yii::t('app','toendact')?></div>
                                <div id="timerid-<?= $model->id ?>" class="clock_timer clock_timer-1"></div>
                        </div>
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
                                $this->registerJs($js, View::POS_LOAD);
                                ?>
                            <?php } ?>


                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 sale-page-txt desk_txt_ sale_pg-txttt">
                <?= $model->lang->body ?>
            </div>

            <?= ListView::widget(
                [
                    'dataProvider' => $productProvider,
                    'summary'      => false,
                    'options'      => [
                        'class' => 'list-view style catalog-wrapp-all',
                    ],
                    'itemOptions'  => [
                        'class' => 'col-sm-3 catalog-wr',
                    ],
                    'itemView'     => '../catalog/_product_item',
                ]
            ) ?>
        
        </div>
    </div>
</div>
