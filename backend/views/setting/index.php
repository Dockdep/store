<?php

use artweb\artbox\models\Page;
use artweb\artbox\models\PageSearch;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var  yii\web\View                $this
 * @var  PageSearch                  $searchModel
 * @var  yii\data\ActiveDataProvider $dataProvider
 */

$this->title = \Yii::t('app', 'Настройки');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="box-body">
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>

        <div class="alert alert-info">
            <h4>Кеш</h4>
            <ul class="margin-bottom-none padding-left-lg">
                <li>Обновляет картинки на сайте</li>
                <li>Обновляет статические данные</li>
                <li>Обновляет каталог</li>
                <li>Обновляет фильтр</li>
                <li>Обновляет карточки товара в каталоге</li>
                <li>Автоматическое обновление раз в 24 часа</li>
            </ul>
        </div>
        <button class="btn btn-block btn-social btn-bitbucket" name="refresh" value="cache">
            <i class="fa  fa-database"></i> Обновить Кеш
        </button>
        <br>
        <div class="alert alert-info">
            <h4>Elastic Search</h4>
            <ul class="margin-bottom-none padding-left-lg">
                <li>Товары в каталоге сайта</li>
                <li>Обновляет количество товаров по фильтрам</li>
                <li>Поиск</li>
                <li>Автоматическое обновление раз в 24 часа</li>
                <li>Автоматическое обновление после изменений в карточке товара</li>
                <li>Автоматическое обновление после импорта и обновления цен</li>
            </ul>
        </div>
        <button class="btn btn-block btn-social btn-dropbox"  name="refresh" value="elastic-search">
            <i class="fa  fa-tachometer"></i> Обновить Elastic Search
        </button>

        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>
