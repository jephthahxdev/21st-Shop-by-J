class DurotanSwiperCarouselOption {
	getOptions(selector, settings, breakpoints, check = true, checkBreak = true){
		const	options = {
					loop            : 'yes' === settings.infinite,
					autoplay        : 'yes' === settings.autoplay,
					speed           : settings.autoplay_speed,
					watchOverflow   : true,
					pagination: {
					   el: selector.find('.swiper-pagination'),
					   type: 'bullets',
					   clickable: true
					},
					on              : {
						init: function() {
							selector.css( 'opacity', 1 );
						}
					},
					breakpoints     : {}
				};

		if (check){
			options.navigation = {
				nextEl: selector.find('.durotan-swiper-button-next'),
				prevEl: selector.find('.durotan-swiper-button-prev'),
			}
		}

		if (checkBreak){
			options.breakpoints[breakpoints.xs] = { slidesPerView: settings.slidesToShow_mobile, slidesPerGroup: settings.slidesToScroll_mobile  };
			options.breakpoints[breakpoints.md] = { slidesPerView: settings.slidesToShow_tablet, slidesPerGroup: settings.slidesToScroll_tablet  };
			options.breakpoints[breakpoints.lg] = { slidesPerView: settings.slidesToShow, slidesPerGroup: settings.slidesToScroll };
		}

		return options;
	}
}

class DurotanMapWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-map'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	get_coordinates(){
		var	self = this,
			el = self.elements.$container,
			elsMap = el.data('map'),
			local = elsMap.local,
			mapboxClient = mapboxSdk( { accessToken: elsMap.token } ),
			wrapper = el.find('.durotan-map__content').attr('id');

			mapboxgl.accessToken = elsMap.token;

		el.each( function() {
			mapboxClient.geocoding.forwardGeocode( {
				query       : local,
				autocomplete: false,
				limit       : 1
			} )
				.send()
				.then( function ( response ) {
					if ( response && response.body && response.body.features && response.body.features.length ) {
						var feature = response.body.features[0];

						var item = {
								"type"    : "Feature",
								"geometry": {
									"type"       : "Point",
									"coordinates": feature.center
								}
						};

						var center = [feature.center[0],feature.center[1]];

						var map = new mapboxgl.Map( {
							container: wrapper,
							style    : 'mapbox://styles/mapbox/'+ elsMap.mode ,
							center   : center,
							zoom     : elsMap.zoom
						} );

						var geocoder = new MapboxGeocoder( {
							accessToken: mapboxgl.accessToken
						} );

						map.addControl( geocoder );

						map.on( 'load', function () {
							map.loadImage( elsMap.marker, function ( error ) {
								if ( error ) throw error;

								map.addLayer( {
									"id"    : "points",
									"type"  : "symbol",
									"source": {
										"type": "geojson",
										"data": {
											"type"    : "FeatureCollection",
											"features": item
										}
									},
								} );
							} );

							map.addSource( 'single-point', {
								"type": "geojson",
								"data": {
									"type"    : "FeatureCollection",
									"features": []
								}
							} );

							map.addLayer( {
								"id"    : "point",
								"source": "single-point",
								"type"  : "circle",
								"paint" : {
									"circle-radius": 10,
									"circle-color" : "#007cbf"
								}
							} );

							map.setPaintProperty( 'water', 'fill-color', elsMap.color_1 );

							geocoder.on( 'result', function ( ev ) {
								map.getSource( 'single-point' ).setData( ev.result.geometry );
							} );
						} );
					}
				} );
		} );
	}

	onInit() {
		super.onInit();
		this.get_coordinates();
	}
}

class DurotanImageBoxCarouselWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-image-box-carousel'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSwiperOption() {
		let el = new DurotanSwiperCarouselOption();

		const ops = el.getOptions(this.elements.$container, this.getElementSettings(), elementorFrontend.config.breakpoints);

		return ops;
	}

	getSwiperInit() {
		new Swiper( this.elements.$container, this.getSwiperOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		this.getSwiperInit();
	}
}

class DurotanCountDownWidgetHandler extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-countdown'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getCountDownInit() {
		this.elements.$container.durotan_countdown();
	}

	onInit() {
		super.onInit();
		this.getCountDownInit();
	}
}

