<?php
/**
 * Stops the theme from running on an older PHP version and explains why.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Switches back to the previous theme after activation on an unsupported PHP version.
 */
function jwsthemes_voyara_switch_back() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- removes the core "activated" message only.
	add_action( 'admin_notices', 'jwsthemes_voyara_php_notice' );
}
add_action( 'after_switch_theme', 'jwsthemes_voyara_switch_back' );

/**
 * Notice with the required and current PHP versions.
 */
function jwsthemes_voyara_php_notice() {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: 1: required PHP version, 2: current PHP version. */
				__( 'Voyara needs PHP %1$s or later. This server runs PHP %2$s. Ask your host to update PHP, then activate the theme again.', 'voyara' ),
				JWSTHEMES_VOYARA_THEME_MIN_PHP,
				PHP_VERSION
			)
		)
	);
}
