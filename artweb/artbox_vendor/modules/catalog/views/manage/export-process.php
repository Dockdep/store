<?php
    /**
     * @var View $this
     */
    
    use yii\web\View;

?>
<?php
    $this->registerJs("var in_process=true;
    var count=1;
    var filename = null;
    
    doExport(0,filename);
    
    function doExport(from,filename) {
        from = typeof(from) != 'undefined' ? from : 0;
        
        $.ajax({
            method: 'get',
            url: '" . Yii::$app->request->baseUrl . '/product/manage/export-process' . "',
            data: {
                from:from,
                filename: filename
            },
            dataType: 'json',
            success: function(data){
                
                var per = Math.round(100*data.from/data.totalsize)+'%';
                $('#progressbar div').css({width: per});
                
                if(data != false && !data.end)
                {
                    doExport(data.from,data.filename);
                }
                else
                {
                    console.log(data.link);
                    $(progressbar).hide('fast');
                    $('#result_link').attr('href', data.link).removeClass('hidden');
                    in_process = false;
                }
            },
            error: function(xhr, status, errorThrown) {
            }
        });
    }");
?>

<!--<script>-->
<!--    var in_process=true;-->
<!--    var count=1;-->
<!--    var filename = null;-->
<!--    -->
<!--    doExport(0,filename);-->
<!--    -->
<!--    function doExport(from,filename) {-->
<!--        from = typeof(from) != 'undefined' ? from : 0;-->
<!--        -->
<!--        $.ajax({-->
<!--            method: 'get',-->
<!--            url: '".Yii::$app->request->baseUrl .'/product/manage/export-process'."',-->
<!--            data: {-->
<!--                from:from,-->
<!--                filename: filename-->
<!--            },-->
<!--            dataType: 'json',-->
<!--            success: function(data){-->
<!--                -->
<!--                var per = Math.round(100*data.from/data.totalsize)+'%';-->
<!--                $('#progressbar div').css({width: per});-->
<!--                -->
<!--                if(data != false && !data.end)-->
<!--                {-->
<!--                    doExport(data.from,data.filename);-->
<!--                }-->
<!--                else-->
<!--                {-->
<!--                    console.log(data.link);-->
<!--                    progressbar.hide('fast');-->
<!--                    in_process = false;-->
<!--                }-->
<!--            },-->
<!--            error: function(xhr, status, errorThrown) {-->
<!--            }-->
<!--        });-->
<!--    }-->
<!--</script>-->

<div class="product-import-process-form">
    <h1>Экспорт данных товаров</h1>
    
    <?= \yii\jui\ProgressBar::widget([
        'clientOptions' => [
            'value' => 100,
            'label' => '',
        ],
        'options'       => [
            'id' => 'progressbar',
        ],
    ]); ?>
    <ul id="process-result"></ul>
    <a id="result_link" href="" class="hidden"><?php echo \Yii::t('app', 'Get File'); ?></a>
</div>