class DurotanCountDownBannerWidgetHandler extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-countdown-banner'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getCountDownInit() {
		this.elements.$container.find('.durotan-countdown-banner__countdown').durotan_countdown();
	}

	onInit() {
		super.onInit();
		this.getCountDownInit();
	}
}

class DurotanSlidesWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-swiper-carousel-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSwiperOption() {

		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();

		const 	options = {
			slidesPerView   : 'auto',
			slidesPerGroup  : 1,
			loop            : 'yes' === settings.infinite,
			autoplay        : 'yes' === settings.autoplay,
			speed           : settings.autoplay_speed,
			lazy            : 'yes' === settings.lazyload,
			navigation: {
				nextEl: container.find('.durotan-swiper-button-next'),
				prevEl: container.find('.durotan-swiper-button-prev'),
			},
			pagination: {
				el: container.find('.durotan-slide__pagination'),
				clickable: true,
				renderBullet: function (index, className) {
					return '<span class="durotan-slide__pagination-bullet ' + className + '"></span>';
				},
			},
			effect : settings.effect,
			fadeEffect: {
				crossFade: true
			},
			on              : {
				init: function() {
					container.css( 'opacity', 1 );
				},
				beforeSlideChangeStart: function () {
					var $total = container.find('.durotan-slider-item:not(.swiper-slide-duplicate)').length;

					container.find('.durotan-slide__fraction-total').text($total);
				},
				slideChange: function () {
					var $total = container.find('.durotan-slider-item:not(.swiper-slide-duplicate)').length,
						$current = this.realIndex + 1;

					container.find('.durotan-slide__fraction-current').text($current);
					container.find('.durotan-slide__fraction-total').text($total);
				},
			},
		};

		if( settings.autoplay && settings.delay ) {
			options.autoplay = {
				delay: settings.delay,
				disableOnInteraction: false,
			};
		}

		return options;
	}

	getSliderTabs() {

		this.elements.$container.on( 'click', '.durotan-slide__image-swatches-link', function( e ) {
			e.preventDefault();

			var $tab = jQuery( this ),
				$panels = $tab.parent().siblings( '.durotan-slide-price-wrapper' ),
				$panelsBg = $tab.closest('.durotan-slider-item').find( '.durotan-sliders-bg-wrapper' );

			if ( $tab.hasClass( 'active' ) ) {
				return;
			}

			$tab.addClass( 'active' ).siblings().removeClass( 'active' );
			$panels.children().eq( $tab.index() ).addClass( 'active' ).siblings().removeClass( 'active' );
			$panelsBg.children().eq( $tab.index() ).addClass( 'active' ).siblings().removeClass( 'active' );
		} );
	}

	getPopupOption() {
		const 	options = {
					type: 'iframe',
	            	mainClass: 'mfp-fade',
		            removalDelay: 300,
		            preloader: false,
		            fixedContentPos: false,
		            disableOn: 700
				};

		return options;
	}

	getParallax() {
		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();
		if ( 'yes' !== settings.parallax ) {
			return;
		}

		jQuery(window).on('resize load', function() {
            var top = 0,
				fHeight = container.outerHeight(),
				hHeight = jQuery('.site-header').outerHeight(),
				cbHeight = jQuery('.durotan-campaign-bar').outerHeight();

			if ( ! cbHeight ) {
				cbHeight = 0;
			}

			if ( jQuery('body').hasClass('header-transparent') ) {
				top = cbHeight;
			} else {
				top = hHeight + cbHeight;
				fHeight = fHeight + hHeight + cbHeight;
			}

            jQuery('.durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', top );
            jQuery('.admin-bar .durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', ( top + 32 ) );

			if( jQuery(window).width() < 783 ) {
				jQuery('.admin-bar .durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', ( top + 46 ) );
			}
        });
	}

	getPositionParallax() {
		const 	self 		= this;
		const 	settings 	= self.getElementSettings();
		if ( 'yes' !== settings.parallax ) {
			return;
		}

		jQuery(window).on('scroll', function (e) {
			var container = 0,
				hHeight = jQuery('.site-header').outerHeight(),
				cbHeight = jQuery('.durotan-campaign-bar').outerHeight(),
				windows = jQuery(window).scrollTop();

			if ( ! cbHeight ) {
				cbHeight = 0;
			}

			if ( jQuery('body').hasClass('header-transparent') ) {
				container = cbHeight;
			} else {
				container = hHeight + cbHeight;
			}

			if ( jQuery('.admin-bar').length == 1 ) {

				if( jQuery(window).width() < 783 ) {
					container = container + 46;
				} else {
					container = container + 32;
				}
			}

			scroll = container - windows;

			if ( ! jQuery('body').hasClass('header-transparent') || jQuery('.durotan-campaign-bar').length == 1 ) {

				if ( scroll > 0 ) {
					jQuery('.durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', scroll );
				}
			}
		});
	}

	getSwiperInit() {
		new Swiper( this.elements.$container, this.getSwiperOption() );
	}

	getPopupInit() {
		this.elements.$container.find('.durotan-slide__play-video-button').magnificPopup( this.getPopupOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		this.getSwiperInit();

		this.getSliderTabs();

		this.getPopupInit();

		this.getParallax();

		this.getPositionParallax();
	}
}

