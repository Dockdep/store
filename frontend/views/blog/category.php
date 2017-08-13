<?php
    use artweb\artbox\blog\models\BlogCategory;
    use artweb\artbox\seo\widgets\Seo;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ListView;
    
    /**
     * @var View               $this
     * @var BlogCategory       $model
     * @var ActiveDataProvider $dataProvider
     */
    $this->title = $model->lang->title;
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
    $this->params[ 'seo' ][ 'title' ] = !empty( $model->lang->meta_title ) ? $model->lang->meta_title : '';
    
    $this->params[ 'seo' ][ 'fields' ][ 'name' ] = $model->lang->title;
    $this->params[ 'seo' ][ 'h1' ] = $model->lang->title;
    $this->params[ 'seo' ][ 'seo_text' ] = $model->lang->seo_text;
    $this->params[ 'seo' ][ 'description' ] = $model->lang->meta_description;
?>
<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 title_card"><?= Seo::widget([ 'row' => Seo::H1 ]) ?></h1>
    </div>
    <div class="row posts_row">
        <?php
            echo ListView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'itemView'     => '_item',
                    'itemOptions'  => [
                        'class' => 'row',
                    ],
                    'options'      => [
                        'class' => 'col-xs-12 col-sm-12',
                    ],
                    'layout'       => "{items}\n{pager}",
                ]
            )
        ?>
    </div>

</div>
