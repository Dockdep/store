$(
    function() {
        $(document).on('click', '.toggle-status', function(e) {
            e.preventDefault();
            var tag = $(this);
            $.ajax({
                url: tag.attr('href'),
                type: "GET",
                success: function(data) {
                    if (data.success) {
                        tag.html(data.tag);
                        $.notify({
                            message: data.message
                        },{
                            type: 'success'
                        });
                    }
                }
            });
        });

        $(document).on('fileclear', '.artbox-delete-file',  function(e) {
            if(!e.target.multiple) {
                var deleteUrl = $(e.target).attr('deleteurl');
                if(deleteUrl) {
                    $.post(deleteUrl);
                }
            }
        });

        $(document).on('change', '.credit_input', function(e) {
            e.preventDefault();
            var credit_sum = parseFloat($('#order-credit_sum').val());
            var total_sum = parseFloat($('#order-credit_sum').data('sum'));
            var credit_month = parseInt($('#order-credit_month').val());
            var credit_value_full = total_sum - credit_sum;
            $('.credit_value_full').html(credit_value_full+' грн');
            $('.credit_value').html(Math.ceil((credit_value_full / credit_month) + (credit_value_full * 2 / 100)));
        });


        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(
                    strs, function(i, str) {
                        matches.push(str);
                    }
                );

                cb(matches);
            };
        };

        var status = [
            '?М',
            '?Мсток',
            '?Го',
            '?Гр',
            '?Х',
            '?О',
            '?Д',
            '?Б',
            '?Б2',
            '?Осок',
            'брак',
            'бронь',
            'вернет',
            'У нас'
        ];

        var booking = [
            'М',
            'Мсток',
            'Го',
            'Гр',
            'Х',
            'О',
            'Д',
            'Б',
            'Б2',
            'Осок'
        ];

        initTypeahead();

        function initTypeahead() {
            $('.status-typeahead')
            .typeahead(
                {
                    hint : true,
                    highlight : true,
                    minLength : 0
                }, {
                    name : 'status',
                    limit: 14,
                    source : substringMatcher(status)
                }
            );

            $('.booking-typeahead')
            .typeahead(
                {
                    hint : true,
                    highlight : true,
                    minLength : 0
                }, {
                    name : 'booking',
                    limit: 10,
                    source : substringMatcher(booking)
                }
            );
        }

        $(document)
        .on(
            'pjax:complete', function() {
                initTypeahead();
            }
        )

        $(document).on('click', '.no-click', function(e) {
            e.preventDefault();
        });

    }
);