<?php
/**
 * Created by PhpStorm.
 * User: vitaliy
 * Date: 05.10.15
 * Time: 16:20
 */
use artweb\artbox\file\models\ImageSizerForm;
use yii\helpers\Html;

$id = $model->tableSchema->primaryKey[0];

?>

    <?php if(!$multi):?>
<div class="row">

    <?=  Html::activeHiddenInput( $model,$field,['id' => "{$field}_picture_link"]) ?>


    <input type="hidden" id="<?=$field?>_old_img" name="ImageSizerForm[old_img]" value="<?=$model->$field?>"/>
    <input type="hidden" id="<?=$field?>_new_img" name="ImageSizerForm[new_img]" value=""/>
    <input type="hidden" id="<?=$field?>_row_id" name="ImageSizerForm[new_img]" value="<?=$model->$id?>"/>
    <input type="hidden" id="<?=$field?>_row_field" name="ImageSizerForm[field]" value="<?=$field?>"/>
    <div id="<?= $field?>_img_block">
        <div  class="col-xs-6 col-md-3 image-container">

            <?php if($remover && $model->$field): ?>
                <span id="<?=$field?>_remove_img" class="remover_image  glyphicon glyphicon-trash" ></span>
            <?php endif; ?>

            <?php if($model->$field):?>
                <?= Html::a(Html::img($model->$field),'#',['class'=>'thumbnail']) ?>
            <?php endif; ?>
        </div>
    </div>

</div>
<div class="row">
    <span class="btn btn-success fileinput-button uploader-button">
    <!--        <i class="glyphicon glyphicon-plus"></i>-->
        <span><?=$name?></span>

       <?=  Html::activeFileInput( new ImageSizerForm(),'file',['id'=>$field, 'data-url'=>Yii::$app->getUrlManager()->createUrl('file/uploader/download-photo')]);?>
    </span>
</div>









    <script>
    $(function()
    {

        $("#<?= $field?>").fileupload(
            {
                dataType : 'json', formData : {size : '<?= json_encode($size)?>',field:'<?= $field ?>'},
                done : function(e, data)
                {
                    if($("#<?=$field?>_buttons_block").length)
                    {
                        $("#<?=$field?>_buttons_block").remove()
                    }

                    $("#<?= $field?>").parent().prev().find('.admin-ava-wr').remove()

                    var img = data.result.view;

                    var block = $("#<?= $field?>_img_block");

                    block.find('.image-container').remove();

                    block.append(img);

                    $("#<?=$field?>_picture_link").val(data.result.link);

                    $("#<?=$field?>_new_img").val(data.result.link);

                }
            }
        );

        $('body').on(
            'click', '#<?=$field?>_save_img', function()
            {

                $("#<?=$field?>_buttons_block").remove();


                var old_url = $('#<?=$field?>_old_img').val();
                var new_url = $('#<?=$field?>_new_img').val();
                var model = '<?=str_replace('\\', '-', $model::className());?>';
                $.post(
                    "/file/uploader/delete-image", {
                        new_url : new_url, old_img : old_url, model : model, field : "<?= $field?>",
                        id : "<?=$model->$id?>", action : 'save'
                    }, function()
                    {
                    }
                );
                $("#<?=$field?>_picture_link").val(new_url);
            }
        );

        $('body').on(
            'click', '#<?=$field?>_remove_img', function()
            {
                $("#<?=$field?>_buttons_block").remove();

                var old_url = $('#<?=$field?>_old_img').val();
                var new_url = $('#<?=$field?>_new_img').val();
                $.post(
                    "/file/uploader/delete-image", {old_img : new_url}, function()
                    {
                    }
                );
                if(<?=$remover?>){

                    $("#<?=$field?>_picture_link").val('');
                    $('#<?=$field?>_img_block').find('img').remove();

                } else {

                    $("#<?=$field?>_picture_link").val(old_url);

                    if(old_url.length<=1){
                        $('#<?=$field?>_img_block').find('img').remove()
                    }
                    else {
                        console.log(old_url);
                        $('#<?=$field?>_img_block').find('img').attr('src',old_url);
                    }

                }

            }
        );
    });
</script>

<?php else:?>

    <span class="btn btn-success fileinput-button uploader-button">
    <i class="glyphicon glyphicon-plus"></i>
    <span><?=$name?></span>

        <?=  Html::activeFileInput( new ImageSizerForm(),'file',['id'=>$field, 'data-url'=>Yii::$app->getUrlManager()->createUrl('file/uploader/download-photo'), 'multiple'=> 'multiple' ]);?>
    </span>

    <?=  Html::activeHiddenInput( $model,$field,['id' => "{$field}_picture_link"]) ?>


    <input type="hidden" name="ImageSizerForm[multi]" value="true"/>

    <div id="<?= $field?>_img_block">
        <?php

        foreach($this->context->getGallery() as  $image){
            echo $this->render('@common/modules/file/views/_gallery_item', [ 'item' => ['image'=>$image]]);
        }
        ?>
    </div>
    <script>
        $(function(){

            $("#<?= $field?>").fileupload({
                dataType: 'json',
                formData: {size:'<?= json_encode($size)?>', multi: 1,field : "<?= $field?>"},
                done: function (e, data) {
                    var img = data.result.view;
                    var block = $("#<?= $field?>_img_block");
                    block.append(img);
                    var gallery = $("#<?= $field?>_picture_link");
                    gallery.val(gallery.val()+data.result.link+',');
                }
            });
            $('body').on('click','.delete-gallery-item', function(){
                var url = $(this).data('url');
                $(this).parent('.image-container').remove();
                var gallery = $("#<?= $field?>_picture_link");



                var urls = gallery.val();


                gallery.val(urls.replace(url+',', ""));


                $.post( "/file/uploader/delete-image",{old_img: url},  function( data ) {
                    $( ".result" ).html( data );
                });
            })

        })
    </script>

<?php endif;?>
