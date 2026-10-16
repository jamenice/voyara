<?php
/**
 * Not found. Voyara Core prints the Theme Builder 404 template when one matches.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! jwsthemes_voyara_render_location( '404' ) ) :
	?>
	<section class="vy-wrap vy-404">
		<p class="vy-eyebrow"><?php esc_html_e( 'Error 404', 'voyara' ); ?></p>
		<h1 class="vy-title"><?php esc_html_e( 'This page is not on the map', 'voyara' ); ?></h1>
		<p><?php esc_html_e( 'The link may be old or mistyped. Search the site or go back to the home page.', 'voyara' ); ?></p>
		<?php get_search_form(); ?>
		<p><a class="vy-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'voyara' ); ?></a></p>
	</section>
	<?php
endif;

get_footer();
