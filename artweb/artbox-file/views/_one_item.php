<?php

use yii\helpers\Html;

?>

<div class="col-xs-6 col-md-3 image-container">

    <div id="<?=$field?>_buttons_block">
        <span title="Вернуть старую" id="<?=$field?>_remove_img" class="glyphicon glyphicon-repeat" ></span>
        <span title="Сохранить" id="<?=$field?>_save_img" class="glyphicon glyphicon-ok" ></span>
    </div>

    <?= Html::a( Html::img($item['image']),'#',['class'=>'thumbnail']) ?>

</div>

