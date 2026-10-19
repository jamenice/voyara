<?php
/**
 * Lost password form: the email field beside the photo panel of the Sign in page.
 *
 * Override of the WooCommerce template. The field, hooks, filter and nonce are those of
 * WooCommerce 9.2.0; only the layout and the words changed.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Voyara
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="vy-login vy-login--reset">
	<?php jwsthemes_voyara_login_aside(); ?>

	<div class="vy-login__main">
		<a class="vy-login__back" href="<?php echo esc_url( jwsthemes_voyara_login_url() ); ?>">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
			<?php esc_html_e( 'Back to sign in', 'voyara' ); ?>
		</a>

		<div class="vy-login__pane">
			<h1 class="vy-login__title"><?php esc_html_e( 'Forgot your password?', 'voyara' ); ?></h1>
			<p class="vy-login__lead"><?php echo esc_html( apply_filters( 'woocommerce_lost_password_message', __( 'Enter the email address or username of your account. We will email you a link to set a new password.', 'voyara' ) ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WooCommerce filter. ?></p>

			<form method="post" class="woocommerce-form woocommerce-ResetPassword lost_reset_password">

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="user_login"><?php esc_html_e( 'Email address or username', 'voyara' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
				</p>

				<?php do_action( 'woocommerce_lostpassword_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Send reset link', 'voyara' ); ?></button>
				</p>

				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

			</form>
		</div>
	</div>
</div>
<?php
do_action( 'woocommerce_after_lost_password_form' );
