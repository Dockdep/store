<?php

use artweb\artbox\seo\widgets\Seo;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ListView;

/**
 * @var View   $this
 * @var ArrayDataProvider $dataProvider
 */


$this->title = \Yii::t('app','my_cabinet');
$this->params['breadcrumbs'][] = [
    'label' => Html::tag(
        'span',
        $this->title,
        [
            'itemprop' => 'name',
        ]
    ),
    'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
];
$this->params[ 'seo' ][ Seo::H1] =  $this->title ;
$this->params[ 'seo' ][ Seo::TITLE] = $this->title;
?>
<div style="padding-bottom: 9px;" class="col-xs-12 col-sm-12 col-md-8 col-lg-9">
    <div class="artbox_cab-forms-wr style">
        <div class="artbox_cab-forms-bg"></div>
        <div class="artbox_cab-forms">
            <ul class="mob-cab-list">
                <li>
                    <?= Html::a("<div class='menu-ico_1'></div>".\Yii::t('app','данные'), Url::to(['cabinet/main']),[
                        "class" => "mob-cab-link"
                    ]);?>
                </li>
                <li>
                    <?= Html::a("<div class='menu-ico_5'></div>".\Yii::t('app','заказы'), Url::to(['cabinet/my-orders']),[
                        "class" => "mob-cab-link"
                    ]);?>
                </li>
                <li class="active-2">
                    <?= Html::a("<div class='menu-ico_6'></div>".\Yii::t('app','просмотры'), Url::to(['cabinet/history']),[
                        "class" => "mob-cab-link"
                    ]);?>

                    <?= ListView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'summary' =>false,
                            'options'      => [
                                'class' => 'list-view style catalog-wrapp-all',
                            ],
                            'itemOptions'  => [
                                'class' => 'col-sm-4 col-md-6 col-lg-4 catalog-wr',
                            ],
                            'itemView'     => '../catalog/_product_item',
                        ]
                    ) ?>
                </li>
                <li>
                    <?= Html::a("<div class='menu-ico_9'></div>".\Yii::t('app', "выход"),Url::to(['site/logout']),[
                        "class" => "mob-cab-link"
                    ]);?>
                </li>
            </ul>
        </div>
    </div>
</div>