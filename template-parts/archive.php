<?php
/**
 * Post list for the blog, archives and search.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="vy-wrap vy-archive">
	<header class="vy-archive__head">
		<?php
		if ( is_search() ) {
			printf(
				'<h1 class="vy-title">%s</h1>',
				/* translators: %s: search text. */
				esc_html( sprintf( __( 'Results for "%s"', 'voyara' ), get_search_query() ) )
			);
		} elseif ( is_home() && ! is_front_page() ) {
			printf( '<h1 class="vy-title">%s</h1>', esc_html( single_post_title( '', false ) ) );
		} elseif ( is_archive() ) {
			the_archive_title( '<h1 class="vy-title">', '</h1>' );
			the_archive_description( '<div class="vy-archive__intro">', '</div>' );
		}
		?>
	</header>
	<div class="vy-archive__body">
		<div class="vy-grid">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
			else :
				?>
				<div class="vy-none">
					<p><?php esc_html_e( 'Nothing found. Try another search.', 'voyara' ); ?></p>
					<?php get_search_form(); ?>
				</div>
				<?php
			endif;
			?>
		</div>
		<?php jwsthemes_voyara_pagination(); ?>
	</div>
	<?php if ( is_active_sidebar( 'sidebar-1' ) && ! is_search() ) : ?>
		<aside class="vy-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'voyara' ); ?>">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</aside>
	<?php endif; ?>
</section>
