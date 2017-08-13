<?php
    
    use artweb\artbox\seo\models\SeoCategory;
    use artweb\artbox\seo\models\SeoCategoryLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View              $this
     * @var SeoCategory       $model
     * @var SeoCategoryLang[] $modelLangs
     */
    
    $this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Seo Category',
        ]) . $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Seo Categories'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->lang->title,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="seo-category-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model'       => $model,
        'modelLangs' => $modelLangs,
    ]) ?>

</div>
