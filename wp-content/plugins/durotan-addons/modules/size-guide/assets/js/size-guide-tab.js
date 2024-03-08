jQuery( document ).ready( function( $ ) {
	$( '.durotan-size-guide-tabs' ).on( 'click', '.durotan-size-guide-tabs__nav li', function() {
        var $tab = $( this ),
            index = $tab.data( 'target' ),
            $panels = $tab.closest( '.durotan-size-guide-tabs' ).find( '.durotan-size-guide-tabs__panels' ),
            $panel = $panels.find( '.durotan-size-guide-tabs__panel[data-panel="' + index + '"]' );

        if ( $tab.hasClass( 'active' ) ) {
            return;
        }

        $tab.addClass( 'active' ).siblings( 'li.active' ).removeClass( 'active' );

        if ( $panel.length ) {
            $panel.addClass( 'active' ).siblings( '.durotan-size-guide-tabs__panel.active' ).removeClass( 'active' );
        }
    } );
} );    