<?php
    use artweb\artbox\seo\widgets\Seo;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ListView;
    
    /**
     * @var View               $this
     * @var ActiveDataProvider $dataProvider
     */
    $this->title = \Yii::t('app', 'Распродажа');
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Html::tag(
            'span',
            $this->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];
    $this->params[ 'seo' ][ 'h1' ] = $this->title;
?>
<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 title_card">
            <?= Seo::widget([ 'row' => Seo::H1 ]) ?>
        </h1>
    </div>
    <div class="row">
        <div class="style" style="margin-top: -10px;padding-bottom: 11px;">
            <?= ListView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'summary'      => false,
                    'itemOptions'  => [
                        'class' => '',
                    ],
                    'itemView'     => '_event_sale_item',
                ]
            ) ?>
        </div>
    </div>
</div>