$(function() {
    lazyInit();
    $(document).on('pjax:complete', function() {
        lazyInit();
    });
    $(document).on('translated.owl.carousel', function() {
        lazyInit();
    });
});
function lazyInit() {
    $('img.artbox-lazy').lazyload({
        skip_invisible: false
    });
    lazyThreshold();
    lazyEvent();
    lazyEffect();
}
function lazyThreshold() {
    $.each($('img.artbox-lazy-threshold'), function(index, value) {
        var threshold = 205;
        var attribute = $(value).data('threshold');
        if(attribute) {
            threshold = attribute;
        }
        $(value).lazyload({
            threshold: threshold
        });
    });
}
function lazyEvent() {
    $.each($('img.artbox-lazy-event'), function(index, value) {
        var event = 'lazy.artbox';
        var attribute = $(value).data('event');
        if(attribute) {
            event = attribute;
        }
        $(value).lazyload({
            event: event
        });
    });
}
function lazyEffect() {
    $.each($('img.artbox-lazy-effect'), function(index, value) {
        var effect = 'fadeIn';
        var attribute = $(value).data('effect');
        if(attribute) {
            effect = attribute;
        }
        $(value).lazyload({
            effect: effect
        });
    });
}