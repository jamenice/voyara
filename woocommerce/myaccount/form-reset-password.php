<?php
/**
 * Reset password form: the new password fields beside the photo panel of the Sign in page.
 *
 * Override of the WooCommerce template. The fields, hooks, filter and nonce are those of
 * WooCommerce 9.2.0; only the layout and the words changed. WooCommerce adds its password
 * strength meter under the first field.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Voyara
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<div class="vy-login vy-login--reset">
	<?php jwsthemes_voyara_login_aside(); ?>

	<div class="vy-login__main">
		<div class="vy-login__pane">
			<h1 class="vy-login__title"><?php esc_html_e( 'Set a new password', 'voyara' ); ?></h1>
			<p class="vy-login__lead"><?php echo esc_html( apply_filters( 'woocommerce_reset_password_message', __( 'Choose a password you do not use on other sites. You sign in with it from now on.', 'voyara' ) ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WooCommerce filter. ?></p>

			<form method="post" class="woocommerce-form woocommerce-ResetPassword lost_reset_password">

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_1"><?php esc_html_e( 'New password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" required aria-required="true" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_2"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" required aria-required="true" />
				</p>

				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

				<?php do_action( 'woocommerce_resetpassword_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save password', 'voyara' ); ?></button>
				</p>

				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

			</form>
		</div>
	</div>
</div>
<?php
do_action( 'woocommerce_after_reset_password_form' );
