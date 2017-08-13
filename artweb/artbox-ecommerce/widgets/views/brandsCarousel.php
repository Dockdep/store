<?php
    /**
     * @var Brand[] $brands
     */
    use artweb\artbox\components\artboximage\ArtboxImageHelper;
    use artweb\artbox\ecommerce\models\Brand;

?>
<div class="slider_prod">
    <div class="pc_prev"></div>
    <div class="prods_carousel">
        <ul>
            <?php foreach ($brands as $brand) { ?>
                <li>
                    <span><a href="<?= \yii\helpers\Url::to(
                            '/brands/' . $brand->lang->alias
                        ) ?>" title="<?= $brand->lang->title ?>"><?= $brand->image ? ArtboxImageHelper::getImage(
                                $brand->imageFile,
                                'brandlist'
                            ) : '' ?></a></span>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="pc_next"></div>
</div>