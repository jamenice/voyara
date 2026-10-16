<?php
/**
 * Single post and single listing. Voyara Core prints the Theme Builder single
 * template when one matches; otherwise the post content shows here.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! jwsthemes_voyara_render_location( 'single' ) ) :
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'vy-wrap vy-entry' ); ?>>
			<header class="vy-entry__head">
				<?php the_title( '<h1 class="vy-title">', '</h1>' ); ?>
				<?php
				if ( 'post' === get_post_type() ) {
					jwsthemes_voyara_posted_on();
				}
				?>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="vy-entry__media"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<div class="vy-entry__content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<nav class="vy-pages" aria-label="' . esc_attr__( 'Pages', 'voyara' ) . '">',
						'after'  => '</nav>',
					)
				);
				?>
			</div>
			<?php
			if ( 'post' === get_post_type() ) {
				the_post_navigation(
					array(
						'prev_text' => '<span class="vy-meta">' . esc_html__( 'Previous', 'voyara' ) . '</span> %title',
						'next_text' => '<span class="vy-meta">' . esc_html__( 'Next', 'voyara' ) . '</span> %title',
					)
				);
			}
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</article>
		<?php
	endwhile;
endif;

get_footer();
