<?php
    /**
     * @var Brand[] $brands
     * @var View    $this
     */
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\Brand;
    use artweb\artbox\seo\widgets\Seo;
    use yii\bootstrap\Html;
    use yii\web\View;
    
    $this->title = \Yii::t('app', 'brands');
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
            'span',
            $this->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">{link}<meta itemprop="position" content="2" /></li>',
    ];

    $this->params[ 'seo' ][ 'fields' ][ 'name' ] = $this->title;

    $this->params[ 'seo' ][ 'h1' ] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title_card">
            <h1><?= Seo::widget([ 'row' => Seo::H1 ]) ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <?php
                foreach ($brands as $brand) {
                    echo Html::tag(
                        'div',
                        Html::a(
                            ArtboxImageHelper::getImage(
                                $brand->imageUrl,
                                'brand_list',
                                [
                                    'boder' => 0,
                                    'alt'   => $brand->lang->title,
                                    'title' => $brand->lang->title,
                                ],
                                90,
                                true
                            ),
                            [
                                'brand/view',
                                'slug' => $brand->lang->alias,
                            ],
                            [ 'title' => $brand->lang->title ]
                        ),
                        [
                            'class' => 'box_brend',
                        ]
                    );
                }
            ?>
        </div>
    </div>
</div>
