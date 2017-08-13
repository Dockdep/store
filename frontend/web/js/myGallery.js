$(document).ready(function(){
    $('body').append('<div id="overlay-gallery"></div><div class="gallery-page"><div class="closed-form gallery-closed"></div> <div class="gallery-show-wr"><div class="gallery-show-big-wrp"><table align="center" class="gallery-show-big" width="840" height="560" cellpadding="0" cellspacing="0" border="0"></table><div class="gallery-big-left"></div><div class="gallery-big-right"></div> <div class="copy-gallery-big-left"></div> <div class="copy-gallery-big-right"></div></div><div class="galley-show-min-wrapper"><div class="gallery-show-min"></div></div><div class="gallery-min-left"></div><div class="gallery-min-right"></div><div class="copy-gallery-min-left"></div><div class="copy-gallery-min-right"></div></div></div>');

    var previewImgWidth = 152;
    var previewImgMargin = 20;
    var minPicLenght = 5;

    var timeAnimate = 500;
    var newPrevScrl = previewImgWidth+previewImgMargin;
    var gallery = $('.gallery-page');
    var procent = 1.68;
    $('.gallery-box-min').click(function(e){
        e.preventDefault();
        $('body').addClass('open-gallery')
        var galleryWindowShow = $(this).parent().find('.gallery-box-hidden');
        if($(galleryWindowShow).hasClass('gallery-box-hidden')) {
            var newHeight = procent;
            var windH = $(window).height();
            var gallery = $('.gallery-page');
            if (windH<755){
                $('#overlay-gallery').fadeIn(400,
                    function(){$('.gallery-page').css({display:'block', marginTop:-(gallShowHeight/2)}).animate({opacity: 1, top:'50%'}, 200);});
                gallery.css({height:((windH/newHeight)+94)+'px', marginTop:-(((windH/newHeight)+94)/2)+'px'});
            }
            else {
                var gallShowHeight = ($('.gallery-show-big-wrp').height())+($('.galley-show-min-wrapper').height());
                $('#overlay-gallery').fadeIn(400,
                    function(){$('.gallery-page').css({display:'block', marginTop:-(gallShowHeight/2)}).animate({opacity: 1, top:'50%'}, 200);});
            }

            addImgToModal(this);
            clickSmallImg();
            $('.gallery-min-right').unbind("click");
            $('.gallery-min-left').unbind("click");
            $('.gallery-min-right').bind("click", arrowNaviRight());
            $('.gallery-min-left').bind("click", arrowNaviLeft());

            $('.gallery-big-right').unbind("click");
            $('.gallery-big-left').unbind("click");
            $('.gallery-big-right').bind("click", bigClickRight());
            $('.gallery-big-left').bind("click", bigClickLeft());
        }
    });

    $('.closed-form, #overlay-gallery').click( function(){
        $('.gallery-page')
            .animate({opacity: 0, top: 255}, 200,
            function(){
                $('body').removeClass('open-gallery')
                $(this).css('display', 'none');
                $('#overlay-gallery').fadeOut(400);
            }
        );
        $('.gallery-big-left, .gallery-big-right').attr('style','');
        $('.gallery-show-wr .gallery-show-min, .gallery-show-wr .gallery-show-big').empty();
    });
    function addImgToModal(label){
        $('.gallery-show-wr .gallery-show-min, .gallery-show-wr .gallery-show-big').empty();
        var minClickIndex = $(label).index();
        $('.gallery-show-min').css({marginLeft:0});
        var previewImg = $(label).parent().find('.gallery-box-preview span');
        var bigImg = $(label).parent().find('.gallery-box-big span');
        for(var pi=0;pi<previewImg.length; pi++) {
            var previewImgLink = $(previewImg[pi]).attr('data-link');
            $('.gallery-show-min').append('<span><img src="'+previewImgLink+'"/></span>');
        }
        if(previewImg.length<=1){
            $('.gallery-big-right, .copy-gallery-big-left').css({display:'none'});
            $('.copy-gallery-big-left').css({display:'block'});
        }
        $('.gallery-show-min span:first-child').addClass('gallery-active-pic');
        $('.gallery-show-min span:last-child').css({marginRight:0});
        $('.gallery-show-min').append('<div class="border-gallery"></div>');


        for (var px = 0; px< bigImg.length; px++) {
            var bigImgLink = $(bigImg[px]).attr('data-link');
            $('.gallery-show-big').append('<tr><td><img data-link="' + bigImgLink + '"/></td></tr>');
        }
        var bigImgFirstShow = $('.gallery-show-big tr:first-child').find('img');
        var newBigImgFirstShow=bigImgFirstShow.attr('data-link');
        bigImgFirstShow.attr('src',newBigImgFirstShow);

        var newHeight = procent;
        var windH = $(window).height();
        var gallery = $('.gallery-page');
        if (windH<755){
            $('.gallery-show-big-wrp, .gallery-show-big, .gallery-show-big tr td').css({height:(windH/newHeight)+'px'});
            $('.gallery-show-big img').css({height:(windH/newHeight)+'px'});
        }

        sizeMinBlock();
        function sizeMinBlock(){
            var sizeimmin = $('.gallery-show-min span').length;
            $('.gallery-show-min').css({width:(newPrevScrl*sizeimmin)-previewImgMargin});
        }

        var previewImgMin = $('.gallery-show-min span');
        $(previewImgMin).removeClass('gallery-active-pic');
        $(previewImgMin[minClickIndex]).addClass('gallery-active-pic');

        $('.border-gallery').css({left:newPrevScrl*minClickIndex});

        function test(){
            var indexBigImg = $('.gallery-show-big tr');
            $('.gallery-show-big tr').css({display:'none'});

            $(indexBigImg[minClickIndex]).fadeIn(100);
            var bigImgShow = $(indexBigImg[minClickIndex]).find('img');
            var newBigImgShow=bigImgShow.attr('data-link');
            bigImgShow.attr('src',newBigImgShow);
        }
        test()

        if(minClickIndex==0) {
            $('.gallery-big-left').css({display:'none'})
        }
        else {$('.gallery-big-left').css({display:'block'})}
    }

    function clickSmallImg(){
        var img2 = $('.gallery-show-min span');

        var allMinImgLenght = $('.gallery-show-min span').length;

        if(allMinImgLenght<=minPicLenght){
            $('.gallery-min-left, .gallery-min-right, .copy-gallery-min-left, .copy-gallery-min-right').css({display:'none'})
        }
        else {
            $('.gallery-min-right, .copy-gallery-min-left, .copy-gallery-min-right').css({display:'block'})
            $('.gallery-min-left').css({display:'none'})
        }

        $('.gallery-show-min span').on('click', function(){
            var img3 = img2;
            var wrappOffset = $('.gallery-show-min').offset().left;
            if($('.gallery-show-min span').length<3){
                img3 = ($(img3[1]).offset().left)-wrappOffset;
            } else {
                img3 = ($(img3[2]).offset().left)-wrappOffset;
            }


            $('.gallery-show-min span').removeClass('gallery-active-pic');
            $(this).addClass('gallery-active-pic');

            var offsetParent = ($('.gallery-active-pic').offset().left)-wrappOffset;
            var smallImgIndex = $('.gallery-active-pic').index();
            //console.log('---------/'+offsetParent)

            function test(){
                var indexBigImg = $('.gallery-show-big tr');
                $('.gallery-show-big tr').css({display:'none'});

                $(indexBigImg[smallImgIndex]).fadeIn(100);
                var bigImgShow = $(indexBigImg[smallImgIndex]).find('img');
                var newBigImgShow=bigImgShow.attr('data-link');
                bigImgShow.attr('src',newBigImgShow);
            }

            var lenghtLeft = allMinImgLenght-(smallImgIndex+1);
            //console.log(lenghtLeft)

            if(smallImgIndex==0) {
                console.log('this')
                $('.gallery-big-right').css({display:'block'})
            }
            if(smallImgIndex<=3-1) {
                test();
                $('.border-gallery').css({left:offsetParent});
                $('.gallery-show-min span').removeClass('act-list');
                $('.gallery-show-min span:first-child').addClass('act-list')
                $('.gallery-min-left').css({display:'none'})
            }
            if(smallImgIndex==3-1){
                test();
                $('.gallery-show-min').stop().animate({marginLeft:-(offsetParent-img3)},timeAnimate)
                $('.gallery-show-min span').removeClass('act-list');
                $('.gallery-show-min span:first-child').addClass('act-list')
            }

            if(smallImgIndex<=minPicLenght-1) {
                test();
                $('.border-gallery').css({left:offsetParent});
                if(smallImgIndex>3) {
                    $('.gallery-show-min').stop().animate({marginLeft:-(offsetParent-img3)},timeAnimate)
                }
            }

            if(smallImgIndex>=minPicLenght-1) {
                $('.gallery-min-left').css({display:'block'});
                if(allMinImgLenght==minPicLenght){
                    if(smallImgIndex==minPicLenght-1){
                        $('.border-gallery').css({left:offsetParent});
                        $('.gallery-show-min').stop().animate({marginLeft:0},timeAnimate)
                    }
                }
                if(lenghtLeft==0) {
                    test();
                    $('.border-gallery').css({left:offsetParent});

                    $('.gallery-show-min span').removeClass('act-list');
                    $(this).prev().prev().prev().prev().addClass('act-list')
                    setTimeout(function(){
                        $('.gallery-min-right').css({display:'none'})
                    },timeAnimate)
                }
                if( lenghtLeft==1) {
                    test();
                    $('.gallery-show-min').stop().animate({marginLeft:-((offsetParent-img3)-newPrevScrl)},timeAnimate, function(){
                        $('.gallery-min-right').css({display:'none'})

                    })
                    $('.border-gallery').css({left:offsetParent});

                    $('.gallery-show-min span').removeClass('act-list');
                    $(this).prev().prev().prev().addClass('act-list')
                }
                if (lenghtLeft==2) {
                    test();
                    $('.border-gallery').css({left:offsetParent});
                    $('.gallery-show-min').stop().animate({marginLeft:-(offsetParent-img3)},timeAnimate, function(){$('.gallery-min-right').css({display:'none'})})

                    $('.gallery-show-min span').removeClass('act-list');
                    $(this).prev().prev().addClass('act-list')
                }

                if (lenghtLeft>2) {

                    test();
                    $('.border-gallery').css({left:offsetParent});
                    $('.gallery-show-min').stop().animate({marginLeft:-(offsetParent-img3)},timeAnimate, function(){$('.gallery-min-right').css({display:'block'})})

                    $('.gallery-show-min span').removeClass('act-list');
                    $(this).prev().prev().addClass('act-list')
                }
                if(allMinImgLenght<=minPicLenght) {
                    $('.gallery-min-left').css({display:'none'})
                }
            }

            if(allMinImgLenght>minPicLenght && lenghtLeft>2) {
                $('.gallery-min-right').css({display:'block'})
            }

            if(smallImgIndex==4-1){
                $('.gallery-show-min span').removeClass('act-list');
                $(this).prev().prev().addClass('act-list')
                $('.gallery-show-min').stop().animate({marginLeft:-(offsetParent-img3)},timeAnimate)
                $(img2[1]).click(function(){
                    $('.gallery-show-min').stop().animate({marginLeft:0},timeAnimate);
                });
                if (allMinImgLenght<=minPicLenght) {
                    $('.gallery-show-min').stop().animate({marginLeft:0},timeAnimate);
                }
                else {
                    $('.gallery-min-left').attr('style', 'disply:"block"');
                }
            }
            if(smallImgIndex>=1 && smallImgIndex<allMinImgLenght-1){
                $('.gallery-big-left, .gallery-big-right').css({display:'block'})

            }

            if(smallImgIndex==allMinImgLenght-1) {
                $('.gallery-big-right').css({display:'none'})
            }

            if(smallImgIndex==0) {
                $('.gallery-big-left').css({display:'none'})
            }
            //console.log(smallImgIndex + " smallImgIndex  / -" + allMinImgLenght)
        });
    }

    function arrowNaviRight(){
        var minList = $('.gallery-show-min span');
        $('.gallery-show-min span').removeClass('act-list');
        $('.gallery-show-min span:first-child').addClass('act-list');

        $('.gallery-min-right').on('click', function(){
            var actList = $('.act-list');
            var actListIndex = actList.index()+1;
            var newAllMinImgLenght = $('.gallery-show-min span').length;

            var howLeft = newAllMinImgLenght-actListIndex;
            $(this).css('display','none');
            if(howLeft>=(minPicLenght*2)-1) {
                $('.gallery-show-min span').removeClass('act-list');
                $(minList[actListIndex+(minPicLenght-1)]).addClass('act-list');
                $('.gallery-show-min').stop().animate({marginLeft:-((actListIndex+(minPicLenght-1))*newPrevScrl)},timeAnimate, function(){
                    $('.gallery-min-right').css('display','block')
                    if (howLeft<(minPicLenght*2)) {
                        $('.gallery-min-right').css('display','none')
                    }
                });
            }
            if (howLeft<(minPicLenght*2)-1) {
                var newActlistIndex = newAllMinImgLenght-(actListIndex+4);
                newActlistIndex = (actListIndex+newActlistIndex)-1;
                $('.gallery-show-min span').removeClass('act-list');
                $(minList[newActlistIndex]).addClass('act-list');
                $('.gallery-show-min').stop().animate({marginLeft:-((newActlistIndex)*newPrevScrl)},timeAnimate, function(){
                    //$('.copy-gallery-min-right').css('display','none')
                });
            }

            if(actListIndex>0) {
                setTimeout(function(){
                    $('.gallery-min-left').css({display:'block'})
                },timeAnimate)
            }
        })
    }

    function arrowNaviLeft(){
        var minList = $('.gallery-show-min span');
        $('.gallery-show-min span').removeClass('act-list');
        $('.gallery-show-min span:first-child').addClass('act-list');

        $('.gallery-min-left').on('click', function(){
            var actList = $('.act-list');
            var actListIndex = actList.index()+1;
            var howLeft = actListIndex-1;
            if (howLeft>0) {
                $(this).css('display','none');
                if(howLeft<=minPicLenght-1) {
                    $('.gallery-show-min span').removeClass('act-list');
                    $('.gallery-show-min span:first-child').addClass('act-list');
                    $('.gallery-show-min').stop().animate({marginLeft:0},timeAnimate, function(){
                        if(actListIndex<=minPicLenght+1) {
                            //$('.copy-gallery-min-right').attr('style', '')
                            $('.gallery-min-right').css({display:'block'})
                        } else {
                            $('.gallery-min-left').css('display','block')}
                    });
                }
                if (howLeft>minPicLenght-1) {
                    $('.gallery-show-min span').removeClass('act-list');
                    $(minList[actListIndex-(minPicLenght+1)]).addClass('act-list');
                    $('.gallery-show-min').stop().animate({marginLeft:-((actListIndex-(minPicLenght+1))*newPrevScrl)},timeAnimate, function(){
                        if(actListIndex<=minPicLenght+1) {
                            //$('.copy-gallery-min-left').css({display:'none'})
                        } else {
                            $('.gallery-min-right').css('display','block')
                            $('.gallery-min-left').css('display','block')}
                    });
                }
            }
        });
    }

    function bigClickRight() {
        var allMinImgLenght = $('.gallery-show-min span').length;
        var img2 = $('.gallery-show-min span');
        var img3 = newPrevScrl*3;
        $('.gallery-big-right').on('click', function(){
            var oneClick = $('.gallery-active-pic');
            var oneClickIndex = oneClick.index()+1;
            var smallImgIndex = oneClickIndex;
            function test(){
                var indexBigImg = $('.gallery-show-big tr');
                $('.gallery-show-big tr').css({display:'none'});
                $(indexBigImg[smallImgIndex]).fadeIn(100);
                var bigImgShow = $(indexBigImg[smallImgIndex]).find('img');
                var newBigImgShow=bigImgShow.attr('data-link');
                bigImgShow.attr('src',newBigImgShow);
            }
            if(oneClickIndex<=3-1){
                $('.gallery-show-min span').removeClass('gallery-active-pic act-list');
                $(img2[oneClickIndex]).addClass('gallery-active-pic');
                $('.gallery-show-min span:first-child').addClass('act-list')
                $('.border-gallery').css({left:(newPrevScrl*oneClickIndex)});
                test();
                $('.gallery-show-min').stop().animate({marginLeft:0},timeAnimate)
            }
            if (allMinImgLenght-oneClickIndex>0 && oneClickIndex>3-1){
                $('.gallery-show-min span').removeClass('gallery-active-pic act-list');
                $(img2[oneClickIndex]).addClass('gallery-active-pic');
                $(img2[oneClickIndex]).prev().prev().addClass('act-list');
                test();
                $('.border-gallery').css({left:(newPrevScrl*oneClickIndex)});
                if(allMinImgLenght-oneClickIndex>2) {
                    $('.gallery-show-min').stop().animate({marginLeft:-((oneClickIndex*newPrevScrl)-img3+newPrevScrl)},timeAnimate)
                }
                if(oneClickIndex>=minPicLenght-2) {
                    if(oneClickIndex>allMinImgLenght) {
                        setTimeout(function(){
                            $('.gallery-min-left').css({display:'block'})
                        },timeAnimate)
                    }
                }
                if(oneClickIndex>=allMinImgLenght-3) {
                    $('.gallery-min-right').css({display:'none'})
                }
                if(oneClickIndex>=3 && oneClickIndex<=allMinImgLenght-4){
                    $('.gallery-min-right').css({display:'block'})
                }
            }
            if(oneClickIndex>=1) {
                $('.gallery-big-left').css({display:'block'})
                if(allMinImgLenght>minPicLenght){
                    if(oneClickIndex>3-1){
                        $('.gallery-min-left').css({display:'block'})
                    }
                }
            }
            if(oneClickIndex+1>=allMinImgLenght) {
                $('.gallery-big-right').css({display:'none'})
            }
        })
    }
    function bigClickLeft() {
        var allMinImgLenght = $('.gallery-show-min span').length;
        var img2 = $('.gallery-show-min span');
        var img3 = newPrevScrl*3;
        $('.gallery-big-left').on('click', function(){
            var oneClick = $('.gallery-active-pic');
            var oneClickIndex = oneClick.index()-1;
            var smallImgIndex = oneClickIndex;
            function test(){
                var indexBigImg = $('.gallery-show-big tr');
                $('.gallery-show-big tr').css({display:'none'});
                $(indexBigImg[smallImgIndex]).fadeIn(100);
                var bigImgShow = $(indexBigImg[smallImgIndex]).find('img');
                var newBigImgShow=bigImgShow.attr('data-link');
                bigImgShow.attr('src',newBigImgShow);
            }
            if(oneClickIndex<=3-1){
                if(oneClickIndex>0){
                    $('.gallery-show-min span').removeClass('gallery-active-pic act-list');
                    $(img2[oneClickIndex]).addClass('gallery-active-pic');
                    $('.gallery-show-min span:first-child').addClass('act-list')
                    $('.border-gallery').css({left:(newPrevScrl*oneClickIndex)});
                    test();
                    $('.gallery-show-min').stop().animate({marginLeft:0},timeAnimate)
                }
                if(oneClickIndex==0) {
                    $('.gallery-show-min span').removeClass('gallery-active-pic act-list');
                    $(img2[0]).addClass('gallery-active-pic');
                    $('.gallery-show-min span:first-child').addClass('act-list')
                    $('.border-gallery').css({left:(newPrevScrl*oneClickIndex)});
                    test()
                }
            }
            if(oneClickIndex<3){
                setTimeout(function(){
                    $('.gallery-min-left').css({display:'none'})
                },timeAnimate)
            }
            if (allMinImgLenght-oneClickIndex>0 && oneClickIndex>3-1){
                $('.gallery-show-min span').removeClass('gallery-active-pic act-list');
                $(img2[oneClickIndex]).addClass('gallery-active-pic');
                $(img2[oneClickIndex]).prev().prev().addClass('act-list');
                test();
                $('.border-gallery').css({left:(newPrevScrl*oneClickIndex)});
                if(allMinImgLenght-oneClickIndex>2) {
                    $('.gallery-show-min').stop().animate({marginLeft:-((oneClickIndex*newPrevScrl)-img3+newPrevScrl)},timeAnimate)
                }

                if(oneClickIndex>=3 && oneClickIndex<=allMinImgLenght-4){
                    $('.gallery-min-right').css({display:'block'})
                }
            }
            if(oneClickIndex<1) {
                $('.gallery-big-left').css({display:'none'})
            }
            if(oneClickIndex<=allMinImgLenght-2) {
                $('.gallery-big-right').css({display:'block'})
            }

        })
    }
    var newHeight = procent;
    $(window).resize(function(){
        var windowHeight = ($(window).height());
        if (windowHeight<755){
            $('.gallery-show-big-wrp, .gallery-show-big, .gallery-show-big tr td').css({height:(windowHeight/newHeight)+'px'})
            $('.gallery-show-big img').css({height:(windowHeight/newHeight)+'px'})
            gallery.css({height:((windowHeight/newHeight)+94)+'px', marginTop:-(((windowHeight/newHeight)+94)/2)+'px'})
        }
        else {
            $('.gallery-show-big-wrp, .gallery-show-big, .gallery-show-big tr td, .gallery-show-big img').css({height:560})
            gallery.css({height:710, marginTop:-(710/2)+'px'})
        }
    })
});