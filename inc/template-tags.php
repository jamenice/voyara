<?php
/**
 * Template helpers.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lets the Voyara Core Theme Builder print a template for a location.
 *
 * Voyara Core hooks `jwsthemes_voyara_render_location` and returns true when it printed
 * an Elementor template. Without the plugin, or without a matching template, the theme
 * prints its own fallback, so the site still works when the plugin or Elementor is off.
 *
 * @param string $location header, footer, single, archive, taxonomy or 404.
 * @return bool True when the plugin printed the location.
 */
function jwsthemes_voyara_render_location( $location ) {
	return (bool) apply_filters( 'jwsthemes_voyara_render_location', false, $location );
}

/**
 * Site logo, or the site name when no logo is set.
 */
function jwsthemes_voyara_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="vy-brand" href="%s" rel="home">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Date and author line for posts.
 */
function jwsthemes_voyara_posted_on() {
	printf(
		'<p class="vy-meta"><time datetime="%1$s">%2$s</time><span aria-hidden="true"> · </span>%3$s</p>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_html( get_the_author() )
	);
}

/**
 * Pagination for archives.
 */
function jwsthemes_voyara_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => esc_html__( 'Previous', 'voyara' ),
			'next_text' => esc_html__( 'Next', 'voyara' ),
		)
	);
}
