<?php
/**
 * Not found. Voyara Core prints the Theme Builder 404 template when one matches;
 * otherwise the title, text, image and button come from Voyara > Settings > 404 page.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! jwsthemes_voyara_render_location( '404' ) ) :
	?>
	<section class="vy-wrap vy-404">
		<?php
		$jwsthemes_voyara_404_image = jwsthemes_voyara_option_image( 'jwsthemes_404_image' );
		if ( $jwsthemes_voyara_404_image ) {
			echo wp_get_attachment_image( $jwsthemes_voyara_404_image, 'large', false, array( 'class' => 'vy-404__image' ) );
		}
		$jwsthemes_voyara_404_url = jwsthemes_voyara_option( 'jwsthemes_404_button_url' );
		?>
		<p class="vy-eyebrow"><?php esc_html_e( 'Error 404', 'voyara' ); ?></p>
		<h1 class="vy-title"><?php echo esc_html( jwsthemes_voyara_option( 'jwsthemes_404_title' ) ); ?></h1>
		<?php if ( '' !== jwsthemes_voyara_option( 'jwsthemes_404_text' ) ) : ?>
			<p><?php echo esc_html( jwsthemes_voyara_option( 'jwsthemes_404_text' ) ); ?></p>
		<?php endif; ?>
		<?php get_search_form(); ?>
		<p><a class="vy-btn" href="<?php echo esc_url( '' !== $jwsthemes_voyara_404_url ? $jwsthemes_voyara_404_url : home_url( '/' ) ); ?>"><?php echo esc_html( jwsthemes_voyara_option( 'jwsthemes_404_button_label' ) ); ?></a></p>
	</section>
	<?php
endif;

get_footer();
