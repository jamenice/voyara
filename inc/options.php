<?php
/**
 * Theme Options values.
 *
 * Every option is a theme mod named `jwsthemes_*`. Theme Options (Redux, see
 * inc/theme-options.php) saves them with the `theme_mods_expanded` database mode, the
 * Voyara Console setup wizard writes the brand colours, logo and map provider, and the
 * demo importer and exporter move them between sites. Templates read them through
 * jwsthemes_voyara_option(), so the theme works the same when Redux is not active.
 *
 * Two values stay where WordPress keeps them: the light logo is the `custom_logo` theme
 * mod and the favicon is the `site_icon` option. Theme Options shows and saves both.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default value of every option.
 *
 * @return array<string,mixed>
 */
function jwsthemes_voyara_option_defaults() {
	return array(
		// General.
		'jwsthemes_logo_dark'          => array(),
		'jwsthemes_container'          => 1366,
		'jwsthemes_motion'             => true,
		// Colours, see jwsthemes_voyara_color_vars().
		'jwsthemes_color_main'         => '#0E3B36',
		'jwsthemes_color_accent'       => '#C9962B',
		'jwsthemes_color_heading'      => '#15201F',
		'jwsthemes_color_body'         => '#2C3534',
		'jwsthemes_color_muted'        => '#5C6664',
		'jwsthemes_color_border'       => '#E6E1D8',
		'jwsthemes_color_soft'         => '#F3EEE4',
		'jwsthemes_color_light'        => '#FBF9F5',
		'jwsthemes_color_page'         => '#FFFFFF',
		'jwsthemes_color_dark'         => '#15201F',
		// Typography.
		'jwsthemes_font_heading'       => 'marcellus',
		'jwsthemes_font_body'          => 'jost',
		'jwsthemes_font_size'          => 17,
		// Header.
		'jwsthemes_header_template'    => 0,
		'jwsthemes_header_sticky'      => false,
		'jwsthemes_header_phone'       => '',
		'jwsthemes_header_cta_label'   => '',
		'jwsthemes_header_cta_url'     => '',
		// Footer.
		'jwsthemes_footer_template'    => 0,
		'jwsthemes_social_facebook'    => '',
		'jwsthemes_social_instagram'   => '',
		'jwsthemes_social_x'           => '',
		'jwsthemes_social_youtube'     => '',
		'jwsthemes_social_tiktok'      => '',
		'jwsthemes_footer_copyright'   => '© {year} {site}. All rights reserved.',
		// Maps.
		'jwsthemes_map_provider'       => 'openstreetmap',
		'jwsthemes_osm_tile_url'       => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
		'jwsthemes_mapbox_token'       => '',
		'jwsthemes_mapbox_style'       => 'mapbox/streets-v12',
		'jwsthemes_google_maps_key'    => '',
		'jwsthemes_google_map_id'      => '',
		'jwsthemes_map_lat'            => '20',
		'jwsthemes_map_lng'            => '0',
		'jwsthemes_map_zoom'           => 2,
		// Blog.
		'jwsthemes_blog_layout'        => 'grid',
		'jwsthemes_blog_sidebar'       => 'right',
		'jwsthemes_blog_related'       => true,
		'jwsthemes_blog_author'        => true,
		'jwsthemes_blog_progress'      => true,
		// 404.
		'jwsthemes_404_title'          => __( 'This page took a wrong turn', 'voyara' ),
		'jwsthemes_404_text'           => __( 'The link may be out of date, or the page was removed. Search the site or go back to the home page.', 'voyara' ),
		'jwsthemes_404_image'          => array(),
		'jwsthemes_404_button_label'   => __( 'Back to home', 'voyara' ),
		'jwsthemes_404_button_url'     => '',
	);
}

/**
 * One option value, or its default.
 *
 * Redux saves a switch as '1' or '0', a slider as a string and an empty text field as
 * '', so the saved value is cast to the type of the default. An empty value falls
 * back to the default for text that must not be blank (colours, fonts, URLs with a
 * default), and stays empty for text a buyer may clear (phone, CTA, social links).
 *
 * @param string $id Option id, for example jwsthemes_color_main.
 * @return mixed
 */
function jwsthemes_voyara_option( $id ) {
	$defaults = jwsthemes_voyara_option_defaults();
	$default  = $defaults[ $id ] ?? null;
	$value    = get_theme_mod( $id, $default );

	if ( is_bool( $default ) ) {
		return (bool) $value;
	}
	if ( is_int( $default ) ) {
		return is_numeric( $value ) ? (int) $value : $default;
	}
	if ( is_array( $default ) ) {
		return is_array( $value ) ? $value : $default;
	}
	if ( is_string( $default ) ) {
		$value = is_scalar( $value ) ? trim( (string) $value ) : '';
		return ( '' === $value && in_array( $id, jwsthemes_voyara_required_text(), true ) ) ? $default : $value;
	}
	return $value;
}

/**
 * Text options that fall back to their default when saved empty.
 *
 * @return string[]
 */
function jwsthemes_voyara_required_text() {
	return array(
		'jwsthemes_color_main',
		'jwsthemes_color_accent',
		'jwsthemes_color_heading',
		'jwsthemes_color_body',
		'jwsthemes_color_muted',
		'jwsthemes_color_border',
		'jwsthemes_color_soft',
		'jwsthemes_color_light',
		'jwsthemes_color_page',
		'jwsthemes_color_dark',
		'jwsthemes_font_heading',
		'jwsthemes_font_body',
		'jwsthemes_map_provider',
		'jwsthemes_osm_tile_url',
		'jwsthemes_mapbox_style',
		'jwsthemes_map_lat',
		'jwsthemes_map_lng',
		'jwsthemes_blog_layout',
		'jwsthemes_blog_sidebar',
		'jwsthemes_404_title',
		'jwsthemes_404_button_label',
	);
}