class DurotanSlides2WidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-slides2-carousel-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSlides2Option() {

		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();

		const 	options = {
			menu: null,
			direction: settings.direction,
			easing: settings.effect,
			loopTop: true,
			loopBottom: true,
			navigation: true,
			afterLoad: function(anchorLink, index){
				container.find('.durotan-slide__fraction-current').text(index);
			},
		};

		return options;
	}

	getArrowSlider() {
		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();
		jQuery(function() {
			container.on('click', '.durotan-slides2-button-prev', function(){
				jQuery.fn.pagepiling.moveSectionUp();
			});
			container.on('click', '.durotan-slides2-button-next', function(){
				jQuery.fn.pagepiling.moveSectionDown();
			});
			jQuery.fn.pagepiling.setAllowScrolling(settings.allowscrolling);

			var $total = container.find('.durotan-slider-item').length;
			container.find('.durotan-slide__fraction-total').text($total);
		});
	}

	getSliderTabs() {

		this.elements.$container.on( 'click', '.durotan-slide__image-swatches-link', function( e ) {
			e.preventDefault();

			var $tab = jQuery( this ),
				$panels = $tab.parent().siblings( '.durotan-slide-price-wrapper' ),
				$panelsBg = $tab.closest('.durotan-slider-items').find( '.durotan-sliders-bg-wrapper' );

			if ( $tab.hasClass( 'active' ) ) {
				return;
			}

			$tab.addClass( 'active' ).siblings().removeClass( 'active' );
			$panels.children().eq( $tab.index() ).addClass( 'active' ).siblings().removeClass( 'active' );
			$panelsBg.children().eq( $tab.index() ).addClass( 'active' ).siblings().removeClass( 'active' );
		} );
	}

	getPopupOption() {
		const 	options = {
					type: 'iframe',
	            	mainClass: 'mfp-fade',
		            removalDelay: 300,
		            preloader: false,
		            fixedContentPos: false,
		            disableOn: 700
				};

		return options;
	}

	getParallax() {
		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();
		if ( 'yes' !== settings.parallax ) {
			return;
		}

		jQuery(window).on('resize load', function() {
            var top = 0,
				fHeight = container.outerHeight(),
				hHeight = jQuery('.site-header').outerHeight(),
				cbHeight = jQuery('.durotan-campaign-bar').outerHeight();

			if ( ! cbHeight ) {
				cbHeight = 0;
			}

			if ( jQuery('body').hasClass('header-transparent') ) {
				top = cbHeight;
			} else {
				top = hHeight + cbHeight;
				fHeight = fHeight + hHeight + cbHeight;
			}

            jQuery('.durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', top );
            jQuery('.admin-bar .durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', ( top + 32 ) );

			if( jQuery(window).width() < 783 ) {
				jQuery('.admin-bar .durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', ( top + 46 ) );
			}
        });
	}

	getPositionParallax() {
		const 	self 		= this;
		const 	settings 	= self.getElementSettings();
		if ( 'yes' !== settings.parallax ) {
			return;
		}

		jQuery(window).on('scroll', function (e) {
			var container = 0,
				hHeight = jQuery('.site-header').outerHeight(),
				cbHeight = jQuery('.durotan-campaign-bar').outerHeight(),
				windows = jQuery(window).scrollTop();

			if ( ! cbHeight ) {
				cbHeight = 0;
			}

			if ( jQuery('body').hasClass('header-transparent') ) {
				container = cbHeight;
			} else {
				container = hHeight + cbHeight;
			}

			if ( jQuery('.admin-bar').length == 1 ) {

				if( jQuery(window).width() < 783 ) {
					container = container + 46;
				} else {
					container = container + 32;
				}
			}

			scroll = container - windows;

			if ( ! jQuery('body').hasClass('header-transparent') || jQuery('.durotan-campaign-bar').length == 1 ) {

				if ( scroll > 0 ) {
					jQuery('.durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', scroll );
				}
			}
		});
	}

	getSlides2Init() {
		this.elements.$container.find('.slides2-wrapper').pagepiling( this.getSlides2Option() );
	}

	getPopupInit() {
		this.elements.$container.find('.durotan-slide__play-video-button').magnificPopup( this.getPopupOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}
		this.getSlides2Init();

		this.getArrowSlider();

		this.getSliderTabs();

		this.getPopupInit();

		this.getParallax();

		this.getPositionParallax();
	}
}

class DurotanSliderProductWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-slider-product-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSliderProductOption() {

		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();

		const 	options = {
			menu: null,
			direction: settings.direction,
			scrollingSpeed: settings.autoplay_speed,
			easing: settings.effect,
			loopTop: true,
			loopBottom: true,
			navigation: true,
			normalScrollElements: '.durotan-slider-product__show-scroll-product-group-yes div.product-type-variable table.variations, .durotan-slider-product__show-scroll-product-group-yes div.product-type-grouped table',
			afterLoad: function(anchorLink, index){
				container.find('.durotan-slide__fraction-current').text(index);
			},
		};

		return options;
	}

	getArrowSlider() {
		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();
		jQuery(function() {
			jQuery.fn.pagepiling.setAllowScrolling(settings.allowscrolling);

			var $total = container.find('.durotan-slider-item').length;
			container.find('.durotan-slide__fraction-total').text($total);
		});
	}

	getParallax() {
		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();
		if ( 'yes' !== settings.parallax ) {
			return;
		}

		jQuery(window).on('resize load', function() {
            var top = 0,
				fHeight = container.outerHeight(),
				hHeight = jQuery('.site-header').outerHeight(),
				cbHeight = jQuery('.durotan-campaign-bar').outerHeight();

			if ( ! cbHeight ) {
				cbHeight = 0;
			}

			if ( jQuery('body').hasClass('header-transparent') ) {
				top = cbHeight;
			} else {
				top = hHeight + cbHeight;
				fHeight = fHeight + hHeight + cbHeight;
			}

            jQuery('.durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', top );
            jQuery('.admin-bar .durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', ( top + 32 ) );

			if( jQuery(window).width() < 783 ) {
				jQuery('.admin-bar .durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', ( top + 46 ) );
			}
        });
	}

	getPositionParallax() {
		const 	self 		= this;
		const 	settings 	= self.getElementSettings();
		if ( 'yes' !== settings.parallax ) {
			return;
		}

		jQuery(window).on('scroll', function (e) {
			var container = 0,
				hHeight = jQuery('.site-header').outerHeight(),
				cbHeight = jQuery('.durotan-campaign-bar').outerHeight(),
				windows = jQuery(window).scrollTop();

			if ( ! cbHeight ) {
				cbHeight = 0;
			}

			if ( jQuery('body').hasClass('header-transparent') ) {
				container = cbHeight;
			} else {
				container = hHeight + cbHeight;
			}

			if ( jQuery('.admin-bar').length == 1 ) {

				if( jQuery(window).width() < 783 ) {
					container = container + 46;
				} else {
					container = container + 32;
				}
			}

			scroll = container - windows;

			if ( ! jQuery('body').hasClass('header-transparent') || jQuery('.durotan-campaign-bar').length == 1 ) {

				if ( scroll > 0 ) {
					jQuery('.durotan-slider-carousel--parallax-yes .durotan-slider--parallax').css( 'top', scroll );
				}
			}
		});
	}

	getSliderProductInit() {
		this.elements.$container.find('.durotan-slider-product__wrapper').pagepiling( this.getSliderProductOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}
		this.getSliderProductInit();

		this.getArrowSlider();

		this.getParallax();

		this.getPositionParallax();
	}
}

class DurotanBannerShoppableWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-banner-shoppable-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	hotSpotHandle(){
		const 	container = this.elements.$container;
		const 	item = container.find('.durotan-hotspot__point');

        container.on('click', '.durotan-hotspot__point-icon', function (e) {
            var el = jQuery(this).closest('.durotan-hotspot__point'),
                siblings = el.siblings();

            el.toggleClass('active');
            siblings.removeClass('active');
        });

        container.on('click', '.durotan-hotspot__button-close .durotan-svg-icon', function (e) {
            item.removeClass('active');
        });
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		this.hotSpotHandle();
	}
}

class DurotanVideoBannerWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-video-banner-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	videoBannerHandle(){
		const 	container = this.elements.$container;
		const 	videoIframe = container.find('.durotan-video-banner__video');

		if ( videoIframe.attr('data-video-source') === 'youtube' ) {
			videoIframe.html('<iframe id="videoPlay" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" width="640" height="360" src="'+videoIframe.attr('data-video-url')+'?enablejsapi=1&version=3&playerapiid=ytplayer&showinfo=0&fs=0&modestbranding=1&rel=0&loop=1&controls=0&mute=1&autohide=1"></iframe>');
		} else if ( videoIframe.attr('data-video-source') === 'vimeo' ) {
			videoIframe.html('<iframe id="videoPlay" frameborder="0" allowfullscreen="1" height="100%" src="'+videoIframe.attr('data-video-url')+'?background=1&autoplay=1&loop=1&api=1&title=0&portrait=0&byline=0" allow="autoplay;fullscreen" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
		} else {
			videoIframe.html('<video id="videoPlay" height="100%" src="'+videoIframe.attr('data-video-url')+'" autoplay="true" muted="muted" allow="autoplay"></video>');
		}

		jQuery(window).on('scroll load', function() {
			videoIframe.each( function(i){
				var scroll_position = jQuery(window).scrollTop();
				var bottom_of_video = jQuery(videoIframe).offset().top + (jQuery(videoIframe).outerHeight() / 2);
				var bottom_of_window3 = jQuery(window).scrollTop() + jQuery(window).height();

				if( bottom_of_window3 > bottom_of_video && scroll_position < bottom_of_video ) {
					if ( videoIframe.attr('data-video-source') === 'youtube' ) {
						jQuery('#videoPlay')[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
					} else if ( videoIframe.attr('data-video-source') === 'vimeo' ) {
						jQuery('#videoPlay')[0].contentWindow.postMessage('{"method":"play"}', '*');
					} else {
						document.getElementById("videoPlay").play();
					}
				} else {
					if ( videoIframe.attr('data-video-source') === 'youtube' ) {
						jQuery('#videoPlay')[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
					} else if ( videoIframe.attr('data-video-source') === 'vimeo' ) {
						jQuery('#videoPlay')[0].contentWindow.postMessage('{"method":"pause"}', '*');
					} else {
						document.getElementById("videoPlay").pause();
					}
				}
			});
		});
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		this.videoBannerHandle();

	}
}

class DurotanTwitterCarouselWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-twitter-carousel-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSwiperOption() {

		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();

		const 	options = {
			spaceBetween    : settings.space_between,
			slidesPerView   : settings.slide_to_show,
			slidesPerGroup  : settings.slide_to_scroll,
			loop            : 'yes' === settings.infinite,
			autoplay        : 'yes' === settings.autoplay,
			speed           : settings.autoplay_speed,
			navigation: {
				nextEl: container.find('.durotan-swiper-button-next'),
				prevEl: container.find('.durotan-swiper-button-prev'),
			},
			pagination: {
				el: container.find('.durotan-twitter-carousel__pagination'),
				clickable: true,
				renderBullet: function (index, className) {
					return '<span class="durotan-twitter-carousel__pagination-bullet ' + className + '"></span>';
				},
			},
			on              : {
				init: function() {
					container.css( 'opacity', 1 );
				},
			},
			breakpoints     : {
				0: {
					slidesPerView: settings.slide_to_show_mobile  ? settings.slide_to_show_mobile : 1,
					spaceBetween:  settings.space_between_mobile  ? settings.space_between_mobile : 20,
				},
				481: {
					slidesPerView: settings.slide_to_show_tablet  ? settings.slide_to_show_tablet : 2,
					spaceBetween:  settings.space_between_tablet  ? settings.space_between_tablet : 20,
				},
				1025: {
					slidesPerView: settings.slide_to_show  ? settings.slide_to_show : 2,
					spaceBetween:  settings.space_between  ? settings.space_between : 80,
				},
			},
		};

		return options;
	}

	getSwiperInit() {
		new Swiper( this.elements.$container.find('.durotan-twitter-carousel__items'), this.getSwiperOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		this.getSwiperInit();
	}
}

class DurotanTwitterCarousel2WidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-twitter-carousel-2-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSwiperOption() {

		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();

		const 	options = {
			loop            : 'yes' === settings.infinite,
			autoplay        : 'yes' === settings.autoplay,
			speed           : settings.autoplay_speed,
			navigation: {
				nextEl: container.find('.durotan-swiper-button-next'),
				prevEl: container.find('.durotan-swiper-button-prev'),
			},
			pagination: {
				el: container.find('.durotan-twitter-carousel-2__pagination'),
				clickable: true,
				renderBullet: function (index, className) {
					return '<span class="durotan-twitter-carousel-2__pagination-bullet ' + className + '"></span>';
				},
			},
			on              : {
				init: function() {
					container.css( 'opacity', 1 );
				},
			},
		};

		return options;
	}

	getSwiperInit() {
		new Swiper( this.elements.$container.find('.durotan-twitter-carousel-2__items'), this.getSwiperOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		this.getSwiperInit();
	}
}

class DurotanInstagramCarouselWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-instagram-carousel-elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSwiperOption() {

		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();

		const 	options = {
			spaceBetween    : settings.space_between,
			slidesPerView   : settings.slide_to_show,
			slidesPerGroup  : settings.slide_to_scroll,
			loop            : 'yes' === settings.infinite,
			autoplay        : 'yes' === settings.autoplay,
			speed           : settings.autoplay_speed,
			navigation: {
				nextEl: container.find('.durotan-swiper-button-next'),
				prevEl: container.find('.durotan-swiper-button-prev'),
			},
			pagination: {
				el: container.find('.durotan-instagram-carousel__pagination'),
				clickable: true,
				renderBullet: function (index, className) {
					return '<span class="durotan-instagram-carousel__pagination-bullet ' + className + '"></span>';
				},
			},
			scrollbar: {
				el: ".swiper-scrollbar",
				hide: false,
			},
			on              : {
				init: function() {
					container.css( 'opacity', 1 );
				},
			},
			breakpoints     : {
				0: {
					slidesPerView: settings.slide_to_show_mobile  ? settings.slide_to_show_mobile : 3,
					slidesPerGroup: settings.slide_to_scroll_mobile  ? settings.slide_to_scroll_mobile : 3,
				},
				481: {
					slidesPerView: settings.slide_to_show_tablet  ? settings.slide_to_show_tablet : 4,
					slidesPerGroup: settings.slide_to_scroll_tablet  ? settings.slide_to_scroll_tablet : 4,
				},
				1025: {
					slidesPerView: settings.slide_to_show  ? settings.slide_to_show : 5,
					slidesPerGroup: settings.slide_to_scroll  ? settings.slide_to_scroll : 1,
				},
			},
		};

		return options;
	}

	getSwiperInit() {
		new Swiper( this.elements.$container.find('.durotan-instagram-carousel__items'), this.getSwiperOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		this.getSwiperInit();
	}
}

class DurotanPostCarouselWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-posts-carousel__elementor'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSwiperOption() {

		let el = new DurotanSwiperCarouselOption();
		let settings = this.getElementSettings();

		let checkBreak = settings.layout == 'layout-2' ? false : true ;
		const ops = el.getOptions(this.elements.$container, settings, elementorFrontend.config.breakpoints,true,checkBreak);

		if(settings.layout == 'layout-2'){
			ops.breakpoints[elementorFrontend.config.breakpoints.xs] = { slidesPerView: settings.slidesToShow_mobile, slidesPerGroup: settings.slidesToScroll_mobile};
			ops.breakpoints[elementorFrontend.config.breakpoints.md] = { slidesPerView: settings.slidesToShow_tablet, slidesPerGroup: settings.slidesToScroll_tablet, spaceBetween: 30};
			ops.breakpoints[elementorFrontend.config.breakpoints.lg] = { slidesPerView: settings.slidesToShow > 2 ? 2 : settings.slidesToShow, slidesPerGroup: settings.slidesToScroll > 2 ? 2 : settings.slidesToScroll};
			ops.breakpoints[elementorFrontend.config.breakpoints.xl] = { slidesPerView: settings.slidesToShow, slidesPerGroup: settings.slidesToScroll };
		}

		if(settings.layout == 'layout-3'){
			ops.breakpoints[elementorFrontend.config.breakpoints.xs] = { slidesPerView: settings.slidesToShow_mobile, slidesPerGroup: settings.slidesToScroll_mobile};
			ops.breakpoints[elementorFrontend.config.breakpoints.md] = { slidesPerView: settings.slidesToShow_tablet, slidesPerGroup: settings.slidesToScroll_tablet, spaceBetween: 30};
			ops.breakpoints[elementorFrontend.config.breakpoints.lg] = { slidesPerView: settings.slidesToShow > 2 ? 2 : settings.slidesToShow, slidesPerGroup: settings.slidesToScroll > 2 ? 2 : settings.slidesToScroll};
			ops.breakpoints[elementorFrontend.config.breakpoints.xl] = { slidesPerView: settings.slidesToShow, slidesPerGroup: settings.slidesToScroll };
		}

		ops.spaceBetween = 60;
		return ops;
	}

