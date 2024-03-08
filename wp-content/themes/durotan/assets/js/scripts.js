(function($) {
    'use strict';

    var durotan = durotan || {};
    durotan.init = function() {
        durotan.$body = $(document.body),
            durotan.$window = $(window),
            durotan.$header = $('#masthead');

        // Blog
        this.postsFeaturedCarousel();
        this.postsLatestCarousel();
        this.blogFilterAjax();
        this.blogLoadingAjax();

        // Product Loop
        this.addWishlist();
        this.addCompare();
        this.productThumbnailsSlider();
        this.productLoopATCForm();
        this.productQuickView();

        this.productLoaded();

        // Search
        this.toggleModals();
        this.instanceSearch();
        this.windowLoad();

        //Wishlist
        this.ajaxCounter();

        //productQuantity
        this.productQuantity();

        // Single Product
        this.reviewProduct();
        this.productLightBox();

        this.addToCartSingleAjax();
        this.buyNow();

        // Product Notification
        this.openMiniCartPanel();

        // Cart
        this.cartPageQuantity();
        this.updateQuantityAuto();

        // Account
        this.accountOrder();
        this.loginPanel();

        // Modules
        this.closeCampaignbar();
        this.navSmartDot();
        this.toggleOffCanvas();
        this.inputNumber();
        this.stickyHeader();

        // Menu
        this.menuSideBar();

        this.recentlyViewedProducts();

        // Style
		this.inlineStyle();
    };

    // Blog
    durotan.postsFeaturedCarousel = function() {
        if (typeof Swiper === 'undefined') {
            return;
        }

        var container = $('.durotan-featured-posts-carousel'),
            options = {
                loop: true,
                autoplay: false,
                delay: 800,
                speed: 1000,
                watchOverflow: true,
                lazy: false,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: container.find('.swiper-pagination'),
                    type: 'bullets',
                    clickable: true
                },
                on: {
                    init: function() {
                        container.css('opacity', 1);
                    }
                }
            };

        new Swiper(container, options);

        if ( container.hasClass('swiper-container-horizontal') ) {
            container.removeClass('swiper-container-horizontal');
        }

    };
    durotan.postsLatestCarousel = function() {
        if (typeof Swiper === 'undefined') {
            return;
        }

        var container = $('.durotan-latest-posts-carousel'),
            options = {
                slidesPerView: 2,
                slidesPerGroup: 2,
                spaceBetween: 60,
                loop: false,
                autoplay: false,
                delay: 800,
                speed: 1000,
                watchOverflow: true,
                lazy: false,
                navigation: {
                    nextEl: container.find('.durotan-posts-button-next'),
                    prevEl: container.find('.durotan-posts-button-prev'),
                },
                fadeEffect: {
                    crossFade: true
                },
                on: {
                    init: function() {
                        container.css('opacity', 1);
                    }
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                        slidesPerGroup: 1,
                        spaceBetween: 10,
                    },
                    480: {
                        slidesPerView: 1,
                        slidesPerGroup: 1,
                        spaceBetween: 10,
                    },
                    600: {
                        slidesPerView: 2,
                        slidesPerGroup: 1,
                        spaceBetween: 15,
                    },
                    992: {
                        slidesPerView: 2,
                        slidesPerGroup: 1,
                        spaceBetween: 60,
                    },
                }
            };

        new Swiper(container, options);
    };
    durotan.blogFilterAjax = function() {

        durotan.$body.find('.durotan-posts-header__taxs-list').on('click', 'a', function(e) {
            e.preventDefault();
            var btn = $(this),
                url = btn.attr('href');

            durotan.$body.trigger('durotan_blog_filter_ajax', url, $(this));

            durotan.$body.on('durotan_ajax_filter_request_success', function(e, btn) {
                $(this).addClass('selected');
            });
        });

        durotan.$body.on('durotan_blog_filter_ajax', function(e, url) {

            var $container = $('.durotan-posts-list'),
                $categories = $('.durotan-posts-header__taxs-list');

            $('.durotan-loading__background').addClass('show');

            if ('?' == url.slice(-1)) {
                url = url.slice(0, -1);
            }

            url = url.replace(/%2C/g, ',');

            history.pushState(null, null, url);

            if (durotan.ajaxXHR) {
                durotan.ajaxXHR.abort();
            }

            durotan.ajaxXHR = $.get(url, function(res) {
                $container.replaceWith($(res).find('.durotan-posts-list'));
                $categories.html($(res).find('.durotan-posts-header__taxs-list').html());

                $('.durotan-loading__background').removeClass('show');
                $('.durotan-posts-list .blog-wrapper').addClass('animated durotanFadeInUp');

                durotan.$body.trigger('durotan_ajax_filter_request_success', [res, url]);

            }, 'html');


        });
    };

    durotan.blogLoadingAjax = function() {

        durotan.$body.on('click', '#durotan-blog-previous-ajax a', function(e) {
            e.preventDefault();

            if ($(this).data('requestRunning')) {
                return;
            }

            $(this).data('requestRunning', true);

            var $posts = $(this).closest('#primary'),
                $postList = $posts.find('.durotan-posts-list'),
                $pagination = $(this).parents('.load-navigation');

            $pagination.addClass('loading');

            $.get(
                $(this).attr('href'),
                function(response) {
                    var content = $(response).find('.durotan-posts-list').children('.blog-wrapper');
                    if (content.length == 0) {
                        content = $(response).find('.durotan-posts-list').children('.post');
                    }
                    var $pagination_html = $(response).find('.load-navigation').html();
                    $pagination.addClass('loading');

                    $pagination.html($pagination_html);
                    $postList.append(content);
                    $pagination.find('a').data('requestRunning', false);
                    // Add animation class
                    for (var index = 0; index < content.length; index++) {
                        $(content[index]).css('animation-delay', index * 100 + 'ms');
                    }
                    content.addClass('durotanFadeInUp');
                    $pagination.removeClass('loading');
                }
            );
        });

    };

    // Product Loop
    durotan.addWishlist = function() {
        durotan.$body.on('click', '.yith-wcwl-add-to-wishlist a', function() {
            $(this).addClass('loading');
        });

        durotan.$body.on('added_to_wishlist', function(e) {
            e.preventDefault();
            $('ul.products li.product .yith-wcwl-add-button a').removeClass('loading');
        });
    };

    durotan.addCompare = function() {
        var $thisAddCompare = '';
        durotan.$body.on('click', 'a.compare:not(.added)', function(e) {
            e.preventDefault();

            var $el = $(this);
            $thisAddCompare = $(this);
            $el.addClass('loading');

            $el.closest('.product-loop_buttons').find('.compare:not(.loading)').trigger('click');

            var compare = false;

            if ($(this).hasClass('added')) {
                compare = true;
            }

            if (compare === false) {
                var compare_counter = durotan.$header.find('.mini-compare-counter').html();
                compare_counter = parseInt(compare_counter, 10) + 1;

                setTimeout(function() {
                    durotan.$header.find('.mini-compare-counter').html(compare_counter);
                    $el.removeClass('loading');
                }, 2000);
            }
        });

        durotan.$body.on('yith_woocompare_open_popup', function() {
            $('.yith_woocompare_colorbox #cboxClose').text('');
            $('.yith_woocompare_colorbox #cboxClose').append('<span class="durotan-svg-icon"><svg viewBox="0 0 64 64"><path d="M34.1,32L63.6,2.6c0.6-0.6,0.6-1.5,0-2.1c-0.6-0.6-1.5-0.6-2.1,0L32,29.9L2.6,0.4C2-0.1,1-0.1,0.4,0.4C-0.1,1-0.1,2,0.4,2.6L29.9,32L0.4,61.4c-0.6,0.6-0.6,1.5,0,2.1C0.7,63.9,1.1,64,1.5,64s0.8-0.1,1.1-0.4L32,34.1l29.4,29.4c0.3,0.3,0.7,0.4,1.1,0.4s0.8-0.1,1.1-0.4c0.6-0.6,0.6-1.5,0-2.1L34.1,32z"/></svg></span>');
            if ($thisAddCompare.hasClass('added')) {
                $thisAddCompare.html('<span class="durotan-svg-icon"><svg viewBox="0 0 32 32"><path d="M31.766 13.834c-0.312-0.312-0.819-0.312-1.131 0l-1.838 1.838c-0.082-3.296-1.405-6.383-3.745-8.724-2.418-2.418-5.632-3.749-9.051-3.749-4.759 0-9.098 2.616-11.323 6.826-0.206 0.391-0.057 0.875 0.333 1.081s0.875 0.057 1.081-0.333c1.948-3.685 5.745-5.974 9.909-5.974 6.063 0 11.016 4.843 11.194 10.863l-1.829-1.829c-0.312-0.312-0.819-0.312-1.131 0s-0.312 0.819 0 1.131l3.2 3.2c0.156 0.156 0.361 0.234 0.566 0.234s0.409-0.078 0.566-0.234l3.2-3.2c0.312-0.312 0.312-0.819 0-1.131zM26.99 20.893c-0.391-0.206-0.875-0.057-1.081 0.333-1.948 3.685-5.745 5.974-9.909 5.974-6.063 0-11.016-4.843-11.194-10.863l1.829 1.829c0.156 0.156 0.361 0.234 0.566 0.234s0.409-0.078 0.566-0.234c0.312-0.312 0.312-0.819 0-1.131l-3.2-3.2c-0.312-0.312-0.819-0.312-1.131 0l-3.2 3.2c-0.312 0.312-0.312 0.819 0 1.131s0.819 0.312 1.131 0l1.838-1.838c0.082 3.297 1.405 6.383 3.745 8.724 2.418 2.418 5.632 3.749 9.051 3.749 4.759 0 9.098-2.616 11.323-6.826 0.206-0.391 0.057-0.875-0.333-1.081zM16.8 20.8c-0.442 0-0.8-0.358-0.8-0.8v-7.2h-0.8c-0.442 0-0.8-0.358-0.8-0.8s0.358-0.8 0.8-0.8h1.6c0.442 0 0.8 0.358 0.8 0.8v8c0 0.442-0.358 0.8-0.8 0.8z"></path></svg></span><span class="loop_button-text">' + $thisAddCompare.text() + '</span>');
            }
        });

        $(document).find('.compare-list').on('click', '.remove a', function(e) {
            e.preventDefault();
            var compare_counter = $('.mini-compare-counter', window.parent.document).html();
            compare_counter = parseInt(compare_counter, 10) - 1;
            if (compare_counter < 0) {
                compare_counter = 0;
            }

            $('.mini-compare-counter', window.parent.document).html(compare_counter);

            $('a.compare').removeClass('loading');
        });

        $('.yith-woocompare-widget').on('click', 'li a.remove', function(e) {
            e.preventDefault();
            var compare_counter = durotan.$header.find('.mini-compare-counter').html();
            compare_counter = parseInt(compare_counter, 10) - 1;
            if (compare_counter < 0) {
                compare_counter = 0;
            }

            setTimeout(function() {
                durotan.$header.find('.mini-compare-counter').html(compare_counter);
            }, 2000);

        });

        $('.yith-woocompare-widget').on('click', 'a.clear-all', function(e) {
            e.preventDefault();
            setTimeout(function() {
                durotan.$header.find('.mini-compare-counter').html('0');
            }, 2000);
        });
    };

    durotan.productThumbnailsSlider = function() {

        var $selector = durotan.$body.find('ul.products .product-thumbnails--slider'),
            options = {
                loop: false,
                autoplay: false,
                speed: 800,
                watchOverflow: true,
                lazy: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    renderBullet: function(index, className) {
                        return '<span class="' + className + '">' + (index + 1) + "</span>";
                    },
                },
                breakpoints: {}
            };

        $selector.find('.woocommerce-loop-product__link').addClass('swiper-slide');

        durotan.$body.find('ul.products').imagesLoaded(function() {
            setTimeout(function() {
                $selector.each(function() {
                    options.navigation = {
                        nextEl: $(this).find('.durotan-product-loop-swiper-next'),
                        prevEl: $(this).find('.durotan-product-loop-swiper-prev'),
                    }
                    new Swiper($(this), options);
                });
            }, 200);
            setTimeout(function() {
                    durotan.$body.find('.durotan-shop-content').removeClass('loading');
            }, 400);
        });
    };

    durotan.productLoopATCForm = function() {

        durotan.$body.on('change', 'input[name="variation_id"]', function(e) {
            var variation = $(this).val(),
                product_id = $(this).parent().find('input[name="product_id"]').val(),
                $this, $this_remove;

            $.ajax({
                url: durotanData.ajax_url.toString().replace('%%endpoint%%', 'durotan_product_loop_form'),
                type: 'POST',
                data: {
                    nonce: durotanData.nonce,
                    variation_id: variation,
                    product_id: product_id
                },
                beforeSend: function() {
                    durotan.$body.find('input[value="' + product_id + '"]').closest('li.product').find('.price').removeClass('show-price');
                    durotan.$body.find('input[value="' + product_id + '"]').closest('li.product').find('.price .price-html-variation').remove();
                },
                success: function(response) {
                    $this = durotan.$body.find('input[value="' + variation + '"]').closest('li.product').find('.product-thumbnail a:first-child .product-loop__variation--image');
                    $this_remove = durotan.$body.find('input[value="' + product_id + '"]').closest('li.product').find('.product-thumbnail a:first-child .product-loop__variation--image');

                    if (response.data) {
                        $this.closest('.product-thumbnail').find('.swiper-pagination > span:first-child').click();
                        $this.html(response.data[0].variation);
                        $this.closest('li.product').find('.price').addClass('show-price');
                        $this.closest('li.product').find('.price').append('<span class="price-html-variation" style="display: flex;">' + response.data[0].price_variation + '</span>');
                    } else {
                        $this_remove.html('');
                        $this.closest('li.product').find('.price').removeClass('show-price');
                        $this_remove.closest('li.product').find('.price .price-html-variation').remove();
                    }
                }
            })
        });

        durotan.$body.on('click', 'a.product_type_variable', function(e) {
            if( $(this).closest('li.product').find('.variations_form .single_add_to_cart_button').length > 0 ) {
                e.preventDefault();
                $(this).closest('li.product').find('.variations_form .single_add_to_cart_button').trigger('click');
            }
        });

        durotan.$body.on('click', 'li.product .variations_form .single_add_to_cart_button', function(e) {
            e.preventDefault();
            var $this = $(this),
                $cartForm = $this.closest('.variations_form'),
                $cartButtonLoading = $this.closest('li.product').find('a.product_type_variable');

            if ($(this).is('.disabled')) {
                return;
            }

            durotan.addToCartFormAJAX($this, $cartForm, $cartButtonLoading);

            return false;
        });
    };

    durotan.addToCartSingleAjax = function() {

        var $selector = $('div.product, .durotan-sticky-add-to-cart');

        if ($selector.length < 1) {
            return;
        }

        if (!$selector.hasClass('product-add-to-cart-ajax')) {
            return;
        }

        $selector.find('form.cart').on('click', '.single_add_to_cart_button', function(e) {
            var $el = $(this),
                $cartForm = $el.closest('form.cart');

            if ($el.closest('.product').hasClass('product-type-external')) {
                return;
            }

            if ( $el.hasClass( 'has-buy-now' ) ) {
				return;
			}

            if ($el.is('.disabled')) {
                return;
            }

            if ($cartForm.length > 0) {
                e.preventDefault();
            } else {
                return;
            }

            durotan.addToCartFormAJAX($el, $cartForm, $el);
        });

    };

    durotan.buyNow = function() {
        durotan.$body.on( 'click', 'form.cart .durotan-buy-now-button', function ( e ) {
			e.preventDefault();
			var $form = $( this ).closest( 'form.cart' ),
                $submit_btn  = $form.find( '[type="submit"]' ),
				is_disabled = $( this ).is( ':disabled' );

			if ( is_disabled ) {
				$('html, body').animate( {
						scrollTop: $submit_btn.offset().top - 200
					}, 900
				);
			} else {
				$form.append( '<input type="hidden" value="1" name="durotan_buy_now" />' );
				$form.find( '.single_add_to_cart_button' ).addClass( 'has-buy-now' );
				$form.find( '.single_add_to_cart_button' ).trigger( 'click' );
			}
		} );
    }

    durotan.addToCartFormAJAX = function($cartButton, $cartForm, $cartButtonLoading) {

        if ($cartButton.data('requestRunning')) {
            return;
        }

        $cartButton.data('requestRunning', true);

        var found = false;

        if ($cartButtonLoading.hasClass('loading')){
            return;
        }
        $cartButtonLoading.addClass('loading');

        if (found) {
            return;
        }
        found = true;

        var formData = $cartForm.serializeArray(),
            formAction = $cartForm.attr('action');

        if ($cartButton.val() != '') {
            formData.push({ name: $cartButton.attr('name'), value: $cartButton.val() });
        }

        $(document.body).trigger('adding_to_cart', [$cartButton, formData]);

        $.ajax({
            url: formAction,
            method: 'post',
            data: formData,
            error: function() {
                window.location = formAction;
            },
            success: function(response) {

                if (!response) {
                    window.location = formAction;
                }

                if (typeof wc_add_to_cart_params !== 'undefined') {
                    if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                        window.location = wc_add_to_cart_params.cart_url;
                        return;
                    }
                }

                if ($(response).find('.woocommerce-message').length > 0) {
                    $(document.body).trigger('wc_fragment_refresh');
                }

                $cartButton.data('requestRunning', false);
                $cartButton.removeClass('loading');
                $cartButtonLoading.removeClass('loading');
                if ($cartButton.closest('.quick-view-modal').length > 0) {
                    $cartButton.closest('.quick-view-modal').removeClass('loading loaded open');
                    $(document.body).removeAttr('style').removeClass('modal-opened quick-view-modal-opened body-modals');
                }
                found = false;
            }
        });
    };

    /**
     * Quick view modal.
     */
    durotan.productQuickView = function() {
        $(document.body).on('click', '.quick-view-button', function(event) {
            event.preventDefault();

            var $el = $(this),
                product_id = $el.data('id'),
                $target = $('#quick-view-modal'),
                $container = $target.find('.woocommerce'),
                ajax_url = durotanData.ajax_url.toString().replace('%%endpoint%%', 'product_quick_view');

            $target.addClass('loading').removeClass('loaded');
            $container.find('.product').html('');

            $.post(
                ajax_url, {
                    action: 'durotan_get_product_quickview',
                    product_id: product_id,
                },
                function(response) {
                    $container.find('.product').replaceWith(response.data);

                    if (response.success) {
                        update_quickview();
                    }

                    $target.removeClass('loading').addClass('loaded');
                    durotan.addToCartSingleAjax();
                    durotan.$body.trigger('durotan_product_quick_view_loaded');

                    if ($container.find('.deal-expire-countdown').length > 0) {
                        $(document.body).trigger('durotan_countdown', [$('.deal-expire-countdown')]);
                    }
                }
            );

            /**
             * Update quick view common elements.
             */
            function update_quickview() {
                var $product = $container.find('.product'),
                    $gallery = $product.find('.woocommerce-product-gallery'),
                    $variations = $product.find('.variations_form'),
                    $size_guide = $product.find('.size-guide-modal');

                if( $size_guide.length ){
                    $container.siblings('.size-guide-modal').remove();
                    $size_guide.insertAfter($container);
                }
                // Prevent clicking on gallery image link.
                $gallery.on('click', '.woocommerce-product-gallery__image a', function(event) {
                    event.preventDefault();
                });

                $gallery.removeAttr('style');

                if ($gallery.find('.woocommerce-product-gallery__wrapper').children().length > 1) {
                    $gallery.addClass('swiper-container');
                    $gallery.find('.woocommerce-product-gallery__wrapper').addClass('swiper-wrapper');
                    $gallery.find('.woocommerce-product-gallery__image').addClass('swiper-slide');
                    $gallery.after('<span class="durotan-svg-icon durotan-quickview-button-prev durotan-swiper-button"><svg	viewBox="0 0 64 64"><path d="M19.8,32.7c-0.4-0.4-0.4-1,0-1.4L47.1,2.5C47.6,1.9,47.6,1,47,0.4s-1.6-0.5-2.1,0.1L17.6,29.2c-1.4,1.5-1.4,4,0,5.5 l27.3,28.8c0.3,0.3,0.7,0.5,1.1,0.5c0.4,0,0.7-0.1,1-0.4c0.6-0.6,0.6-1.5,0.1-2.1L19.8,32.7z"/></svg></span>');
                    $gallery.after('<span class="durotan-svg-icon durotan-quickview-button-next durotan-swiper-button"><svg viewBox="0 0 64 64"><path d="M46.4,29.2L19.1,0.5c-0.6-0.6-1.5-0.6-2.1-0.1c-0.6,0.6-0.6,1.5-0.1,2.1l27.3,28.8c0.4,0.4,0.4,1,0,1.4L16.9,61.5c-0.6,0.6-0.5,1.6,0.1,2.1c0.3,0.3,0.7,0.4,1,0.4c0.4,0,0.8-0.2,1.1-0.5l27.3-28.8C47.8,33.2,47.8,30.8,46.4,29.2z"/></svg></span>');
                    $gallery.after('<div class="durotan-product-pagination"></div>');

                    var options = {
                        loop: false,
                        autoplay: false,
                        speed: 800,
                        watchOverflow: true,
                        navigation: {
                            nextEl: '.durotan-quickview-button-next',
                            prevEl: '.durotan-quickview-button-prev',
                        },
                        pagination: {
                            el: '.durotan-product-pagination',
                            type: 'bullets',
                            clickable: true,
                        },
                        on: {
                            init: function() {
                                $gallery.css('opacity', 1);
                            }
                        },
                    };

                    $gallery.imagesLoaded(function() {
                        new Swiper($gallery, options);
                    });
                }

                if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                    $variations.find('td.value select').each(function() {
                        $(this).on('change', function() {
                            var value = $(this).find('option:selected').text();
                            $(this).closest('tr').find('td.label .durotan-attr-value').html(value);
                            $(this).closest('tr').find('td.label .durotan-attr-value').data('value',$(this).find('option:selected').val())

                            if ( ( $(this).closest('.variation-selector.hidden').length || $(this).closest('.wcboost-variation-swatches').length ) && $(this).closest('.entry-summary').length) {
                                $(this).closest('tr').find('td.label').show();
                            }
                        }).trigger('change');
                    });

                    $variations.each(function() {
                        $(this).wc_variation_form();
                    });
                }

                if (typeof $.fn.tawcvs_variation_swatches_form !== 'undefined') {
                    $variations.tawcvs_variation_swatches_form();
                }

                if (typeof $.fn.wcboost_variation_swatches !== 'undefined') {
                    $variations.wcboost_variation_swatches();
                }

            }

        });
    };

    // Search
    durotan.toggleModals = function() {
        $(document.body).on('click', '[data-toggle="modal"]', function(event) {
            var target = '#' + $(this).data('target');

            if ($(target).hasClass('open')) {
                durotan.closeModal(target);
            } else if (durotan.openModal(target)) {
                event.preventDefault();
            }
        }).on('click', '.modal__button-close, .modal__backdrop', function(event) {
            event.preventDefault();

            durotan.closeModal(this);
        }).on('keyup', function(e) {
            if (e.keyCode === 27) {
                durotan.closeModal();
            }
        });
    };

    /**
     * Open a modal.
     *
     * @param string target
     */
    durotan.openModal = function(target) {
        var $target = $(target);

        if (!$target.length) {
            return false;
        }

        $target.fadeIn();
        $target.addClass('open');

        var widthScrollBar = window.innerWidth - document.documentElement.clientWidth;
        $(document.body).css({ 'padding-right': widthScrollBar, 'overflow': 'hidden' });
        $('.header-transparent .site-header,.durotan-sticky-add-to-cart').css({ 'right': widthScrollBar });

        $(document.body).addClass('modal-opened ' + $target.attr('id') + '-opened').trigger('durotan_modal_opened', [$target]);
        $('body').addClass('body-modals');

        return true;
    }

    /**
     * Close a modal.
     *
     * @param string target
     */
    durotan.closeModal = function(target) {
        if (!target) {
            $('.modal').removeClass('open').fadeOut(150);

            $('.modal').each(function() {
                var $modal = $(this);

                if (!$modal.hasClass('open')) {
                    return;
                }

                $modal.removeClass('open').fadeOut(150);
                $(document.body).removeClass($modal.attr('id') + '-opened');
            });
        } else {
            target = $(target).closest('.modal');
            target.removeClass('open').fadeOut();

            $(document.body).removeClass(target.attr('id') + '-opened');
        }

        $(document.body).removeAttr('style');
        $('.header-transparent .site-header').removeAttr('style');

        $(document.body).removeClass('modal-opened').trigger('durotan_modal_closed', [target]);
        $('body').removeClass('body-modals');
    }

    /**
     * Product instance search
     */
    durotan.instanceSearch = function() {

        if (durotanData.header_ajax_search != '1') {
            return;
        }

        var $modal = $('#search-modal, .header-search-form');

        var xhr = null,
            searchCache = {},
            $form = $modal.find('form');

        $modal.on('keyup', '.search-field', function(e) {
            var valid = false;

            if (typeof e.which == 'undefined') {
                valid = true;
            } else if (typeof e.which == 'number' && e.which > 0) {
                valid = !e.ctrlKey && !e.metaKey && !e.altKey;
            }

            if (!valid) {
                return;
            }

            if (xhr) {
                xhr.abort();
            }

            $modal.find('.result-list-found, .result-list-not-found').html('');
            $modal.find('.search-result__label').addClass('not-found');

            var $currentForm = $(this).closest('.form-search');
            $('.durotan-loading__background').addClass('show');
            $currentForm.removeClass('searching searched actived found-products found-no-product invalid-length');

            search($currentForm);
        }).on('click', '.durotan-product-cats_search__taxs-list a', function() {
            $('.durotan-product-cats_search__taxs-list li').removeClass('active');
            $(this).parent().addClass('active');
            $form.removeClass('actived');
            $('.durotan-loading__background').addClass('show');

            if (xhr) {
                xhr.abort();
            }

            var catSlug = $(this).attr('data-catslug');
            if (catSlug == undefined) {
                catSlug = 0;
            }
            $form.find('input[name="product_cat"]').val(catSlug);

            $modal.find('.result-list-found').html('');
            $modal.find('.search-result__label').addClass('not-found');

            var $currentForm = $(this).closest('.form-search');

            search($currentForm);
            return false;
        }).on('click', '.search-reset', function() {

            if (xhr) {
                xhr.abort();
            }

            $form.removeClass('searching actived found-products found-no-product invalid-length');
            $modal.find('.result-list-found').html('');
            $modal.find('.search-result__label').addClass('not-found');
        }).on('click', '.modal__button-close', function() {

            if (xhr) {
                xhr.abort();
            }

            $('.durotan-product-cats_search__taxs-list li').removeClass('active');
            $('.durotan-product-cats_search__taxs-list li:first-child').addClass('active');

            $form.removeClass('searching actived found-products found-no-product invalid-length');
            $form.find('.search-field').val('');

            $modal.find('.result-list-found').html('');
            $modal.find('.search-result__label').addClass('not-found');
        });

        $(document).on('click', function(e) {
            if ($('#search-modal').find('.form-search').hasClass('actived')) {
                return;
            }
            var target = e.target;

            if ($(target).closest('.products-search').length < 1) {
                $form.removeClass('searching actived found-products found-no-product invalid-length');
            }
        });

        /**
         * Private function for search
         */
        function search($currentForm) {
            var $search = $currentForm.find('input.search-field'),
                keyword = $search.val(),
                cat = 0,
                $results = $currentForm.parent().find('.search-result__items');

            if ($modal.hasClass('search-modal')) {
                $results = $modal.find('.search-result__items');
            }

            if ($currentForm.find('.durotan-product-cats_search__taxs-list').length > 0) {
                cat = $currentForm.find('.durotan-product-cats_search__taxs-list .active a').attr('data-catslug');
            }


            if (keyword.trim().length < 2) {
                $currentForm.removeClass('searching found-products found-no-product').addClass('invalid-length');
                $('.durotan-loading__background').removeClass('show');
                return;
            }

            $currentForm.removeClass('found-products found-no-product').addClass('searching');

            var keycat = keyword + cat,
                url = $form.attr('action') + '?' + $form.serialize();

            if (keycat in searchCache) {
                var result = searchCache[keycat];

                $currentForm.removeClass('searching');
                $currentForm.addClass('found-products');
                $results.html(result.products);


                $(document.body).trigger('durotan_ajax_search_request_success', [$results]);

                $currentForm.removeClass('invalid-length');
                $currentForm.addClass('searched actived');
                $('.durotan-loading__background').removeClass('show');
            } else {
                var data = {
                        'term': keyword,
                        'cat': cat,
                        'ajax_search_number': durotanData.header_search_number,
                        'search_type': durotanData.search_content_type
                    },
                    ajax_url = durotanData.ajax_url.toString().replace('%%endpoint%%', 'durotan_instance_search_form');

                xhr = $.post(
                    ajax_url,
                    data,
                    function(response) {
                        var $products = response.data;

                        $currentForm.removeClass('searching');
                        $currentForm.addClass('found-products');
                        $results.html($products);
                        $currentForm.removeClass('invalid-length');


                        $(document.body).trigger('durotan_ajax_search_request_success', [$results]);

                        // Cache
                        searchCache[keycat] = {
                            found: true,
                            products: $products
                        };

                        $results.find('.search-result__view-more a').attr('href', url);
                        $('.durotan-loading__background').removeClass('show');
                        $currentForm.addClass('searched actived');
                    }
                );
            }
        }
    }

    durotan.windowLoad = function() {
        $(window).load(function() {
            $('.durotan-product-cats_search__taxs-list li').removeClass('active');
            $('.durotan-product-cats_search__taxs-list li:first-child').addClass('active');

            if ( durotanData.footer_parallax == 1 ) {
                var fHeight = $('.site-footer').outerHeight();

                durotan.$body.css('padding-bottom', fHeight);

                if (durotan.$body.hasClass('footer-has-parallax')) {

                    var hHeader = $('.site-header').height(),
                        pHeader = $('.page-header').height();

                    durotan.$window.on('scroll', function () {
                        if (durotan.$window.scrollTop() > ( hHeader + pHeader ) ) {
                            $('.site-footer').addClass('active');
                        } else {
                            $('.site-footer').removeClass('active');
                        }
                    });
                }
            }
        });

        $(window).on( 'load resize', function() {
            if( durotanData.product_loop_layout != 3 ) {
                return;
            }

            var wH = $('.product-loop-layout-3 li.product');

            $(wH).each(function() {

                var Hw = $(this).find('form.variations_form').outerHeight();

                $(this).find('form.variations_form').closest('.woocommerce-details').css( 'transform', 'translateY(-' + Hw + 'px)');

            });

        });
    }

    // Wishlist
    durotan.ajaxCounter = function() {
        $(document).on('added_to_wishlist removed_from_wishlist', function() {
            var ajax_url = durotanData.ajax_url.toString().replace('%%endpoint%%', 'update_wishlist_count');
            $.get(
                ajax_url,
                function(data) {
                    $('.header-wishlist__counter').html(data.count);
                }
            );
        });
    }

    // Modules
    durotan.closeCampaignbar = function() {
        durotan.$body.on('click', '.durotan-close-campaign-bar', function() {
            $(this).closest('.durotan-campaign-bar').slideUp();
        });
    }

    durotan.navSmartDot = function() {
        var $header = $('.site-header'),
            $mainNav = $('.primary-menu', $header),
            $menu = $('ul.menu', $mainNav);

        if (!durotan.$body.hasClass('header-has-smart-dot')) {
            return;
        }

        if (!$menu.length) {
            return;
        }

        $menu.append('<li class="durotan-menu-item__dot"></li>');

        var $smartDot = $('.durotan-menu-item__dot'),
            $menuItem = $menu.children(),
            $currentMenuItem = $menu.children('li.current-menu-item');

        if ($currentMenuItem.length == 0) {
            $currentMenuItem = $menu.children('li.current-menu-parent');

            if ($currentMenuItem.length == 0) {
                $currentMenuItem = $menu.children('li.menu-item-has-children');
            }

            if ($currentMenuItem.length == 0) {
                $currentMenuItem = $menu.children('li.current_page_parent');
            }

            if ($currentMenuItem.length == 0) {
                $currentMenuItem = $menu.children('li').first();
            }
        }

        var oriPosLeft = $currentMenuItem.position().left + $currentMenuItem.outerWidth() / 2 - $smartDot.outerWidth() / 2;

        $smartDot
            .data('left', oriPosLeft)
            .css('left', oriPosLeft);

        $menuItem.mouseover(function() {
            var $el = $(this),
                posLeft = $el.position().left + $el.outerWidth() / 2 - $smartDot.outerWidth() / 2;

            $smartDot.css('left', posLeft);
        });

        $menuItem.mouseleave(function() {
            var posLeft = $currentMenuItem.position().left + $currentMenuItem.outerWidth() / 2 - $smartDot.outerWidth() / 2;

            $smartDot.css('left', posLeft);
        });

        $menuItem.on('click', function() {
            var posLeft = $currentMenuItem.position().left + $currentMenuItem.outerWidth() / 2 - $smartDot.outerWidth() / 2;

            $smartDot
                .data('left', posLeft)
                .css('left', posLeft);
        });
    }

    /**
     * Toggle off-screen panels
     */
    durotan.toggleOffCanvas = function() {
        $(document.body).on('click', '[data-toggle="off-canvas"]', function(event) {
            var target = '#' + $(this).data('target');

            if ($(target).hasClass('open')) {
                durotan.closeOffCanvas(target);
            } else if (durotan.openOffCanvas(target)) {
                event.preventDefault();
            }

            $(this).find('.hamburger-box').closest('body').find('.hamburger-box').addClass('active');
        }).on('click', '.offscreen-panel__button-close, .offscreen-panel__backdrop', function(event) {
            event.preventDefault();

            durotan.closeOffCanvas(this);
        }).on('keyup', function(e) {
            if (e.keyCode === 27) {
                durotan.closeOffCanvas();
            }
        });
    };

    /**
     * Open off canvas panel.
     * @param string target Target selector.
     */
    durotan.openOffCanvas = function(target) {
        var $target = $(target);

        if (!$target.length) {
            return false;
        }

        $target.fadeIn();
        $target.addClass('open');

        var widthScrollBar = window.innerWidth - document.documentElement.clientWidth;
        $(document.body).css({ 'padding-right': widthScrollBar, 'overflow': 'hidden' });
        $('.header-transparent .site-header').css({ 'right': widthScrollBar });

        $(document.body).addClass('offcanvas-opened ' + $target.attr('id') + '-opened').trigger('durotan_off_canvas_opened', [$target]);
        $('body').addClass('body-modals');

        return true;
    }

    /**
     * Close off canvas panel.
     * @param DOM target
     */
    durotan.closeOffCanvas = function(target) {
        if (!target) {
            $('.offscreen-panel').each(function() {
                var $panel = $(this);

                if (!$panel.hasClass('open')) {
                    return;
                }

                $panel.removeClass('open').fadeOut();
                $(document.body).removeClass($panel.attr('id') + '-opened');
            });
        } else {
            target = $(target).closest('.offscreen-panel');
            target.removeClass('open').fadeOut();

            $(document.body).removeClass(target.attr('id') + '-opened');
        }

        $(document.body).removeAttr('style');
        $('.header-transparent .site-header').removeAttr('style');

        $(document.body).removeClass('offcanvas-opened').trigger('durotan_off_canvas_closed', [target]);
        $('body').removeClass('body-modals');
        $('body').find('.hamburger-box').removeClass('active');
    }

    /**
	 * Change product quantity
	 */
	 durotan.inputNumber = function () {
		durotan.$body.on('click', '.durotan-qty-button', function (e) {
			e.preventDefault();

			var $this = $(this),
				$qty = $this.siblings('.qty'),
				current = 0,
				min = parseFloat($qty.attr('min')),
				max = parseFloat($qty.attr('max')),
				step = parseFloat($qty.attr('step'));

			if ($qty.val() !== '') {
				current = parseFloat($qty.val());
			} else if ($qty.attr('placeholder') !== '') {
				current = parseFloat($qty.attr('placeholder'))
			}

			if ($this.hasClass('decrease') && current > min) {
				$qty.val(current - step);
				$qty.trigger('change');
			}
			if ($this.hasClass('increase') && current < max) {
				$qty.val(current + step);
				$qty.trigger('change');
			}

		});

	};

    durotan.updateQuantityAuto = function() {
		$( document.body ).on( 'change', 'table.cart .qty', function() {
			if (typeof durotanData.update_cart_page_auto !== undefined && durotanData.update_cart_page_auto == '1') {
				durotan.$body.find('button[name="update_cart"]').attr( 'clicked', 'true' ).prop( 'disabled', false ).attr( 'aria-disabled', false );
				durotan.$body.find('button[name="update_cart"]').trigger('click');
			}
		} );

		$( document.body ).on( 'change', '.woocommerce-mini-cart .qty', function() {
			durotan.updateCartAJAX( this.value, this );
		} );
	}

    durotan.updateCartAJAX = function (value, input) {
		var $row = $( input ).closest('.woocommerce-mini-cart-item'),
		key = $row.find('a.remove').data('cart_item_key'),
		nonce = $row.find('.woocommerce-mini-cart-item__qty').data('nonce'),
		ajax_url = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_cart_item');

		if ($.fn.block) {
			$row.block({
				message: null,
				overlayCSS: {
					opacity: 0.6,
					background: '#fff'
				}
			});
		}

		$.post(
			ajax_url, {
				cart_item_key: key,
				qty: value,
				security: nonce
			}, function (response) {
				if (!response || !response.fragments) {
					return;
				}

				if ($.fn.unblock) {
					$row.unblock();
				}

				$( document.body ).trigger( 'added_to_cart', [response.fragments, response.cart_hash] );
			}).fail(function () {
			if ($.fn.unblock) {
				$row.unblock();
			}

			return;
		});
	}

    // Sticky Header
    durotan.stickyHeader = function() {

        if (!durotan.$body.hasClass('header-sticky')) {
            return;
        }

        var isHeaderTransparent = durotan.$body.hasClass('header-transparent'),
            $headerMinimized = $('#site-header-minimized'),
            heightHeaderMain = durotan.$header.find('.header__main').outerHeight(),
            heightHeaderBottom = durotan.$header.find('.header__bottom').outerHeight(),
            heightHeaderMobile = durotan.$header.find('.header__mobile').outerHeight(),
            heightHeaderMinimized = heightHeaderMain + heightHeaderBottom;

        if (durotan.$header.hasClass('header-bottom-no-sticky')) {
            heightHeaderMinimized = heightHeaderMain;
        } else if (durotan.$header.hasClass('header-main-no-sticky')) {
            heightHeaderMinimized = heightHeaderBottom;
        }

        if (durotan.$body.hasClass('header-v7')) {
            heightHeaderMinimized = heightHeaderBottom;
        }

        if (isHeaderTransparent) {
            durotan.$header.addClass('has-transparent');
        }

        durotan.$window.on('scroll load', function() {
            var scrollTop = 20,
                scroll = durotan.$window.scrollTop(),
                hHeader = durotan.$header.outerHeight(true),
                hBody = durotan.$body.outerHeight(true);

            scrollTop = scrollTop + hHeader;

            if (hBody <= scrollTop + durotan.$window.height()) {
                return;
            }

            if (scroll > scrollTop) {

                durotan.$header.addClass('minimized');
                $('#durotan-header-minimized').addClass('minimized');
                durotan.$body.addClass('sticky-minimized');

                if (isHeaderTransparent) {
                    durotan.$body.removeClass('header-transparent');
                }

                if (durotan.$window.width() > 992) {
                    $headerMinimized.css('height', heightHeaderMinimized);
                } else {
                    $headerMinimized.css('height', heightHeaderMobile);
                }

            } else {
                durotan.$header.removeClass('minimized');
                $('#durotan-header-minimized').removeClass('minimized');
                durotan.$body.removeClass('sticky-minimized');

                if (isHeaderTransparent) {
                    durotan.$body.addClass('header-transparent');
                }

                $headerMinimized.removeAttr('style');
            }
        });
    }

    // Menu
    // Toggle Menu Sidebar
    durotan.menuSideBar = function() {
        var $menuSidebar = $('#mobile-menu-modal');
        $menuSidebar.find('.nav-menu .menu-item-has-children').removeClass('active');
        $menuSidebar.find('.nav-menu .menu-item-has-children > a').prepend('<span class="toggle-menu-children"><span class="durotan-svg-icon"><svg viewBox="0 0 32 32"><path d="M0 9.6c0-0.205 0.078-0.409 0.234-0.566 0.312-0.312 0.819-0.312 1.131 0l13.834 13.834 13.834-13.834c0.312-0.312 0.819-0.312 1.131 0s0.312 0.819 0 1.131l-14.4 14.4c-0.312 0.312-0.819 0.312-1.131 0l-14.4-14.4c-0.156-0.156-0.234-0.361-0.234-0.566z"></path></svg></span></span>');

        $menuSidebar.find('.click-item li.menu-item-has-children > a').on('click', function(e) {
            e.preventDefault();
            $(this).closest('li').siblings().find('ul.sub-menu, ul.dropdown-submenu').slideUp();
            $(this).closest('li').siblings().removeClass('active');

            $(this).closest('li').children('ul.sub-menu, ul.dropdown-submenu').slideToggle();
            $(this).closest('li').toggleClass('active');

        });
    };


    //productQuantity
    durotan.productQuantity = function() {
        durotan.$body.on('click', '.qty-box .increase, .qty-box .decrease', function(e) {
            e.preventDefault();

            var $this = $(this),
                $qty = $this.siblings('.qty'),
                step = parseInt($qty.attr('step'), 10),
                current = parseInt($qty.val(), 10),
                min = parseInt($qty.attr('min'), 10),
                max = parseInt($qty.attr('max'), 10);

            min = min ? min : 0;
            max = max ? max : current + 1;

            if (($this.hasClass('decrease') && current > min)) {
                $qty.val(current - step);
                $qty.trigger('change');
            }

            if (($this.hasClass('increase') && current < max)) {
                $qty.val(current + step);
                $qty.trigger('change');
            }

        });
        durotan.calculateSubtotalProductGrouped();
        durotan.$body.on('change paste keyup', '.woocommerce-grouped-product-list-item__quantity .qty', durotan.calculateSubtotalProductGrouped);
    }
    durotan.calculateSubtotalProductGrouped = function() {
        var totalSum = 0,
            $subtotal = $('.woocommerce_grouped_product__total').find('.subtotal');

        $('.woocommerce-grouped-product-list-item__quantity .qty').each(function() {
            var $this = $(this),
                $current_price = $this.closest('td').find('.qty-label').data('current'),
                current = parseInt($this.val(), 10),
                min = parseInt($this.attr('min'), 10),
                max = parseInt($this.attr('max'), 10);

            var min_check = min ? min : 1;
            var max_check = max ? max : current + 1;
            if (current <= max_check && current >= min_check) {
                var $total = $current_price * current;
                totalSum += parseFloat($total);
            }
        });
        $subtotal.html(totalSum.toFixed(2));
    };

    /**
     * Open product image lightbox when click on the zoom image
     */
    durotan.productLightBox = function() {
        if (typeof wc_single_product_params === 'undefined' || wc_single_product_params.photoswipe_enabled !== '1') {
            $('.woocommerce-product-gallery').on('click', '.woocommerce-product-gallery__image', function(e) {
                e.preventDefault();
            });
            return false;
        }

        $('.woocommerce-product-gallery').on('click', '.zoomImg', function() {
            if (wc_single_product_params.flexslider_enabled) {
                $(this).closest('.woocommerce-product-gallery').children('.woocommerce-product-gallery__trigger').trigger('click');
            } else {
                $(this).prev('a').trigger('click');
            }
        });
    };

    /**
     * Handle product reviews
     */
    durotan.reviewProduct = function() {
        setTimeout(function() {
            $('#respond p.stars a').prepend('<span class="durotan-svg-icon "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></span>');
        }, 100);

        durotan.$body.on('click', '.add-review', function(event) {
            event.preventDefault();

            var $reviews = $(this).closest('#reviews');

            $('#review_form_wrapper', $reviews).fadeIn();
            $('#comments', $reviews).fadeOut();
        }).on('click', '.cancel-review a', function(event) {
            event.preventDefault();

            var $reviews = $(this).closest('#reviews');

            $('#review_form_wrapper', $reviews).fadeOut();
            $('#comments', $reviews).fadeIn();
        });
    };

    // Product Notification
    /**
     * Open Mini Cart
     */
    durotan.openMiniCartPanel = function() {
        if (typeof durotanData.added_to_cart_notice === 'undefined') {
            return;
        }

        if (durotanData.added_to_cart_notice.added_to_cart_notice_layout !== 'panel') {
            return;
        }

        var product_title = '';
        $(document.body).on('adding_to_cart', function(event, $thisbutton) {
            if (durotanData.added_to_cart_notice.added_to_cart_notice_type === 'panel') {
                product_title = '1';
            } else if ( durotanData.added_to_cart_notice.added_to_cart_notice_type === 'page' && ! durotan.$body.hasClass( 'header-v7' ) ) {
                product_title = '2';
            }
        });

        $(document.body)
            .on('added_to_cart wc_fragments_refreshed', function() {
                if (product_title === '1') {
                    durotan.openModal('#cart-panel');
                }
                if (product_title === '2') {
                    $('html, body').animate({ scrollTop: 0 }, 800);
                    $('.header-cart__mini-cart').css({ "opacity": "1", "transform": "translate(0, 0)", "pointer-events": "initial", "z-index": "999" });
                    setTimeout(function() {
                        $('.header-cart__mini-cart').removeAttr('style');
                    }, 3000);
                }
            });

    };

    durotan.cartPageQuantity = function() {
        durotan.$body.on('click', '.qty-box__cart .increase, .qty-box__cart .decrease', function(e) {
            var value = $(this).siblings('.qty').val(),
                $row = $(this).closest('.woocommerce-cart-form__cart-item'),
                key = $row.find('a.remove').data('cart_item_key'),
                nonce = $row.find('.product-quantity').data('nonce'),
                ajax_url = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_cart_item');

            $.post(
                ajax_url, {
                    cart_item_key: key,
                    qty: value,
                    security: nonce
                },
                function(response) {
                    if (!response || !response.fragments) {
                        return;
                    }

                    $(document.body).trigger('updated_wc_div');
                    $(document.body).trigger('wc_update_cart');
                });
        });
        durotan.$body.on('change', '.qty-box__cart input.qty', function(e) {
            var value = $(this).val(),
                $row = $(this).closest('.woocommerce-cart-form__cart-item'),
                key = $row.find('a.remove').data('cart_item_key'),
                nonce = $row.find('.product-quantity').data('nonce'),
                ajax_url = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_cart_item');

            $.post(
                ajax_url, {
                    cart_item_key: key,
                    qty: value,
                    security: nonce
                },
                function(response) {
                    if (!response || !response.fragments) {
                        return;
                    }

                    $(document.body).trigger('updated_wc_div');
                    $(document.body).trigger('wc_update_cart');
                });
        });
    };

    durotan.accountOrder = function() {
        if (!durotan.$body.hasClass('woocommerce-account')) {
            return;
        }

        $('.woocommerce-MyAccount-navigation .woocommerce-MyAccount-navigation-link > a').append('<span class="durotan-svg-icon "><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.50495 4.82011L0.205241 1.04856C-0.0684137 0.808646 -0.0684137 0.419663 0.205241 0.179864C0.478652 -0.0599547 0.922098 -0.0599547 1.19549 0.179864L5.00007 3.5171L8.80452 0.179961C9.07805 -0.0598577 9.52145 -0.0598577 9.79486 0.179961C10.0684 0.41978 10.0684 0.808743 9.79486 1.04866L5.49508 4.8202C5.35831 4.94011 5.17925 5 5.00009 5C4.82085 5 4.64165 4.94 4.50495 4.82011Z"/></svg></span>');

        $('table.my_account_orders').on('click', '.item-plus', function() {
            $(this).closest('ul').find('li').show();

            $(this).closest('ul').find('.item-plus').hide();
        });
    };

    /**
     * Toggle register/login form in the login panel.
     */
    durotan.loginPanel = function () {
        $(document.body)
            .on('click', '.tabs-login .create-account', function (event) {
                event.preventDefault();

                $(this).closest('.u-column1').fadeOut(function () {
                    $(this).next('.u-column2').fadeIn();
                });
            }).on('click', '.tabs-login .already_registered', function (event) {
            event.preventDefault();

            $(this).closest('.u-column2').fadeOut(function () {
                $(this).prev('.u-column1').fadeIn();
            });
        });
    };

    durotan.productLoaded = function () {
        durotan.$body.on('durotan_products_loaded', function (e, $content) {

            var $variations = $content.find('.variations_form');

            if (typeof $.fn.wc_variation_form !== 'undefined') {
                $variations.each(function () {
                    $(this).wc_variation_form();
                });
            }

            if (typeof $.fn.tawcvs_variation_swatches_form !== 'undefined') {
                $variations.tawcvs_variation_swatches_form();
            }

            if (typeof $.fn.wcboost_variation_swatches !== 'undefined') {
                $variations.wcboost_variation_swatches();
            }

            durotan.productThumbnailsSlider();
        });

        durotan.$body.on('durotan_shop_view_after_change', function (e, $content) {
            durotan.productThumbnailsSlider();
        });
    };

    // Recently Viewed Products
    durotan.recentlyViewedProducts = function () {

        var $recently = $('#durotan-recently-viewed-product');

        if ($recently.length < 1) {
            return;
        }

        if ($recently.hasClass('loaded')) {
            return;
        }

        if ($recently.hasClass('no-ajax')) {
            recentlyViewedProductsCarousel();
            return;
        }

        durotan.$window.on('scroll', function () {
            if (durotan.$body.find('#durotan-recently-viewed-product').is(':in-viewport')) {
                loadProductsAJAX();
            }
        }).trigger('scroll');

        function loadProductsAJAX() {
            if ($recently.hasClass('loaded')) {
                return;
            }
            if ($recently.data('requestRunning')) {
                return;
            }

            $recently.data('requestRunning', true);

            var ajax_url = durotanData.ajax_url.toString().replace('%%endpoint%%', 'durotan_get_recently_viewed');

            $.post(
                ajax_url,
                function (response) {

                    $recently.find('.recently-products ').html(response.data);
                    if ($recently.find('.product-list').hasClass('no-products')) {
                        $recently.addClass('no-products');
                    }
                    recentlyViewedProductsCarousel();
                    $('.recently-products ul.product-loop-layout-3').parent().addClass('swiper-container__extra');
                    $recently.addClass('loaded');
                    $recently.data('requestRunning', false);
                    durotan.$body.trigger('durotan_products_loaded', [$recently, false]);
                }
            );
        }
        function recentlyViewedProductsCarousel() {
            var $related = $('#durotan-recently-viewed-product'),
                $products = $related.find('ul.products'),
                $col = $related.data('col');

            if (!$related.length) {
                return;
            }

            $products.wrap('<div class="swiper-container history-products-carousel"></div>');
            $products.after('<div class="swiper-scrollbar"></div>');
            $products.addClass('swiper-wrapper');
            $products.find('li.product').addClass('swiper-slide');

            new Swiper($related.find('.history-products-carousel'), {
                loop: false,
                scrollbar: {
                    el: $related.find('.swiper-scrollbar'),
                    hide: false,
                    draggable: true
                },
                on: {
                    init: function () {
                        if ($related.hasClass('no-ajax')) {
                            $related.find('.recently-products').css('opacity', 1);
                        }
                    }
                },
                speed: 1000,
                spaceBetween: 50,
                breakpoints: {
                    300: {
                        slidesPerView: 2,
                        slidesPerGroup: 2,
                        spaceBetween: 10,
                    },
                    480: {
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                        spaceBetween: 20,
                    },
                    1200: {
                        slidesPerView: $col <= 4 ? $col : 4,
                        slidesPerGroup: $col <= 4 ? $col : 4
                    },
                    1400: {
                        slidesPerView: $col ? $col : 5,
                        slidesPerGroup: $col ? $col : 5
                    }
                }
            });

        }
    };

    durotan.inlineStyle = function() {
        $(document).ready(function() {
            $('.related ul.product-loop-layout-3').parent().addClass('swiper-container__extra');
        });
    };

    /**
     * Document ready
     */
    $(function() {
        durotan.init();
    });

})(jQuery);