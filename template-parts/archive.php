<?php
/**
 * Post list for the blog, archives and search. Card style and sidebar come from
 * Voyara > Settings > Blog.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

$jwsthemes_voyara_side   = jwsthemes_voyara_blog_sidebar();
$jwsthemes_voyara_layout = 'list' === jwsthemes_voyara_option( 'jwsthemes_blog_layout' ) && ! is_search() ? 'vy-grid vy-grid--list' : 'vy-grid';
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
		<div class="<?php echo esc_attr( $jwsthemes_voyara_layout ); ?>">
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
	<?php if ( 'none' !== $jwsthemes_voyara_side ) : ?>
		<aside class="vy-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'voyara' ); ?>">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</aside>
	<?php endif; ?>
</section>