	getSwiperInit() {
		new Swiper( this.elements.$container.find('.list-posts'), this.getSwiperOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}

		let settings = this.getElementSettings();

		if( settings.limit > 2 ) {
			this.getSwiperInit();
		}

	}
}
class DurotanIconBoxListWidgetHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.durotan-icon-box-list'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$container: this.$element.find( selectors.container )
		};
	}

	getSwiperOption() {

		const 	self 		= this;
		const 	container 	= self.elements.$container;
		const 	settings 	= self.getElementSettings();

		const 	options = {
					//spaceBetween    : settings.space_between,
					loop            : 'yes' === settings.infinite,
					autoplay        : 'yes' === settings.autoplay,
					speed           : settings.autoplay_speed,
					watchOverflow   : true,
					pagination: {
					   el: container.find('.swiper-pagination'),
					   type: 'bullets',
					   clickable: true
					},
					scrollbar: {
						el: ".durotan-swiper-scrollbar",
						hide: false,
					},
					on              : {
						init: function() {
							container.find('.durotan-icon-box-list__wrapper').css( 'opacity', 1 );
						}
					},
					breakpoints     : {
	                    0: {
	                        slidesPerView : settings.slidesToShow_mobile  ? settings.slidesToShow_mobile : 2,
	                        slidesPerGroup: settings.slidesToScroll_mobile ? settings.slidesToScroll_mobile : 2
	                    },

	                    481 : {
	                        slidesPerView : settings.slidesToShow_tablet  ? settings.slidesToShow_tablet : 4,
	                        slidesPerGroup: settings.slidesToScroll_tablet ? settings.slidesToScroll_tablet : 4
	                    },

						1025: {
							slidesPerView: settings.slidesToShow ? settings.slidesToShow : 5,
							slidesPerGroup: settings.slidesToScroll ? settings.slidesToScroll : 5,
						},
	                }
				};

		return options;
	}

	getSwiperInit() {
		new Swiper( this.elements.$container.find('.durotan-icon-box-list__wrapper'), this.getSwiperOption() );
	}

	onInit() {
		super.onInit();

		if ( ! this.elements.$container.length ) {
			return;
		}
		this.getSwiperInit();
	}
}

jQuery( window ).on( 'elementor/frontend/init', () => {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-map.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanMapWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-image-box-carousel.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanImageBoxCarouselWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-countdown.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanCountDownWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-countdown-banner.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanCountDownBannerWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-slides.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanSlidesWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-slides2.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanSlides2WidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-slider-product.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanSliderProductWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-banner-shoppable.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanBannerShoppableWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-video-banner.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanVideoBannerWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-twitter-carousel.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanTwitterCarouselWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-twitter-carousel-2.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanTwitterCarousel2WidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-instagram-carousel.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanInstagramCarouselWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-posts-carousel.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanPostCarouselWidgetHandler, { $element } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/durotan-icon-box-list.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( DurotanIconBoxListWidgetHandler, { $element } );
	} );
} );