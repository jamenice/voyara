<?php
/**
 * One post card in lists.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'vy-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="vy-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium_large' ); ?></a>
	<?php endif; ?>
	<div class="vy-card__body">
		<?php the_title( '<h2 class="vy-card__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
		<?php
		if ( 'post' === get_post_type() ) {
			jwsthemes_voyara_posted_on();
		}
		?>
		<div class="vy-card__text"><?php the_excerpt(); ?></div>
	</div>
</article>
