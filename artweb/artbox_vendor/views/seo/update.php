<?php
    
    use artweb\artbox\models\Seo;
    use artweb\artbox\models\SeoLang;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View      $this
     * @var Seo       $model
     * @var SeoLang[] $modelLangs
     */
    
    $this->title = Yii::t(
            'app',
            'Update {modelClass}: ',
            [
                'modelClass' => 'Seo',
            ]
        ) . $model->url;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Yii::t('app', 'Seos'),
        'url'   => [ 'index' ],
    ];
    $this->params[ 'breadcrumbs' ][] = [
        'label' => $model->url,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="seo-update">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render(
        '_form',
        [
            'model'      => $model,
            'modelLangs' => $modelLangs,
        ]
    ) ?>

</div>