/**
 * Attachment id of a media option (Redux saves media as an array with an id).
 *
 * @param string $id Option id.
 * @return int
 */
function jwsthemes_voyara_option_image( $id ) {
	$value = jwsthemes_voyara_option( $id );
	$image = is_array( $value ) && ! empty( $value['id'] ) ? absint( $value['id'] ) : 0;
	return $image && wp_attachment_is_image( $image ) ? $image : 0;
}

/**
 * Bundled and system font stacks offered in Typography.
 *
 * Only bundled fonts and system fonts are offered, so no font loads from a CDN.
 *
 * @return array<string,array{label:string,stack:string}>
 */
function jwsthemes_voyara_fonts() {
	return array(
		'marcellus' => array(
			'label' => 'Marcellus',
			'stack' => "Marcellus, Georgia, 'Times New Roman', serif",
		),
		'jost'      => array(
			'label' => 'Jost',
			'stack' => 'Jost, system-ui, -apple-system, \'Segoe UI\', sans-serif',
		),
		'georgia'   => array(
			'label' => __( 'Georgia (system serif)', 'voyara' ),
			'stack' => "Georgia, 'Times New Roman', serif",
		),
		'system'    => array(
			'label' => __( 'System sans-serif', 'voyara' ),
			'stack' => 'system-ui, -apple-system, \'Segoe UI\', Roboto, sans-serif',
		),
	);
}

/**
 * Moves values saved before Theme Options existed to the option ids above.
 *
 * Runs once per site. The setup wizard used to save the colours as one array
 * (`jwsthemes_brand_colors`) and one map key for whichever provider was chosen
 * (`jwsthemes_map_key`).
 */
function jwsthemes_voyara_migrate_options() {
	if ( (int) get_theme_mod( 'jwsthemes_options_version', 0 ) >= 1 ) {
		return;
	}

	$legacy = get_theme_mod( 'jwsthemes_brand_colors', null );
	if ( is_array( $legacy ) ) {
		$map = array(
			'green' => 'jwsthemes_color_main',
			'gold'  => 'jwsthemes_color_accent',
			'ink'   => 'jwsthemes_color_heading',
			'sand'  => 'jwsthemes_color_soft',
		);
		foreach ( $map as $old => $new ) {
			$color = isset( $legacy[ $old ] ) ? sanitize_hex_color( $legacy[ $old ] ) : '';
			if ( $color && null === get_theme_mod( $new, null ) ) {
				set_theme_mod( $new, strtoupper( $color ) );
			}
		}
		remove_theme_mod( 'jwsthemes_brand_colors' );
	}

	$key = trim( (string) get_theme_mod( 'jwsthemes_map_key', '' ) );
	if ( '' !== $key ) {
		$target = 'google' === get_theme_mod( 'jwsthemes_map_provider' ) ? 'jwsthemes_google_maps_key' : 'jwsthemes_mapbox_token';
		if ( '' === (string) get_theme_mod( $target, '' ) ) {
			set_theme_mod( $target, $key );
		}
	}
	remove_theme_mod( 'jwsthemes_map_key' );

	set_theme_mod( 'jwsthemes_options_version', 1 );
}
add_action( 'after_setup_theme', 'jwsthemes_voyara_migrate_options', 20 );

/**
 * Theme Options URL for Voyara Console (Console > Settings, the setup wizard and the
 * header menu link to it). Empty while Redux is not active.
 *
 * @param string $url URL.
 * @return string
 */
function jwsthemes_voyara_theme_options_url( $url ) {
	return class_exists( 'Redux' ) ? admin_url( 'themes.php?page=' . JWSTHEMES_VOYARA_OPTIONS_PAGE ) : $url;
}
add_filter( 'jwsthemes_voyara_theme_options_url', 'jwsthemes_voyara_theme_options_url' );

/**
 * Default header and footer from Theme Options, used when no Theme Builder template
 * matches the page by its display conditions.
 *
 * Order: a per-page override, then display conditions, then this default, then the
 * theme fallback. A page set to "no template" keeps the theme fallback.
 *
 * @param int    $id       Template id chosen by Voyara Core, 0 for none.
 * @param string $location Location.
 * @return int
 */
function jwsthemes_voyara_default_template( $id, $location ) {
	if ( $id || ! in_array( $location, array( 'header', 'footer' ), true ) || ! class_exists( 'JWSThemes_Voyara_Theme_Builder' ) ) {
		return $id;
	}
	if ( is_singular( JWSThemes_Voyara_Post_Types::TEMPLATE ) ) {
		return $id;
	}
	$object = get_queried_object();
	if ( $object instanceof WP_Post && (int) get_post_meta( $object->ID, JWSThemes_Voyara_Theme_Builder::META_OVERRIDE . $location, true ) < 0 ) {
		return $id;
	}

	$default = jwsthemes_voyara_option( 'jwsthemes_' . $location . '_template' );
	if ( ! $default ) {
		return $id;
	}
	$post = get_post( $default );
	if ( $post && JWSThemes_Voyara_Post_Types::TEMPLATE === $post->post_type && 'publish' === $post->post_status && JWSThemes_Voyara_Theme_Builder::get_type( $default ) === $location ) {
		return $default;
	}
	return $id;
}
add_filter( 'jwsthemes_voyara_template_for', 'jwsthemes_voyara_default_template', 10, 2 );
