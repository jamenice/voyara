<?php
/**
 * Site footer. Voyara Core prints the Theme Builder footer when one matches;
 * otherwise this fallback shows the footer menu and the copyright line.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php if ( ! jwsthemes_voyara_render_location( 'footer' ) ) : ?>
	<footer class="vy-footer">
		<div class="vy-wrap vy-footer__bar">
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Footer', 'voyara' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>
			<p class="vy-copy">
				<?php
				printf(
					/* translators: 1: year, 2: site name. */
					esc_html__( '© %1$s %2$s', 'voyara' ),
					esc_html( wp_date( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
		</div>
	</footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
