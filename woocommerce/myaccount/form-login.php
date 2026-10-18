<?php
/**
 * Login Form: Sign in and Create account as two tabs beside a photo panel.
 *
 * Override of the WooCommerce template. The fields, hooks and nonces are those of
 * WooCommerce 9.9.0; only the layout changed. The photo is the featured image of the
 * My account page; the panel text can be changed with the
 * `jwsthemes_voyara_login_panel` filter.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Voyara
 * @version 9.9.0
 */

defined( 'ABSPATH' ) || exit;

$jwsthemes_voyara_register = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- only picks the open tab.
$jwsthemes_voyara_tab   = $jwsthemes_voyara_register && ( isset( $_POST['register'] ) || ( isset( $_GET['action'] ) && 'register' === $_GET['action'] ) ) ? 'register' : 'login';
$jwsthemes_voyara_panel = jwsthemes_voyara_login_panel();

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="vy-login" id="customer_login">
	<aside class="vy-login__panel">
		<?php if ( $jwsthemes_voyara_panel['image'] ) : ?>
			<?php echo wp_get_attachment_image( $jwsthemes_voyara_panel['image'], 'large', false, array( 'class' => 'vy-login__img', 'alt' => '' ) ); ?>
		<?php endif; ?>
		<span class="vy-login__shade" aria-hidden="true"></span>
		<div class="vy-login__ptext">
			<?php if ( '' !== $jwsthemes_voyara_panel['eyebrow'] ) : ?>
				<span class="vy-login__eyebrow"><?php echo esc_html( $jwsthemes_voyara_panel['eyebrow'] ); ?></span>
			<?php endif; ?>
			<span class="vy-login__ptitle"><?php echo esc_html( $jwsthemes_voyara_panel['title'] ); ?></span>
			<?php if ( '' !== $jwsthemes_voyara_panel['text'] ) : ?>
				<span class="vy-login__plead"><?php echo esc_html( $jwsthemes_voyara_panel['text'] ); ?></span>
			<?php endif; ?>
		</div>
	</aside>

	<div class="vy-login__main">
		<?php if ( $jwsthemes_voyara_register ) : ?>
			<div class="vy-login__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Account', 'voyara' ); ?>">
				<button type="button" role="tab" id="vy-login-tab" aria-controls="vy-login-pane" aria-selected="<?php echo 'login' === $jwsthemes_voyara_tab ? 'true' : 'false'; ?>"<?php echo 'login' === $jwsthemes_voyara_tab ? '' : ' tabindex="-1"'; ?>><?php esc_html_e( 'Sign in', 'voyara' ); ?></button>
				<button type="button" role="tab" id="vy-register-tab" aria-controls="vy-register-pane" aria-selected="<?php echo 'register' === $jwsthemes_voyara_tab ? 'true' : 'false'; ?>"<?php echo 'register' === $jwsthemes_voyara_tab ? '' : ' tabindex="-1"'; ?>><?php esc_html_e( 'Create account', 'voyara' ); ?></button>
			</div>
		<?php endif; ?>

		<div class="vy-login__pane" id="vy-login-pane" role="<?php echo $jwsthemes_voyara_register ? 'tabpanel' : 'region'; ?>" aria-labelledby="<?php echo $jwsthemes_voyara_register ? 'vy-login-tab' : 'vy-login-h'; ?>"<?php echo 'login' === $jwsthemes_voyara_tab ? '' : ' hidden'; ?>>
			<h1 class="vy-login__title" id="vy-login-h"><?php esc_html_e( 'Welcome back', 'voyara' ); ?></h1>

			<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="vy-login__row">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Keep me signed in', 'voyara' ); ?></span>
					</label>
					<a class="vy-login__lost" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'voyara' ); ?></a>
				</div>
				<p class="form-row">
					<?php
					wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' );
					// Back to the page that sent the visitor here, for example a dashboard.
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- the address is validated.
					$jwsthemes_voyara_back = isset( $_GET['redirect_to'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ), '' ) : '';
					if ( '' !== $jwsthemes_voyara_back ) {
						echo '<input type="hidden" name="redirect" value="' . esc_url( $jwsthemes_voyara_back ) . '">';
					}
					?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Sign in', 'voyara' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>
		</div>

		<?php if ( $jwsthemes_voyara_register ) : ?>
			<div class="vy-login__pane" id="vy-register-pane" role="tabpanel" aria-labelledby="vy-register-tab"<?php echo 'register' === $jwsthemes_voyara_tab ? '' : ' hidden'; ?>>
				<h2 class="vy-login__title"><?php esc_html_e( 'Create your account', 'voyara' ); ?></h2>

				<?php if ( '' !== $jwsthemes_voyara_panel['partner'] ) : ?>
					<div class="vy-login__roles">
						<span class="vy-login__label"><?php esc_html_e( 'I want to', 'voyara' ); ?></span>
						<div class="vy-login__role-row">
							<span class="vy-login__role is-on" aria-current="true"><?php esc_html_e( 'Book trips', 'voyara' ); ?></span>
							<a class="vy-login__role" href="<?php echo esc_url( $jwsthemes_voyara_panel['partner'] ); ?>"><?php esc_html_e( 'List my business', 'voyara' ); ?></a>
						</div>
					</div>
				<?php endif; ?>

				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
						</p>

					<?php endif; ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
						<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
					</p>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
						</p>

					<?php else : ?>

						<p class="vy-login__note"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<p class="woocommerce-form-row form-row">
						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Create account', 'voyara' ); ?></button>
					</p>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
