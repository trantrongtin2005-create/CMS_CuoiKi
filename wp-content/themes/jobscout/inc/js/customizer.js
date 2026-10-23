/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// Logo Color Live Preview
	wp.customize( 'logo_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-branding .site-title, .site-branding .site-title a' ).css( 'color', to );
		} );
	} );

	// Logo Hover Color Live Preview
	wp.customize( 'logo_hover_color', function( value ) {
		value.bind( function( to ) {
			var $style = $( '#jobscout-logo-hover-preview' );
			if ( ! $style.length ) {
				$style = $( '<style id="jobscout-logo-hover-preview"></style>' ).appendTo( 'head' );
			}
			$style.text( '.site-branding .site-title a:hover, .site-branding .site-title a:focus { color: ' + to + ' !important; }' );
		} );
	} );
} )( jQuery );
