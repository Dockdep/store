<?php
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\LinkPager;
    use yii\widgets\ListView;
    
    /**
     * @var View               $this
     * @var ActiveDataProvider $dataProvider
     */
    $this->title = \Yii::t('app', 'Видео');
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
            'span',
            $this->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='3' /></li>\n",
    ];
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title_card"><?php echo $this->title; ?></div>
    </div>
    <div class="row contacts_row">
        <div class="col-xs-12 col-sm-12 videos-pg">
            <?php
                echo ListView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'options'      => [
                            'class' => 'style video_list',
                            'tag'   => 'ul',
                        ],
                        'itemView'     => '_item',
                        'itemOptions'  => [
                            'tag' => 'li',
                        ],
                        'layout'       => "{items}",
                    ]
                );
            ?>
        </div>
        <div class="col-xs-12 col-sm-12">
            <?php
                echo LinkPager::widget(
                    [
                        'pagination' => $dataProvider->pagination,
                    ]
                );
            ?>
        </div>
    </div>
</div>
