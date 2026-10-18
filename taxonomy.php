<?php
/**
 * Taxonomy archives such as destinations and listing types.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! jwsthemes_voyara_render_location( 'taxonomy' ) ) {
	get_template_part( 'template-parts/archive' );
}

get_footer();
