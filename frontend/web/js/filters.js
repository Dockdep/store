/**
 * Created by vitaliy on 07.11.16.
 */

/**
 *
 * @param links
 * @param cache
 */

function renderAllLinksCount(links,cache) {
    var count = $("#filters").data('count');
    links.each(function(i){
        var tag = $(this).parents('.sidebar_checks');
        var status = $(this).parents('.input-blocks').find('.filter-status').data('active');
        renderCount(cache[i], tag, count, this);
    });

}


/**
 *
 * @param resultCount
 * @param tag
 * @param count
 * @param link
 */
function renderCount(resultCount, tag, count, link){
    var status = $(link).parents('.input-blocks').find('.filter-status').data('active');
    if(resultCount=='0'){
        $(link).addClass('disabled-link');
        tag.find('input').prop( "disabled", true );
        $(link).find("p").html('('+(resultCount)+')');
    } else {
        if(status){
            $(link).removeClass('disabled-link');
            tag.find('input').prop( "disabled", false );
            count = resultCount-count;
            $(link).find("p").html('('+(count > 0 ? '+'+count : count )+')');
        } else {
            $(link).removeClass('disabled-link');
            tag.find('input').prop( "disabled", false );
            $(link).find("p").html('('+(resultCount)+')');
        }

    }
}

/**
 *
 * @param link
 * @param cache
 * @param links
 * @param check
 */

function priceRequest(link, cache, links,check){
    var tag = $(link).parents('.sidebar_checks');
    var filter = tag.data('filter');
    var filterUrl = tag.data('filter-url');
    var id = $("#filters").data('category');
    cache.push('');
    var num = cache.length-1;
    $.ajax({
        url: filterUrl,
        type: 'GET',
        data: {info:filter,category:id},
        success: function(result){
            cache[num] = result;
            check.push(result);

            renderAllLinksCount(links, cache );

    }});
}


function loadPrices(){
    const cache = [];
    const check = [];
    const $filterLinks = $('.filter-link').filter(function () {
        return !$(this).parents('.sidebar_checks').data('checked');
    });

    $filterLinks.each(function(){
        priceRequest(this, cache,$filterLinks,check);
    });


}


function priceSlider(){

    var $price_interval = $('#price_interval');
    var $filter_prices_min = $('#filter-prices-min');
    var $filter_prices_max = $('#filter-prices-max');


    if($price_interval.length){

        var block = $('#price_block');
        var link = block.data('url');
        var min = block.data('min');
        var max = block.data('max');
        var from = block.data('from');
        var to = block.data('to');


        $price_interval.ionRangeSlider({
            type: 'double',
            min: min,
            max: max,
            from: from,
            to: to,
            grid: false,
            hide_min_max: true,
            hide_from_to: true,
            onChange: function (e) {
                $filter_prices_min.val(e.from);
                $filter_prices_max.val(e.to)
            },
            onFinish: function(e) {
                // $('#filter-prices-min').val(e.from)
                // $('#filter-prices-max').val(e.to)
                // var url = link;
                // var from = e.from;
                // var to = e.to;
                // $.pjax({url: url.replace('{from}', from).replace('{to}', to), container: "#list-container",timeout:5000, scrollTo: false})
            }
        });

        $('#btn_ok span').click(function () {
            var from = $filter_prices_min.val();
            var to = $filter_prices_max.val();
            $.pjax({url: link.replace('{from}', from).replace('{to}', to), container: "#list-container",timeout:5000, scrollTo: 0})
        });

        var slider = $price_interval.data("ionRangeSlider");

        $filter_prices_min.change(function () {
            var newVal = $(this).val();
            slider.update({
                from: newVal
            });
        });

        $filter_prices_max.change(function () {
            var newVal = $(this).val();
            slider.update({
                to: newVal
            });
        })
        

    }
}

$( document ).ready(function() {
  //loadPrices();
    priceSlider();
    var $body = $('body');

    $body.on('click', '.disabled-link', function(e){
       e.preventDefault();
   });

    $body.on('click', '.features-option', function(){
       var link = $(this).siblings('a');
       var url = link.attr('href');
       if(link.hasClass('disabled-link')){
           e.preventDefault();
       } else {
           $.pjax({url: url, container: '#list-container',timeout:5000,scrollTo:0 })
       }

   });

    $("#list-container").on("pjax:end", function() {
       //loadPrices();
        priceSlider();
    });
});
