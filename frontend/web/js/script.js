$(document)
    .ready(
        function() {
            var basket = new ArtboxBasket();
            footer();
            socialBlock();
            footerHover();
            headerPhones();
            firstMenuHover();
            openMobMenu();
            closeMobMenu();
            callMobile();
            menuCatalog();
            addOverlayMenu();
            citySelectHomeAndFoterAndShops();
            sizeTable();
            descriptionCard();
            formComments();
            help();
            // loadMap();
            hotLine();
            informPay();
            registerModalText();
            login();
            seoText();
            linkWhereBuy();
            activateTab();
            scrollArrow();
            btnScroll();

            // Otp script
            $('.partner').fancybox();

            function scrollArrow() {
                $('#scrollupbtn').click(function () {
                    $('body, html').animate({scrollTop:0}, 500);
                })
            }

            function activateTab()
            {
                var hash = location.hash;
                if(hash.length > 1) {
                    console.log(hash);
                    $(hash+"[data-tab]").trigger('click');
                }
            }

            function footer() {
                footerBottom();
                resizeFooterBottom();

                function footerBottom() {
                    var heightHeader = $('.section-box-header')
                        .height()
                    var heightFooter = $('.section-box-footer')
                        .height()
                    var windowHeight = $(window)
                        .height()
                    $('.section-box-content')
                        .css({minHeight: windowHeight - heightHeader - heightFooter})
                }

                function resizeFooterBottom() {
                    $(window)
                        .resize(
                            function() {
                                footerBottom();
                            }
                        )
                }

            }

            function mobOverlayAdd() {
                $('body')
                    .append('<div class="mob-overlay"></div>')
            }

            function mobOverlayRemove() {
                $('.mob-overlay')
                    .remove()
            }

            function socialBlock() {
                $('ul.blog-social-ico li')
                    .click(
                        function() {
                            $('ul.blog-social-ico li')
                                .removeClass('active')
                            $(this)
                                .addClass('active')
                            var socialIconIndex = $(this)
                                                      .index() + 1;
                            if (socialIconIndex == 1) {
                                $('.blog-social-navi-arrow')
                                    .css({marginLeft: 9});
                            } else if (socialIconIndex == 2) {
                                $('.blog-social-navi-arrow')
                                    .css({marginLeft: 53});
                            }

                            var socialHide = $('.blog-social-hide li');
                            $('.blog-social-hide li')
                                .css({display: 'none'});
                            $(socialHide[ socialIconIndex - 1 ])
                                .toggle(200);
                        }
                    )
            }

            function footerHover() {
                $('.menu-list-second-footer li')
                    .hover(
                        function() {
                            $('.menu-list-second-footer li')
                                .removeClass('active')
                            $(this)
                                .addClass('active')
                        }, function() {
                            $('.menu-list-second-footer li')
                                .removeClass('active')
                        }
                    )
            }

            function headerPhones() {
                function removeHiddeOperatorPhones() {
                    $('.hidden-phones-h_list')
                        .removeClass('active')
                }

                $('.phones-h_list')
                    .click(
                        function() {
                            $('.hidden-phones-h_list')
                                .addClass('active')

                        }
                    )

                $('.hidden-phones-h_list li')
                    .click(
                        function() {
                            var imgPgone = $(this)
                                .find('div')
                                .clone()
                            var txtPhone = $(this)
                                .find('p')
                                .html()
                            $('.phones-h-num')
                                .empty()
                            $('.phones-h-num')
                                .html(txtPhone)
                            removeHiddeOperatorPhones()
                            $('.phones-h_list div:first-child')
                                .remove()
                            $('.phones-h_list div')
                                .before(imgPgone)
                            $('.hidden-phones-h_list li')
                                .removeClass('hidden_')
                            $(this)
                                .addClass('hidden_')
                        }
                    )
                $('.phones-header-wr')
                    .hover(
                        function() {

                        }, function() {
                            removeHiddeOperatorPhones()
                        }
                    )
                $('.callback_h')
                    .click(
                        function() {
                            removeHiddeOperatorPhones()
                        }
                    )

            }

            function firstMenuHover() {
                $('.first-menu-wr ul li a')
                    .hover(
                        function() {
                            if ($(this)
                                    .find('span').length > 0) {
                                $(this)
                                    .find('span')
                                    .addClass('active')
                            } else {
                                $(this)
                                    .addClass('active')
                            }
                        }, function() {
                            $(this)
                                .find('span')
                                .removeClass('active')
                            $(this)
                                .removeClass('active')
                        }
                    )
            }

            function openMobMenu() {
                $('.menu_mob')
                    .click(
                        function() {
                            mobOverlayAdd()
                            $('body')
                                .addClass('off-scroll')
                            $('#menu-mob-hidden')
                                .addClass('open')
                            setTimeout(
                                function() {
                                    $('#menu-mob-hidden')
                                        .addClass('visible')
                                }, 10
                            )
                        }
                    )
            }

            function closeCallMobileHide() {
                $('.call-mobile-wr')
                    .removeClass('visible')
                setTimeout(
                    function() {
                        $('.call-mobile-wr')
                            .removeClass('open')
                    }, 200
                )
            }

            // function callMobile() {
            //     $('.call-button-mobile')
            //         .click(
            //             function() {
            //                 if ($('.call-button-mobile')
            //                         .hasClass('close')) {
            //                     mobOverlayRemove()
            //                     closeCallMobileHide()
            //                     $('.call-button-mobile')
            //                         .removeClass('close')
            //                     $('.call-mobile-wr')
            //                         .removeClass('open')
            //                     $('body')
            //                         .removeClass('off-scroll')
            //                     $('.call-button-mobile.close')
            //                         .click(
            //                             function() {
            //
            //                             }
            //                         )
            //                 } else {
            //                     // $('body')
            //                     //     .addClass('off-scroll')
            //                     mobOverlayAdd()
            //                     $(this)
            //                         .addClass('close')
            //                     $('.call-mobile-wr')
            //                         .addClass('visible')
            //                     setTimeout(
            //                         function() {
            //                             $('.call-mobile-wr')
            //                                 .addClass('open')
            //                         }, 20
            //                     )
            //                 }
            //             }
            //         )
            //
            // }

            function callMobile() {
                $('.mobile_call_header img').click(function() {
                  
                        $('.call-button-mobile').addClass('close')
                        mobOverlayAdd()

                        $('.call-mobile-wr').addClass('visible')
                        setTimeout(
                            function() {
                                $('.call-mobile-wr').addClass('open')
                            }, 20
                        )

                 })

                $('.call-button-mobile').click(function () {
                    mobOverlayRemove()
                    closeCallMobileHide()
                    $('.call-button-mobile')
                        .removeClass('close')
                    $('.call-mobile-wr')
                        .removeClass('open')
                    $('body')
                        .removeClass('off-scroll')
                })

            }

            function closeMobMenu() {
                $('body')
                    .on(
                        'click', '.mob-overlay', function() {
                            mobOverlayRemove()
                            if ($('#menu-mob-hidden')
                                    .hasClass('visible')) {
                                $('#menu-mob-hidden')
                                    .removeClass('visible')
                                setTimeout(
                                    function() {
                                        $('#menu-mob-hidden')
                                            .removeClass('open')
                                    }, 200
                                )
                                $('body')
                                    .removeClass('off-scroll')
                            }
                            if ($('.call-button-mobile')
                                    .hasClass('close')) {
                                $('.call-button-mobile')
                                    .removeClass('close')
                                closeCallMobileHide()
                            }

                        }
                    )
                $('.close-menu-mob .close_mob')
                    .click(
                        function() {
                            mobOverlayRemove()
                            $('#menu-mob-hidden')
                                .removeClass('visible')
                            setTimeout(
                                function() {
                                    $('#menu-mob-hidden')
                                        .removeClass('open')
                                }, 200
                            )
                            $('body')
                                .removeClass('off-scroll')
                        }
                    )
            }

            function addOverlayMenu() {
                $('body')
                    .append('<div id="overlay-menu"></div>')
            }

            function menuCatalog() {
                var buttonDestktop = '.catalog_';
                var buttonTablet = '.catalog_mob.tablet';
                var buttonMobile = '.catalog_mob.mob';
                var menuCatalog = $('.catalog-menu-wr');
                $('' + buttonDestktop + ',' + buttonTablet + '')
                    .click(
                        function(e) {
                            e.preventDefault()
                            if ($(this)
                                    .hasClass('homepage_notclick')) {
                                var posCatalogMenu = $('.catalog-menu')
                                    .offset().top
                                $('body')
                                    .animate({scrollTop: posCatalogMenu - 40}, 500);

                            } else {
                                menuCatalog.toggleClass('opens')

                                if (menuCatalog.hasClass('opens')) {
                                    $('' + buttonDestktop + '')
                                        .addClass('open')
                                    $('' + buttonTablet + '')
                                        .addClass('active')
                                    $('.catalog-menu li')
                                        .removeClass('activ-li')

                                } else {
                                    $('' + buttonDestktop + '')
                                        .removeClass('open')
                                    $('' + buttonTablet + '')
                                        .removeClass('active')
                                }
                            }

                        }
                    )

                $(buttonMobile)
                    .click(
                        function() {
                            mobOverlayAdd()
                            $('body')
                                .addClass('off-scroll')

                            menuCatalog.addClass('mobile_catalog_menu')

                            setTimeout(
                                function() {
                                    menuCatalog.addClass('op_mb_ct')
                                }, 10
                            )

                            if (menuCatalog.hasClass('mobile_catalog_menu')) {

                                function hideCatMob() {
                                    menuCatalog.removeClass('op_mb_ct')
                                    setTimeout(
                                        function() {
                                            menuCatalog.removeClass('mobile_catalog_menu')
                                            $('.catalog-menu li')
                                                .removeClass('act_mb')
                                            mobOverlayRemove()
                                            $('body')
                                                .removeClass('off-scroll')
                                        }, 200
                                    )
                                }

                                $('.mob-catalog-close')
                                    .click(
                                        function() {
                                            hideCatMob()
                                        }
                                    )
                                $('.mob-overlay')
                                    .click(
                                        function() {

                                            hideCatMob()

                                        }
                                    )
                                $('.catalog-menu li').click(function () {
                                    $('.catalog-menu li').removeClass('act_mb')
                                    if($(this).hasClass('act_mb')){
                                    } else {
                                        $(this).addClass('act_mb')
                                    }


                                })
                            } else {

                            }

                        }
                    )

                $(window)
                    .resize(
                        function() {
                            if (($(window)
                                    .width()) > 768) {
                                menuCatalog.removeClass('mobile_catalog_menu')
                            } else {
                                // menuCatalog.addClass('mobile_catalog_menu');  //проверить
                            }
                        }
                    )

                openSecondMenu();
                function openSecondMenu() {
                    var timeout;
                    $('.catalog-menu li')
                        .hover(
                            function() {
                                if ($('.catalog-menu-wr')
                                        .hasClass('mobile_catalog_menu')) {

                                } else {
                                    var this_ = $(this)

                                    if ($(this)
                                            .parent()
                                            .hasClass('catalog-menu')) {
                                        timeout = setTimeout(
                                            function() {

                                                $('#overlay-menu')
                                                    .addClass('show')
                                                $('.catalog-menu li')
                                                    .removeClass('activ-li')
                                                this_.addClass('activ-li')
                                                $('.catalog-menu')
                                                    .addClass('openSecondLi')
                                            }, 170
                                        )
                                    }
                                }

                            }, function() {

                                clearTimeout(timeout)
                            }
                        )
                }

                $('.mob_lang a')
                    .click(
                        function() {
                            $('.mob_lang a')
                                .removeClass('active');
                            $(this)
                                .addClass('active');
                        }
                    )

                $('.lang_header a')
                    .click(
                        function() {
                            $('.lang_header a')
                                .removeClass('active');
                            $(this)
                                .addClass('active');
                        }
                    )

            }

            hideMenuCatalog()
            function hideMenuCatalog() {

                function hideClickHoverMenu() {
                    if ($('.catalog-menu-wr')
                            .hasClass('homepage_notclose')) {
                        $('.catalog_')
                            .removeClass('open')
                        $('.catalog_mob.tablet')
                            .removeClass('active')
                        $('#overlay-menu')
                            .removeClass('show')
                        $('.catalog-menu li')
                            .removeClass('activ-li')
                        $('.catalog-menu')
                            .removeClass('openSecondLi')
                    } else {
                        $('.catalog-menu-wr')
                            .removeClass('opens')
                        $('.catalog_')
                            .removeClass('open')
                        $('.catalog_mob.tablet')
                            .removeClass('active')
                        $('#overlay-menu')
                            .removeClass('show')
                        $('.catalog-menu li')
                            .removeClass('activ-li')
                        $('.catalog-menu')
                            .removeClass('openSecondLi')
                    }
                }

                $('#overlay-menu')
                    .on(
                        'click', function() {
                            hideClickHoverMenu()
                        }
                    )

                var timeoutOverlay
                $('#overlay-menu')
                    .hover(
                        function() {

                            timeoutOverlay = setTimeout(
                                function() {
                                    hideClickHoverMenu()
                                }, 300
                            )
                        }, function() {
                            clearTimeout(timeoutOverlay)
                        }
                    )

            }

            sidebarChecked()
            clearFilter()
            filterMobile()
            closeFilterMobile()
            sortList()
            hoverCatalog()
            cityDeliveryCard()
            moreVariants()

            function sidebarChecked() {
                $('.form-register input.custom-check + label a, .already-registered input.custom-check + label a, .form-register input.custom-radio + label a, .already-registered input.custom-radio + label a')
                    .click(
                        function(e) {
                            e.preventDefault()
                        }
                    )
                $('.sidebar input.custom-check + label a, .form-register input.custom-check + label a, .already-registered input.custom-check + label a')
                    .click(
                        function() {
                            var checkSidebar = $(this)
                                .parent()
                                .parent()
                                .find('.custom-check')
                            if (checkSidebar.is(':checked')) {
                                checkSidebar.prop("checked", false);
                            } else {
                                checkSidebar.prop("checked", true);
                            }
                        }
                    );
                $('a, span', '.form-register input.custom-radio + label, .already-registered input.custom-radio + label')
                    .click(
                        function() {
                            var checkSidebar = $(this)
                                .parent()
                                .parent()
                                .find('.custom-radio');
                            if ($(checkSidebar)
                                    .hasClass('root-radio')) {
                                $('input.parent_radio')
                                    .prop('checked', false);
                            }
                            if (!checkSidebar.is(':checked')) {
                                checkSidebar.prop("checked", true);
                                checkSidebar.trigger('change');
                            }
                        }
                    );
                $('a, span', '.form-register input.custom-radio2 + label, .already-registered input.custom-radio2 + label')
                    .click(
                        function(e) {
                            e.preventDefault()
                            var checkSidebar = $(this)
                                .parent()
                                .parent()
                                .find('.custom-radio2')
                            if (!checkSidebar.is(':checked')) {
                                checkSidebar.prop("checked", true);
                            }
                        }
                    );

                $('.hidden_txt label')
                    .click(
                        function() {
                            $('.hidden_txt')
                                .removeClass('active')
                            $(this)
                                .parents('.hidden_txt')
                                .addClass('active')
                            var parrents = $(this)
                                .parents('.hidden_form_radio').length
                            if (parrents > 0) {
                                $('.hidden_form_txt2')
                                    .removeClass('active')
                                $(this)
                                    .parent()
                                    .find('.hidden_form_txt2')
                                    .addClass('active')
                            }
                        }
                    )
            }

            function clearFilter() {
                $('.resetFilters span')
                    .click(
                        function() {
                            var clearUrl = window.location.pathname;
                            window.location = clearUrl;
                        }
                    )
            }

            function filterMobile() {
                $(document).on('click', '.filter_mobile_', function(e) {
                            console.log('test-sidebar2')
                            e.preventDefault();
                            $(this)
                                .css({opacity: 0})
                            $('.sidebar-transform')
                                .addClass('visible_')
                            $('.sidebar-transform')
                                .animate(
                                    {
                                        opacity: 1,
                                        left: 0
                                    }, 300
                                )

                            $('body')
                                .addClass('hidden_scroll-y')
                        }
                    )
            }

            function closeFilterMobile() {
                $(document).on('click', '.close_filters', function() {
                            $('.sidebar-transform')
                                .animate(
                                    {
                                        opacity: 0,
                                        left: '-100%'
                                    }, 300
                                )
                            $('.filter_mobile_')
                                .css({opacity: 1})

                            setTimeout(
                                function() {
                                    $('.sidebar-transform')
                                        .removeClass('visible_')
                                }, 290
                            )

                            $('body').removeClass('hidden_scroll-y')
                        }
                    )

            }

            function sortList() {
                $('.sort-cat>a')
                    .click(
                        function(e) {
                            e.preventDefault()
                            $('.sort-cat')
                                .toggleClass('active')
                        }
                    )

                $('.sort-cat ul li a')
                    .click(
                        function() {
                            $('.sort-cat ul li')
                                .css({display: 'block'})
                            $(this)
                                .parent()
                                .css({display: 'none'})
                            $('.sort-cat>a')
                                .html(
                                    $(this)
                                        .text()
                                )
                            $('.sort-cat')
                                .removeClass('active')
                        }
                    )

                var timeoutSort
                $('.sort-cat')
                    .hover(
                        function() {
                            clearTimeout(timeoutSort)
                        }, function() {
                            timeoutSort = setTimeout(
                                function() {
                                    $('.sort-cat')
                                        .removeClass('active')
                                }, 320
                            )

                        }
                    )
            }

            function cityDeliveryCard() {
                $('.city-sel-deliv>a')
                    .click(
                        function(e) {
                            e.preventDefault()
                            $('.city-sel-deliv')
                                .toggleClass('active')
                        }
                    )

                $('.city-sel-deliv ul li a')
                    .click(
                        function() {
                            $('.city-sel-deliv ul li')
                                .css({display: 'block'})
                            $(this)
                                .parent()
                                .css({display: 'none'})
                            $('.city-sel-deliv>a')
                                .html(
                                    $(this)
                                        .text()
                                )
                            $('.city-sel-deliv')
                                .removeClass('active')
                        }
                    )

                var timeoutSort
                $('.city-sel-deliv')
                    .hover(
                        function() {
                            clearTimeout(timeoutSort)
                        }, function() {
                            timeoutSort = setTimeout(
                                function() {
                                    $('.city-sel-deliv')
                                        .removeClass('active')
                                }, 320
                            )

                        }
                    )
            }

            function hoverCatalog() {
                var timerBorder;
                var timerMove;
                $('.catalog-wr')
                    .hover(
                        function() {
                            var this_ = $(this)
                            var slider = this_.parents('.slider-wr')
                            var width = $(window)
                                .width()
                            if (slider.length > 0) {

                                // $(this).parents('.owl-stage').find('.owl-item.active:nth-child(4)')
                                var tst = $(this)
                                    .parents('.owl-stage')
                                    .find('.owl-item.active')

                                var countSliders = 0
                                if (width >= 751 && width < 1183) {
                                    countSliders = 2
                                } else if (width >= 1183) {
                                    countSliders = 3
                                }

                                if (($(tst[ countSliders ])
                                        .index()) == ($(this)
                                        .parents('.owl-item')
                                        .index())) {
                                    this_.addClass('hover hover_left')
                                } else {
                                    this_.addClass('hover')
                                }

                            } else {

                                var num = ($(this)
                                        .index()) + 1
                                if (num % 3) {
                                    this_.addClass('hover')
                                } else {
                                    this_.addClass('hover hover_left')
                                }

                            }

                            setTimeout(
                                function() {
                                    this_.find('.additional_wr')
                                         .addClass('startAnim')
                                }, 10
                            )
                            timerMove = setTimeout(
                                function() {
                                    this_.find('.addit_wr')
                                         .addClass('move')
                                }, 200
                            )

                            timerBorder = setTimeout(
                                function() {
                                    this_.addClass('hover-border')
                                }, 200
                            )

                            $(this).find('.artbox-lazy-event').trigger('lazy.artbox');

                        }, function() {
                            var this_ = $(this)
                            var slider = this_.parents('.slider-wr')
                            this_.find('.additional_wr')
                                 .removeClass('startAnim')
                            this_.removeClass('hover hover-border')
                            setTimeout(
                                function() {
                                    this_.removeClass('hover hover_left')
                                    this_.find('.addit_wr')
                                         .removeClass('move')
                                }, 220
                            )

                            clearTimeout(timerBorder)
                            clearTimeout(timerMove)

                        }
                    )
            }

            //bannerNew();

            function bannerNew() {

                var catalog = $('.catalog-wrapp-all')

                if (catalog.length > 0) {
                    var numberItems = $('.catalog-wr').length;
                    if (numberItems > 4) {
                        numberItems = 4
                    }
                    ;
                    var banner = $('.banners')
                        .html()
                    var bannerNum = $('.catalog-wrapp-all .catalog-wr:nth-child(' + numberItems + ')')
                    var bannerHeight = bannerNum.css('height')

                    var winWidth = ($(window)
                            .width()) + 17

                    var bannerWidth = bannerNum.css('width')
                    bannerWidth = bannerWidth.replace('px', '')
                    bannerWidth = +bannerWidth
                    if ($('html')
                            .hasClass('mobil') || $('html')
                            .hasClass('tablet')) {

                    } else {

                        // if(winWidth>767 && winWidth<992){
                        //     console.log(winWidth)
                        //     $('.catalog-wrapp-all .catalog-wr:nth-child(5)').css({marginLeft:bannerWidth})
                        // }

                    }

                    $(window)
                        .resize(
                            function() {
                                var winWidth = ($(window)
                                        .width()) + 17
                                var bannerWidth = bannerNum.css('width')
                                bannerWidth = bannerWidth.replace('px', '')
                                bannerWidth = +bannerWidth
                                if ($('html')
                                        .hasClass('mobil') || $('html')
                                        .hasClass('tablet')) {

                                } else {
                                    // if(winWidth>767 && winWidth<992){
                                    //     console.log(winWidth)
                                    //     $('.catalog-wrapp-all .catalog-wr:nth-child(5)').css({marginLeft:bannerWidth})
                                    // } else {
                                    //     $('.catalog-wrapp-all .catalog-wr:nth-child(5)').attr('style','')
                                    // }
                                }

                            }
                        )

                    addBanners()

                    function addBanners() {

                        bannerNum.after(banner)

                        $('.catalog-wrapp-all .banner_')
                            .css({height: bannerHeight})
                        $(window)
                            .resize(
                                function() {
                                    bannerHeight = bannerNum.css('height')
                                    $('.catalog-wrapp-all .banner_')
                                        .css({height: bannerHeight})
                                }
                            )
                    }
                }
            }

            function citySelectHomeAndFoterAndShops() {
                $('.title_city-sel span.addCity,.title_card span.addCity, .shops-title span.addCity')
                    .click(
                        function() {
                            $(this)
                                .toggleClass('active')
                            if ($(this)
                                    .hasClass('active')) {
                                $(this)
                                    .parents('.city-sel')
                                    .addClass('active')
                                $(this)
                                    .parents('.city-sel')
                                    .find('#hidden_shops')
                                    .removeClass('_off')
                            } else {
                                $(this)
                                    .parents('.city-sel')
                                    .find('#hidden_shops')
                                    .addClass('_off')
                                $(this)
                                    .parents('.city-sel')
                                    .removeClass('active')
                            }
                        }
                    )

                $('#hidden_shops li')
                    .click(
                        function() {
                            $('#hidden_shops li')
                                .removeClass('active')
                            $(this)
                                .addClass('active')

                            var cityName = $(this)
                                .find('span.s_')
                                .html()
                            $(this)
                                .parents('.city-sel')
                                .find('span.addCity')
                                .empty()
                                .html(cityName)

                            var footerCity = $(this)
                                .parents('.shops-title').length
                            if (footerCity <= 0) {
                                var cityPhones = $(this)
                                    .find('div.phones_content')
                                    .html()
                                $('#shops_phones, #shops_page')
                                    .animate(
                                        {opacity: 0}, 450, function() {
                                            $(this)
                                                .animate({opacity: 1}, 250)
                                                .empty()
                                                .html(cityPhones)
                                        }
                                    )

                                $(this)
                                    .parents('.city-sel')
                                    .find('span.addCity')
                                    .removeClass('active')
                                $(this)
                                    .parents('.city-sel')
                                    .removeClass('active')
                                $(this)
                                    .parents('.city-sel')
                                    .find('#hidden_shops')
                                    .addClass('_off')
                            } else {

                                var shops = $(this)
                                    .find('.phones_content')
                                    .html();

                                $('.footer_add')
                                    .empty()
                                    .append(shops)

                                $(this)
                                    .parents('.city-sel')
                                    .find('span.addCity')
                                    .removeClass('active')
                                $(this)
                                    .parents('.city-sel')
                                    .removeClass('active')
                                $(this)
                                    .parents('.city-sel')
                                    .find('#hidden_shops')
                                    .addClass('_off')
                            }

                        }
                    )

                var timeoutCitySel
                $('.city-sel')
                    .hover(
                        function() {
                            var this__ = $(this)
                            clearTimeout(timeoutCitySel)
                        }, function() {
                            var this__ = $(this)
                            timeoutCitySel = setTimeout(
                                function() {
                                    this__.find('span.addCity')
                                          .removeClass('active')
                                    this__.removeClass('active')
                                    this__.find('#hidden_shops')
                                          .addClass('_off')
                                }, 320
                            )

                        }
                    )
            }

            function moreVariants() {
                $('.more_card')
                    .click(
                        function() {
                            var this_height = ($(this)
                                .parents('.options_bl')
                                .parents('.style')
                                .height())
                            $(this)
                                .parents('.options_bl')
                                .parents('.style')
                                .css({height: this_height})
                            $('.options_bl')
                                .removeClass('open_bl')
                            $(this)
                                .parents('.options_bl')
                                .addClass('open_bl')
                        }
                    )

                $('.size_growth li a')
                    .click(
                        function(e) {
                            e.preventDefault();
                            $('.size_growth li a')
                                .removeClass('active');
                            $(this)
                                .addClass('active');
                            var this_index = $(this)
                                .parent()
                                .index()
                            var ul = $('.size_growth-list')
                            ul.removeClass('active')
                            $(ul[ this_index ])
                                .addClass('active')
                        }
                    )

                $('.colors-img ul li a')
                    .click(
                        function(e) {
                            // e.preventDefault()
                            $('.colors-img ul li a')
                                .removeClass('active')
                            $(this)
                                .addClass('active')

                            // сюда подгрузка картинки

                            $('.img-small-wr ul li')
                                .removeClass('active')
                            $('.img-small-wr ul li:first-child')
                                .addClass('active')
                            var dataBig = $('.img-small-wr ul li:first-child')
                                .find('img')
                                .attr('data-big-img')
                            $('a.gallery-box-min img')
                                .attr('src', dataBig)

                        }
                    )

                $('.size_growth-list li a')
                    .click(
                        function(e) {
                            // e.preventDefault()
                            $('.size_growth-list li')
                                .removeClass('active')
                            $(this)
                                .parent()
                                .addClass('active')
                        }
                    )

                $('.weather_list li a')
                    .click(
                        function(e) {
                            // e.preventDefault()
                            $('.weather_list li')
                                .removeClass('active')
                            $(this)
                                .parent()
                                .addClass('active')
                        }
                    )

                //закрытие
                $('.shadow_bl div ')
                    .click(
                        function() {
                            $(this)
                                .parents('.options_bl')
                                .removeClass('open_bl')
                            $(this)
                                .parents('.options_bl')
                                .parents('.style')
                                .css({height: 'auto'})
                        }
                    )

            }

            function sizeTable() {
                $('.size_table')
                    .click(
                        function(e) {
                            e.preventDefault();
                            var dataLink = $(this)
                                .attr('data-link')
                            $('body')
                                .append(
                                    '<div id="overlay-size-tb"></div><div style="top:' + $(window)
                                        .scrollTop() + 'px" id="size-img"><a href="#"><img  src="' + dataLink + '"><span></span></a></div>'
                                )
                        }
                    )
                $('body')
                    .on(
                        'click', '#size-img a', function(e) {
                            e.preventDefault()
                        }
                    )
                function closeSizeTb() {
                    $('#size-img')
                        .remove()
                    $('#overlay-size-tb')
                        .remove()
                }

                $('body')
                    .on(
                        'click', '#size-img a span', function(e) {
                            closeSizeTb()
                        }
                    )
                $('body')
                    .on(
                        'click', '#size-img', function(e) {
                            closeSizeTb()
                        }
                    )
                $('body')
                    .on(
                        'click', '#overlay-size-tb', function(e) {
                            closeSizeTb()
                        }
                    )

            }

            function descriptionCard() {
                $('.desk_name')
                    .click(
                        function(e) {
                            e.preventDefault()
                            $('ul.description_list li')
                                .removeClass('active')
                            $(this)
                                .parent()
                                .addClass('active')
                            var this_index = $(this)
                                .parent()
                                .index()
                            var blocks = $('.desk_list-wr')
                            blocks.removeClass('active')
                            $(blocks[ this_index ])
                                .addClass('active')
                        }
                    )

                $('.btn_mobil_show_desk')
                    .click(
                        function(e) {
                            e.preventDefault();
                            if (($(this)
                                    .parents('.desk_list-wr')).hasClass('active-mobile')) {
                                $(this)
                                    .parents('.desk_list-wr')
                                    .removeClass('active-mobile')
                            } else {
                                $('.desk_list-wr')
                                    .removeClass('active-mobile')
                                $(this)
                                    .parents('.desk_list-wr')
                                    .addClass('active-mobile')
                            }

                        }
                    )
            }

            function formComments() {
                $('.btn_scroll_to_comment')
                    .click(
                        function(e) {
                            e.preventDefault();
                            var commentScroll = $('.form-comm-wr')
                                                    .offset().top - 30;
                            $('body,html')
                                .animate(
                                    {scrollTop: commentScroll}, 500, function() {
                                        $('#commentmodel-username')
                                            .focus()
                                    }
                                )
                        }
                    )
            }

            function help() {
                $('.question_')
                    .click(
                        function(e) {
                            e.preventDefault();
                            $('.question-wr').removeClass('show')
                            var thisPos = $(this)
                                .offset().top;
                            $('body, html').animate({scrollTop: thisPos - 11}, 500)
                            $(this).parents('.question-wr').addClass('show')
                        }
                    )
            }

            // var w = screen.width,
            //     h = screen.height;
            //  alert(w + 'x' + h + '('+$(window).width()+'x'+$(window).height()+')')

            // alert($(window).width()+'x'+$(window).height())
            // function loadMap() {
            //     $('.title_shops #hidden_shops ul li')
            //         .click(
            //             function() {
            //                 $.post(
            //                     "maps/maps.php", function(data) {
            //                         $("#map_cloud")
            //                             .empty();
            //                         $("#map_cloud")
            //                             .append(data);
            //                         initialize();
            //                     }
            //                 );
            //             }
            //         )
            // }

            function hotLine() {
                $('.hot_line, .mob_hot_line')
                    .click(
                        function(e) {
                            e.preventDefault()

                            mobOverlayRemove()
                            $('#menu-mob-hidden')
                                .removeClass('visible')
                            setTimeout(
                                function() {
                                    $('#menu-mob-hidden')
                                        .removeClass('open')
                                }, 200
                            )
                            $('body')
                                .removeClass('off-scroll')

                            $('#overlay')
                                .fadeIn(
                                    400, function() {
                                        $('#hot_line')
                                            .css('display', 'block')
                                            .animate(
                                                {
                                                    opacity: 1,
                                                    top: '30'
                                                }, 200
                                            );
                                    }
                                );
                        }
                    )

            }

            function informPay() {
                $('.mob_inform_pay, .inform_pay')
                    .click(
                        function(e) {
                            e.preventDefault()
                            mobOverlayRemove()
                            $('#menu-mob-hidden')
                                .removeClass('visible')
                            setTimeout(
                                function() {
                                    $('#menu-mob-hidden')
                                        .removeClass('open')
                                }, 200
                            )
                            $('body')
                                .removeClass('off-scroll')

                            $('#overlay')
                                .fadeIn(
                                    400, function() {
                                        $('#inform')
                                            .css('display', 'block')
                                            .animate(
                                                {
                                                    opacity: 1,
                                                    top: '30'
                                                }, 200
                                            );
                                    }
                                );
                        }
                    )

            }

            function registerModalText() {
                $('.terms_of_use, .rules_of')
                    .click(
                        function(e) {
                            e.preventDefault()
                            var header = $('.header-full');

                            var status = header.css('display')
                            var statusTxt = 'block'
                            var headerHe = header.height()
                            var headerMob = $('.section-box-header .hidden.visible-mobile')
                                .height()
                            if (status == statusTxt) {
                                var window_ = (($(window)
                                        .scrollTop()) + 30) - headerHe
                            } else {
                                var window_ = (($(window)
                                        .scrollTop()) + 30) - headerMob
                            }

                            if ($(this)
                                    .hasClass('terms_of_use')) {
                                $('#overlay')
                                    .fadeIn(
                                        400, function() {
                                            $('#terms_of_use')
                                                .css('display', 'block')
                                                .animate(
                                                    {
                                                        opacity: 1,
                                                        top: window_
                                                    }, 200
                                                );
                                        }
                                    );
                            } else {
                                $('#overlay')
                                    .fadeIn(
                                        400, function() {
                                            $('#rules_of')
                                                .css('display', 'block')
                                                .animate(
                                                    {
                                                        opacity: 1,
                                                        top: window_
                                                    }, 200
                                                );
                                        }
                                    );
                            }

                        }
                    )
            }

            $(document)
                .on(
                    'click', '#modal_close, #overlay, #overlay_s, #modal_close-2', function(e) {
                        basket.hideBasket();
                        $('.forms_, .forms_reg')
                            .animate(
                                {
                                    opacity: 0,
                                    top: '0'
                                }, 200, function() {
                                    $(this)
                                        .css('display', 'none');
                                    $('#overlay, #overlay_s')
                                        .fadeOut(400);
                                }
                            );
                        $('#success_form')
                            .animate(
                                {
                                    opacity: 0,
                                    top: '0'
                                }, 200, function() {
                                    $(this)
                                        .css('display', 'none');
                                    $(this)
                                        .css({top: '50%'});
                                }
                            );
                    }
                );
            //после удачной отправки формы запускать success()
            // success()
            function success() {
                $('.forms_')
                    .animate(
                        {
                            opacity: 0,
                            top: '0'
                        }, 200, function() {
                            $(this)
                                .css('display', 'none');
                        }
                    );
                setTimeout(
                    function() {
                        $('#success_form')
                            .css('display', 'block')
                            .animate({opacity: 1}, 700);
                    }, 400
                )
            }

            fileVal();
            function fileVal() {
                $('input[type="file"]')
                    .change(
                        function() {
                            var fileVal = $(this)
                                .val();
                            if (fileVal.indexOf('C:\\fakepath\\') + 1) {
                                fileVal = fileVal.substr(12);
                            }
                            var exeLenght = fileVal.search(/\..+$/)
                            exeLenght = exeLenght - 3
                            var newExeTtx = fileVal.substr(exeLenght);
                            var newFileVal = fileVal.substring(8, 0)

                            if (fileVal.length > 15) {
                                $('.input-wr-file label')
                                    .text(newFileVal + '...' + newExeTtx)
                            } else {
                                $('.input-wr-file label')
                                    .text(fileVal)
                            }

                        }
                    );

            }

            clickSmallImg()
            function clickSmallImg() {
                $('.img-small-wr li')
                    .click(
                        function() {
                            $('.img-small-wr li')
                                .removeClass('active')
                            $(this)
                                .addClass('active')
                            var dataBig = $(this)
                                .find('img')
                                .attr('data-big-img')
                            $('a.gallery-box-min img')
                                .attr('src', dataBig)
                        }
                    )
            }

            $(document)
                .on('click', '.remove_ico', confirmRemove);
            $(document)
                .on('click', '.remove_confirm a', removeBasket);
            $(document)
                .on('click', '.quantity-wr span', changeBasket);
            $(document)
                .on('change', '.quantity-wr input', setBasket);
            $(document)
                .on('keypress', '.quantity-wr input', setControl);
            $(document)
                .on('click', 'a.btn_buy_cat', addBasket);
            $(document)
                .on('click', 'a.buy_card_credit', addBasketCredit);

            // Delivery to payment custom manipulation
            $(document)
                .on(
                    'change', '[name="OrderFrontend[delivery]"]', function(e) {
                        if ($(this)
                                .val() == 3) {
                            $('#payment-wrapper-8')
                                .removeClass('hidden');
                            $('#payment-wrapper-3')
                                .addClass('hidden');
                        } else {
                            $('#payment-wrapper-8')
                                .addClass('hidden');
                            $('#payment-wrapper-3')
                                .removeClass('hidden');
                        }
                        if ($('.field-orderfrontend-payment.hidden input:checked').length) {
                            $('.field-orderfrontend-payment:not(.hidden) a')
                                .first()
                                .trigger('click');
                        }
                    }
                );

            $(document)
                .on(
                    'change', '[name="OrderFrontend[payment]"]', function(e) {
                        $('.field-orderfrontend-payment .hint_block')
                            .addClass('hidden_form_txt');
                        $(this)
                            .parents('.field-orderfrontend-payment')
                            .find('.hint_block')
                            .removeClass('hidden_form_txt');
                    }
                );

            $(document).on('click', '.card_delivery_link', function(e) {
                $('#card_deliveries').trigger('click');
            });

            var checked_delvery = $('[name="OrderFrontend[delivery]"]:checked');
            if(checked_delvery.length && !checked_delvery.hasClass('root-radio')) {
                var parent = checked_delvery.parents('.check-box-form');
                parent.find('.parent_radio + label a').trigger('click');
                parent.find('.parent_radio').prop('checked', true);
            }

            $('#feedback-header').on('afterValidate', function(event, messages, errorAttributes) {
                if(!errorAttributes.length) {
                    $.post('/site/feedback', $(this).serialize()).always(function() {
                        location.reload();
                    });
                }
            });

            $('#basket-form').on('beforeValidate', function(event, attribute, messages) {
                var delivery_valid = ($('[name="OrderFrontend[delivery]"]:checked').length > 0);
                var payment_valid = ($('[name="OrderFrontend[payment]"]:checked').length > 0);
                if(!delivery_valid || !payment_valid) {
                    if(!delivery_valid) {
                        $('.delivery-wrapper').addClass('has-error');
                    }
                    if(!payment_valid) {
                        $('.payment-wrapper').addClass('has-error');
                    }
                    return false;
                } else {
                    if(delivery_valid && payment_valid) {
                        $('.delivery-wrapper, .payment-wrapper').removeClass('has-error');
                    } else if(delivery_valid) {
                        $('.delivery-wrapper').removeClass('has-error');
                    } else if(payment_valid) {
                        $('.payment-wrapper').removeClass('has-error');
                    }
                }
            });

            $('#feedback-header').on('submit', function(e) {
                e.preventDefault();
            });

            function confirmRemove(e) {
                e.preventDefault();
                $(this)
                    .parent()
                    .addClass('confirm');
            }

            function changeBasket(e) {
                var variant = $(this)
                    .parents('tr.variant_tr')
                    .data('variant');
                var input = $(this)
                    .parent()
                    .find('input');
                var oldVal = input.val();
                if ($(this)
                        .hasClass('minus')) {
                    if (oldVal > 1) {
                        basket.add(variant, -1);
                    }
                } else {
                    basket.add(variant, 1);
                }
            }

            function removeBasket(e) {
                e.preventDefault();
                if ($(this)
                        .hasClass('remove-yes')) {
                    var variant = $(this)
                        .parents('tr.variant_tr')
                        .data('variant');
                    //удаление ячейки "tr" в корзине
                    basket.remove(variant);
                    $(this)
                        .parents('.confirm')
                        .parent()
                        .remove()
                } else {
                    $(this)
                        .parents('.confirm')
                        .removeClass('confirm')
                }
            }

            function setBasket(e) {
                e.preventDefault();
                var variant = $(this)
                    .parents('tr.variant_tr')
                    .data('variant');
                var count = $(this)
                    .val();
                basket.set(variant, count);
            }

            function setControl(e) {
                if (e.which == 13) {
                    $(this)
                        .trigger('change');
                    return false;
                } else if (!(e.which == 8 || (e.which > 47 && e.which < 58))) {
                    return false;
                }
            }

            function showBasket() {
                var pos = ($(window)
                        .scrollTop()) + 30;
                $('#overlay')
                    .fadeIn(
                        400, function() {
                            $('.basket_modal')
                                .css('display', 'block')
                                .animate(
                                    {
                                        opacity: 1,
                                        top: pos
                                    }, 200
                                );
                        }
                    );
            }

            function addBasket(e) {
                e.preventDefault();
                var variant = $(this)
                    .data('variant');
                basket.add(variant, 1);
                showBasket();
            }

            function addBasketCredit(e) {
                var date = new Date();
                date.setDate(date.getDate() + 3);
                document.cookie = "isCredit=true; path=/; expires="+date.toUTCString();
                addBasket.call(this, e);
            }

            function login() {
                $('.login_link, .mob-login_link, a[data-form="register"]')
                    .click(
                        function(e) {
                            e.preventDefault()
                            mobOverlayRemove()
                            $('#menu-mob-hidden')
                                .removeClass('visible')
                            setTimeout(
                                function() {
                                    $('#menu-mob-hidden')
                                        .removeClass('open')
                                }, 200
                            )
                            $('body')
                                .removeClass('off-scroll')
                            var posq = ($(window).scrollTop()) + 30;
                            $('#overlay')
                                .fadeIn(
                                    400, function() {
                                        $('.modal_login')
                                            .css('display', 'block')
                                            .animate(
                                                {
                                                    opacity: 1,
                                                    top: posq
                                                }, 200
                                            );
                                    }
                                );
                        }
                    )
            }

            $("#list-container")
                .on(
                    "pjax:end", function() {
                        hoverCatalog();
                        sortList();
                        //bannerNew();
                        $('.jcarousel')
                            .jcarousel(
                                {
                                    vertical: true,
                                    scroll: 1,
                                    animation: 250
                                }
                            );
                    }
                );

            $(document)
                .on(
                    'ajaxComplete', '#payment_form', function(e, attribute) {
                        $('#paymentinform-captcha-image')
                            .yiiCaptcha('refresh');
                    }
                );

            $(document)
                .on(
                    'submit', '#payment_form', function(e) {
                        e.preventDefault();
                        var form = this;
                        var preloader = showPreloader('#inform');
                        $.ajax(
                            {
                                url: '/ru/site/payment-inform',
                                type: 'POST',
                                dataType: 'JSON',
                                data: new FormData(form),
                                processData: false,
                                contentType: false,
                                success: function(data, status) {
                                    $(form)
                                        .yiiActiveForm('updateMessages', data, true);
                                    if (data[ 'paymentinform-captcha' ].length || data[ 'error' ]) {
                                        $('#paymentinform-captcha-image')
                                            .yiiCaptcha('refresh');
                                        if (data[ 'error' ]) {
                                            alert(data[ 'message' ]);
                                        }
                                    } else if (data[ 'success' ]) {
                                        $('#inform')
                                            .find('.modal-body')
                                            .html('<p>' + data[ 'success' ] + '</p>');
                                        var body = $("html, body");
                                        body.stop()
                                            .animate({scrollTop: 0}, '500', 'swing');
                                    } else {
                                        alert('Неизвестная ошибка');
                                    }
                                },
                                error: function(xhr, desc, err) {
                                    alert(desc);
                                },
                                complete: function(xhr, status) {
                                    hidePreloader(preloader);
                                }
                            }
                        );
                    }
                );

            $(document)
                .on(
                    'focus', '[data-krajee-timepicker]', function(e) {
                        $(this)
                            .timepicker('showWidget');
                    }
                );

            $(document)
                .on(
                    'click', '.timepicker-clear', function(e) {
                        $('[data-krajee-timepicker]')
                            .timepicker('hideWidget');
                        $(this)
                            .parents('.bootstrap-timepicker')
                            .find('input[data-krajee-timepicker]')
                            .val('');
                    }
                );

            $('#list-container').on('pjax:start', function() {
                showPreloader(this);
            });

            $('#list-container').on('pjax:complete', function() {
                hidePreloader('.preloader');
            });

            $('#paymentinform-payedat')
                .mask('00:00');

            $('#basket-form').on('click', 'a[data-form="register"]', function(e) {
                $('#basket-register-tab').click();
            });

            $('._datepicer-payment')
                .datepicker(
                    {
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'dd.mm.yy',
                        closeText: 'Закрыть',
                        prevText: 'Пред',
                        nextText: 'След',
                        monthNames: [
                            'Январь',
                            'Февраль',
                            'Март',
                            'Апрель',
                            'Май',
                            'Июнь',
                            'Июль',
                            'Август',
                            'Сентябрь',
                            'Октябрь',
                            'Ноябрь',
                            'Декабрь'
                        ],
                        monthNamesShort: [
                            'Январь',
                            'Февраль',
                            'Март',
                            'Апрель',
                            'Май',
                            'Июнь',
                            'Июль',
                            'Август',
                            'Сентябрь',
                            'Октябрь',
                            'Ноябрь',
                            'Декабрь'
                        ],
                        dayNames: [
                            'воскресенье',
                            'понедельник',
                            'вторник',
                            'среда',
                            'четверг',
                            'пятница',
                            'суббота'
                        ],
                        dayNamesShort: [
                            'вск',
                            'пнд',
                            'втр',
                            'срд',
                            'чтв',
                            'птн',
                            'сбт'
                        ],
                        dayNamesMin: [
                            'Вс',
                            'Пн',
                            'Вт',
                            'Ср',
                            'Чт',
                            'Пт',
                            'Сб'
                        ],
                        firstDay: 1
                    }
                );
            function seoText(){
                $('.read_more_seo').click(function (e) {
                    e.preventDefault();
                    var txt1 = $(this).attr('data-text-read');
                    var txt2 = $(this).attr('data-text-hide');
                    if($('.seo_txt-wrapp').hasClass('hidden-seo-txt')){
                        $('.seo_txt-wrapp').removeClass('hidden-seo-txt')
                        $(this).html(txt2)
                    } else {
                        $('.seo_txt-wrapp').addClass('hidden-seo-txt')
                        $(this).html(txt1)
                    }
                })
            }

            function linkWhereBuy() {
                $('.where_can_buy').click(function (e) {
                    e.preventDefault();
                    if($(this).parent().hasClass('active-where')){
                        $(this).parent().removeClass('active-where')
                    } else {
                        $(this).parent().addClass('active-where')
                    }
                })
                $('.close_where').click(function () {
                    $(this).parents('.active-where').removeClass('active-where')
                })

                // $('.where_buy_hidden').hover(function () {
                //
                // }, function () {
                //     $(this).parents('.active-where').removeClass('active-where')
                // })
            }

            $(document).on('initialized.owl.carousel', '#Main-slider', function(e) {
                $(document).trigger('main-slider.artbox');
            });
            $('#basket-form').on('afterValidateAttribute', afterCredit);
            $('#basket-form').on('beforeValidate', beforeCredit);
            $(document).on('change', '.credit_input', handleCredit);
            $(document).on('keyup', '.credit_input', handleCredit);
            $(document).on('change', '.modal_input', handleModal);

            $(document).on('click', '.buy_in_one_click a', function(e) {
                e.preventDefault();
                var variant_id = $(this).data('variant');
                $('#orderfrontend-variant_id').val(variant_id);
                var newScroll = $(window).scrollTop();
                $('#overlay').fadeIn(400,
                    function(){
                        $('#buy_in_click')
                            .css('display', 'block')
                            .animate({opacity: 1, top: newScroll}, 200);
                    });
            });

            $(document).on('beforeSubmit', '#one-click-form', function(e) {
                var preloader = showPreloader('#'+$(this).attr('id'));
                $.post($(this).attr('action'), $(this).serialize(), function(data) {
                    hidePreloader(preloader);
                    if(data.success) {
                        alert(data.error);
                    } else {
                        $(this)[0].reset();
                    }
                });
                return false;
            });
        }
    );
