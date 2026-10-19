<?php
/**
 * Lost password sent: "Check your email" beside the photo panel of the Sign in page.
 *
 * Override of the WooCommerce template. The hooks and filter are those of WooCommerce
 * 3.9.0; the page itself says the email was sent, so it prints no notice above it.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Voyara
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="vy-login vy-login--reset">
	<?php jwsthemes_voyara_login_aside(); ?>

	<div class="vy-login__main">
		<div class="vy-login__pane">
			<span class="vy-login__icon" aria-hidden="true">
				<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
			</span>
			<h1 class="vy-login__title"><?php esc_html_e( 'Check your email', 'voyara' ); ?></h1>

			<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

			<p class="vy-login__lead"><?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', __( 'We sent a link to set a new password to the email address of your account. It can take a few minutes to arrive, so check your spam folder too. The link works once.', 'voyara' ) ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WooCommerce filter. ?></p>

			<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>

			<div class="vy-login__actions">
				<a class="vy-login__btn" href="<?php echo esc_url( jwsthemes_voyara_login_url() ); ?>"><?php esc_html_e( 'Back to sign in', 'voyara' ); ?></a>
				<p class="vy-login__note">
					<?php esc_html_e( 'No email after 10 minutes?', 'voyara' ); ?>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Send it again', 'voyara' ); ?></a>
				</p>
			</div>
		</div>
	</div>
</div>
