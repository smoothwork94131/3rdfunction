$(function($) {
    "use strict";

    $(document).ready(function() {

        // Profile Dropdown
        $('.profilearea.my-dropdown').on('mouseover', function() {
            $('.profilearea.my-dropdown .my-dropdown-menu.profile-dropdown').stop().show(0);
        });
        $('.profilearea.my-dropdown').on('mouseout', function() {
            $('.profilearea.my-dropdown .my-dropdown-menu.profile-dropdown').stop().hide(0);
        });
        
        $(window).resize(function() {
            resizeLayout();
        });

        var orgin_scr_width = $("body").width();

        function resizeLayout() {
            resizeHeader();
        }

        function resizeHeader() {
            var scr_width = $("body").width();
            var bar_cnt = $(".top-menu .navbar-nav").children().length;
            var bar_item_width = ($(".navbar").width() + 10) / bar_cnt;

            $(".navbar-nav li").css("width", bar_item_width + "px");
            $(".navbar-nav .dropdown-menu").css("width", bar_item_width + "px");

            $(".navbar-nav li a").css({
                "width": bar_item_width + "px",
                "textAlign": "center",
            });
            
            if (scr_width < 768) {
                $(".socials-div").css("display", "none");
                $(".locations-div").css("display", "none");
                $(".tiny-menu").css("display", "block");

                $(".logo-div").removeClass("col-sm-3");
                $(".logo-div").addClass("col-sm-6");

                $(".logo-div").addClass("fix-height-85-50");

                $(".top-menu .navbar").css("display", "none");
                $(".navbar-nav li a").css({
                    "width": $(".bottom-menu").width() + "px",
                    "textAlign": "left"
                });

                $(".navbar-nav .dropdown .dropdown-menu").css("position", "unset !important");

                $(".dropdown-menu .nav-item").css("width", bar_item_width + "px");
                $(".navbar-nav li").css("width", $(".bottom-menu").width() + "px");
                $(".bottom-menu .navbar").css("display", "block");

                $(".header-base-tool").css("display", "none");
                $(".header-min-tool").css("display", "flex");
                $(".mobile-search-field").css("display", "block") ;
                $(".desktop-search-field").css("display", "none") ;
                // $(".search-dropdown").css("top", "40px") ;
                
                $("#phone_num_desc").text("CALL:");
            } else {
                $(".socials-div").css("display", "flex");
                $(".locations-div").css("display", "block");
                $(".tiny-menu").css("display", "none");

                $(".logo-div").removeClass("col-sm-6");
                $(".logo-div").addClass("col-sm-3");

                $(".top-menu .navbar").css("display", "block");
                $(".bottom-menu .navbar").css("display", "none");

                $(".header-base-tool").css("display", "flex");
                $(".header-min-tool").css("display", "none");

                $(".logo-div").css("fix-height-85-50");
                $(".dropdown-menu .nav-item").css("width", "100% !important");
                $(".mobile-search-field").css("display", "none") ;
                $(".desktop-search-field").css("display", "block") ;
                $(".search-dropdown").css("top", "100%") ;

                $("#phone_num_desc").text("FOR ASSISTANCE CALL:");
            }
        }

        resizeLayout();

        $(function() {

            var url = window.location.href,
                urlRegExp = new RegExp(url.replace(/\/$/, '') + "$");

            // now grab every link from the navigation
            $('.core-nav-list li a').each(function() {
                // and test its normalized href against the url pathname regexp
                if (url == this.href) {
                    $(this).addClass('active');
                }
            });

        });

        /*------addClass/removeClass categories-------*/
        var w = window.innerWidth;

        $('.categories_menu_inner').stop().slideUp();
        
        $(document).on("click", ".categories_title", function() {
            $(this).addClass('active');
            $(this).parent().siblings('.categories_menu').children('.categories_menu_inner').stop().slideUp();
            $(this).parent().children('.categories_menu_inner').stop().slideToggle();
            var type = $(this).data('type');
            var series = $(this).data('series')
            var category = replaceDataToPath($(this).data('category')) 
            
            var model = replaceDataToPath($(this).data('model'));
            var section = replaceDataToPath($(this).data('section'));
            var link = $(this).data('url');
            var token = $(this).data('token');
            var domain = $(this).data("domain_name");
            var cat_elem = $(this).parent().children('.categories_menu_inner');


            if (type) {
                if (type != 'group') {
                    cat_elem.html('');
                }

                $.ajax({
                    method: "get",
                    url: link,
                    data: {
                        '_token': token,
                        'type': type,
                        'series': series,
                        'category': category,
                        'model': model,
                        'section': section,
                        'req_type':'json',
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        
                        if (data.categories.length > 0) {
                            var element = ``;
                            if (type == 'model') {
                                for (var x in data.categories) {
                                    element += `<div class="categories_menu">
                                    <div class="categories_title" 
                                    data-type="section"
                                    data-model="${data.categories[x].model}"
                                    data-series="${series}"
                                    data-url="${link}" 
                                    data-domain="${domain}" 
                                    data-category="${category}"
                                    data-status="0" data-token="${token}">
                                        <h2 class="categori_toggle"> ${data.categories[x].model} <i class="fa fa-angle-down arrow-down"></i>
                                        </h2>
                                    </div>
                                    <div class="common-parts" style="background-color: white">
                                    </div>
                                    <div class="categories_menu_inner sections" style="display: none">
                                        loading...
                                    </div>
                                </div>`;
                                }
                            } else if(type == "category") {
                                for (var x in data.categories) {
                                    element += `<div class="categories_menu">
                                        <div class="categories_title"
                                        data-type="model"
                                        data-section="${data.categories[x].name}"
                                        data-model="${model}"
                                        data-category="${category}"
                                        data-series="${data.categories[x].name}"
                                        data-url="${link}"
                                        data-domain="${domain}" 
                                        data-status="0" data-token="${token}"><h2 class="categori_toggle"> ${data.categories[x].name} <i
                                                        class="fa fa-angle-down arrow-down"></i></h2>
                                        </div>
                                        <div class="categories_menu_inner groups" style="display: none">
                                            <ul class="category-groups">
                                                loading...
                                            </ul>
                                        </div>
                                    </div>`;
                                }
                            } else if (type == 'section') {
                                for (var x in data.categories) {
                                    element += `<div class="categories_menu">
                                        <div class="categories_title"
                                        data-type="group"
                                        data-section="${data.categories[x].section_name}"
                                        data-model="${model}"
                                        data-series="${series}"
                                        data-url="${link}" 
                                        data-domain="${domain}" 
                                        data-category="${category}"
                                        data-status="0" data-token="${token}"><h2 class="categori_toggle"> ${data.categories[x].section_name} <i
                                                        class="fa fa-angle-down arrow-down"></i></h2>
                                        </div>
                                        <div class="categories_menu_inner groups" style="display: none">
                                            <ul class="category-groups">
                                                loading...
                                            </ul>
                                        </div>
                                    </div>`;
                                }
                            
                            } else if (type == 'group') {
                                for (var x in data.categories) {
                                    var group_Id = replaceDataToPath(data.categories[x].group_Id) ;
                                    if(domain == 'mahindra') {
                                        element += `<li><a href="${mainurl}/collection/${category}/${series}/${model}/${section}/${group_Id}">> ${data.categories[x].group_name}</a></li>`;
                                    }
                                    else {
                                        element += `<li><a href="${mainurl}/category/${category}/${series}/${model}/${section}/${group_Id}">> ${data.categories[x].group_name}</a></li>`;
                                    }
                                }
                                cat_elem = cat_elem.children('.category-groups');
                                cat_elem.html(element);
                            }   

                            if (type != 'group') {
                                cat_elem.append(element);
                            }

                            if (type == 'section') {
                                $.ajax({
                                    method: 'GET',
                                    url: `${mainurl}/common/parts/${category}/${series}/${model}
                                    `,
                                    success: (data) => {
                                        cat_elem.prepend(data);
                                    }
                                });
                            }

                        } else {
                            cat_elem.append('<li><div align="center">No Data</div></li>');
                        }
                    }
                });
            }

        });

        function replaceDataToPath($path) {
            $path = $path + "";
            if($path == undefined) return $path ;
            if($path.indexOf("/")) {
                $path = $path.replace(/[/]/g, ":::") ;
            }
            return $path ;
        }
        /*------addClass/removeClass categories-------*/
        $(".categories_menu_inner > ul > li").on("click", function() {
            $(this).find('.link-area a').toggleClass('open').parent().parent().find('.categories_mega_menu, categorie_sub').addClass('open');
            $(this).siblings().find('.categories_mega_menu, .categorie_sub').removeClass('open');
        });



        $(document).on('click', function(e) {
            var container = $(".categories_menu_inner, .categories_mega_menu, .categories_title");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('.categories_menu_inner').stop().slideUp('medium');
                $('.categories_mega_menu, .categorie_sub').removeClass('open');
                $(".categories_mega_menu").removeClass('open');
                $(".categories_title").removeClass('active');
            }
        });

        /*------addClass/removeClass categories-------*/


        /*------addClass/removeClass categories-------*/



        $('nav').coreNavigation({
            menuPosition: "right",
            container: false,
            dropdownEvent: 'hover',
            onOpenDropdown: function() {
                console.log('open');
            },
            onCloseDropdown: function() {
                console.log('close');
            }
        });

        // Nice Select Start
        $('select.nice').niceSelect();

        $('#example').DataTable({
            "paging": true,
            "ordering": false,
            "info": true
        });


        //   magnific popup activation
        $('.video-play-btn').magnificPopup({
            type: 'video'
        });



        // Tooltip Section

        $('[data-toggle="tooltip"]').tooltip({

        });

        $('[rel-toggle="tooltip"]').tooltip();

        $('[data-toggle="tooltip"]').on('click', function() {
            $(this).tooltip('hide');
        })


        $('[rel-toggle="tooltip"]').on('click', function() {
            $(this).tooltip('hide');
        })

        // Tooltip Section Ends

        /*-----------------------------
            Accordion Active js
        -----------------------------*/
        $("#accordion, #accordion2").accordion({
            heightStyle: "content",
            collapsible: true,
            icons: {
                "header": "ui-icon-caret-1-e",
                "activeHeader": " ui-icon-caret-1-s"
            }
        });
        $("#product-details-tab").tabs();


        // Hero Area Slider
        var $mainSlider = $('.intro-carousel');
        if ($('.intro-content').length > 1) {
            $mainSlider.owlCarousel({
                loop: true,
                //navText: ['<i class="fas fa-angle-double-left"></i>', '<i class="fas fa-angle-double-right"></i>'],
                nav: false,
                dots: true,
                autoplay: true,
                autoplayTimeout: 8000,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                smartSpeed: 1000,
                onInitialized: startProgressBar,
                onTranslate: resetProgressBar,
                onTranslated: startProgressBar,
                responsive: {
                    0: {
                        dots: false,
                        items: 1
                    },
                    768: {
                        items: 1
                    }
                },

            });
        }

        if ($('.intro-content').length > 1) {
            var $currentItem = $('.owl-item', $mainSlider).eq(2);
            var $class1 = $currentItem.find('.subtitle').attr('data-animation');
            $currentItem.find('.subtitle').addClass($class1);
            setTimeout(function() {
                $currentItem.find('.subtitle').removeClass($class1);
            }, 900);

            var $class2 = $currentItem.find('.title').attr('data-animation');
            $currentItem.find('.title').addClass($class2);
            setTimeout(function() {
                $currentItem.find('.title').removeClass($class2);
            }, 900);

            var $class3 = $currentItem.find('.text').attr('data-animation');
            $currentItem.find('.text').addClass($class3);
            setTimeout(function() {
                $currentItem.find('.text').removeClass($class3);
            }, 900);

        }




        if ($('.intro-content').length > 1) {

            $mainSlider.on('changed.owl.carousel', function(event) {
                var $currentItem = $('.owl-item', $mainSlider).eq(event.item.index)

                var $class11 = $currentItem.find('.subtitle').attr('data-animation');
                $currentItem.find('.subtitle').addClass($class11);
                setTimeout(function() {
                    $currentItem.find('.subtitle').removeClass($class11);
                }, 900);

                var $class22 = $currentItem.find('.title').attr('data-animation');
                $currentItem.find('.title').addClass($class22);
                setTimeout(function() {
                    $currentItem.find('.title').removeClass($class22);
                }, 900);
                var $class33 = $currentItem.find('.text').attr('data-animation');
                $currentItem.find('.text').addClass($class33);
                setTimeout(function() {
                    $currentItem.find('.text').removeClass($class33);
                }, 900);


            });

        }

        var $mainProductSlider = $('.main_product-slider');

        $mainProductSlider.owlCarousel({
            items: 1,
            autoplay: true,
            margin: 0,
            loop: true,
            dots: true,
            nav: true,
            center: false,
            autoplayHoverPause: true,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 1,
                },
                414: {
                    items: 1
                }
            }
        });



        function startProgressBar() {
            // apply keyframe animation
            $(".slide-progress").css({
                width: "100%",
                transition: "width 8000ms"
            });
        }

        function resetProgressBar() {
            $(".slide-progress").css({
                width: 0,
                transition: "width 0s"
            });
        }
        // Hero Area Slider End





        // flas_deal_slider
        var $flas_deal_slider = $('.flas-deal-slider');

        if ($flas_deal_slider.children().length > 1) {
            $flas_deal_slider.owlCarousel({
                loop: true,
                nav: true,
                navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
                dots: false,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 6000,
                smartSpeed: 1000,
                responsive: {
                    0: {
                        items: 1,
                    },
                    414: {
                        items: 2,
                    },
                    768: {
                        items: 2,
                    },
                    992: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                }
            });

        }



        // Product deal countdown
        $('[data-countdown]').each(function() {
            var $this = $(this),
                finalDate = $(this).data('countdown');
            $this.countdown(finalDate, function(event) {
                $this.html(event.strftime('<span>%D : <small>Days</small></span> <span>%H : <small>Hrs</small></span>  <span>%M : <small>Min</small></span> <span>%S <small>Sec</small></span>'));
            });
        });

        // trending item  slider
        var $trending_slider = $('.trending-item-slider');

        if ($trending_slider.children().length > 1) {
            $trending_slider.owlCarousel({
                items: 4,
                autoplay: true,
                margin: 0,
                loop: true,
                dots: true,
                nav: true,
                center: false,
                autoplayHoverPause: true,
                navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                smartSpeed: 800,
                responsive: {
                    0: {
                        items: 1,
                    },
                    414: {
                        items: 1,
                    },
                    768: {
                        items: 2,
                    },
                    992: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                }
            });
        }
        
        // trending item  slider
        var $hot_new_slider = $('.hot-and-new-item-slider');

        if ($hot_new_slider.children().length > 1) {
            $hot_new_slider.owlCarousel({
                items: 1,
                autoplay: true,
                margin: 0,
                loop: true,
                dots: true,
                nav: true,
                center: false,
                autoplayHoverPause: true,
                navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                smartSpeed: 800,
                responsive: {
                    0: {
                        items: 1,
                    },
                    414: {
                        items: 1
                    }
                }
            });

        }

        // aside_review_slider
        var $aside_review_slider = $('.aside-review-slider');

        if ($aside_review_slider.children().length > 1) {

            $aside_review_slider.owlCarousel({
                loop: true,
                nav: false,
                dots: true,
                margin: 30,
                autoplay: true,
                autoplayTimeout: 6000,
                smartSpeed: 1000,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 1
                    }
                }
            });

        }


        $(document).on('click', function(e) {
            var container = $(".autocomplete-items");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $(".autocomplete").hide();
            }
        });

        if (w <= 991)
        {
            $(document).on('mouseover', function(e) {
                var container = $(".xzoom-preview");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    $(".xzoom-preview").css('display', 'none');
                }
            });

        }

        if (w <= 991)

        {

            $('.carticon').on('click', function() {
                $(this).next().toggleClass('show');
            });

            $(document).on('click', function(e) {
                var container = $(".carticon, .my-dropdown-menu");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    $('.my-dropdown-menu').removeClass('show');
                }
            });
        }



    });

    /*-------------------------------
        back to top
    ------------------------------*/
    $(document).on('click', '.bottomtotop', function() {
        $("html,body").animate({
            scrollTop: 0
        }, 2000);
    });

    //define variable for store last scrolltop
    var lastScrollTop = '';
    $(window).on('scroll', function() {

        /*---------------------------
            back to top show / hide
        ---------------------------*/
        var st = $(this).scrollTop();
        var ScrollTop = $('.bottomtotop');
        if ($(window).scrollTop() > 1000) {
            ScrollTop.fadeIn(1000);
        } else {
            ScrollTop.fadeOut(1000);
        }
        lastScrollTop = st;

    });

    $(window).on('load', function() {

        /*---------------------
            Preloader
        -----------------------*/
        var preLoder = $("#preloader");
        preLoder.addClass('hide');
        var backtoTop = $('.back-to-top')
            /*-----------------------------
                back to top
            -----------------------------*/
        var backtoTop = $('.bottomtotop')
        backtoTop.fadeOut(100);
    });

    // Coupon code toggle code
    $('#coupon-link').on('click', function() {
        $("#coupon-form,#check-coupon-form").toggle();
    })

    $('.preload-close').click(function() {
        $('.subscribe-preloader-wrap').hide();
    });

    $(window).load(function() {
        setTimeout(function() {
            $('#subscriptionForm').show();
        }, 10000)
    });


    // partner-slider
    var $partner_Slider = $('.partner-slider');
    $partner_Slider.owlCarousel({
        loop: true,
        nav: false,
        dots: true,
        autoplay: true,
        margin: 30,
        autoplayTimeout: 3000,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 2
            },
            767: {
                items: 3
            },
            993: {
                items: 4
            },
            1200: {
                items: 5
            },
            1920: {
                items: 5
            }
        }
    });

    var $product_slider = $('.all-slider');
    $product_slider.owlCarousel({
        loop: true,
        dots: false,
        nav: true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        margin: 0,
        autoplay: false,
        items: 4,
        autoplayTimeout: 6000,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 4
            },
            768: {
                items: 4
            }
        }
    });



});