function afterCredit(e, attr, msg) {
    if(attr.name == 'credit_month' || attr.name == 'credit_sum') {
        if(!msg.length) {
            var result = true;
            $('.credit_input').each(function(i, v) {
                if(!Boolean($(v).val())) {
                    result = false;
                    return false;
                }
            });
            if(result) {
                var calculated_data = calculateCredit();
                $('.credit_size_value').html(calculated_data.credit_payment);
                $('.credit_value').html(calculated_data.credit_per_month);
            }
        }
    }
}
function beforeCredit(e, msg) {
    if($('.payment-wrapper').find('input:checked').val() != 10) {
        $('.credit_input').each(function(i, v) {
            $(v).val($(v).data('default'));
        });
    }
}
function handleCredit(e) {
    var form = $('#basket-form');
    if(form.length) {
        form.yiiActiveForm('validateAttribute', $(this).attr('id'), true);
    }
}
function handleModal(e) {
    if(parseInt($(this).val()) > parseInt($(this).attr('max')) || parseInt($(this).val()) < parseInt($(this).attr('min'))) {
        $(this).val($(this).data('default'));
    }
    var calculated_data = calculateModal();
    $('.modal_size_value').html(calculated_data.credit_payment);
    $('.modal_value').html(calculated_data.credit_per_month);
}
function calculateModal(percent) {
    var credit_month = parseInt($('.modal_month_input').val());
    var credit_sum = parseFloat($('.modal_sum_input').val());
    var basket_sum = parseFloat($('.modal_sum_input').data('sum'));
    if(percent === undefined) {
        percent = 2;
    }
    if(credit_month !== undefined || credit_sum !== undefined) {
        var credit_payment = basket_sum - credit_sum;
        return {
            'basket_sum': basket_sum,
            'credit_payment': credit_payment,
            'credit_per_month': Math.ceil((credit_payment / credit_month) + (credit_payment * percent / 100))
        };
    } else {
        return {
            'basket_sum': basket_sum,
            'credit_payment': 'Невозможно посчитать',
            'credit_per_month': 'Невозможно посчитать'
        };
    }
}
function calculateCredit(percent) {
    var credit_month = parseInt($('.credit_month_input').val());
    var credit_sum = parseFloat($('.credit_sum_input').val());
    var basket_sum = parseFloat($('.credit_sum_input').data('sum'));
    if(percent === undefined) {
        percent = 2;
    }
    if(credit_month !== undefined || credit_sum !== undefined) {
        var credit_payment = basket_sum - credit_sum;
        return {
            'basket_sum': basket_sum,
            'credit_payment': credit_payment,
            'credit_per_month': Math.ceil((credit_payment / credit_month) + (credit_payment * percent / 100))
        };
    } else {
        return {
            'basket_sum': basket_sum,
            'credit_payment': 'Невозможно посчитать',
            'credit_per_month': 'Невозможно посчитать'
        };
    }
}
function showPreloader(parent) {
    var preloader = $("<div class='preloader'></div>");
    $(parent).prepend(preloader);
     return preloader;
}
function hidePreloader(preloader) {
     $(preloader).remove();
    $('body').removeClass('hidden_scroll-y')
}
function btnScroll() {
    btnScrollPos()
    function btnScrollPos() {
        var containerPos = $('.container').offset().left
        $('.btn_scroll').css({right:containerPos})
    }

    btnScrollShowHide()
    function btnScrollShowHide() {
        var windowHeight = $(window).height()

        if($(this).scrollTop() < windowHeight)
        {
            $('.btn_scroll').removeClass('visible-btn')
        } else {
            $('.btn_scroll').addClass('visible-btn')
        }
    }

    $(window).resize(function () {
        btnScrollPos()
        btnScrollShowHide()
    })

    $(window).scroll(function () {
        btnScrollShowHide()
    })


    $('.btn_scroll').click(function () {
        $('body,html').animate( { scrollTop: 0 }, 500 );
    })

}