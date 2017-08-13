<?php
    use artweb\artbox\blog\models\BlogArticle;
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use yii\helpers\Html;
    use yii\helpers\StringHelper;
    use yii\web\View;
    
    /**
     * @var View        $this
     * @var BlogArticle $model
     */
?>
<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 posts_img">
    <?php
        echo Html::a(
            ArtboxImageHelper::getImage(
                $model->imageUrl,
                'blog_list',
                [
                    'title' => $model->lang->title,
                    'alt'   => $model->lang->title,
                ],
                90,
                true
            ),
            [
                'blog/view',
                'slug' => $model->lang->alias,
            ]
        );
    ?>
</div>
<div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
    <div class="style posts_title">
        <?php
            echo Html::a(
                $model->lang->title,
                [
                    'blog/view',
                    'slug' => $model->lang->alias,
                ]
            );
        ?>
    </div>
    <?php
        echo Html::tag(
            'div',
            $model->lang->body_preview ? : StringHelper::truncateWords($model->lang->body, 100),
            [
                'class' => 'style posts_txt',
            ]
        );
    ?>
    <div class="posts_link">
        <?php echo Html::a(
            \Yii::t('app', 'more'),
            [
                'blog/view',
                'slug' => $model->lang->alias,
            ]
        ); ?>
    </div>
</div>
