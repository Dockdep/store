<?php
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View         $this
     * @var ProductVideo $model
     */
    $this->title = $model->title ? : \Yii::t('app', 'Видео без названия');
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
            'span',
            \Yii::t('app', 'Видео'),
            [
                'itemprop' => 'name',
            ]
        ),
        'url'      => [ 'video/list' ],
        'itemprop' => 'item',
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];
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
        <div class="col-xs-12 col-sm-12 title_card video_card">
            <?php
                echo $this->title;
            ?>
        </div>
    </div>
    <div class="row contacts_row">
        <div class="col-xs-12 col-sm-12 videos-pg">
            <?php
                echo $model->url;
            ?>
        </div>
    </div>