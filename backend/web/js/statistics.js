$( function() {
    $('[data-toggle="popover"]').popover();

    $(document).on('click', '#generate-button', function() {
        var link = this.dataset.link;
        var a = $(this);
        $.ajax({
            type: "GET",
            url: link,
            success: function(data) {
                a.replaceWith(data.button);
                $.notify({
                    message: data.message
                },{
                    type: 'info'
                });
            }
        });
    })
});
