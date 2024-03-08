(function($) {
    'use strict';

    var durotan = durotan || {};
    durotan.init = function() {
        durotan.data = durotanData || {};
        durotan.$body = $(document.body),
        durotan.$window = $(window),
        durotan.$header = $('#masthead');

        // Single product
        this.relatedProductsCarousel();
        this.relatedProductsCarouselv2();
        this.productVariation();

        // Product Layout
        this.singleProductV1();
        this.singleProductV2();
        this.singleProductV3();
        this.singleProductV4();
        this.singleProductV5();
        this.singleProductV6();
        this.singleProductV7();

        this.productVideo();
        this.stickyATC();
    };

    /**
     * Product Thumbnails
     */
    durotan.productThumbnails = function($vertical, $layout) {
        var $gallery = $('.woocommerce-product-gallery'),
            $video = $gallery.find('.woocommerce-product-gallery__image.durotan-product-video');

        $gallery.on('wc-product-gallery-after-init', function(){
            $gallery.imagesLoaded(function () {
                var $thumbnail = $gallery.find('.flex-control-thumbs');
                if ($video.length > 0) {
                    var videoNumber = $('.woocommerce-product-gallery').data('video') - 1;
                    $('.woocommerce-product-gallery').addClass('has-video');
                    if ($thumbnail.find('li').length < videoNumber) {
                        $thumbnail.find('li').eq($thumbnail.find('li').length - 1).append('<div class="i-video"></div>');
                    } else {
                        $thumbnail.find('li').eq(videoNumber).append('<div class="i-video"></div>');
                    }
                }

                // Add an <span> to thumbnails for responsive bullets.
                $('li', $thumbnail).append('<span/>');

                var slidesToShow = $gallery.data('columns'),
                    swiper = null;

                if ($vertical) {
                    slidesToShow = parseInt( $gallery.height() / $thumbnail.children().first().height());
                } else {
                    //slidesToShow = parseInt( $gallery.width() / $thumbnail.children().first().width() );
                }
                if ($thumbnail.children().length > slidesToShow && $( window ).width() >= 992) {
                    var options = {
                        slidesPerView: slidesToShow,
                        loop: false,
                        autoplay: false,
                        speed: 800,
                        watchOverflow: true,
                        spaceBetween: 10,
                        navigation: {
                            nextEl: '.durotan-thumbs-button-next',
                            prevEl: '.durotan-thumbs-button-prev',
                        },
                        on: {
                            init: function() {
                                $thumbnail.parent().css('opacity', 1);
                            }
                        },
                        breakpoints: {
                            300: {
                                spaceBetween: 0,
                                allowTouchMove: false,
                            },
                            991: {
                                spaceBetween: 10,
                            },
                        },
                    };

                    switch ($layout) {
                        case 'v4':
                            $thumbnail.wrap('<div class="durotan-swiper-container"><div class="woocommerce-product-gallery__thumbs-carousel swiper-container" style="opacity:0"></div></div>');
                            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-thumbs-button-next durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" ><path d="M30.966 16.234l-9.6-9.6c-0.312-0.312-0.819-0.312-1.131 0s-0.312 0.819 0 1.131l8.234 8.234h-26.069c-0.442 0-0.8 0.358-0.8 0.8s0.358 0.8 0.8 0.8h26.069l-8.234 8.234c-0.312 0.312-0.312 0.819 0 1.131 0.156 0.156 0.361 0.234 0.566 0.234s0.409-0.078 0.566-0.234l9.6-9.6c0.312-0.312 0.312-0.819 0-1.131z"></path></svg></span>');
                            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-thumbs-button-prev durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" ><path d="M1.034 16.234l9.6-9.6c0.312-0.312 0.819-0.312 1.131 0s0.312 0.819 0 1.131l-8.234 8.234h26.069c0.442 0 0.8 0.358 0.8 0.8s-0.358 0.8-0.8 0.8h-26.069l8.234 8.234c0.312 0.312 0.312 0.819 0 1.131-0.156 0.156-0.361 0.234-0.566 0.234s-0.409-0.078-0.566-0.234l-9.6-9.6c-0.312-0.312-0.312-0.819 0-1.131z"></path></svg></span>');
                            break;
                        case 'v5':
                        case 'v6':
                            $thumbnail.wrap('<div class="durotan-swiper-container"><div class="woocommerce-product-gallery__thumbs-carousel swiper-container" style="opacity:0"></div></div>');
                            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-thumbs-button-prev durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></span>');
                            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-thumbs-button-next durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></span>');
                            options.spaceBetween = 15;
                            break;
                        default:
                            $thumbnail.wrap('<div class="woocommerce-product-gallery__thumbs-carousel swiper-container" style="opacity:0"></div>');
                            $thumbnail.after('<span class="durotan-svg-icon durotan-thumbs-button-prev durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></span>');
                            $thumbnail.after('<span class="durotan-svg-icon durotan-thumbs-button-next durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></span>');
                    }
                    $thumbnail.addClass('swiper-wrapper');
                    $thumbnail.find('li').addClass('swiper-slide');

                    if ($vertical) {
                        options.direction = 'vertical';
                    } else {
                        options.direction = 'horizontal';
                    }

                    swiper = new Swiper($thumbnail.parent(), options);
                }
                $(window).on( 'resize', function() {
                    if(swiper){
                        if ($vertical) {
                            swiper.params.slidesToShow = parseInt( $gallery.height() / $('img',$thumbnail.children().first()).outerHeight(true) );
                        }
                        swiper.update();
                    }
                });
                $(window).trigger('resize')
            });
        });
    };

    /**
     * Set the product background similar to product gallery images
     */
    durotan.productBackgroundFromGallery = function($product) {
        if (typeof BackgroundColorTheif == 'undefined') {
            return;
        }

        var $gallery = $product.find('.woocommerce-product-gallery'),
            $image = $gallery.find('.wp-post-image'),
            imageColor = new BackgroundColorTheif();

        // Change background base on main image.
        $image.one('load', function() {
            setTimeout(function() {
                changeProductBackground($image.get(0));
            }, 100);
        }).each(function() {
            if (this.complete) {
                $(this).trigger('load');
            }
        });

        // Change background when slider change.
        setTimeout(function() {
            var slider = $gallery.data('flexslider');

            if (!slider) {
                return;
            }

            slider.vars.before = function(slider) {
                setTimeout(function() {
                    changeProductBackground(slider.slides.filter('.flex-active-slide').find('a img').get(0));
                }, 150);
            };
        }, 150);

        // Support Jetpack images lazy loads.
        $gallery.on('jetpack-lazy-loaded-image', '.wp-post-image', function() {
            $(this).one('load', function() {
                changeProductBackground(this);
            });
        });

        // Change background when variation changed
        $gallery.on('woocommerce_gallery_reset_slide_position', function() {
            changeProductBackground($image.get(0));
        });

        durotan.$body.on('durotan_product_navigation', function(e, image) {
            changeProductBackground(image.find('img').get(0));
        })

        /**
         * Change product backgound color
         */
        function changeProductBackground(image) {
            // Stop if this image is not loaded.
            if (image === undefined || image.src === '') {
                return;
            }

            if (image.classList.contains('jetpack-lazy-image')) {
                if (!image.dataset['lazyLoaded']) {
                    return;
                }
            }

            var rgb = imageColor.getBackGroundColor(image);
            $product.get(0).style.backgroundColor = 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')';
        }
    }

    /**
     * Single Product V1
     */
    durotan.singleProductV1 = function() {

        var $product = $('div.product.layout-v1');

        if (!$product.length) {
            return;
        }

        durotan.productThumbnails(true);

    };

    /**
     * Single Product V2
     */
    durotan.singleProductV2 = function() {

        var $product = $('div.product.layout-v2');

        if (!$product.length) {
            return;
        }

        durotan.productThumbnails(true);
        durotan.modalProductTabs();
    };

    /**
     * Single Product V3
     */
    durotan.singleProductV3 = function() {

        var $product = $('div.product.layout-v3');

        if (!$product.length) {
            return;
        }

        // Add page preload when automatically setting background
        if (!$product.hasClass('background-set') && durotan.data.product_auto_background === '1') {
            durotan.preload();
        }

        // Make product fullwidth
        durotan.productFullWidth($product);

        // Product tabs
        durotan.modalProductTabs();

        // Navigation Item smooth scroll
        if ($('.durotan-control-nav').length > 0) {
            durotan.$body.on('click','a[href*="#control-navigation-image"]:not([href="#"])', function() {

                if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

                    // if wordpress admin bar exists
                    var adminBarHeight = 0;
                    if ($('body').hasClass('admin-bar')) {
                        var adminBarHeight = 32;
                    }

                    var header_height = $('.site-header.minimized').length ? $('.site-header.minimized').outerHeight() : 0;

                    if (target.length) {
                        $('html, body').stop().animate({
                            scrollTop: target.offset().top - header_height - adminBarHeight
                        }, 500);
                        return false;
                    }
                }
            });
        }

        durotan.$body.on( 'durotan_product_sticky', function(e, product) {
            // Products Dot Navigation
            var $gallery = $('.woocommerce-product-gallery', product),
            productImages = $gallery.find(".product-image"),
            productSummary = $gallery.parent().find(".summary"),
            navController = $gallery.find('.durotan-control-nav'),
            navItems =  navController.find("li a");

            $(window).scroll(function() {
                clearTimeout($.data(this, 'scrollTimer'));
                if (productImages.length > 0 && navController.length > 0) {
                    var scrollDistance = $(window).scrollTop(),
                        setHeight = 30;
                    if ($('.site-header.minimized').length) {
                        setHeight += $('.site-header.minimized').outerHeight();
                        if ($('body').hasClass('admin-bar')) {
                            setHeight += 32;
                        }
                        navController.css('top', setHeight);
                        productSummary.css('top', setHeight);
                    }
                    productImages.each(function(i) {
                        var $this = $(this);
                        if ($(this).position().top <= scrollDistance) {
                            navItems.removeClass('current');
                            navItems.eq(i).addClass('current');
                        }
                    });
                    $.data(this, 'scrollTimer', setTimeout(function() {
                        durotan.$body.trigger('durotan_product_navigation',  [navItems.filter('.current')]);
                    }, 250));
                }
            });
        });

        durotan.responsiveProductGallery();
    };

    /**
     * Single Product V4
     */
    durotan.singleProductV4 = function() {

        var $product = $('div.product.layout-v4');

        if (!$product.length) {
            return;
        }

        durotan.productThumbnails(false, 'v4');
    };

    /**
     * Single Product V5
     */
    durotan.singleProductV5 = function() {
        var $product = $('div.product.layout-v5');

        if (!$product.length) {
            return;
        }
        durotan.productThumbnails(false, 'v5');

        // Product tabs
        var $tabs = $product.find('.woocommerce-tabs'),
            $hash = window.location.hash;

        if ($hash.toLowerCase().indexOf("comment-") >= 0 || $hash === "#reviews" || $hash === "#tab-reviews") {
            $tabs.find(".tab-title-reviews").addClass("active");
            $tabs.find(".woocommerce-Tabs-panel--reviews").show();
        }

        $(".woocommerce-review-link").on("click", function() {
            $(".durotan-accordion-title.tab-title-reviews").trigger('click');
        });

        $tabs.on("click", ".durotan-accordion-title", function(e) {
            e.preventDefault();

            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(this).siblings(".woocommerce-Tabs-panel").stop().slideUp(300);
            } else {
                $tabs.find(".durotan-accordion-title").removeClass("active");
                $tabs.find(".woocommerce-Tabs-panel").slideUp();
                $(this).addClass("active");
                $(this).siblings(".woocommerce-Tabs-panel").stop().slideDown(300);
            }
        });

        // Make product fullwidth
        durotan.productFullWidth($product);

        // Set top padding of product
        $(window).on( 'resize', function() {
			if (durotan.$body.hasClass('header-transparent')) {
                $product.find('.summary ').css({ paddingTop: durotan.$header.height() });
            }
		});

        if (durotan.$body.hasClass('header-transparent')) {
            $product.find('.summary ').css({ paddingTop: durotan.$header.height() });
        }

        // Auto change background color
        if (!$product.hasClass('background-set') && durotan.data.product_auto_background === '1') {
            durotan.productBackgroundFromGallery($product);
        }
    }

    /**
     * Single Product V6
     */
    durotan.singleProductV6 = function() {

        var $product = $('div.product.layout-v6'),
            $summary = $product.find( '.summary' ),
			$summaryInner = $summary.children( '.summary-inner' );

        if (!$product.length) {
            return;
        }
        durotan.productThumbnails(false, 'v6');
        durotan.modalProductTabs();


        // Make product fullwidth
		productWidth();

		$(window).on( 'resize', function() {
			productWidth();
		});
        /**
		 * Set product width
		 */
		function productWidth() {
			var width = $(window).width();
				//bonus = width > 1560 ? 60 : 0;

            if (durotan.$body.hasClass('header-v7')) {
                var $header = durotan.$header.find('.header__main');

                if ($header.is(':visible')) {
                    width -= $header.outerWidth();
                }
            }
			$product.width( width );
			if ( durotan.data.rtl ) {
				$product.css( 'margin-right', -width / 2 );
				$summary.css( 'padding-left', width / 2 - $( '.product-content-container' ).width() / 2 );
			} else {
				$product.css( 'margin-left', -width / 2 );
				$summary.css( 'padding-right', width / 2 - $( '.product-content-container' ).width() / 2 );
			}
		}
    };

    /**
     * Single Product V7
     */
    durotan.singleProductV7 = function() {

        var $product = $('div.product.layout-v7');

        if (!$product.length) {
            return;
        }

        durotan.productThumbnails(true);
    };

    /**
     * Make product full width
     */
    durotan.productFullWidth = function($product) {
        var $window = $(window);

        // Set width of product
        changeProductWidth();

        $window.on('resize', function() {
            changeProductWidth();
        });

        /**
         * Change the product width
         */
        function changeProductWidth() {
            var width = $window.width();

            if (durotan.$body.hasClass('header-v7')) {
                var $header = durotan.$header.find('.header__main');

                if ($header.is(':visible')) {
                    width -= $header.outerWidth();
                }
            }

            $product.width(width);

            if (durotanData.rtl) {
                $product.css('marginRight', -width / 2);
            } else {
                $product.css('marginLeft', -width / 2);
            }
        }
    };


    /**
     * Product type variation
     */
    durotan.productVariation = function() {

        $('.variations_form').on('found_variation.wc-variation-form', function(e, variation) {
            var $sku = $('div.product').find('.sku_wrapper .sku');

            if (typeof $sku.wc_set_content !== 'function') {
                return;
            }

            if (typeof $sku.wc_reset_content !== 'function') {
                return;
            }

            if (variation.sku) {
                $sku.wc_set_content(variation.sku);
            } else {
                $sku.wc_reset_content();
            }
        });

        var $sticky_act = $('#durotan-sticky-add-to-cart'),
            $price = $('.durotan-sticky-add-to-cart__content-price',$sticky_act),
            $price_clone = $price.clone();

        $('form.variations_form').on('hide_variation', function(){
            var $button = $( '.durotan-buy-now-button' , $(this));
            $button.prop( 'disabled', true ).addClass('disabled');
            $price.html($price_clone);
        });

        $('form.variations_form').on('show_variation', function(event, data){
            var $button = $( '.durotan-buy-now-button' , $(this));
            $button.prop( 'disabled', false ).removeClass('disabled');
            if($sticky_act.length > 0 && data.price_html){
                $price.html(data.price_html);
            }
        });

        $('.variations_form td.value').find('select').each(function() {
            $(this).on('change', function() {
                if ( ( $(this).closest('.variation-selector.hidden').length || $(this).closest('.wcboost-variation-swatches').length ) && $(this).closest('.entry-summary').length) {
                    $(this).closest('tr').find('td.label').show();
                }
            }).trigger('change');
        });
    };

    /**
     * Init product video
     */
    durotan.productVideo = function() {
        var $gallery = $('.woocommerce-product-gallery');
        var $video = $gallery.find('.woocommerce-product-gallery__image.durotan-product-video');
        var $thumbnail = $gallery.find('.flex-control-thumbs');

        if ($video.length < 1) {
            return;
        }

        $thumbnail.on('click', 'li', function() {

            var $video = $gallery.find('.durotan-product-video');

            var $iframe = $video.find('iframe'),
                $wp_video = $video.find('video.wp-video-shortcode');

            if ($iframe.length > 0) {
                $iframe.attr('src', $iframe.attr('src'));
            }

            if ($wp_video.length > 0) {
                $wp_video[0].pause();
            }

            return false;

        });

        $video.find('.video-vimeo > iframe').attr('width', '100%').attr('height', 500);

        $thumbnail.find('li').on('click', '.i-video', function(e) {
            e.preventDefault();
            $(this).closest('li').find('img').trigger('click');
        });
    };


    /**
     * Modal product tabs
     */
    durotan.modalProductTabs = function() {
        var $product = $('div.product');
        durotan.$body
            .off("click", ".wc-tabs li a, ul.tabs li a")
            .on('click', '.woocommerce-tabs.panels-offscreen .wc-tabs li a', function(e) {
                e.preventDefault();
                var $el = $(this),
                    $wrapper = $el.closest('.wc-tabs-wrapper, .woocommerce-tabs'),
                    $tabs = $wrapper.find('.wc-tabs, ul.tabs'),
                    $panels = $wrapper.find('.panels'),
                    $target = $wrapper.find($el.attr('href')),
                    $scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

                $tabs.find('li').removeClass('active');
                $el.closest('li').addClass('active');
                $panels.find('.panel').show();
                $panels.fadeIn();
                $target.addClass('open');

                $wrapper.closest('.summary').css( 'position', 'static' );
                durotan.$body.addClass('offcanvas-opened');
                durotan.$body.css('padding-right', $scrollbarWidth + 'px');
            })
            .on('click', '.woocommerce-tabs .backdrop, .woocommerce-tabs .button-close', function(e) {
                e.preventDefault();

                var $wrapper = $(this).closest('.wc-tabs-wrapper, .woocommerce-tabs'),
                    $panels = $wrapper.find('.panels'),
                    $opened = $panels.find('.panel.open');

                $opened.removeClass('open');
                $wrapper.find('.tabs').children('li').removeClass('active');
                $panels.fadeOut();

                if ($opened.is('#tab-reviews')) {
                    $opened.find('#review_form_wrapper').fadeOut();
                    $opened.find('#comments').fadeIn();
                }

                $wrapper.closest('.summary').css( 'position', 'sticky' );
                durotan.$body.removeClass('offcanvas-opened');
                durotan.$body.removeAttr('style');
            })
            .on('keyup', function(e) {
                if (e.keyCode === 27) {
                    var $wrapper = $('.wc-tabs-wrapper, .woocommerce-tabs'),
                        $panels = $wrapper.find('.panels'),
                        $opened = $panels.find('.panel.open');

                    $opened.removeClass('open');
                    $wrapper.find('.tabs').children('li').removeClass('active');
                    $panels.fadeOut();

                    if ($opened.is('#tab-reviews')) {
                        $opened.find('#review_form_wrapper').fadeOut();
                        $opened.find('#comments').fadeIn();
                    }

                    $wrapper.closest('.summary').css( 'position', 'sticky' );
                    durotan.$body.removeClass('offcanvas-opened');
                }
            });

        // Remove active tab
        if (!window.location.hash) {
            $product.find('.wc-tabs, ul.tabs').first().find('li:first').removeClass('active');
        } else {
            $product.find('.wc-tabs, ul.tabs').first().find('li.active a').trigger('click');
        }
    };


    /**
     * Init slider for product gallery on mobile.
     */
    durotan.responsiveProductGallery = function () {

        if (typeof durotanData.product_gallery_slider === 'undefined') {
            return;
        }

        if (durotanData.product_gallery_slider || !$.fn.wc_product_gallery) {
            return;
        }

        var $window = $(window),
            $product = $('.woocommerce div.product'),
            default_flexslider_enabled = false,
            default_flexslider_options = {};

        if (!$product.length) {
            return;
        }

        var $gallery = $('.woocommerce-product-gallery', $product),
            $originalGallery = $gallery.clone(),
            $video = $gallery.find('.woocommerce-product-gallery__image.durotan-product-video'),
            sliderActive = false,
            $durotan_nav = $('.durotan-control-nav', $product);

        $originalGallery.children('.woocommerce-product-gallery__trigger').remove();

        // Turn off events then we init them again later.
        $originalGallery.off();

        if (typeof wc_single_product_params !== undefined) {
            default_flexslider_enabled = wc_single_product_params.flexslider_enabled;
            default_flexslider_options = wc_single_product_params.flexslider;
        }

        initProductGallery();
        $window.on('resize', initProductGallery);


        // Init product gallery
        function initProductGallery() {

            // Auto change background color
            if (!$product.hasClass('background-set') && durotan.data.product_auto_background === '1') {
                durotan.productBackgroundFromGallery($product);
            }
            if (window.innerWidth >= 992) {
                //Init zoom for product gallery images
                if ('1' === durotanData.product_image_zoom && $product.hasClass('layout-v3')) {
                    $gallery.find('.woocommerce-product-gallery__image').each(function () {
                        durotan.zoomSingleProductImage(this);
                    });
                }
                durotan.$body.trigger('durotan_product_sticky', $product);

                if (!sliderActive) {
                    return;
                }

                if (typeof wc_single_product_params !== undefined) {
                    wc_single_product_params.flexslider_enabled = default_flexslider_enabled;
                    wc_single_product_params.flexslider = default_flexslider_options;
                }

                // Destroy is not supported at this moment.
                $gallery.replaceWith($originalGallery.clone());
                $gallery = $('.woocommerce-product-gallery', $product);

                $gallery.each(function () {
                    $(this).wc_product_gallery();
                });

                $('form.variations_form select', $product).trigger('change');

                sliderActive = false;
            } else {
                if (sliderActive) {
                    return;
                }

                if (typeof wc_single_product_params !== undefined) {
                    wc_single_product_params.flexslider_enabled = true;
                    wc_single_product_params.flexslider.controlNav = true;
                }

                $gallery.replaceWith($originalGallery.clone());
                $gallery = $('.woocommerce-product-gallery', $product);
                $durotan_nav = $('.durotan-control-nav', $product);

                if ($durotan_nav.hasClass('flex-control-nav')){
                    $durotan_nav.remove();
                }
                durotan.$body.trigger('durotan_product_sticky', $product);

                if($('.woocommerce-product-gallery__image', $gallery).parent().is( ".product-image" )){
                    $('.woocommerce-product-gallery__image', $gallery).unwrap();
                    $('.woocommerce-product-gallery__image',$gallery).removeAttr('style');
                }

                $gallery.imagesLoaded(function() {
                    setTimeout(function () {
                            $gallery.each(function () {
                                $(this).wc_product_gallery();
                            });
                    }, 200);
                });

                $('form.variations_form select', $product).trigger('change');

                sliderActive = true;

                if ($video.length > 0) {
                    $('.woocommerce-product-gallery').addClass('has-video');
                }
            }
        }
    };

    /**
     * Related & ppsell products carousel.
     */
     durotan.relatedProductsCarousel = function() {
        var $related = $('.single-product:not(.product-v6) .products.related,.single-product:not(.product-v6) .products.upsells');

        if (!$related.length) {
            return;
        }
        console.log();
        $related.each(function(i) {
            var $this = $(this);
            var $products = $this.find('ul.products');

            $products.after('<span class="durotan-svg-icon swiper-button-next-' + i + ' durotan-button-next durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" ><path d="M 23.199219 14.601562 L 9.550781 0.25 C 9.25 -0.0507812 8.800781 -0.0507812 8.5 0.199219 C 8.199219 0.5 8.199219 0.949219 8.449219 1.25 L 22.101562 15.648438 C 22.300781 15.851562 22.300781 16.148438 22.101562 16.351562 L 8.449219 30.75 C 8.148438 31.050781 8.199219 31.550781 8.5 31.800781 C 8.648438 31.949219 8.851562 32 9 32 C 9.199219 32 9.398438 31.898438 9.550781 31.75 L 23.199219 17.351562 C 23.898438 16.601562 23.898438 15.398438 23.199219 14.601562 Z M 23.199219 14.601562 "/></svg></span>');
            $products.after('<span class="durotan-svg-icon swiper-button-prev-' + i + ' durotan-button-prev durotan-swiper-button"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" ><path d="M 9.898438 16.351562 C 9.699219 16.148438 9.699219 15.851562 9.898438 15.648438 L 23.550781 1.25 C 23.800781 0.949219 23.800781 0.5 23.5 0.199219 C 23.199219 -0.101562 22.699219 -0.0507812 22.449219 0.25 L 8.800781 14.601562 C 8.101562 15.351562 8.101562 16.601562 8.800781 17.351562 L 22.449219 31.75 C 22.601562 31.898438 22.800781 32 23 32 C 23.199219 32 23.351562 31.949219 23.5 31.800781 C 23.800781 31.5 23.800781 31.050781 23.550781 30.75 Z M 9.898438 16.351562 "/></svg></span>');
            $products.wrap('<div class="swiper-container linked-products-carousel" style="opacity: 0;"></div>');
            $products.after('<div class="swiper-scrollbar"></div>');
            $products.addClass('swiper-wrapper');
            $products.find('li.product').addClass('swiper-slide');

            new Swiper($this.find('.linked-products-carousel'), {
                loop: false,
                watchOverflow: true,
                scrollbar: {
                    el: '.swiper-scrollbar',
                    hide: false,
                    draggable: true,
                },
                on: {
                    init: function() {
                        this.$el.css('opacity', 1);
                    }
                },
                navigation: {
                    nextEl: '.swiper-button-next-' + i,
                    prevEl: '.swiper-button-prev-' + i,
                },
                breakpoints: {
                    300: {
                        slidesPerView: durotan.data.mobile_portrait == '' ? 2 : durotan.data.mobile_portrait,
                        slidesPerGroup: durotan.data.mobile_portrait == '' ? 2 : durotan.data.mobile_portrait,
                        spaceBetween: 10,
                    },
                    480: {
                        slidesPerView: durotan.data.mobile_landscape == '' ? 3 : durotan.data.mobile_landscape,
                        slidesPerGroup: durotan.data.mobile_landscape == '' ? 3 : durotan.data.mobile_landscape,
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        spaceBetween: 20,
                        slidesPerView: 3,
                        slidesPerGroup: 3
                    },
                    1200: {
                        spaceBetween: 30,
                        slidesPerView: 4,
                        slidesPerGroup: 4
                    },
                    1440: {
                        slidesPerView: $related.closest('.single-product').hasClass('product-v7') ? 4 : 5,
                        slidesPerGroup: $related.closest('.single-product').hasClass('product-v7') ? 4 : 5,
                        spaceBetween: $related.closest('.single-product').hasClass('product-v7') ? 30 : 50,
                    }
                }
            });
        });
    };

    /**
     * Related & ppsell products carousel v2.
     */
    durotan.relatedProductsCarouselv2 = function() {
        if (typeof Swiper === 'undefined') {
            return;
        }

        var $related = $('.single-product.product-v6 .products.related,.single-product.product-v6 .products.upsells');

        if (!$related.length) {
            return;
        }
        $related.each(function(i) {
            var $this = $(this);
            var $products = $this.find('ul.products');
            if(durotan.data.swiper_message !== ''){
                $products.after('<span class="durotan-swiper-message">' + durotan.data.swiper_message + '</div>');
            }
            $products.wrap('<div class="durotan-swiper-container swiper-container linked-products-carousel" style="opacity: 0;"></div>');
            $products.after('<div class="swiper-pagination"></div>');
            $products.addClass('swiper-wrapper');
            $products.find('li.product').addClass('swiper-slide');

            new Swiper($this.find('.linked-products-carousel'), {
                loop: false,
                spaceBetween: 30,
                speed: 800,
                watchOverflow: true,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                    clickable: true,
                    renderBullet: function(index, className) {
                        return '<span class="' + className + '"><span></span></span>';
                    }
                },
                on: {
                    init: function() {
                        this.$el.css('opacity', 1);
                    }
                },
                breakpoints: {
                    300: {
                        slidesPerView: durotan.data.mobile_portrait == '' ? 2 : durotan.data.mobile_portrait,
                        slidesPerGroup: durotan.data.mobile_portrait == '' ? 2 : durotan.data.mobile_portrait,
                        spaceBetween: 10,
                    },
                    480: {
                        slidesPerView: durotan.data.mobile_landscape == '' ? 3 : durotan.data.mobile_landscape,
                        slidesPerGroup: durotan.data.mobile_landscape == '' ? 3 : durotan.data.mobile_landscape,
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        spaceBetween: 20,
                        slidesPerView: 3,
                        slidesPerGroup: 3
                    },
                    992: {
                        spaceBetween: 30,
                        slidesPerView: 4,
                        slidesPerGroup: 4
                    },
                    1440: {
                        slidesPerView: 'auto',
                    }
                }
            });
        });
    };

    /**
     * Zoom an image.
     * Copy from WooCommerce single-product.js file.
     */
    durotan.zoomSingleProductImage = function(zoomTarget) {
        if (typeof wc_single_product_params == 'undefined' || !$.fn.zoom) {
            return;
        }

        var $target = $(zoomTarget),
            width = $target.width(),
            zoomEnabled = false;

        $target.each(function(index, target) {
            var $image = $(target).find('img');

            if ($image.data('large_image_width') > width) {
                zoomEnabled = true;
                return false;
            }
        });

        // Only zoom if the img is larger than its container.
        if (zoomEnabled) {
            var zoom_options = $.extend({
                touch: false
            }, wc_single_product_params.zoom_options);

            if ('ontouchstart' in document.documentElement) {
                zoom_options.on = 'click';
            }

            $target.trigger('zoom.destroy');
            $target.zoom(zoom_options);
        }
    }

    /**
	 * Add preloader
	 */
    durotan.preload = function () {
        var $preloader = $( '#durotan-preloader' );

        durotan.$window.load(function(){
            if ( ! $preloader.length ) {
                return;
            }
            $preloader.fadeOut( 500 );
        });

		var ignorePreloader = false;

		durotan.$body.on( 'click', 'a[href^=mailto], a[href^=tel]', function() {
			ignorePreloader = true;
		} );

		durotan.$window.on( 'beforeunload', function() {
			if ( ! ignorePreloader ) {
				$preloader.fadeIn( 'slow' );
			}

			ignorePreloader = false;
		} );


	};


    /**
     * Init sticky add to cart
     */
     durotan.stickyATC = function () {
        var $selector = $('#durotan-sticky-add-to-cart'),
            $btn = $selector.find('.durotan-sticky-add-to-cart__content-button');

        if (!$selector.length) {
            return;
        }

        if (!$('div.product .entry-summary form.cart').length) {
            return;
        }

        var headerHeight = 0,
            cartHeight;

        if (durotan.$body.hasClass('admin-bar')) {
            headerHeight += 32;
        }

        function stickyAddToCartToggle() {
            cartHeight = $('.entry-summary form.cart').offset().top + $('.entry-summary form.cart').outerHeight() - headerHeight;
            if (window.pageYOffset > cartHeight) {
                $selector.addClass('open');
            } else {
                $selector.removeClass('open');
            }
        }

        durotan.$window.on('scroll', function () {
            stickyAddToCartToggle();
        }).trigger('scroll');

        if (!$btn.hasClass('ajax_add_to_cart')) {
            $btn.on('click', function (event) {
                event.preventDefault();

                $('html,body').stop().animate({
                    scrollTop: $(".entry-summary").offset().top
                },'slow');
            });
        }
        var $summary = $('div.product .entry-summary'),
            $footer = $('.site-footer'),
            $atc_wrap = $selector.find('.product-button-wrapper');

        var mq = window.matchMedia( "(min-width: 992px)" );
        function changeAtcWrap(x) {
            if (x.matches) { // If media query matches
                $atc_wrap.css('width', $summary.width());
                $selector.find('.durotan-sticky-add-to-cart__content-button').css('min-width', $summary.width());
            } else {
                $atc_wrap.removeAttr('style');
                $selector.find('.durotan-sticky-add-to-cart__content-button').removeAttr('style');
            }
        }
        changeAtcWrap(mq);
        $(window).on( 'resize', function() {
            changeAtcWrap(mq);
            $footer.css('padding-bottom', $selector.outerHeight());
        });


        if ($.fn.selectDropdown) {
            $('#durotan-sticky-add-to-cart .variations ').find('select').selectDropdown();
            $('#durotan-sticky-add-to-cart .variations_form').on('woocommerce_variation_has_changed', function () {
                $(this).find('select').selectDropdown();
            });
        }
    };

    $.fn.selectDropdown = function( options ) {
        var defaults = {
			maxItems: 4,
            height:34,
		};
        options = $.extend( defaults, options );

        return this.each( function() {
            var $select = $( this ),
                $wrap = $select.closest( 'tr' ),
                $value = $select.closest( '.value' ),
                $label = $wrap.find('.label'),
                $dropdown = $( '<ul/>' ),
                $current_value =  $( '<span class="current" />' ),
                $wcboost = $value.find('.wcboost-variation-swatches'),
                current = $select.val(),
                current_text = $select.find("option:selected").text(),
                min = 1,
				max = $select.find('option.enabled').length,
                maxItems = options.maxItems,
                height = 34,
                values = [],
				visible = [];

            // if already initialized.
			if ( $wrap.data( 'selectDropdown' ) ) {
                $wrap.off();
                $wrap.find('.select-dropdown').remove();
			}

            if ( max < maxItems ) {
				maxItems = max;
			}
            if(current){
                if(!$wcboost.is('.wcboost-variation-swatches--color')){
                    $label.show();
                }
            }else{
                $('<span class="value none" />').html(current_text).appendTo($current_value);
                $label.hide();
            }

            $select.find('option.enabled').each(function() {
                var $el  = $(this),
                    val  = $el.val(),
                    html = $('<li data-value="' + val + '" />').html($el.text());

                if( $wcboost.length && $wcboost.find('.wcboost-variation-swatches__item[data-value="' + val + '"]').length){
                    var $swatch = $wcboost.find('.wcboost-variation-swatches__item[data-value="' + val + '"]').clone();
                    if($wcboost.is('.wcboost-variation-swatches--color,.wcboost-variation-swatches--image')){
                        $swatch.off().removeClass('wcboost-variation-swatches__item');
                        if($wcboost.is('.wcboost-variation-swatches--image')){
                            $swatch.find('.wcboost-variation-swatches__name').remove();
                        }
                        var $content = $("<span />").html($swatch.contents());
                        $.each($swatch.prop("attributes"), function() {
                            $content.attr(this.name, this.value);
                        });
                        html = html.prepend($content.clone());
                    }
                }
                if (current == val) {
					html.addClass('active');
                    if($wcboost.is('.wcboost-variation-swatches--color')){
                        $content.clone().addClass('swatch-color').prependTo( $current_value );
                        $('<span class="value" />').html($el.text()).appendTo($current_value);
                    }else if($wcboost.is('.wcboost-variation-swatches--image')){
                        $content.clone().addClass('swatch-image').prependTo( $current_value );
                    }else{
                        $('<span class="value" />').html($el.text()).appendTo($current_value);
                    }
				}

                html.appendTo($dropdown)
				values.push( val );
            });

            var $selectDropdown = $('<div class="select-dropdown"/>')
                                .append($current_value)
                                .append($dropdown).appendTo($value);
            $dropdown.wrap( '<div class="dropdown-options"/>' );
            $wrap.data( 'selectDropdown', options );

            $wrap
				.on('click', '.label label, .select-dropdown .current', function (e) {
                    e.preventDefault();
					var $el = $(this),
                        $dropdown = $el.closest('tr').find('.dropdown-options');
					current = $el.data('value');

                    scroll( current );

                    if(values.length > maxItems){
                        $dropdown.css( 'height', maxItems * height);
                    }

					$dropdown.fadeToggle();
				})
                .on('click', '.select-dropdown li', function (e) {
                    e.preventDefault();
                    var $el = $(this);

					current = $el.data('value');

                    if($el.hasClass('active')){
                        $el.closest( '.dropdown-options' ).fadeOut();
                        return;
                    }

                    $el.addClass('active').siblings('.active').removeClass('active');

					$el.closest( '.dropdown-options' ).fadeOut();

                    if( $wcboost.length &&  $wcboost.find('.wcboost-variation-swatches__item[data-value="' + current + '"]').length){
                        $wcboost.find('.wcboost-variation-swatches__item[data-value="' + current + '"]').trigger('click');
                    }else{
                        $el.closest('.value').find('select').val(current).trigger( 'change' );
                    }
                })

            // Scroll & swipe event.
			$wrap.find( '.select-dropdown .dropdown-options' )
                .on('wheel', function(event) {
                    if ( event.originalEvent.deltaY < 0 ) {
                        scroll( 'up' ); // Up
                    } else {
                        scroll( 'down' ); //Down
                    }
                    return false;
                }).on('swipeup', function() {
                    scroll( 'down' );
                }).on('swipedown', function() {
                    scroll('up');
                });

            // Close dropdown when click outsite.
			$(document.body).on('click', function (event) {
				if (!$wrap.is(event.target) && $wrap.has(event.target).length === 0) {
					$selectDropdown.find( '.dropdown-options' ).fadeOut();
				}
			});

            /**
			 * Scroll the options.
			 */
            function scroll( value ) {
				var minVisible = values.indexOf( visible[0] ),
					maxVisible = values.indexOf( visible[visible.length - 1]),
					distance = 0;

				if ( 'up' === value ) {
					if ( minVisible <= min - 1) {
						return;
					}
					distance = -(values.indexOf( visible[0] ) - 1)  * height;
					visible.pop();
					visible.unshift( values[minVisible - 1]);
				} else if ( 'down' === value ) {
					if ( maxVisible >= max - 1) {
						return;
					}
					distance = -values.indexOf( visible[1] ) * height;
					visible.shift();
					visible.push( values[maxVisible + 1] );
				} else {
					var index = values.indexOf( value ),
						middle = Math.floor( maxItems/2 );

					if ( index > middle ) {
						index = index - middle;
					} else {
						index = 0;
					}
					// Reset visible.
					visible = [];
					for ( var i = index; i < index + maxItems; i++ ) {
						if ( i in values ) {
							visible.push(values[i]);
						}
					}

					distance = -index * height;
				}

				$dropdown.css( 'transform', 'translate3d(0, ' + distance + 'px, 0)' );
			}
        });
    };
    /**
     * Document ready
     */
    $(function() {
        durotan.init();
    });
})(jQuery);