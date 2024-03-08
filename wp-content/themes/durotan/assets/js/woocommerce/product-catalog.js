(function ($) {
    'use strict';

    var durotan = durotan || {};
    durotan.init = function () {
        durotan.$body = $(document.body),
            durotan.$window = $(window),
            durotan.$header = $('#site-header');

        // Catalog
        this.shopView();
        this.productsTools();
        this.productsFilterActivated();
        this.productsLoading();
        this.productsInfinite();
        this.catalogCollapseWidget();
        this.productWidgetButtonMore();
        this.productWidgetCategory();

        this.scrollFilterSidebar();
        this.changeCatalogElementsFiltered();
    };

    // Shop View
    durotan.shopView = function () {
        $('#durotan-shop-view').on('click', '.shop-view__icon a', function (e) {
            e.preventDefault();
            var $el = $(this),
                view = $el.data('view');

            if ($el.hasClass('current')) {
                return;
            }

            $el.closest('.durotan-catalog-toolbar').siblings('.durotan-shop-content').addClass('loading');

            $el.addClass('current').siblings().removeClass('current');
            durotan.$body.removeClass('catalog-view-grid catalog-view-grid-3 catalog-view-list').addClass('catalog-view-' + view);

            durotan.shopViewSwich();

            document.cookie = 'catalog_view=' + view + ';domain=' + window.location.host + ';path=/';

            durotan.$body.trigger('durotan_shop_view_after_change');
        });

        durotan.shopViewSwich();
    };

    durotan.shopViewSwich = function () {
        if ( durotan.$body.hasClass( 'catalog-view-grid-3' ) ) {
            durotan.$body.find( 'ul.products' ).removeClass( 'columns-2 columns-4').addClass( 'columns-3' );
        } else if( durotan.$body.hasClass( 'catalog-view-grid' ) ) {
            durotan.$body.find( 'ul.products' ).removeClass( 'columns-2 columns-3' ).addClass( 'columns-4' );
        } else if( durotan.$body.hasClass( 'catalog-view-list' ) ) {
            durotan.$body.find( 'ul.products' ).removeClass( 'columns-3 columns-4' ).addClass( 'columns-2' );
        }
    };

    /**
     * Handle products tools events.
     */
     durotan.productsTools = function () {
        // Toggle products filter.
        $(document.body).on('click', '[data-target="catalog-filters-dropdown"]', function (e) {
            e.preventDefault();

            var el = $(this),
                $toolbar = el.closest('.durotan-catalog-toolbar'),
                $shopContentFilter = $('.products-filter-dropdown');

            el.toggleClass('active');

            if ($toolbar.siblings('.products-filter-dropdown').hasClass('open')) {
                $toolbar.siblings('.products-filter-dropdown').slideUp(500).removeClass('open');
                $toolbar.find('.durotan-toggle-filters').removeClass('active');
            } else {
                setTimeout(function () {
                    $shopContentFilter.slideToggle(500).toggleClass('open');
                    el.children().toggleClass('active');
                }, 200);
            }
        });

        var $selector = $('#catalog-filters');
        durotan.$body.on('durotan_products_filter_before_send_request', function () {
            $selector.find('.products-filter-widget .products-filter__filter-control').hide();
            $selector.find('.products-filter-widget .products-filter__filter-name').removeClass('durotan-active');

            var $toolbar = durotan.$body.closest('.durotan-catalog-toolbar'),
                $shopContentFilter = $('.products-filter-dropdown');

            $toolbar.find('.durotan-toggle-filters').removeClass('active');
            $shopContentFilter.slideToggle(500).removeClass('open');
        });

        durotan.$body.on('durotan_products_filter_request_success', function (response, url, form) {
            var content = $(url).find('#durotan-shop-content').find('ul.products').children('li.product'),
                numberProduct = content.length;

            durotan.$body.find('.durotan-products-found > span').html(numberProduct);
            durotan.$body.find('.catalog-toolbar-filters').hide();
            durotan.$body.find('.durotan-toggle-filters').removeClass('active');
        });
    };

    durotan.productsFilterActivated = function () {
        var $primaryFilter = $('#durotan-products-filter__activated'),
            $widgetFilter = $('.products-filter-widget').find('.products-filter__activated');

        $primaryFilter.html($widgetFilter.html());

        durotan.$body.on('durotan_products_filter_widget_updated', function (e, form) {
            var $widgetNewFilter = $(form).closest('.products-filter-widget').find('.products-filter__activated');
            $primaryFilter.html($widgetNewFilter.html());
        });

        $primaryFilter.on('click', '.remove-filtered', function (e) {
            var currentIndex = $(this).index();

            if (currentIndex !== 'undefined') {
                $(this).remove();
                $widgetFilter.find('.remove-filtered:eq(' + currentIndex + ')').trigger('click');
            }

            durotan.$body.find('.catalog-toolbar-filters').hide();

            return false;

        });
    };

    durotan.catalogCollapseWidget = function () {
        if (typeof durotanCatalogData.catalog_widget_collapse_content === 'undefined') {
            return;
        }

        if (durotanCatalogData.catalog_widget_collapse_content !== '1') {
            return;
        }

        $('#primary-sidebar').find('.products-filter__filter-name').append('<span class="durotan-svg-icon icon-plus"><svg viewBox="0 0 64 64"><path d="M62.5,30.5h-29v-29C33.5,0.7,32.8,0,32,0c-0.8,0-1.5,0.7-1.5,1.5v29h-29C0.7,30.5,0,31.2,0,32c0,0.8,0.7,1.5,1.5,1.5h29v29c0,0.8,0.7,1.5,1.5,1.5c0.8,0,1.5-0.7,1.5-1.5v-29h29c0.8,0,1.5-0.7,1.5-1.5C64,31.2,63.3,30.5,62.5,30.5z"></path></svg></span>');
        $('#primary-sidebar').find('.products-filter__filter-name').append('<span class="durotan-svg-icon icon-minus"><svg viewBox="0 0 64 64"><path d="M62.5,30.2l-61,0.5c-0.8,0-1.5,0.7-1.5,1.5c0,0.8,0.7,1.5,1.5,1.5c0,0,0,0,0,0l61-0.5c0.8,0,1.5-0.7,1.5-1.5 C64,30.9,63.3,30.3,62.5,30.2z"></path></svg></span>');

        durotan.collapseWidget($('#primary-sidebar'));

    };

    durotan.collapseWidget = function ($this) {
        $this.on('click', '.widget-title', function (e) {
            e.preventDefault();

            var $el = $(this),
                $wrapper = $el.closest('.widget');

            if ($el.closest('.widget').hasClass('.products-filter-widget')) {
                return;
            }

            $wrapper.find('.widget-content').slideToggle();
            $el.toggleClass('durotan-active');
        });

        $this.on('click', '.products-filter__filter-name', function (e) {
            e.preventDefault();
            $(this).next().slideToggle();
            $(this).closest('.products-filter__filter').toggleClass('durotan-active');
        });
    };

    durotan.productWidgetButtonMore = function () {
        var $widget = $('.products-filter__filter'),
            catNumbers = parseInt($widget.find('input.filter-more-numbers').val(), 10);

        if (!$widget.hasClass('products-filter--view-more')) {
            return;
        }

        var count = $widget.find('.products-filter__options > .products-filter__option').size();

        if (count > catNumbers) {
            $widget.find('.show-more').show();
        }

        $widget.find('ul.products-filter__options li.products-filter__option:lt(' + catNumbers + ')').show();
        $widget.find('.products-filter__options span.products-filter__option:lt(' + catNumbers + ')').css( 'display', 'flex' );

        $widget.on('click', '.show-more', function () {
            $(this).closest($widget).find('ul.products-filter__options > li.products-filter__option').show();
            $(this).closest($widget).find('.products-filter__options > span.products-filter__option').css( 'display', 'flex' );
            $(this).hide();
            $(this).closest($widget).find('.show-less').show();
        });

        $widget.on('click', '.show-less', function () {
            $(this).closest($widget).find('.products-filter__options > .products-filter__option').not(':lt(' + catNumbers + ')').hide();
            $(this).hide();
            $(this).closest($widget).find('.show-more').show();
        });
    };

    durotan.productWidgetCategory = function () {
        var $widget = $('.catalog-sidebar .widget_product_categories');

        $widget.find('li.cat-parent > a').append('<span class="durotan-svg-icon"><svg viewBox="0 0 32 32"><path d="M0 9.6c0-0.205 0.078-0.409 0.234-0.566 0.312-0.312 0.819-0.312 1.131 0l13.834 13.834 13.834-13.834c0.312-0.312 0.819-0.312 1.131 0s0.312 0.819 0 1.131l-14.4 14.4c-0.312 0.312-0.819 0.312-1.131 0l-14.4-14.4c-0.156-0.156-0.234-0.361-0.234-0.566z"></path></svg></span>');

        $widget.on('click', 'li.cat-parent a', function (e) {
            e.preventDefault();

            $(this).toggleClass('open');
            $(this).siblings().slideToggle(500);
        });
    }

    durotan.scrollFilterSidebar = function () {
        durotan.$body.on('durotan_products_filter_before_send_request', function () {
            var $offset = 0,
                $heightSticky = durotan.$body.find('.site-header').height();

            if( ! $("#durotan-shop-content").length ) {
                return;
            }

            if (durotan.$body.hasClass('header-sticky')) {
                $offset = 200;
            } else {
                $offset = $heightSticky + 200;
            }

            if( $('.products-filter__activated').length ) {
                $offset += $('.products-filter__activated').height();
            }

            $('html,body').stop().animate({
                    scrollTop: $("#durotan-shop-content").offset().top - $offset
                },
                'slow');

            $('#durotan-shop-content').find('.durotan-products-found').hide();
        });
    };

    durotan.changeCatalogElementsFiltered = function () {
        durotan.$body.on('durotan_products_filter_request_success durotan_products_filter_after_response', function (response, url) {
            var $filter_widget    = $('.products-filter__filter'),
                $sidebar          = durotan.$body.find('.catalog-sidebar');

            if (durotanCatalogData.catalog_widget_collapse_content == '1') {
                durotan.catalogCollapseWidget();
            }

            if ($filter_widget.hasClass('products-filter--view-more')) {
                durotan.productWidgetButtonMore();
            }

            if($sidebar.length) {
                if(window.location.href.indexOf('?') == -1) {
                    return;
                };

                var hash,
                    hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for(var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');

                    if( hash ) {
                        var $filter = $sidebar.find('.products-filter-widget').find('[name=' + hash[0] + ']');
                        $filter.closest('.products-filter__filter').addClass('durotan-active');
                    }
                }
            }
        });

        durotan.$body.on( 'durotan_products_filter_request_success', function (e, response ) {
            var $html            = $(response),
                $product_taxonomy = durotan.$body.find( '.durotan-product-taxonomy-list__catalog' );

            if( $product_taxonomy.length ) {
                if( $html.find( '.durotan-product-taxonomy-list__catalog' ).length ) {
                    $product_taxonomy.replaceWith( $html.find( '.durotan-product-taxonomy-list__catalog' ) );
                }
            }
        });

    };

    durotan.productsLoading = function () {
        durotan.$body.on('click', '#durotan-catalog-previous-ajax > a', function (e) {
            e.preventDefault();

            var $this = $(this);
            if ($this.data('requestRunning')) {
                return;
            }

            $this.data('requestRunning', true);

            var $wrapper = $this.closest('.durotan-shop-content'),
                $products = $wrapper.find('ul.products'),
                $pagination = $wrapper.find('.next-posts-navigation'),
                numberPosts = $products.children('li.product').length,
                href = $this.attr('href');

            $pagination.addClass('loading');

            $.get(
                href,
                function (response) {
                    var content = $(response).find('.durotan-shop-content').find('ul.products').children('li.product');

                    // Add animation class
                    for (var index = 0; index < content.length; index++) {
                        $(content[index]).css('animation-delay', index * 100 + 'ms');
                    }
                    content.addClass('durotanFadeInUp');
                    if ($(response).find('.next-posts-navigation').length > 0) {
                        $pagination.html($(response).find('.next-posts-navigation').html());
                    } else {
                        $pagination.fadeOut();
                    }
                    $products.append(content);
                    $pagination.find('.nav-previous-ajax > a').data('requestRunning', false);

                    numberPosts += content.length;
                    $wrapper.find('.durotan-posts__found .current-post').html(' ' + numberPosts);
                    $pagination.removeClass('loading');
                    $(document.body).trigger('durotan_products_loaded', [content, true]);
                }
            );
        });
    };

    durotan.productsInfinite = function () {
        if (!$('.woocommerce-navigation').hasClass('ajax-infinite')) {
            return;
        }
        durotan.$window.on('scroll', function () {
            if (durotan.$body.find('#durotan-catalog-previous-ajax').is(':in-viewport')) {
                durotan.$body.find('#durotan-catalog-previous-ajax > a').trigger('click');
            }
        }).trigger('scroll');
    };

    /**
     * Document ready
     */
    $(function () {
        durotan.init();
    });

})(jQuery);