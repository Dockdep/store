<?php
    use artweb\artbox\blog\models\BlogArticle;
use artweb\artbox\seo\widgets\Seo;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View        $this
     * @var BlogArticle $model
     */
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label' => Html::tag(
            'span',
            $model->blogCategory->lang->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'url'   => [
            'blog/category',
            'slug' => $model->blogCategory->lang->alias,
        ],
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
    $this->params['seo']['title'] = !empty($model->lang->meta_title) ? $model->lang->meta_title : '';

    $this->params['seo']['fields']['name'] = $model->lang->title;
    $this->params['seo']['h1'] = $model->lang->title;
    $this->params['seo']['seo_text'] = $model->lang->seo_text;
    $this->params['seo']['description'] = $model->lang->meta_description;
?>
<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 title_card"><?= Seo::widget(['row'=>Seo::H1])?></h1>
    </div>
    <div class="row post_txt">
        <div class="col-xs-12 col-sm-12">
            <?php
                echo $model->lang->body;
            ?>
        </div>
    </div>

</div>
