<?php
/**
 * Site footer. Voyara Core prints the Theme Builder footer when one matches, or the
 * default footer chosen in Theme Options; otherwise this fallback shows the footer
 * menu, the social links and the copyright line from Theme Options > Footer.
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
			<?php jwsthemes_voyara_social_links(); ?>
			<?php jwsthemes_voyara_copyright(); ?>
		</div>
	</footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
