<?php
/**
 * Site header. Voyara Core prints the Theme Builder header when one matches;
 * otherwise this fallback shows the logo and the primary menu.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="vy-skip" href="#vy-main"><?php esc_html_e( 'Skip to content', 'voyara' ); ?></a>
<?php if ( ! jwsthemes_voyara_render_location( 'header' ) ) : ?>
	<header class="vy-header">
		<div class="vy-wrap vy-header__bar">
			<?php jwsthemes_voyara_logo(); ?>
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<button type="button" class="vy-menu-toggle" aria-controls="vy-primary-menu" aria-expanded="false">
					<span class="vy-menu-toggle__bars" aria-hidden="true"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'voyara' ); ?></span>
				</button>
				<nav class="vy-nav" aria-label="<?php esc_attr_e( 'Primary', 'voyara' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'vy-primary-menu',
							'container'      => false,
							'depth'          => 2,
						)
					);
					?>
				</nav>
			<?php endif; ?>
		</div>
	</header>
<?php endif; ?>
<main id="vy-main" class="vy-main">
