<?php
/**
 * Colours, fonts and page width from Voyara > Settings, printed as CSS custom properties.
 *
 * Every colour in the theme, the Voyara Core widgets and Voyara Console reads a
 * `--vy-*` variable, so changing a colour in Voyara > Settings changes the whole site.
 * Hover colours are mixed in CSS (assets/css/theme.css), and the text colour on the
 * main and accent colours is picked here for contrast.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Colour option behind each CSS variable.
 *
 * @return array<string,string> Variable name => option id.
 */
function jwsthemes_voyara_color_vars() {
	return array(
		'--vy-main'   => 'jwsthemes_color_main',
		'--vy-accent' => 'jwsthemes_color_accent',
		'--vy-ink'    => 'jwsthemes_color_heading',
		'--vy-ink-2'  => 'jwsthemes_color_body',
		'--vy-muted'  => 'jwsthemes_color_muted',
		'--vy-line'   => 'jwsthemes_color_border',
		'--vy-sand'   => 'jwsthemes_color_soft',
		'--vy-cream'  => 'jwsthemes_color_light',
		'--vy-bg'     => 'jwsthemes_color_page',
		'--vy-dark'   => 'jwsthemes_color_dark',
	);
}

/**
 * A colour option as #RRGGBB, or '' when the saved value is not a hex colour.
 *
 * @param string $id Option id.
 * @return string
 */
function jwsthemes_voyara_color( $id ) {
	$color = sanitize_hex_color( (string) jwsthemes_voyara_option( $id ) );
	return $color ? strtoupper( $color ) : '';
}

/**
 * WCAG relative luminance of a hex colour.
 *
 * @param string $hex #RGB or #RRGGBB.
 * @return float 0 (black) to 1 (white).
 */
function jwsthemes_voyara_luminance( $hex ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$out = array();
	foreach ( str_split( str_pad( substr( $hex, 0, 6 ), 6, '0' ), 2 ) as $pair ) {
		$c     = hexdec( $pair ) / 255;
		$out[] = $c <= 0.03928 ? $c / 12.92 : ( ( $c + 0.055 ) / 1.055 ) ** 2.4;
	}
	return 0.2126 * $out[0] + 0.7152 * $out[1] + 0.0722 * $out[2];
}

/**
 * Contrast ratio between two hex colours.
 *
 * @param string $a Colour.
 * @param string $b Colour.
 * @return float 1 to 21.
 */
function jwsthemes_voyara_contrast( $a, $b ) {
	$la = jwsthemes_voyara_luminance( $a );
	$lb = jwsthemes_voyara_luminance( $b );
	return ( max( $la, $lb ) + 0.05 ) / ( min( $la, $lb ) + 0.05 );
}

/**
 * Text colour for a background: white when it reaches 4.5:1, otherwise black.
 *
 * @param string $background Hex colour.
 * @return string
 */
function jwsthemes_voyara_text_on( $background ) {
	return jwsthemes_voyara_contrast( $background, '#FFFFFF' ) >= 4.5 ? '#FFFFFF' : '#000000';
}

/**
 * The :root block with every variable from Voyara > Settings.
 *
 * A colour saved as something other than a hex colour is left out, so the CSS uses
 * the fallback value written next to each var() call.
 *
 * @return string CSS.
 */
function jwsthemes_voyara_brand_css() {
	$vars = array();
	foreach ( jwsthemes_voyara_color_vars() as $var => $id ) {
		$color = jwsthemes_voyara_color( $id );
		if ( $color ) {
			$vars[ $var ] = $color;
		}
	}
	if ( isset( $vars['--vy-main'] ) ) {
		$vars['--vy-on-main'] = jwsthemes_voyara_text_on( $vars['--vy-main'] );
	}
	if ( isset( $vars['--vy-accent'] ) ) {
		$vars['--vy-on-accent'] = jwsthemes_voyara_text_on( $vars['--vy-accent'] );
	}

	$fonts = jwsthemes_voyara_fonts();
	foreach ( array(
		'--vy-font-heading' => 'jwsthemes_font_heading',
		'--vy-font-body'    => 'jwsthemes_font_body',
	) as $var => $id ) {
		$key = jwsthemes_voyara_option( $id );
		if ( isset( $fonts[ $key ] ) ) {
			$vars[ $var ] = $fonts[ $key ]['stack'];
		}
	}

	$vars['--vy-font-size'] = min( 20, max( 14, jwsthemes_voyara_option( 'jwsthemes_font_size' ) ) ) . 'px';
	$vars['--vy-container'] = min( 1920, max( 960, jwsthemes_voyara_option( 'jwsthemes_container' ) ) ) . 'px';

	$css = '';
	foreach ( $vars as $var => $value ) {
		$css .= $var . ':' . $value . ';';
	}
	return ':root{' . $css . '}';
}
