<?php
    /**
     * @var Category $rootCategory
     * @var string   $rootClass
     */
    use artweb\artbox\modules\catalog\models\Category;

?>
<div class="menu_item">
    <?= \yii\helpers\Html::a($rootCategory->lang->title, [
        'catalog/category',
        'category' => $rootCategory,
    ], [ 'class' => 'submenu_button ' . $rootClass ]) ?>
    <div class="submenu">
        <ul class="categories">
            <?php foreach($items as $item) : ?>
                <li class="sub_cat">
                    <span><?= $item[ 'item' ]->title ?></span>
                    <?php if(!empty( $item[ 'children' ] )) : ?>
                        <div class="sub_cat_content">
                            <div class="content_items">
                                <?php foreach($item[ 'children' ] as $_item) : ?>
                                    <div class="content_item"><a href="<?= \yii\helpers\Url::to([
                                            'catalog/category',
                                            'category' => $_item[ 'item' ],
                                        ]) ?>">
                                            <div class="picture">
                                                <?php if(empty( $_item[ 'item' ]->image )) : ?>
                                                    <img src="/images/no_photo.png">
                                                <?php else : ?>
                                                    <?= $_item[ 'item' ]->imageUrl ? \artweb\artbox\components\artboximage\ArtboxImageHelper::getImage($_item[ 'item' ]->imageUrl, 'mainmenu') : '' ?>
                                                <?php endif ?>
                                            </div>
                                            <div class="title"><?= $_item[ 'item' ]->title ?></div>
                                        </a></div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>
