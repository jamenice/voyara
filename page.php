<?php
/**
 * Pages. Pages built with Elementor print their content full width.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$jwsthemes_voyara_elementor = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::instance()->documents->get( get_the_ID() ) && \Elementor\Plugin::instance()->documents->get( get_the_ID() )->is_built_with_elementor();
	$jwsthemes_voyara_step      = jwsthemes_voyara_wc_step();
	// The sign in screens of My account (Sign in, lost and reset password) have their own
	// heading beside a photo.
	$jwsthemes_voyara_login = ! $jwsthemes_voyara_elementor && function_exists( 'is_account_page' ) && is_account_page() && ! is_user_logged_in();
	if ( $jwsthemes_voyara_login ) :
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'vy-wrap vy-entry vy-wcpage vy-wcpage--login' ); ?>>
			<div class="vy-entry__content"><?php the_content(); ?></div>
		</article>
		<?php
		continue;
	endif;
	if ( $jwsthemes_voyara_step && ! $jwsthemes_voyara_elementor ) :
		jwsthemes_voyara_wc_hero( $jwsthemes_voyara_step );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'vy-wrap vy-entry vy-wcpage vy-wcpage--' . $jwsthemes_voyara_step ); ?>>
			<div class="vy-entry__content"><?php the_content(); ?></div>
		</article>
		<?php
		continue;
	endif;
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( $jwsthemes_voyara_elementor ? 'vy-entry--full' : 'vy-wrap vy-entry' ); ?>>
		<?php if ( ! $jwsthemes_voyara_elementor ) : ?>
			<header class="vy-entry__head"><?php the_title( '<h1 class="vy-title">', '</h1>' ); ?></header>
		<?php endif; ?>
		<div class="vy-entry__content">
			<?php the_content(); ?>
		</div>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
