<?php
/**
 * Blog index and fallback for every other view.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! jwsthemes_voyara_render_location( 'archive' ) ) {
	get_template_part( 'template-parts/archive' );
}

get_footer();
