jQuery( function( $ ) {
	'use strict';

	var $box = $( '#header-settings' );

	// Toggle header background field
	$( '#durotan_header_background' ).on( 'change', function( event ) {
		var $el = $( this );

		if ( $el.val() === 'transparent' ) {
			$( '.header-text-color', $box ).removeClass( 'hidden' );
		} else {
			$( '.header-text-color', $box ).addClass( 'hidden' );
		}

		if ( $el.val() === 'transparent' ) {
			$( '.header-background-color', $box ).removeClass( 'hidden' );
		} else {
            $( '.header-background-color', $box ).addClass( 'hidden' );
        }
	} ).trigger( 'change' );

	// Toggle spacing fields
	$( '#durotan_content_top_spacing, #durotan_content_bottom_spacing' ).on( 'change', function() {
		var $el = $( this );

		if ( 'custom' === $el.val() ) {
			$el.closest( '.rwmb-field' ).next( '.custom-spacing' ).slideDown();
		} else {
			$el.closest( '.rwmb-field' ).next( '.custom-spacing' ).slideUp();
		}
	} ).trigger( 'change' );
} );
