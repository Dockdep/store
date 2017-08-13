<?php
    /**
     * @var View   $this
     * @var string $method
     */
    use yii\web\View;
    
    $this->registerJs("
var in_process=false;
        var count=1;

        in_process=true;
        doImport();

        function doImport(from) {
            from = typeof(from) != 'undefined' ? from : 0;
            console.log('go', from);
            $.ajax({
                url: '" . \Yii::$app->request->baseUrl . '/ecommerce/manage/' . $method . "',
                data: {from:from},
                dataType: 'json',
                success: function(data){
                    for(var key in data.items)
                    {
                        $('ul#process-result').prepend('<li>'+ data.items[key] +'</li>');
                        count++;
                    }

                    var per = Math.round(100*data.from/data.totalsize)+'%';
                    $('#progressbar div').css({width: per});

                    if(data != false && !data.end)
                    {
                        doImport(data.from);
                    }
                    else
                    {
                        $('ul#process-result').prepend('<li>Импорт цен успешно завершен!</li>');
                        progressbar.hide('fast');
                        in_process = false;
                    }
                },
                error: function(xhr, status, errorThrown) {
                }
            });
        }
");
    //?>

<div class="product-import-process-form">
    <h1>Импорт <?= $method == 'prices' ? 'цен' : 'данных' ?> товаров</h1>
    
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
</div>
