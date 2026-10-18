<?php
/**
 * Required plugin notice.
 *
 * Voyara Core holds listings, bookings and the Theme Builder. Until the TGM Plugin
 * Activation library is bundled, this notice links to the plugin screen.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shows one dismissible notice while Voyara Core is not active.
 */
function jwsthemes_voyara_required_plugin_notice() {
	if ( defined( 'JWSTHEMES_VOYARA_VERSION' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'plugins' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-warning is-dismissible"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'Voyara needs the Voyara Core plugin for listings, bookings and page templates.', 'voyara' ),
		esc_url( admin_url( 'plugins.php' ) ),
		esc_html__( 'Go to Plugins', 'voyara' )
	);
}
add_action( 'admin_notices', 'jwsthemes_voyara_required_plugin_notice' );
