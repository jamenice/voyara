<?php
/**
 * Comments.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="vy-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="vy-comments__title">
			<?php
			$jwsthemes_voyara_count = get_comments_number();
			printf(
				/* translators: %s: number of comments. */
				esc_html( _n( '%s comment', '%s comments', $jwsthemes_voyara_count, 'voyara' ) ),
				esc_html( number_format_i18n( $jwsthemes_voyara_count ) )
			);
			?>
		</h2>
		<ol class="vy-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>
