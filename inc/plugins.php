<?php
/**
 * Voyara Core: install and activate it from an admin notice.
 *
 * Voyara Core holds listings, bookings, the Console and its Setup wizard. While it is missing
 * or inactive, a notice installs and activates it in one click, then opens the Setup wizard.
 * The package is the copy bundled in the theme (plugins/voyara-core.zip) when there is one,
 * otherwise the download on the JWSThemes server. The download address is used on the
 * server only and never printed.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plugin file of Voyara Core relative to the plugins folder, or '' when it is not installed.
 * Found by its text domain, so a copy in another folder is found too.
 *
 * @return string
 */
function jwsthemes_voyara_core_file() {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	foreach ( get_plugins() as $file => $data ) {
		if ( 'voyara-core' === ( $data['TextDomain'] ?? '' ) ) {
			return $file;
		}
	}
	return '';
}

/**
 * Package Voyara Core installs from: a local zip path or a URL.
 *
 * @return string
 */
function jwsthemes_voyara_core_package() {
	$bundled = get_template_directory() . '/plugins/voyara-core.zip';
	$package = is_readable( $bundled ) ? $bundled : 'https://gavencreative.com/plugins/voyara/voyara-core.zip';
	/**
	 * Filters the package Voyara Core is installed from.
	 *
	 * @param string $package Local zip path or URL. Default the zip bundled in the theme, else the JWSThemes download.
	 */
	return (string) apply_filters( 'jwsthemes_voyara_core_package', $package );
}

/**
 * Shows the install or activate notice while Voyara Core is not active, and the error of the
 * last try.
 */
function jwsthemes_voyara_required_plugin_notice() {
	if ( defined( 'JWSTHEMES_VOYARA_VERSION' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && in_array( $screen->id, array( 'update', 'plugin-install' ), true ) ) {
		return;
	}

	$key   = 'jwsthemes_voyara_core_error_' . get_current_user_id();
	$error = get_transient( $key );
	if ( $error ) {
		delete_transient( $key );
		printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $error ) );
	}

	$installed = '' !== jwsthemes_voyara_core_file();
	if ( ! $installed && ! current_user_can( 'install_plugins' ) ) {
		printf( '<div class="notice notice-warning"><p>%s</p></div>', esc_html__( 'Voyara needs the Voyara Core plugin for listings, bookings and page templates. Ask an administrator to install it.', 'voyara' ) );
		return;
	}
	?>
	<div class="notice notice-warning">
		<p><?php esc_html_e( 'Voyara needs the Voyara Core plugin for listings, bookings and page templates. After it is active, the Setup wizard opens.', 'voyara' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:0 0 10px">
			<input type="hidden" name="action" value="jwsthemes_voyara_core">
			<?php wp_nonce_field( 'jwsthemes_voyara_core' ); ?>
			<button type="submit" class="button button-primary"><?php echo $installed ? esc_html__( 'Activate Voyara Core', 'voyara' ) : esc_html__( 'Install Voyara Core', 'voyara' ); ?></button>
		</form>
	</div>
	<?php
}
add_action( 'admin_notices', 'jwsthemes_voyara_required_plugin_notice' );

/**
 * Sends the user back with an error shown by the notice.
 *
 * @param string $message Error text.
 */
function jwsthemes_voyara_core_fail( $message ) {
	set_transient( 'jwsthemes_voyara_core_error_' . get_current_user_id(), $message, 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'themes.php' ) );
	exit;
}

/**
 * admin-post.php?action=jwsthemes_voyara_core: installs Voyara Core when missing, activates it
 * and opens the Setup wizard.
 */
function jwsthemes_voyara_core_install() {
	check_admin_referer( 'jwsthemes_voyara_core' );
	if ( ! current_user_can( 'activate_plugins' ) ) {
		wp_die( esc_html__( 'You do not have permission to activate plugins.', 'voyara' ), '', array( 'response' => 403 ) );
	}

	$manual = __( 'Install it from Plugins > Add New Plugin > Upload Plugin with the voyara-core.zip file from your download, then activate it.', 'voyara' );
	$file   = jwsthemes_voyara_core_file();

	if ( '' === $file ) {
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die( esc_html__( 'You do not have permission to install plugins.', 'voyara' ), '', array( 'response' => 403 ) );
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/misc.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		// Without direct file access WordPress asks for FTP details, which this button cannot show.
		ob_start();
		$credentials = request_filesystem_credentials( '', '', false, WP_PLUGIN_DIR, null, true );
		ob_end_clean();
		if ( false === $credentials || ! WP_Filesystem( $credentials, WP_PLUGIN_DIR, true ) ) {
			jwsthemes_voyara_core_fail( __( 'WordPress needs FTP details to install plugins on this server.', 'voyara' ) . ' ' . $manual );
		}

		$skin     = new Automatic_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );
		$result   = $upgrader->install( jwsthemes_voyara_core_package() );
		if ( true !== $result ) {
			// Transport errors can name the download server, so details go to the debug log only.
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$detail = is_wp_error( $result ) ? $result->get_error_message() : implode( ' ', (array) $skin->get_upgrade_messages() );
				error_log( 'Voyara Core install: ' . $detail ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- debug only.
			}
			jwsthemes_voyara_core_fail( __( 'Voyara Core could not be downloaded or installed.', 'voyara' ) . ' ' . $manual );
		}
		wp_clean_plugins_cache();
		$file = jwsthemes_voyara_core_file();
		if ( '' === $file ) {
			jwsthemes_voyara_core_fail( __( 'Voyara Core could not be downloaded or installed.', 'voyara' ) . ' ' . $manual );
		}
	}

	$active = activate_plugin( $file );
	if ( is_wp_error( $active ) ) {
		/* translators: %s: error message from WordPress. */
		jwsthemes_voyara_core_fail( sprintf( __( 'Voyara Core could not be activated. %s', 'voyara' ), $active->get_error_message() ) );
	}
	wp_safe_redirect( admin_url( 'admin.php?page=voyara#/wizard' ) );
	exit;
}
add_action( 'admin_post_jwsthemes_voyara_core', 'jwsthemes_voyara_core_install' );
