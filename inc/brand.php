<?php
/**
 * Brand colours, stored as a theme mod and printed as CSS custom properties.
 *
 * The Voyara Console setup wizard writes the theme mod `jwsthemes_brand_colors`.
 * Theme Options (Redux) will edit the same values once it is added.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default brand colours from logo direction A, "Valley sun".
 *
 * @return array<string,string>
 */
function jwsthemes_voyara_brand_defaults() {
	return array(
		'gold'  => '#C9962B',
		'green' => '#0E3B36',
		'ink'   => '#15201F',
		'sand'  => '#F3EEE4',
	);
}

/**
 * Current brand colours, each validated as a hex colour.
 *
 * @return array<string,string>
 */
function jwsthemes_voyara_brand_colors() {
	$defaults = jwsthemes_voyara_brand_defaults();
	$saved    = get_theme_mod( 'jwsthemes_brand_colors', array() );
	$out      = array();
	foreach ( $defaults as $key => $value ) {
		$color       = is_array( $saved ) && isset( $saved[ $key ] ) ? sanitize_hex_color( $saved[ $key ] ) : '';
		$out[ $key ] = $color ? $color : $value;
	}
	return $out;
}

/**
 * CSS custom properties for the brand colours.
 *
 * @return string CSS.
 */
function jwsthemes_voyara_brand_css() {
	$c = jwsthemes_voyara_brand_colors();
	return sprintf(
		':root{--vy-gold:%1$s;--vy-green:%2$s;--vy-ink:%3$s;--vy-sand:%4$s}',
		$c['gold'],
		$c['green'],
		$c['ink'],
		$c['sand']
	);
}
