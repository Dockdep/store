<?php
use yii\helpers\Html;

?>

<div class="col-xs-6 col-md-3 image-container">
    <?= Html::img($item['image'])?>
    <span data-url="<?=$item['image']?>" title="удалить изображение" class="glyphicon glyphicon-trash delete-gallery-item"></span>
</div>