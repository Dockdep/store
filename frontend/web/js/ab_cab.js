$(document).ready(function(){
    notice()
    menuFix()
    newPass()
    fixHeightFormsWr()
    ordersExpand()
    ieFixPgnavi()
    helpTitle()
    function notice() {
        var flag = false;
        var animTime = 250
        var liSideBare = $('.box_side-notice ul li')
        var ulSideBare = $('.box_side-notice ul')
        var ulHeight = $('.box_side-notice ul').height()
        if(ulHeight>210) {
            startNotice()
        }
        function startNotice() {
            $('.arrows_down').addClass('on_arrows')
            $(liSideBare[0]).addClass('active_li')

            var fullHeight=0;

            $(liSideBare).each(function(){
                fullHeight+=$(this).height();
            });

            function nextClick(object) {
                if (flag) return false;
                flag = true;
                var activeLi = $('.active_li')
                if(activeLi.index()<(liSideBare.length)-1) {
                    $('.arrows_up').addClass('on_arrows')
                    var margin = ulSideBare.css('marginTop')
                    margin = margin.replace('px','')
                    margin=+margin
                    margin+=-((activeLi.height())+2)
                    // console.log(margin+ulHeight+'/')
                    if((margin+ulHeight)>150){
                        $(ulSideBare).animate({marginTop:margin},animTime)
                        activeLi.removeClass('active_li').next().addClass('active_li')
                        if((activeLi.index())==(liSideBare.length)-4){
                            console.log($(this))
                            $(object).removeClass('on_arrows')
                        }
                    }
                }
                setTimeout(function(){
                    flag = false;
                },animTime)
            }

            function prevClick(object) {
                if (flag) return false;
                flag = true;
                var activeLi = $('.active_li')
                if(activeLi.index()<(liSideBare.length)-1 && activeLi.index()>0) {
                    $('.arrows_down').addClass('on_arrows')
                    var margin = ulSideBare.css('marginTop')
                    margin = margin.replace('px','')
                    margin=+margin
                    margin+=((activeLi.prev().height())+2)
                    if((activeLi.index())==(liSideBare.length)-6){
                        $(object).removeClass('on_arrows')
                    }
                    if((margin+ulHeight)>150){
                        $(ulSideBare).animate({marginTop:margin},animTime)
                        activeLi.removeClass('active_li').prev().addClass('active_li')
                    }
                }
                setTimeout(function(){
                    flag = false;
                },animTime)
            }
           $('.arrows_down').click(function () {
               nextClick(this)
           })

            $('.arrows_up').click(function () {
                prevClick(this)
            })
        }
        addBlur()
        function addBlur() {
            $('.box_side-notice li').hover(function () {
                $('.box_side-notice li').addClass('blur')
                $(this).removeClass('blur')
            }, function () {
                $('.box_side-notice li').removeClass('blur')
            })
        }
    }

    function menuFix() {
        var menuLi = $('.artbox_cab-menu-wr ul li')
        var menuLiLenght = (menuLi.length)-1
        $(menuLi[menuLiLenght]).addClass('last-child')
    }

    function newPass() {
        $('.change_pass_ a').click(function () {
            $(this).parents('.change_pass_').remove()
            $('.input-blocks._hidden_pass').removeClass('_hidden_pass')
        })
    }

    function fixHeightFormsWr() {
        var sidebareHeight = $('.artbox_cab-sidebar-wr').height()
        var menuHeight = $('.artbox_cab-menu-wr').height()
        if(sidebareHeight>=menuHeight) {
            $('.artbox_cab-forms-wr').css({minHeight:sidebareHeight})
        } else {
            $('.artbox_cab-forms-wr').css({minHeight:menuHeight})
        }
    }
    
    function ordersExpand() {
        $('._order_num span').click(function () {
            $(this).parents('._orders').toggleClass('active')
        })
    }

    function ieFixPgnavi() {
      var li = $('.pgnavi_wr ul li')
      var liLenght = (li.length)-1
        $(li[liLenght]).addClass('last_li')
    }

    function helpTitle() {
        var timeOut = setTimeout(function () {
        },100)
        $('._personal_title_link a').hover(function (e) {
            var title = $(this).data('title')
            var thisBlock = $(this).parents('._personal_data')
            timeOut = setTimeout(function () {
                thisBlock.append('<div class="_data_title">'+title+'</div>')
            },700)
        }, function () {

        })
        $('._personal_data').mouseleave(function () {
            $('._data_title').remove()
            clearTimeout(timeOut);
        })
    }
});




