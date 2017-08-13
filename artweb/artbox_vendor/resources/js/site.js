$(
    function()
    {

        var iii = true;

        $('body').on(
            'click', '.btn-warning', function()
            {

                var testt = $(this);

                var id = $(this).attr('id');

                var ddd = document.getElementById('test_tr_class');

                // $.post( "index.php?r=order%2Fupdate&id=1", function( data ) {

                if(!ddd)
                {

                    testt.closest('tr').after(
                        '<tr id="test_tr_class">' + '<td colspan="12" id="content_' + id + '">' + 'data' + '</td>' + '</tr>'
                    );

                    loadShow(testt, id);

                } else
                {
                    document.getElementById('test_tr_class').remove();
                }
                ;

                iii = false;
                console.log(iii);

            }
        );

        function loadShow(testt, id)
        {
            $.post(
                "/admin/order/show?id=" + id + '"', function(data)
                {

                    $('#content_' + id).html(data);

                    $('#add_mod').submit(
                        function()
                        {
                            $.ajax(
                                {
                                    type : "POST",
                                    url : "/admin/order/add?order_id=" + id,
                                    data : $(this).serialize(), // serializes the form's elements.
                                    success : function(data)
                                    {
                                        loadShow(testt, id); // show response from the php script.
                                    }
                                }
                            );
                            return false;
                        }
                    );

                }
            );

        }

    }
);