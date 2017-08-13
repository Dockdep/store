/**
 * Artbox comment plugin
 *
 * @todo Translate Submit and Loading texts
 */
(function($)
{

    $.fn.artbox_comment = function(method)
    {
        if(methods[method])
        {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if(typeof method === 'object' || !method)
        {
            return methods.init.apply(this, arguments);
        } else
        {
            $.error('Method ' + method + ' does not exist on jQuery.comment');
            return false;
        }
    };

    // Default settings
    var defaults = {
        formContainerSelector : '.artbox_form_container',
        formSelector : '#artbox-comment-form',
        listContainerSelector : '.artbox_list_container',
        listSelector : '#artbox-comment-list',
        itemContainerSelector : '.artbox_item_container',
        itemInfoSelector : '.artbox_item_info',
        itemToolsSelector : '.artbox_item_tools',
        itemReplySelector : '.artbox_item_reply',
        childrenContainerSelector : '.artbox_children_container',
        childContainerSelector : '.artbox_child_container',
        childInfoSelector : '.artbox_child_info',
        childToolsSelector : '.artbox_child_tools',
        childReplySelector : '.artbox_child_reply',
        replyContainerSelector : '.artbox_comment_reply_container',
        widgetContainerSelector : '.artbox_comment_container'
    };

    // Methods
    var methods = {
        init : function(options)
        {
            return this.each(
                function()
                {
                    var $commentForm = $(this);
                    if($commentForm.data('artbox_comment'))
                    {
                        return;
                    }
                    var settings = $.extend({}, defaults, options || {});
                    $commentForm.data('artbox_comment', settings);
                    //Add events
                    var eventParams = {commentForm : $commentForm};
                    $commentForm.on('beforeSubmit.artbox_comment', eventParams, beforeSubmitForm);
                    $(settings.listSelector).on('beforeSubmit.artbox_comment', settings.formSelector + '-reply', eventParams, reply);
                    $(settings.listSelector).on('click.artbox_comment', settings.itemToolsSelector + ' [data-action="reply"]', eventParams, replyInit);
                    $(settings.listSelector).on('click.artbox_comment', settings.itemToolsSelector + ' [data-action="reply-cancel"]', eventParams, replyCancel);
                    $(settings.listSelector).on('click.artbox_comment', settings.itemToolsSelector + ' [data-action="delete"]', eventParams, deleteComment);
                    $(settings.listSelector).on('click.artbox_comment', settings.itemToolsSelector + ' [data-action="like"]', eventParams, like);
                    $(settings.listSelector).on('click.artbox_comment', settings.itemToolsSelector + ' [data-action="dislike"]', eventParams, dislike);
                    $(settings.listSelector).on('click.artbox_comment', settings.childToolsSelector + ' [data-action="reply"]', eventParams, replyChildInit);
                    $(settings.listSelector).on('click.artbox_comment', settings.childToolsSelector + ' [data-action="reply-cancel"]', eventParams, replyCancel);
                    $(settings.listSelector).on('click.artbox_comment', settings.childToolsSelector + ' [data-action="delete"]', eventParams, deleteChild);
                    $(settings.listSelector).on('click.artbox_comment', settings.childToolsSelector + ' [data-action="like"]', eventParams, likeChild);
                    $(settings.listSelector).on('click.artbox_comment', settings.childToolsSelector + ' [data-action="dislike"]', eventParams, dislikeChild);
                }
            );
        },
        data : function()
        {
            return this.data('artbox_comment');
        }
    };

    /**
     * Submit reply form
     *
     * @param event
     */
    function reply(event)
    {
        /**
         * @todo Implement
         */
        event.preventDefault();
        var $replyForm = $(this);
        var $commentForm = event.data.commentForm;
        settings = $commentForm.data('artbox_comment');
        $replyForm.find(':submit').prop('disabled', true).text('Загрузка...');
        $.post(
            $replyForm.attr("action"), $replyForm.serialize(), function(data)
            {
                if(data.status == 'success')
                {
                    hideForm($commentForm);
                    $(settings.listSelector).load(
                        ' ' + settings.listSelector, function(data)
                        {
                            $replyForm.find(':submit').prop('disabled', false).text('Добавить комментарий');
                            $replyForm.trigger("reset");
                        }
                    );
                }
                else
                {
                    if(data.hasOwnProperty('errors'))
                    {
                        $replyForm.yiiActiveForm('updateMessages', data.errors, true);
                    } else
                    {
                        $replyForm.yiiActiveForm('updateAttribute', 'commentmodel-text-reply', [data.message]);
                    }
                    $replyForm.find(':submit').prop('disabled', false).text('Добавить комментарий');
                }
            }
        );
        return false;
    }

    /**
     * Submit comment form
     *
     * @param event
     */
    function beforeSubmitForm(event)
    {
        event.preventDefault();
        var $commentForm = $(this), settings = $commentForm.data('artbox_comment');
        $commentForm.find(':submit').prop('disabled', true).text('Загрузка...');
        $.post(
            $commentForm.attr("action"), $commentForm.serialize(), function(data)
            {
                if(data.status == 'success')
                {
                    hideForm($commentForm);
                    $(settings.listSelector).load(
                        ' ' + settings.listSelector, function(data)
                        {
                            $commentForm.find(':submit').prop('disabled', false).text('Добавить комментарий');
                            $commentForm.trigger("reset");
                        }
                    );
                }
                else
                {
                    if(data.hasOwnProperty('errors'))
                    {
                        $commentForm.yiiActiveForm('updateMessages', data.errors, true);
                    } else
                    {
                        $commentForm.yiiActiveForm('updateAttribute', 'commentmodel-text', [data.message]);
                    }
                    $commentForm.find(':submit').prop('disabled', false).text('Добавить комментарий');
                }
            }
        );
        return false;
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function replyInit(event)
    {
        event.preventDefault();
        var data = event.data.commentForm.data('artbox_comment');
        var form = $(data.formSelector + '-reply');
        var button = this;
        var item = $(button).parents(data.itemContainerSelector);
        var item_id = $(item).data('key');
        $(form).find('#commentmodel-artbox_comment_pid-reply').val(item_id);
        $(item).find(data.itemReplySelector).append(form);
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function replyCancel(event)
    {
        event.preventDefault();
        hideForm(event.data.commentForm);
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function deleteComment(event)
    {
        event.preventDefault();
        var data = event.data.commentForm.data('artbox_comment');
        hideForm(event.data.commentForm);
        var button = this;
        var item = $(button).parents(data.itemContainerSelector);
        $.post($(button).data('url'), function(data) {
            if(data.status == 'success')
            {
                $(item).text(data.message);
            }
            else
            {
                console.log(data.message);
            }
        });
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function like(event)
    {
        event.preventDefault();
        /**
         * @todo Implement
         */
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function dislike(event)
    {
        event.preventDefault();
        /**
         * @todo Implement
         */
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function replyChildInit(event)
    {
        event.preventDefault();
        var data = event.data.commentForm.data('artbox_comment');
        var form = $(data.formSelector + '-reply');
        var button = this;
        var item = $(button).parents(data.childContainerSelector);
        var item_id = $(item).data('key');
        $(form).find('#commentmodel-artbox_comment_pid-reply').val(item_id);
        $(item).find(data.childReplySelector).append(form);
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function deleteChild(event)
    {
        event.preventDefault();
        var data = event.data.commentForm.data('artbox_comment');
        hideForm(event.data.commentForm);
        var button = this;
        var item = $(button).parents(data.childContainerSelector);
        $.post($(button).data('url'), function(data) {
            if(data.status == 'success')
            {
                $(item).text(data.message);
            }
            else
            {
                console.log(data.message);
            }
        });
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function likeChild(event)
    {
        event.preventDefault();
        /**
         * @todo Implement
         */
    }

    /**
     * Init reply form
     *
     * @param event
     */
    function dislikeChild(event)
    {
        event.preventDefault();
        /**
         * @todo Implement
         */
    }

    function hideForm(commentForm)
    {
        var data = $(commentForm).data('artbox_comment');
        var form = $(data.formSelector+'-reply');
        $(form).parents(data.widgetContainerSelector).find(data.replyContainerSelector).append(form);
        form.trigger('reset');
    }

})(window.jQuery);
