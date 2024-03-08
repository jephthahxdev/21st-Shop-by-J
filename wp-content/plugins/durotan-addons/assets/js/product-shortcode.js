class DurotanProductShortcodeWidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                gallery: '.woocommerce-product-gallery'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $gallery: this.$element.find(selectors.gallery)
        };

    }

    getSwipperOptions() {
        const swiperOptions = {
            watchOverflow: true,
            slidesPerView: this.elements.$gallery.data('columns'),
            spaceBetween: 15,
            navigation: {
                nextEl: this.elements.$gallery.find('.durotan-gallery-button-next'),
                prevEl: this.elements.$gallery.find('.durotan-gallery-button-prev'),
            },
            breakpoints: {
                300: {
                    spaceBetween: 0,
                    allowTouchMove: false,
                },
                991: {
                    spaceBetween: 15,
                },
            }
        };

        return swiperOptions;
    }

    getFlexSliderInit() {
        var self = this;
        const settings = this.getElementSettings();
        if (!self.$element.closest('body').hasClass('elementor-editor-active')) {
            this.elements.$gallery.on('wc-product-gallery-before-init',function(e, gallery, wc_single_product_params){
                wc_single_product_params.flexslider.controlNav = settings.show_thumbnail ? 'thumbnails' : false;
            });
            return;
        }
        const options = {
            selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
            allowOneSlide: false,
            animation: "slide",
            animationLoop: false,
            controlNav: settings.show_thumbnail ? 'thumbnails' : false,
            animationSpeed: 500,
            directionNav: false,
            rtl: false,
            slideshow: false,
            smoothHeight: true,
            start: function () {
                self.elements.$gallery.css('opacity', 1);
            },
        };

        this.elements.$gallery.flexslider(options);
    }

    getImageZoomInit($target) {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.zoom) {
            return;
        }


        const settings = this.getElementSettings();

        if (settings.show_image_zoom === 'show') {

            var zoom_options = jQuery.extend({
                touch: false
            });

            $target.trigger('zoom.destroy');
            $target.zoom(zoom_options);
        }
    }

    getVariationSwatcher() {
        var $variations = this.$element.find('.variations_form');
        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            $variations.find('td.value select').each(function () {
                jQuery(this).on('change', function () {
                    var value = jQuery(this).find('option:selected').text();
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').html(value);
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').data('value',jQuery(this).find('option:selected').val());

                    if (jQuery(this).closest('.variation-selector.hidden').length && jQuery(this).closest('.entry-summary').length) {
                        jQuery(this).closest('tr').find('td.label').show();
                    }
                }).trigger('change');
            });
        }
    }

    changePriceVariationSwatcher() {
        jQuery('.durotan-product-shortcode').on('change', 'input[name="variation_id"]', function(e) {
            var variationPrice = jQuery(this).closest('.durotan-product-shortcode').find('.woocommerce-variation-price').html(),
                product_id = jQuery(this).parent().find('input[name="product_id"]').val();

            jQuery('.durotan-product-shortcode .product-type-variable').find('p.price').removeClass('show-price');
            jQuery('.durotan-product-shortcode .product-type-variable').find('p.price .price-html-variation').remove();

            jQuery(this).closest('.product-type-variable').find('p.price').addClass('show-price');
            jQuery(this).closest('.product-type-variable').find('p.price').append('<span class="price-html-variation">' + variationPrice + '</span>');

            if( ! jQuery(this).val() ) {
                jQuery('.durotan-product-shortcode .product-type-variable').find('p.price').removeClass('show-price');
                jQuery('.durotan-product-shortcode .product-type-variable').find('p.price .price-html-variation').remove();
            }
        });

        jQuery('.durotan-product-shortcode').on('click', '.reset_variations', function(e) {
            jQuery('.durotan-product-shortcode').find('p.price').removeClass('show-price');
            jQuery('.durotan-product-shortcode').find('p.price .price-html-variation').remove();
        });
    }

    getSwiperInit() {
        var $thumbnail = this.$element.find('.flex-control-thumbs');
        if($thumbnail.children().length > this.elements.$gallery.data('columns')){
            $thumbnail.wrap('<div class="woocommerce-product-gallery__thumbs-carousel swiper-container linked-gallery-carousel"></div>');
            $thumbnail.addClass('swiper-wrapper');
            $thumbnail.find('li').addClass('swiper-slide');
            $thumbnail.after('<span class="durotan-svg-icon durotan-gallery-button-prev durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.467723 13.1881L10.2737 23.5074C10.8975 24.1642 11.9089 24.1642 12.5324 23.5074C13.1559 22.8512 13.1559 21.787 12.5324 21.1308L3.85554 11.9998L12.5321 2.86914C13.1556 2.21269 13.1556 1.14853 12.5321 0.492339C11.9086 -0.164113 10.8973 -0.164113 10.2735 0.492339L0.46747 10.8118C0.155705 11.14 0 11.5698 0 11.9998C0 12.43 0.156009 12.86 0.467723 13.1881Z"></path></svg></span>');
            $thumbnail.after('<span class="durotan-svg-icon durotan-gallery-button-next durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.5323 13.1881L2.72626 23.5074C2.10248 24.1642 1.09112 24.1642 0.467647 23.5074C-0.155882 22.8512 -0.155882 21.787 0.467647 21.1308L9.14446 11.9998L0.467899 2.86914C-0.15563 2.21269 -0.15563 1.14853 0.467899 0.492339C1.09143 -0.164113 2.10273 -0.164113 2.72651 0.492339L12.5325 10.8118C12.8443 11.14 13 11.5698 13 11.9998C13 12.43 12.844 12.86 12.5323 13.1881Z"></path></svg></span>');

            jQuery('li', $thumbnail).append('<span/>');

            new Swiper(this.elements.$gallery.find('.linked-gallery-carousel'), this.getSwipperOptions());
        } else {
            jQuery(window).on('resize load', function() {
                if( jQuery(window).width() < 1025 ) {
                    jQuery('li', $thumbnail).append('<span/>');
                }
            });
        }
    }

    getLightBoxGalleryInit() {
        var self = this;
        const settings = this.getElementSettings();
        if (this.$element.closest('body').hasClass('elementor-editor-active')) {
            if (settings.show_lightbox !== 'show' && settings.show_image_zoom !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }

            if (settings.show_lightbox === 'show' && settings.show_image_zoom === 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > .zoomImg', function (e) {
                    jQuery(this).closest('.woocommerce-product-gallery__image').find('a').trigger('click');
                });

            }

        } else {

            if (settings.show_lightbox !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }
        }
    }
    getCountDownInit() {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.durotan_countdown) {
            return;
        }

        this.$element.find('.durotan-countdown').durotan_countdown();
    }
    toggleDescriptionInit(){
        const settings = this.getElementSettings();
        if(settings.show_all_content == 'show'){
            return;
        }
        var $description = this.$element.find(".product_description");
        if($description.length == 0){
            return;
        }
        $description.on('click', '.read-more', function (e) {
            e.preventDefault();
            $description.find(".collapsed-desc").hide();
            $description.find(".full-desc").show();
            return false;
        });
    }
    onInit() {
        var self = this;

        super.onInit();

        this.toggleDescriptionInit();

        this.getCountDownInit();

        this.getLightBoxGalleryInit();

        this.getFlexSliderInit();

        this.getVariationSwatcher();

        this.changePriceVariationSwatcher();

        this.getImageZoomInit(this.elements.$gallery.find('.woocommerce-product-gallery__image'));

        this.elements.$gallery.imagesLoaded(function () {
            setTimeout(function () {
                self.getSwiperInit();
            }, 200);
        });
    }
}
class DurotanProductShortcode2WidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                gallery: '.woocommerce-product-gallery'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $gallery: this.$element.find(selectors.gallery)
        };

    }

    getSwipperOptions() {
        const swiperOptions = {
            direction:'vertical',
            watchOverflow: true,
            slidesPerView: this.elements.$gallery.data('columns'),
            spaceBetween: 15,
            navigation: {
                nextEl: this.elements.$gallery.find('.durotan-gallery-button-next'),
                prevEl: this.elements.$gallery.find('.durotan-gallery-button-prev'),
            },
            breakpoints: {
                300: {
                    spaceBetween: 0,
                    allowTouchMove: false,
                },
                991: {
                    spaceBetween: 15,
                },
            }
        };

        return swiperOptions;
    }

    getFlexSliderInit() {
        var self = this;
        const settings = this.getElementSettings();
        if (!self.$element.closest('body').hasClass('elementor-editor-active')) {
            if (typeof wc_single_product_params !== undefined) {
                wc_single_product_params.flexslider.controlNav = settings.show_thumbnail == 'show' ? 'thumbnails' : false;
            }
            // if (jQuery.fn.wc_product_gallery) {
            //     setTimeout(function () {
            //         console.log(wc_single_product_params.flexslider);
            //         self.elements.$gallery.each(function () {
            //             jQuery(this).wc_product_gallery();
            //         });
            //     }, 200);
            // }
            self.elements.$gallery.on('wc-product-gallery-before-init',function(e, gallery, wc_single_product_params){
                //console.log(wc_single_product_params);
                wc_single_product_params.flexslider.controlNav = settings.show_thumbnail ? 'thumbnails' : false;
            });
            return;
        }
        const options = {
            selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
            allowOneSlide: false,
            animation: "slide",
            animationLoop: false,
            controlNav: settings.show_thumbnail ? 'thumbnails' : false,
            animationSpeed: 500,
            directionNav: false,
            rtl: false,
            slideshow: false,
            smoothHeight: true,
            start: function () {
                self.elements.$gallery.css('opacity', 1);
            },
        };

        this.elements.$gallery.flexslider(options);
    }

    getImageZoomInit($target) {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.zoom) {
            return;
        }


        const settings = this.getElementSettings();

        if (settings.show_image_zoom === 'show') {

            var zoom_options = jQuery.extend({
                touch: false
            });

            $target.trigger('zoom.destroy');
            $target.zoom(zoom_options);
        }
    }

    getVariationSwatcher() {
        var $variations = this.$element.find('.variations_form');
        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            $variations.find('td.value select').each(function () {
                jQuery(this).on('change', function () {
                    var value = jQuery(this).find('option:selected').text();
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').html(value);
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').data('value',jQuery(this).find('option:selected').val());

                    if (jQuery(this).closest('.variation-selector.hidden').length && jQuery(this).closest('.entry-summary').length) {
                        jQuery(this).closest('tr').find('td.label').show();
                    }
                }).trigger('change');
            });
            $variations.on('hide_variation', function(){
                var $button = jQuery( '.buy_now_button' , jQuery(this));
                $button.prop( 'disabled', true ).addClass('disabled');
            });

            $variations.on('show_variation', function(event, data){
                var $button = jQuery( '.buy_now_button' , jQuery(this));
                $button.prop( 'disabled', false ).removeClass('disabled');
            });
        }
    }

    getSwiperInit() {
        var $thumbnail = this.$element.find('.flex-control-thumbs');
        if($thumbnail.children().length > this.elements.$gallery.data('columns')){
            $thumbnail.wrap('<div class="woocommerce-product-gallery__thumbs-carousel swiper-container linked-gallery-carousel"></div>');
            $thumbnail.addClass('swiper-wrapper');
            $thumbnail.find('li').addClass('swiper-slide');
            $thumbnail.after('<span class="durotan-svg-icon durotan-gallery-button-prev durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.467723 13.1881L10.2737 23.5074C10.8975 24.1642 11.9089 24.1642 12.5324 23.5074C13.1559 22.8512 13.1559 21.787 12.5324 21.1308L3.85554 11.9998L12.5321 2.86914C13.1556 2.21269 13.1556 1.14853 12.5321 0.492339C11.9086 -0.164113 10.8973 -0.164113 10.2735 0.492339L0.46747 10.8118C0.155705 11.14 0 11.5698 0 11.9998C0 12.43 0.156009 12.86 0.467723 13.1881Z"></path></svg></span>');
            $thumbnail.after('<span class="durotan-svg-icon durotan-gallery-button-next durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.5323 13.1881L2.72626 23.5074C2.10248 24.1642 1.09112 24.1642 0.467647 23.5074C-0.155882 22.8512 -0.155882 21.787 0.467647 21.1308L9.14446 11.9998L0.467899 2.86914C-0.15563 2.21269 -0.15563 1.14853 0.467899 0.492339C1.09143 -0.164113 2.10273 -0.164113 2.72651 0.492339L12.5325 10.8118C12.8443 11.14 13 11.5698 13 11.9998C13 12.43 12.844 12.86 12.5323 13.1881Z"></path></svg></span>');

            jQuery('li', $thumbnail).append('<span/>');

            new Swiper(this.elements.$gallery.find('.linked-gallery-carousel'), this.getSwipperOptions());
        } else {
            jQuery(window).on('resize load', function() {
                if( jQuery(window).width() < 1025 ) {
                    jQuery('li', $thumbnail).append('<span/>');
                }
            });
        }
    }

    getLightBoxGalleryInit() {
        var self = this;
        const settings = this.getElementSettings();
        if (this.$element.closest('body').hasClass('elementor-editor-active')) {
            if (settings.show_lightbox !== 'show' && settings.show_image_zoom !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }

            if (settings.show_lightbox === 'show' && settings.show_image_zoom === 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > .zoomImg', function (e) {
                    jQuery(this).closest('.woocommerce-product-gallery__image').find('a').trigger('click');
                });

            }

        } else {

            if (settings.show_lightbox !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }
        }
    }
    getCountDownInit() {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.durotan_countdown) {
            return;
        }

        this.$element.find('.durotan-countdown').durotan_countdown();
    }

    onInit() {
        var self = this;

        super.onInit();

        this.getCountDownInit();

        this.getLightBoxGalleryInit();

        this.getFlexSliderInit();

        this.getVariationSwatcher();

        this.getImageZoomInit(this.elements.$gallery.find('.woocommerce-product-gallery__image'));

        this.elements.$gallery.imagesLoaded(function () {
            setTimeout(function () {
                self.getSwiperInit();
            }, 200);
        });
    }
}
class DurotanProductShortcode3WidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                gallery: '.woocommerce-product-gallery'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $gallery: this.$element.find(selectors.gallery)
        };

    }

    getSwipperOptions() {
        const swiperOptions = {
            watchOverflow: true,
            slidesPerView: this.elements.$gallery.data('columns'),
            spaceBetween: 15,
            navigation: {
                nextEl: this.elements.$gallery.find('.durotan-gallery-button-next'),
                prevEl: this.elements.$gallery.find('.durotan-gallery-button-prev'),
            },
            breakpoints: {
                300: {
                    spaceBetween: 0,
                    allowTouchMove: false,
                },
                991: {
                    spaceBetween: 15,
                },
            }
        };

        return swiperOptions;
    }

    getFlexSliderInit() {
        var self = this;
        const settings = this.getElementSettings();
        if (!self.$element.closest('body').hasClass('elementor-editor-active')) {
            this.elements.$gallery.on('wc-product-gallery-before-init',function(e, gallery, wc_single_product_params){
                wc_single_product_params.flexslider.controlNav = settings.show_thumbnail ? 'thumbnails' : false;
                wc_single_product_params.flexslider.directionNav = true;
                wc_single_product_params.flexslider.prevText = '<span class="durotan-svg-icon"><svg	viewBox="0 0 64 64"><path d="M19.8,32.7c-0.4-0.4-0.4-1,0-1.4L47.1,2.5C47.6,1.9,47.6,1,47,0.4s-1.6-0.5-2.1,0.1L17.6,29.2c-1.4,1.5-1.4,4,0,5.5 l27.3,28.8c0.3,0.3,0.7,0.5,1.1,0.5c0.4,0,0.7-0.1,1-0.4c0.6-0.6,0.6-1.5,0.1-2.1L19.8,32.7z"/></svg>';
                wc_single_product_params.flexslider.nextText = '<span class="durotan-svg-icon"><svg viewBox="0 0 64 64"><path d="M46.4,29.2L19.1,0.5c-0.6-0.6-1.5-0.6-2.1-0.1c-0.6,0.6-0.6,1.5-0.1,2.1l27.3,28.8c0.4,0.4,0.4,1,0,1.4L16.9,61.5c-0.6,0.6-0.5,1.6,0.1,2.1c0.3,0.3,0.7,0.4,1,0.4c0.4,0,0.8-0.2,1.1-0.5l27.3-28.8C47.8,33.2,47.8,30.8,46.4,29.2z"/></svg></span>';
            });
            return;
        }
        const options = {
            selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
            allowOneSlide: false,
            animation: "slide",
            animationLoop: false,
            controlNav: settings.show_thumbnail ? 'thumbnails' : false,
            animationSpeed: 500,
            directionNav: true,
            prevText: '<span class="durotan-svg-icon"><svg	viewBox="0 0 64 64"><path d="M19.8,32.7c-0.4-0.4-0.4-1,0-1.4L47.1,2.5C47.6,1.9,47.6,1,47,0.4s-1.6-0.5-2.1,0.1L17.6,29.2c-1.4,1.5-1.4,4,0,5.5 l27.3,28.8c0.3,0.3,0.7,0.5,1.1,0.5c0.4,0,0.7-0.1,1-0.4c0.6-0.6,0.6-1.5,0.1-2.1L19.8,32.7z"/></svg>',
            nextText: '<span class="durotan-svg-icon"><svg viewBox="0 0 64 64"><path d="M46.4,29.2L19.1,0.5c-0.6-0.6-1.5-0.6-2.1-0.1c-0.6,0.6-0.6,1.5-0.1,2.1l27.3,28.8c0.4,0.4,0.4,1,0,1.4L16.9,61.5c-0.6,0.6-0.5,1.6,0.1,2.1c0.3,0.3,0.7,0.4,1,0.4c0.4,0,0.8-0.2,1.1-0.5l27.3-28.8C47.8,33.2,47.8,30.8,46.4,29.2z"/></svg></span>',
            rtl: false,
            slideshow: false,
            smoothHeight: true,
            start: function () {
                self.elements.$gallery.css('opacity', 1);
            },
        };

        this.elements.$gallery.flexslider(options);
    }

    getImageZoomInit($target) {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.zoom) {
            return;
        }


        const settings = this.getElementSettings();

        if (settings.show_image_zoom === 'show') {

            var zoom_options = jQuery.extend({
                touch: false
            });

            $target.trigger('zoom.destroy');
            $target.zoom(zoom_options);
        }
    }

    getVariationSwatcher() {
        var $variations = this.$element.find('.variations_form');
        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            $variations.find('td.value select').each(function () {
                jQuery(this).on('change', function () {
                    var value = jQuery(this).find('option:selected').text();
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').html(value);
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').data('value',jQuery(this).find('option:selected').val());

                    if (jQuery(this).closest('.variation-selector.hidden').length && jQuery(this).closest('.entry-summary').length) {
                        jQuery(this).closest('tr').find('td.label').show();
                    }
                }).trigger('change');
            });
            $variations.on('hide_variation', function(){
                var $button = jQuery( '.buy_now_button' , jQuery(this));
                $button.prop( 'disabled', true ).addClass('disabled');
            });

            $variations.on('show_variation', function(event, data){
                var $button = jQuery( '.buy_now_button' , jQuery(this));
                $button.prop( 'disabled', false ).removeClass('disabled');
            });
        }
    }

    getSwiperInit() {
        var $thumbnail = this.$element.find('.flex-control-thumbs');
        if($thumbnail.children().length > this.elements.$gallery.data('columns')){
            $thumbnail.wrap('<div class="durotan-swiper-container"><div class="woocommerce-product-gallery__thumbs-carousel swiper-container linked-gallery-carousel"></div></div>');
            $thumbnail.addClass('swiper-wrapper');
            $thumbnail.find('li').addClass('swiper-slide');
            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-gallery-button-prev durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.467723 13.1881L10.2737 23.5074C10.8975 24.1642 11.9089 24.1642 12.5324 23.5074C13.1559 22.8512 13.1559 21.787 12.5324 21.1308L3.85554 11.9998L12.5321 2.86914C13.1556 2.21269 13.1556 1.14853 12.5321 0.492339C11.9086 -0.164113 10.8973 -0.164113 10.2735 0.492339L0.46747 10.8118C0.155705 11.14 0 11.5698 0 11.9998C0 12.43 0.156009 12.86 0.467723 13.1881Z"></path></svg></span>');
            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-gallery-button-next durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.5323 13.1881L2.72626 23.5074C2.10248 24.1642 1.09112 24.1642 0.467647 23.5074C-0.155882 22.8512 -0.155882 21.787 0.467647 21.1308L9.14446 11.9998L0.467899 2.86914C-0.15563 2.21269 -0.15563 1.14853 0.467899 0.492339C1.09143 -0.164113 2.10273 -0.164113 2.72651 0.492339L12.5325 10.8118C12.8443 11.14 13 11.5698 13 11.9998C13 12.43 12.844 12.86 12.5323 13.1881Z"></path></svg></span>');
            jQuery('li', $thumbnail).append('<span/>');

            new Swiper(this.elements.$gallery.find('.linked-gallery-carousel'), this.getSwipperOptions());
        } else {
            jQuery(window).on('resize load', function() {
                if( jQuery(window).width() < 1025 ) {
                    jQuery('li', $thumbnail).append('<span/>');
                }
            });
        }
    }

    getLightBoxGalleryInit() {
        var self = this;
        const settings = this.getElementSettings();
        if (this.$element.closest('body').hasClass('elementor-editor-active')) {
            if (settings.show_lightbox !== 'show' && settings.show_image_zoom !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }

            if (settings.show_lightbox === 'show' && settings.show_image_zoom === 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > .zoomImg', function (e) {
                    jQuery(this).closest('.woocommerce-product-gallery__image').find('a').trigger('click');
                });

            }

        } else {

            if (settings.show_lightbox !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }
        }
    }
    getCountDownInit() {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.durotan_countdown) {
            return;
        }

        this.$element.find('.durotan-countdown').durotan_countdown();
    }
    toggleDescriptionInit(){
        const settings = this.getElementSettings();
        if(settings.show_all_content == 'show'){
            return;
        }
        var $description = this.$element.find(".product_description");
        if($description.length == 0){
            return;
        }
        $description.on('click', '.read-more', function (e) {
            e.preventDefault();
            $description.find(".collapsed-desc").hide();
            $description.find(".full-desc").show();
            return false;
        });
    }

    setGalleryHeight() {
        var height = jQuery( window ).height() - jQuery( '#masthead' ).outerHeight(),
            $topbar = jQuery( '#topbar' ),
            $footer = jQuery( '.footer-main' ),
            $viewport = this.elements.$gallery.find( '.flex-viewport' );
        if ( $topbar.length ) {
            height -= $topbar.outerHeight();
        }
        console.log(height);
        if ( $footer.length ) {
            height -= parseFloat( $footer.css( 'padding-top' ) );
        }
        if ( height && $viewport ) {
            $viewport.css( {
                maxHeight: height,
                height   : height
            } );
        }else{
            this.elements.$gallery.css( {
                maxHeight: height,
                height   : height
            } );
        }
    }

    onInit() {
        var self = this;

        super.onInit();

        this.toggleDescriptionInit();

        this.getCountDownInit();

        this.getLightBoxGalleryInit();

        this.getFlexSliderInit();

        this.getVariationSwatcher();

        this.getImageZoomInit(this.elements.$gallery.find('.woocommerce-product-gallery__image'));

        this.elements.$gallery.imagesLoaded(function () {
            setTimeout(function () {
                self.getSwiperInit();
                self.setGalleryHeight();
            }, 200);
        });
    }
}
class DurotanProductShortcode4WidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                gallery: '.woocommerce-product-gallery'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $gallery: this.$element.find(selectors.gallery)
        };

    }

    getSwipperOptions() {
        const swiperOptions = {
            watchOverflow: true,
            slidesPerView: this.elements.$gallery.data('columns'),
            spaceBetween: 15,
            navigation: {
                nextEl: this.elements.$gallery.find('.durotan-gallery-button-next'),
                prevEl: this.elements.$gallery.find('.durotan-gallery-button-prev'),
            },
            breakpoints: {
                300: {
                    spaceBetween: 0,
                    allowTouchMove: false,
                },
                991: {
                    spaceBetween: 15,
                },
            }
        };

        return swiperOptions;
    }

    getFlexSliderInit() {
        var self = this;
        const settings = this.getElementSettings();

        if (!self.$element.closest('body').hasClass('elementor-editor-active')) {
            this.elements.$gallery.on('wc-product-gallery-before-init',function(e, gallery, wc_single_product_params){
                wc_single_product_params.flexslider.controlNav = settings.show_thumbnail ? 'thumbnails' : false;
                wc_single_product_params.flexslider.directionNav = true;
                wc_single_product_params.flexslider.prevText = '<span class="durotan-svg-icon"><svg	viewBox="0 0 64 64"><path d="M19.8,32.7c-0.4-0.4-0.4-1,0-1.4L47.1,2.5C47.6,1.9,47.6,1,47,0.4s-1.6-0.5-2.1,0.1L17.6,29.2c-1.4,1.5-1.4,4,0,5.5 l27.3,28.8c0.3,0.3,0.7,0.5,1.1,0.5c0.4,0,0.7-0.1,1-0.4c0.6-0.6,0.6-1.5,0.1-2.1L19.8,32.7z"/></svg>';
                wc_single_product_params.flexslider.nextText = '<span class="durotan-svg-icon"><svg viewBox="0 0 64 64"><path d="M46.4,29.2L19.1,0.5c-0.6-0.6-1.5-0.6-2.1-0.1c-0.6,0.6-0.6,1.5-0.1,2.1l27.3,28.8c0.4,0.4,0.4,1,0,1.4L16.9,61.5c-0.6,0.6-0.5,1.6,0.1,2.1c0.3,0.3,0.7,0.4,1,0.4c0.4,0,0.8-0.2,1.1-0.5l27.3-28.8C47.8,33.2,47.8,30.8,46.4,29.2z"/></svg></span>';
            });
            return;
        }

        const options = {
            selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
            allowOneSlide: false,
            animation: "slide",
            animationLoop: false,
            controlNav: settings.show_thumbnail ? 'thumbnails' : false,
            animationSpeed: 500,
            directionNav: true,
            prevText: '<span class="durotan-svg-icon"><svg	viewBox="0 0 64 64"><path d="M19.8,32.7c-0.4-0.4-0.4-1,0-1.4L47.1,2.5C47.6,1.9,47.6,1,47,0.4s-1.6-0.5-2.1,0.1L17.6,29.2c-1.4,1.5-1.4,4,0,5.5 l27.3,28.8c0.3,0.3,0.7,0.5,1.1,0.5c0.4,0,0.7-0.1,1-0.4c0.6-0.6,0.6-1.5,0.1-2.1L19.8,32.7z"/></svg>',
            nextText: '<span class="durotan-svg-icon"><svg viewBox="0 0 64 64"><path d="M46.4,29.2L19.1,0.5c-0.6-0.6-1.5-0.6-2.1-0.1c-0.6,0.6-0.6,1.5-0.1,2.1l27.3,28.8c0.4,0.4,0.4,1,0,1.4L16.9,61.5c-0.6,0.6-0.5,1.6,0.1,2.1c0.3,0.3,0.7,0.4,1,0.4c0.4,0,0.8-0.2,1.1-0.5l27.3-28.8C47.8,33.2,47.8,30.8,46.4,29.2z"/></svg></span>',
            rtl: false,
            slideshow: false,
            smoothHeight: true,
            start: function () {
                self.elements.$gallery.css('opacity', 1);
            },
        };
        this.elements.$gallery.flexslider(options);
    }

    getImageZoomInit($target) {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.zoom) {
            return;
        }


        const settings = this.getElementSettings();

        if (settings.show_image_zoom === 'show') {

            var zoom_options = jQuery.extend({
                touch: false
            });

            $target.trigger('zoom.destroy');
            $target.zoom(zoom_options);
        }
    }

    getVariationSwatcher() {
        var $variations = this.$element.find('.variations_form');
        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            $variations.find('td.value select').each(function () {
                jQuery(this).on('change', function () {
                    var value = jQuery(this).find('option:selected').text();
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').html(value);
                    jQuery(this).closest('tr').find('td.label .durotan-attr-value').data('value',jQuery(this).find('option:selected').val());

                    if (jQuery(this).closest('.variation-selector.hidden').length && jQuery(this).closest('.entry-summary').length) {
                        jQuery(this).closest('tr').find('td.label').show();
                    }
                }).trigger('change');
            });
        }
    }

    getSwiperInit() {
        var $thumbnail = this.$element.find('.flex-control-thumbs');

        if($thumbnail.children().length > this.elements.$gallery.data('columns')){
            $thumbnail.wrap('<div class="durotan-swiper-container"><div class="woocommerce-product-gallery__thumbs-carousel swiper-container linked-gallery-carousel"></div></div>');
            $thumbnail.addClass('swiper-wrapper');
            $thumbnail.find('li').addClass('swiper-slide');
            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-gallery-button-prev durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.467723 13.1881L10.2737 23.5074C10.8975 24.1642 11.9089 24.1642 12.5324 23.5074C13.1559 22.8512 13.1559 21.787 12.5324 21.1308L3.85554 11.9998L12.5321 2.86914C13.1556 2.21269 13.1556 1.14853 12.5321 0.492339C11.9086 -0.164113 10.8973 -0.164113 10.2735 0.492339L0.46747 10.8118C0.155705 11.14 0 11.5698 0 11.9998C0 12.43 0.156009 12.86 0.467723 13.1881Z"></path></svg></span>');
            $thumbnail.parent().after('<span class="durotan-svg-icon durotan-gallery-button-next durotan-swiper-button"><svg class="svg-icon" aria-hidden="true" role="img" focusable="false" width="13" height="24" viewBox="0 0 13 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.5323 13.1881L2.72626 23.5074C2.10248 24.1642 1.09112 24.1642 0.467647 23.5074C-0.155882 22.8512 -0.155882 21.787 0.467647 21.1308L9.14446 11.9998L0.467899 2.86914C-0.15563 2.21269 -0.15563 1.14853 0.467899 0.492339C1.09143 -0.164113 2.10273 -0.164113 2.72651 0.492339L12.5325 10.8118C12.8443 11.14 13 11.5698 13 11.9998C13 12.43 12.844 12.86 12.5323 13.1881Z"></path></svg></span>');
            jQuery('li', $thumbnail).append('<span/>');
            new Swiper(this.elements.$gallery.find('.linked-gallery-carousel'), this.getSwipperOptions());
        } else {
            if( jQuery(window).width() < 1025 ) {
                jQuery('li', $thumbnail).append('<span/>');
            }
        }
    }

    getLightBoxGalleryInit() {
        var self = this;
        const settings = this.getElementSettings();
        if (this.$element.closest('body').hasClass('elementor-editor-active')) {
            if (settings.show_lightbox !== 'show' && settings.show_image_zoom !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }

            if (settings.show_lightbox === 'show' && settings.show_image_zoom === 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > .zoomImg', function (e) {
                    jQuery(this).closest('.woocommerce-product-gallery__image').find('a').trigger('click');
                });

            }

        } else {

            if (settings.show_lightbox !== 'show') {
                this.elements.$gallery.on('click', '.woocommerce-product-gallery__image > a', function (e) {
                    return false;
                });
            }
        }
    }
    getCountDownInit() {

        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        if (!jQuery.fn.durotan_countdown) {
            return;
        }

        this.$element.find('.durotan-countdown').durotan_countdown();
    }

    onInit() {
        var self = this;

        super.onInit();

        this.getCountDownInit();

        this.getLightBoxGalleryInit();

        this.getFlexSliderInit();

        this.getVariationSwatcher();

        this.getImageZoomInit(this.elements.$gallery.find('.woocommerce-product-gallery__image'));

        this.elements.$gallery.imagesLoaded(function () {
            setTimeout(function () {
                self.getSwiperInit();
            }, 200);
        });
    }
}
class DurotanProductsGroupTabWidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                container: '.durotan-products-group-tabs'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $container: this.$element.find(selectors.container)
        };

    }
    getProductCarousel($selector, settings) {

        var self = this,
            $products = $selector.find('ul.products');

        $products.after('<span class="durotan-svg-icon durotan-swiper-button-prev durotan-swiper-button durotan-swiper-button--out"><svg viewBox="0 0 64 64"><path d="M19.8,32.7c-0.4-0.4-0.4-1,0-1.4L47.1,2.5C47.6,1.9,47.6,1,47,0.4s-1.6-0.5-2.1,0.1L17.6,29.2c-1.4,1.5-1.4,4,0,5.5 l27.3,28.8c0.3,0.3,0.7,0.5,1.1,0.5c0.4,0,0.7-0.1,1-0.4c0.6-0.6,0.6-1.5,0.1-2.1L19.8,32.7z"></path></svg></span>');
        $products.after('<span class="durotan-svg-icon durotan-swiper-button-next durotan-swiper-button durotan-swiper-button--out"><svg aria-hidden="true" role="img" focusable="false" viewBox="0 0 64 64"><path d="M46.4,29.2L19.1,0.5c-0.6-0.6-1.5-0.6-2.1-0.1c-0.6,0.6-0.6,1.5-0.1,2.1l27.3,28.8c0.4,0.4,0.4,1,0,1.4L16.9,61.5c-0.6,0.6-0.5,1.6,0.1,2.1c0.3,0.3,0.7,0.4,1,0.4c0.4,0,0.8-0.2,1.1-0.5l27.3-28.8C47.8,33.2,47.8,30.8,46.4,29.2z"></path></svg></span>');
        $products.wrap('<div class="swiper-container linked-products-carousel" style="opacity: 0;"></div>');
        $products.addClass('swiper-wrapper');
        $products.find('li.product').addClass('swiper-slide');
        $products.after('<div class="swiper-pagination"></div>');
        var options = {
            loop: settings.infinite == 'yes' ? true : false,
            autoplay: settings.autoplay == 'yes' ? true : false,
            speed: settings.speed ? settings.speed : 500,
            watchOverflow: true,
            pagination: {
                el: $selector.find('.swiper-pagination'),
                clickable: true
            },
            navigation: {
                nextEl: $selector.find('.durotan-swiper-button-next'),
                prevEl: $selector.find('.durotan-swiper-button-prev'),
            },
            spaceBetween: 30,
            on: {
                init: function () {
                    this.$el.css('opacity', 1);
                    let heights = [];
                    if(this.slides.length){
                        this.slides.each(function(i) {
                            let $image = jQuery(this).find('.attachment-woocommerce_thumbnail')
                            heights.push($image.height());
                            if( i + 1 >= settings.slidesToShow) {
                                return false;
                            }
                        })
                        let counts = {};
                        heights.forEach(function(x) { counts[x] = (counts[x] || 0)+1; });
                        let heightImg = Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b);
                        if(heightImg > 0){
                            let posTop = heightImg/2;
                        }
                    }
                },
                resize: function() {
                }
            },
            breakpoints: {
                300: {
                    slidesPerView: settings.slidesToShow_mobile ? settings.slidesToShow_mobile : durotanData.mobile_portrait,
                    slidesPerGroup: settings.slidesToScroll_mobile ? settings.slidesToScroll_mobile : durotanData.mobile_portrait,
                    slidesPerColumn: settings.slidesToRow_mobile ? settings.slidesToRow_mobile : 1,
                    spaceBetween: 20,
                },
                480: {
                    slidesPerView: settings.slidesToShow_mobile ? settings.slidesToShow_mobile : durotanData.mobile_portrait,
                    slidesPerGroup: settings.slidesToScroll_mobile ? settings.slidesToScroll_mobile : durotanData.mobile_portrait,
                    slidesPerColumn: settings.slidesToRow_mobile ? settings.slidesToRow_mobile : 1,
                },
                768: {
                    slidesPerView: settings.slidesToShow_tablet ? settings.slidesToShow_tablet : 3,
                    slidesPerGroup: settings.slidesToScroll_tablet ? settings.slidesToScroll_tablet : 3,
                    slidesPerColumn: settings.slidesToRow_tablet ? settings.slidesToRow_tablet : 1,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                    slidesPerColumn: settings.slidesToRow,
                    spaceBetween: 30,
                },
                1400: {
                    slidesPerView: settings.slidesToShow,
                    slidesPerGroup: settings.slidesToScroll,
                    slidesPerColumn: settings.slidesToRow,
                }
            },
            slidesPerColumnFill: 'row'
        };

        new Swiper(this.elements.$container.find('.linked-products-carousel'), options);
    };

     /**
     * Get Product AJAX
     */
    getProductsAJAXHandler($el, $tabs) {
        var self = this,
            tab = $el.data('href'),
            $currentTab = $tabs.find('.tabs-' + tab),
            $tabContent = $tabs.find('.tabs-content');


        if ($currentTab.hasClass('tab-loaded')) {
            self.productTabs($tabs, $el, $currentTab);
            return;
        }

        $tabContent.addClass('loading');

        var data = {},
            elementSettings = $currentTab.data('settings'),
            ajax_url = durotanData.ajax_url.toString().replace('%%endpoint%%', 'durotan_elementor_get_products_tab');

        const settings = this.getElementSettings();

        jQuery.post(
            ajax_url,
            {
                settings: elementSettings
            },
            function (response) {
                if ( !response.success ) {
                    $tabContent.removeClass('loading');
                    return;
                }

                var content = response.data;

                $currentTab.prepend(content);

                self.getProductCarousel($currentTab, settings);


                $currentTab.addClass('tab-loaded');

                self.productTabs($tabs, $el, $currentTab);

                $tabContent.removeClass('loading');

                jQuery(document.body).trigger('durotan_products_loaded', [$currentTab.find( 'li.product' ), true]);
            }
        );
    };

    productTabs($tabs, $el, $currentTab) {
        $tabs.find('.tabs-nav a').removeClass('active');
        $el.addClass('active');
        $tabs.find('.tabs-panel').removeClass('active');
        $currentTab.addClass('active');
    }

    loadMoreProducts() {
        var ajax_url = durotanData.ajax_url.toString().replace('%%endpoint%%', 'durotan_elementor_get_products_tab');

        //var swiper = document.querySelector('.linked-products-carousel').swiper;
        var self = this.elements.$container;
        //console.log(swiper);
        // Load Products
        this.elements.$container.on('click', '.load-more-button', function (e) {

            e.preventDefault();

            var $el = jQuery(this),
                $settings = $el.closest('.tabs-panel').data('settings'),
                swiper = $el.closest('.tabs-panel').find('.linked-products-carousel')[0].swiper;

            if ($el.hasClass('loading')) {
                return;
            }
            $el.addClass('loading');
            jQuery.post(
                ajax_url,
                {
                    page: $el.attr('data-page'),
                    settings: $settings
                },
                function (response) {
                    if (!response) {
                        return;
                    }
                    $el.removeClass('loading');

                    var $data = jQuery(response.data),
                        $products = $data.find('li.product'),
                        $container = $el.closest('.tabs-panel'),
                        $wrapper = $container.find('ul.products'),
                        $page_number = $data.find('.page-number').data('page');

                    // If has products
                    if ($products.length) {
                        //$products.addClass('durotanFadeInUp');
                        console.log($products);
                        //$wrapper.append($products);

                        swiper.appendSlide($products);
                        swiper.destroy();

                        if ($page_number == '0') {
                            $el.remove();
                        } else {
                            $el.attr('data-page', $page_number);
                        }
                    }

                    jQuery(document.body).trigger('durotan_products_loaded', [$products, true]);
                }
            );
        });
    }
    productThumbnailsSlider(){
        var $thumbnails = this.$element.find('.tab-loaded.active .product-thumbnails--slider');

        if($thumbnails.length == 0){
            return;
        }
        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }
        var options = {
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

        $thumbnails.each(function() {
            options.navigation = {
                nextEl: jQuery(this).find('.durotan-product-loop-swiper-next'),
                prevEl: jQuery(this).find('.durotan-product-loop-swiper-prev'),
            }
            jQuery(this).find('.woocommerce-loop-product__link').addClass('swiper-slide');
            new Swiper(jQuery(this), options);
        });
    }
    onInit() {
        var self = this;
        const settings = this.getElementSettings();

        super.onInit();

        var $selector = this.elements.$container,
            $panels = $selector.find('.tab-loaded');

        self.getProductCarousel($panels, settings);

        $selector.find('.tabs-nav').on('click', 'a', function (e) {
            e.preventDefault();
            self.getProductsAJAXHandler(jQuery(this), $selector);
        });

        self.loadMoreProducts();

        $selector.find('.tab-loaded.active').imagesLoaded(function () {
            setTimeout(function () {
                self.productThumbnailsSlider();
            }, 200);
        });
    }
}
class DurotanProductCarouselWidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                container: '.durotan-product-carousel'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $container: this.$element.find(selectors.container)
        };

    }

    getProductSwiperInit() {
        const settings = this.getElementSettings();

        var $products = this.elements.$container.find('ul.products'),
            $slidesToShow_mobile = settings.slidesToShow_mobile ? settings.slidesToShow_mobile : 1,
            $slidesToShow_tablet = settings.slidesToShow_tablet ? settings.slidesToShow_tablet : 2;

        $products.wrap('<div class="swiper-container linked-elementor-product-carousel" style="opacity: 0;"></div>');
        $products.addClass('swiper-wrapper');
        $products.find('li.product').addClass('swiper-slide');
        $products.after('<div class="swiper-pagination"></div>');
        var options = {
            watchOverflow: true,
            loop: settings.infinite == 'yes' ? true : false,
            autoplay: settings.autoplay == 'yes' ? true : false,
            speed: settings.speed,
            spaceBetween: 30,
            navigation: {
                nextEl: this.elements.$container.find('.durotan-swiper-button-next'),
                prevEl: this.elements.$container.find('.durotan-swiper-button-prev'),
            },
            pagination: {
                el: this.elements.$container.find('.swiper-pagination'),
                clickable: true
            },
            on: {
                init: function () {
                    this.$el.css('opacity', 1);
                }
            },
            breakpoints: {
                300: {
                    slidesPerView: settings.auto_mode_mobile ? 'auto' : $slidesToShow_mobile,
                    slidesPerGroup: settings.slidesToScroll_mobile ? settings.slidesToScroll_mobile : 1,
                    spaceBetween: 20,
                },
                480: {
                    slidesPerView: settings.auto_mode_mobile ? 'auto' : $slidesToShow_mobile,
                    slidesPerGroup: settings.slidesToScroll_mobile ? settings.slidesToScroll_mobile : 1,
                },
                768: {
                    slidesPerView: settings.auto_mode_tablet ? 'auto' : $slidesToShow_tablet,
                    slidesPerGroup: settings.slidesToScroll_tablet ? settings.slidesToScroll_tablet : 2,
                },
                1024: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                    spaceBetween: 30,
                },
                1200: {
                    slidesPerView:  settings.auto_mode ? 'auto' : settings.slidesToShow > 5 ? 5 : settings.slidesToShow,
                    slidesPerGroup: settings.slidesToScroll > 4 ? 4 : settings.slidesToScroll
                },
                1500: {
                    slidesPerView:  settings.auto_mode ? 'auto' : settings.slidesToShow,
                    slidesPerGroup: settings.slidesToScroll
                }
            }
        };

        new Swiper(this.elements.$container.find('.linked-elementor-product-carousel'), options);
    }
    productThumbnailsSlider(){
        var $thumbnails = this.$element.find('.product-thumbnails--slider');

        if($thumbnails.length == 0){
            return;
        }
        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }
        var options = {
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

        $thumbnails.each(function() {
            options.navigation = {
                nextEl: jQuery(this).find('.durotan-product-loop-swiper-next'),
                prevEl: jQuery(this).find('.durotan-product-loop-swiper-prev'),
            }
            jQuery(this).find('.woocommerce-loop-product__link').addClass('swiper-slide');
            new Swiper(jQuery(this), options);
        });
    }
    onInit() {
        var self = this;
        super.onInit();

        self.getProductSwiperInit();

        this.elements.$container.find('ul.products').imagesLoaded(function () {
            setTimeout(function () {
                self.productThumbnailsSlider();
            }, 200);
        });
    }
}
class DurotanProductGridWidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                container: '.durotan-product-grid'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $container: this.$element.find(selectors.container)
        };
    }

    productThumbnailsSlider(){
        var $thumbnails = this.$element.find('.product-thumbnails--slider');

        if( $thumbnails.length == 0 ){
            return;
        }
        if (!this.$element.closest('body').hasClass('elementor-editor-active')) {
            return;
        }

        var options = {
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

        $thumbnails.each(function() {
            options.navigation = {
                nextEl: jQuery(this).find('.durotan-product-loop-swiper-next'),
                prevEl: jQuery(this).find('.durotan-product-loop-swiper-prev'),
            }
            jQuery(this).find('.woocommerce-loop-product__link').addClass('swiper-slide');
            new Swiper(jQuery(this), options);
        });
    }
    onInit() {
        super.onInit();

        var self = this,
            $selector = this.elements.$container;

        $selector.find('ul.products').imagesLoaded(function () {
            setTimeout(function () {
                self.productThumbnailsSlider();
            }, 200);
        });
    }
}
jQuery(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/durotan-product-group-tab.default', ($element) => {
        elementorFrontend.elementsHandler.addHandler(DurotanProductsGroupTabWidgetHandler, {$element});
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/durotan-product-shortcode.default', ($element) => {
        elementorFrontend.elementsHandler.addHandler(DurotanProductShortcodeWidgetHandler, {$element});
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/durotan-product-shortcode-2.default', ($element) => {
        elementorFrontend.elementsHandler.addHandler(DurotanProductShortcode2WidgetHandler, {$element});
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/durotan-product-shortcode-3.default', ($element) => {
        elementorFrontend.elementsHandler.addHandler(DurotanProductShortcode3WidgetHandler, {$element});
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/durotan-product-shortcode-4.default', ($element) => {
        elementorFrontend.elementsHandler.addHandler(DurotanProductShortcode4WidgetHandler, {$element});
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/durotan-product-carousel.default', ($element) => {
        elementorFrontend.elementsHandler.addHandler(DurotanProductCarouselWidgetHandler, {$element});
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/durotan-product-grid.default', ($element) => {
        elementorFrontend.elementsHandler.addHandler(DurotanProductGridWidgetHandler, {$element});
    });
});
