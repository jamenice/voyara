<?php
/**
 * Voyara theme setup.
 *
 * The theme draws pages only. Listings, bookings, Theme Builder templates and widgets
 * live in the Voyara Core plugin, so switching themes keeps every listing and booking.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

define( 'JWSTHEMES_VOYARA_THEME_VERSION', '0.1.0' );

/*
 * Admin page slug of Theme Options: Appearance > Theme Options.
 */
define( 'JWSTHEMES_VOYARA_OPTIONS_PAGE', 'voyara-options' );

/*
 * Minimum PHP version. The product requirement is 8.3; it stays at 8.2 while the
 * local XAMPP stack runs PHP 8.2.4. Raise this and style.css before release.
 */
define( 'JWSTHEMES_VOYARA_THEME_MIN_PHP', '8.2' );

if ( version_compare( PHP_VERSION, JWSTHEMES_VOYARA_THEME_MIN_PHP, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

require get_template_directory() . '/inc/options.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/brand.php';
require get_template_directory() . '/inc/plugins.php';
require get_template_directory() . '/inc/woocommerce.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/theme-options.php';
}

if ( ! function_exists( 'jwsthemes_voyara_setup' ) ) {
	/**
	 * Theme supports, menus and translations.
	 */
	function jwsthemes_voyara_setup() {
		load_theme_textdomain( 'voyara', get_template_directory() . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
		);
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 96,
				'width'       => 336,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		add_theme_support( 'woocommerce' );

		add_editor_style( 'assets/css/editor.css' );

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary menu', 'voyara' ),
				'footer'  => esc_html__( 'Footer menu', 'voyara' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'jwsthemes_voyara_setup' );

/**
 * Content width for embeds.
 */
function jwsthemes_voyara_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'jwsthemes_voyara_content_width', 760 ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}
add_action( 'after_setup_theme', 'jwsthemes_voyara_content_width', 0 );

/**
 * Sidebar for blog and pages.
 */
function jwsthemes_voyara_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog sidebar', 'voyara' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Shown next to blog posts when the sidebar is on.', 'voyara' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'jwsthemes_voyara_widgets_init' );

/**
 * Styles and scripts. Fonts are bundled with the theme; nothing loads from a CDN.
 */
function jwsthemes_voyara_scripts() {
	wp_enqueue_style( 'voyara-theme', get_template_directory_uri() . '/assets/css/theme.css', array(), JWSTHEMES_VOYARA_THEME_VERSION );
	wp_add_inline_style( 'voyara-theme', jwsthemes_voyara_font_faces() . jwsthemes_voyara_brand_css() );

	wp_enqueue_script( 'voyara-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), JWSTHEMES_VOYARA_THEME_VERSION, array( 'strategy' => 'defer' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'jwsthemes_voyara_scripts' );

/**
 * Preloads the Latin files of the bundled fonts chosen in Theme Options, so headings
 * and text do not reflow when the font arrives.
 */
function jwsthemes_voyara_preload_fonts() {
	$files = array(
		'marcellus' => 'marcellus-latin.woff2',
		'jost'      => 'jost-latin.woff2',
	);
	$used  = array_unique( array( jwsthemes_voyara_option( 'jwsthemes_font_heading' ), jwsthemes_voyara_option( 'jwsthemes_font_body' ) ) );
	foreach ( $used as $font ) {
		if ( isset( $files[ $font ] ) ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( get_template_directory_uri() . '/assets/fonts/' . $files[ $font ] )
			);
		}
	}
}
add_action( 'wp_head', 'jwsthemes_voyara_preload_fonts', 2 );

/**
 * Bundled font faces.
 *
 * @return string CSS.
 */
function jwsthemes_voyara_font_faces() {
	$url = get_template_directory_uri() . '/assets/fonts/';
	$ext = 'U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF';
	$lat = 'U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD';
	$css = '';
	foreach ( array( 'jost' => array( 'Jost', '300 600' ), 'marcellus' => array( 'Marcellus', '400' ) ) as $file => $def ) {
		foreach ( array( 'latin-ext' => $ext, 'latin' => $lat ) as $subset => $range ) {
			$css .= sprintf(
				"@font-face{font-family:'%s';font-style:normal;font-weight:%s;font-display:swap;src:url(%s) format('woff2');unicode-range:%s}",
				$def[0],
				$def[1],
				esc_url( $url . $file . '-' . $subset . '.woff2' ),
				$range
			);
		}
	}
	return $css;
}

/**
 * Body classes for the sidebar, the sticky built-in header and the reading progress bar.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function jwsthemes_voyara_body_classes( $classes ) {
	$side = jwsthemes_voyara_blog_sidebar();
	if ( 'none' !== $side ) {
		$classes[] = 'has-sidebar';
		$classes[] = 'has-sidebar-' . $side;
	}
	if ( jwsthemes_voyara_option( 'jwsthemes_header_sticky' ) ) {
		$classes[] = 'has-sticky-header';
	}
	if ( is_singular( 'post' ) && jwsthemes_voyara_option( 'jwsthemes_blog_progress' ) ) {
		$classes[] = 'has-progress';
	}
	return $classes;
}
add_filter( 'body_class', 'jwsthemes_voyara_body_classes' );

/**
 * Block editor: the same fonts and colour variables as the front end.
 *
 * @param array $settings Editor settings.
 * @return array
 */
function jwsthemes_voyara_editor_vars( $settings ) {
	$settings['styles'][] = array( 'css' => jwsthemes_voyara_font_faces() . jwsthemes_voyara_brand_css() );
	return $settings;
}
add_filter( 'block_editor_settings_all', 'jwsthemes_voyara_editor_vars' );
