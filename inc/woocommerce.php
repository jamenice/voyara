<?php
/**
 * WooCommerce: the theme's look for the cart, checkout and order received pages.
 *
 * The Cart and Checkout pages work with the classic [woocommerce_cart] and
 * [woocommerce_checkout] shortcodes. Their page header becomes a sand band with the
 * breadcrumb, the title and the three steps Cart, Checkout and Order complete.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the page is the cart, the checkout or an order received page.
 *
 * @return string cart, checkout, received or ''.
 */
function jwsthemes_voyara_wc_step() {
	if ( ! function_exists( 'is_cart' ) ) {
		return '';
	}
	if ( is_cart() ) {
		return 'cart';
	}
	if ( is_checkout() ) {
		return is_wc_endpoint_url( 'order-received' ) ? 'received' : 'checkout';
	}
	return '';
}

/**
 * Styles of the WooCommerce pages, after the WooCommerce ones so they win.
 */
function jwsthemes_voyara_wc_styles() {
	if ( ! function_exists( 'is_woocommerce' ) ) {
		return;
	}
	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
		$deps = wp_style_is( 'woocommerce-general', 'registered' ) ? array( 'voyara-theme', 'woocommerce-general' ) : array( 'voyara-theme' );
		wp_enqueue_style( 'voyara-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', $deps, JWSTHEMES_VOYARA_THEME_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'jwsthemes_voyara_wc_styles', 20 );

/**
 * Page header of the cart and checkout: breadcrumb, title and steps.
 *
 * @param string $step cart, checkout or received.
 */
function jwsthemes_voyara_wc_hero( $step ) {
	$titles = array(
		'cart'     => __( 'Your cart', 'voyara' ),
		'checkout' => __( 'Checkout', 'voyara' ),
		'received' => __( 'Order received', 'voyara' ),
	);
	$steps  = array(
		'cart'     => __( 'Cart', 'voyara' ),
		'checkout' => __( 'Checkout', 'voyara' ),
		'received' => __( 'Order complete', 'voyara' ),
	);
	$order  = array_keys( $steps );
	$now    = array_search( $step, $order, true );
	?>
	<header class="vy-wchero">
		<div class="vy-wrap vy-wchero__in">
			<nav class="vy-wchero__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'voyara' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'voyara' ); ?></a>
				<span aria-hidden="true">/</span>
				<?php if ( 'cart' !== $step ) : ?>
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Cart', 'voyara' ); ?></a>
					<span aria-hidden="true">/</span>
				<?php endif; ?>
				<span aria-current="page"><?php echo esc_html( 'received' === $step ? __( 'Order received', 'voyara' ) : $steps[ $step ] ); ?></span>
			</nav>
			<h1 class="vy-wchero__title"><?php echo esc_html( $titles[ $step ] ); ?></h1>
			<ol class="vy-wchero__steps" aria-label="<?php esc_attr_e( 'Checkout steps', 'voyara' ); ?>">
				<?php foreach ( $order as $i => $key ) : ?>
					<?php
					$state = $i < $now ? 'done' : ( $i === $now ? 'now' : 'next' );
					$link  = 'done' === $state && 'cart' === $key && 'received' !== $step;
					?>
					<li class="vy-wchero__step is-<?php echo esc_attr( $state ); ?>"<?php echo 'now' === $state ? ' aria-current="step"' : ''; ?>>
						<?php if ( $link ) : ?>
							<a class="vy-wchero__num" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Back to cart', 'voyara' ); ?>">&#10003;</a>
						<?php else : ?>
							<span class="vy-wchero__num" aria-hidden="true"><?php echo 'done' === $state ? '&#10003;' : esc_html( (string) ( $i + 1 ) ); ?></span>
						<?php endif; ?>
						<span><?php echo esc_html( $steps[ $key ] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</header>
	<?php
}

/**
 * "Your order" heading with an Edit cart link inside the order summary box; the
 * WooCommerce heading above the box is hidden by the theme styles.
 */
function jwsthemes_voyara_wc_review_heading() {
	?>
	<div class="vy-wcreview__head">
		<h3><?php esc_html_e( 'Your order', 'voyara' ); ?></h3>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Edit cart', 'voyara' ); ?></a>
	</div>
	<?php
}
add_action( 'woocommerce_checkout_order_review', 'jwsthemes_voyara_wc_review_heading', 5 );

/**
 * Address of the Sign in page (Voyara Console > Settings > Pages), else My account.
 *
 * @return string
 */
function jwsthemes_voyara_login_url() {
	if ( class_exists( 'JWSThemes_Voyara_Dashboards' ) ) {
		return JWSThemes_Voyara_Dashboards::login_url();
	}
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
}

/**
 * Photo panel of the sign in screens: Sign in, Create account, and the lost and reset
 * password forms.
 *
 * @param array|null $panel Panel from jwsthemes_voyara_login_panel(); null to get it here.
 */
function jwsthemes_voyara_login_aside( $panel = null ) {
	$panel = null === $panel ? jwsthemes_voyara_login_panel() : $panel;
	?>
	<aside class="vy-login__panel">
		<?php if ( $panel['image'] ) : ?>
			<?php echo wp_get_attachment_image( $panel['image'], 'large', false, array( 'class' => 'vy-login__img', 'alt' => '' ) ); ?>
		<?php endif; ?>
		<span class="vy-login__shade" aria-hidden="true"></span>
		<div class="vy-login__ptext">
			<?php if ( '' !== $panel['eyebrow'] ) : ?>
				<span class="vy-login__eyebrow"><?php echo esc_html( $panel['eyebrow'] ); ?></span>
			<?php endif; ?>
			<span class="vy-login__ptitle"><?php echo esc_html( $panel['title'] ); ?></span>
			<?php if ( '' !== $panel['text'] ) : ?>
				<span class="vy-login__plead"><?php echo esc_html( $panel['text'] ); ?></span>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}

/**
 * Photo panel beside the Sign in and Create account forms (the Sign in page, or My account).
 *
 * @return array{image:int,eyebrow:string,title:string,text:string,partner:string}
 */
function jwsthemes_voyara_login_panel() {
	$account = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'myaccount' ) : 0;
	// The featured image of the page showing the form, else the one of My account.
	$page    = is_page() && has_post_thumbnail( get_queried_object_id() ) ? get_queried_object_id() : $account;
	$partner = '';
	if ( class_exists( 'JWSThemes_Voyara_Settings' ) && 'off' !== (string) JWSThemes_Voyara_Settings::value( 'partners', 'reg' ) ) {
		$apply   = (int) JWSThemes_Voyara_Settings::value( 'pages', 'apply' );
		$partner = $apply && 'publish' === get_post_status( $apply ) ? (string) get_permalink( $apply ) : '';
	}
	/**
	 * Filters the photo panel of the login page.
	 *
	 * @param array $panel image (attachment id; default the featured image of the page,
	 *                     else of My account), eyebrow, title, text, and partner (the Become a
	 *                     partner page link behind "List my business"; empty hides the choice).
	 */
	return (array) apply_filters(
		'jwsthemes_voyara_login_panel',
		array(
			'image'   => $page > 0 ? (int) get_post_thumbnail_id( $page ) : 0,
			'eyebrow' => __( 'Members', 'voyara' ),
			'title'   => __( 'Save a trip, finish it later', 'voyara' ),
			'text'    => __( 'Your bookings, wishlist and details in one place.', 'voyara' ),
			'partner' => $partner,
		)
	) + array(
		'image'   => 0,
		'eyebrow' => '',
		'title'   => '',
		'text'    => '',
		'partner' => '',
	);
}
