<?php
/**
 * Theme Options panel (Redux Framework), in Appearance > Theme Options.
 *
 * Loaded in wp-admin only, when Redux is active. Values are theme mods (database mode
 * `theme_mods_expanded`), so templates read them through jwsthemes_voyara_option()
 * without Redux. Defaults live in inc/options.php; the panel repeats them so Reset
 * restores the same values.
 *
 * Module, page and booking settings are not here: they are in Voyara Console >
 * Settings. Currency, tax and coupons stay in WooCommerce.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux' ) ) {
	return;
}

/**
 * Redux name of the panel. Also the prefix of its hooks.
 */
const JWSTHEMES_VOYARA_OPT_NAME = 'jwsthemes_voyara';

/**
 * Registers the panel and its sections.
 */
function jwsthemes_voyara_register_theme_options() {
	$opt = JWSTHEMES_VOYARA_OPT_NAME;
	$d   = jwsthemes_voyara_option_defaults();

	Redux::set_args(
		$opt,
		array(
			'opt_name'                  => $opt,
			'display_name'              => __( 'Voyara Theme Options', 'voyara' ),
			'display_version'           => JWSTHEMES_VOYARA_THEME_VERSION,
			'menu_type'                 => 'submenu',
			'page_parent'               => 'themes.php',
			'menu_title'                => __( 'Theme Options', 'voyara' ),
			'page_title'                => __( 'Theme Options', 'voyara' ),
			'page_slug'                 => JWSTHEMES_VOYARA_OPTIONS_PAGE,
			'page_permissions'          => 'edit_theme_options',
			'database'                  => 'theme_mods_expanded',
			'global_variable'           => false,
			'save_defaults'             => false,
			'default_show'              => false,
			'show_import_export'        => false,
			'customizer'                => false,
			'admin_bar'                 => false,
			'dev_mode'                  => false,
			'update_notice'             => false,
			'use_cdn'                   => false,
			'async_typography'          => false,
			'disable_google_fonts_link' => true,
			'ajax_save'                 => true,
			'footer_credit'             => ' ',
			'intro_text'                => '<p>' . esc_html__( 'Logo, colours, fonts, header, footer, maps, blog and the 404 page. Modules, pages and booking rules are in Voyara Console > Settings.', 'voyara' ) . '</p>',
		)
	);

	$has_core = class_exists( 'JWSThemes_Voyara_Theme_Builder' );

	// General.
	Redux::set_section(
		$opt,
		array(
			'title'  => __( 'General', 'voyara' ),
			'id'     => 'general',
			'icon'   => 'el el-home',
			'fields' => array(
				array(
					'id'       => 'jwsthemes_logo',
					'type'     => 'media',
					'title'    => __( 'Logo for light backgrounds', 'voyara' ),
					'subtitle' => __( 'Dark logo on a white or cream header. Also the Site Logo in Appearance > Customize.', 'voyara' ),
					'desc'     => __( 'SVG or PNG, at least 336 x 96 px.', 'voyara' ),
				),
				array(
					'id'       => 'jwsthemes_logo_dark',
					'type'     => 'media',
					'title'    => __( 'Logo for dark backgrounds', 'voyara' ),
					'subtitle' => __( 'Light logo on a dark or transparent header, and in the dark footer. Empty uses the logo above.', 'voyara' ),
				),
				array(
					'id'       => 'jwsthemes_favicon',
					'type'     => 'media',
					'title'    => __( 'Favicon', 'voyara' ),
					'subtitle' => __( 'Square image, at least 512 x 512 px. Also the Site Icon in Settings > General. Only administrators can change it.', 'voyara' ),
				),
				array(
					'id'            => 'jwsthemes_container',
					'type'          => 'slider',
					'title'         => __( 'Container width', 'voyara' ),
					'subtitle'      => __( 'Width of the page content in px. Default 1366. Set Content Width in Elementor > Site Settings > Layout to the same value.', 'voyara' ),
					'default'       => $d['jwsthemes_container'],
					'min'           => 960,
					'max'           => 1920,
					'step'          => 2,
					'display_value' => 'text',
				),
				array(
					'id'       => 'jwsthemes_motion',
					'type'     => 'switch',
					'title'    => __( 'Scroll animations', 'voyara' ),
					'subtitle' => __( 'Page sections and cards fade in and rise a little as they scroll into view. Visitors who turn motion off in their device settings never see them.', 'voyara' ),
					'default'  => $d['jwsthemes_motion'],
				),
			),
		)
	);

	// Colours.
	$colors = array(
		'jwsthemes_color_main'    => array( __( 'Main color', 'voyara' ), __( 'Main buttons, links, the selected tab and the border of the field being typed in.', 'voyara' ) ),
		'jwsthemes_color_accent'  => array( __( 'Accent color', 'voyara' ), __( 'Book now buttons, rating stars and highlight labels.', 'voyara' ) ),
		'jwsthemes_color_heading' => array( __( 'Heading color', 'voyara' ), __( 'Headings and main text.', 'voyara' ) ),
		'jwsthemes_color_body'    => array( __( 'Body text color', 'voyara' ), __( 'Paragraph text.', 'voyara' ) ),
		'jwsthemes_color_muted'   => array( __( 'Muted text color', 'voyara' ), __( 'Secondary text and notes.', 'voyara' ) ),
		'jwsthemes_color_border'  => array( __( 'Border color', 'voyara' ), __( 'Card borders and divider lines.', 'voyara' ) ),
		'jwsthemes_color_soft'    => array( __( 'Soft background', 'voyara' ), __( 'Chips, icon tiles and light highlight areas.', 'voyara' ) ),
		'jwsthemes_color_light'   => array( __( 'Light background', 'voyara' ), __( 'Secondary sections and table headers.', 'voyara' ) ),
		'jwsthemes_color_page'    => array( __( 'Page background', 'voyara' ), __( 'Background of every page.', 'voyara' ) ),
		'jwsthemes_color_dark'    => array( __( 'Dark section background', 'voyara' ), __( 'Dark bands and the dark footer.', 'voyara' ) ),
	);
	$color_fields = array(
		array(
			'id'    => 'jwsthemes_colors_info',
			'type'  => 'info',
			'style' => 'info',
			'desc'  => __( 'Every colour on the site, in the Voyara widgets and in Voyara Console comes from these fields. Hover colours and the text colour on buttons are worked out from them. Status colours (available, few left, error, information) stay fixed so they keep enough contrast.', 'voyara' ),
		),
	);
	foreach ( $colors as $id => $labels ) {
		$field = array(
			'id'          => $id,
			'type'        => 'color',
			'title'       => $labels[0],
			'subtitle'    => $labels[1],
			'default'     => $d[ $id ],
			'transparent' => false,
			'validate'    => 'color',
		);
		if ( 'jwsthemes_color_main' === $id ) {
			unset( $field['validate'] );
			$field['validate_callback'] = 'jwsthemes_voyara_validate_main_color';
		}
		$color_fields[] = $field;
	}
	Redux::set_section(
		$opt,
		array(
			'title'  => __( 'Colors', 'voyara' ),
			'id'     => 'colors',
			'icon'   => 'el el-brush',
			'fields' => $color_fields,
		)
	);

	// Typography.
	$fonts = wp_list_pluck( jwsthemes_voyara_fonts(), 'label' );
	Redux::set_section(
		$opt,
		array(
			'title'  => __( 'Typography', 'voyara' ),
			'id'     => 'typography',
			'icon'   => 'el el-font',
			'fields' => array(
				array(
					'id'       => 'jwsthemes_font_heading',
					'type'     => 'select',
					'title'    => __( 'Heading font', 'voyara' ),
					'subtitle' => __( 'Marcellus and Jost are included with the theme. The system fonts load nothing.', 'voyara' ),
					'options'  => $fonts,
					'default'  => $d['jwsthemes_font_heading'],
					'select2'  => array( 'allowClear' => false ),
				),
				array(
					'id'      => 'jwsthemes_font_body',
					'type'    => 'select',
					'title'   => __( 'Body font', 'voyara' ),
					'options' => $fonts,
					'default' => $d['jwsthemes_font_body'],
					'select2' => array( 'allowClear' => false ),
				),
				array(
					'id'            => 'jwsthemes_font_size',
					'type'          => 'slider',
					'title'         => __( 'Base font size', 'voyara' ),
					'subtitle'      => __( 'Paragraph text in px. Default 17.', 'voyara' ),
					'default'       => $d['jwsthemes_font_size'],
					'min'           => 14,
					'max'           => 20,
					'step'          => 1,
					'display_value' => 'text',
				),
			),
		)
	);

	// Header.
	$header_fields = array( jwsthemes_voyara_template_field( 'header', $has_core ) );
	array_push(
		$header_fields,
		array(
			'id'       => 'jwsthemes_header_sticky',
			'type'     => 'switch',
			'title'    => __( 'Sticky header', 'voyara' ),
			'subtitle' => __( 'Keeps the built-in header at the top while scrolling. A Theme Builder header has its own Sticky option.', 'voyara' ),
			'default'  => $d['jwsthemes_header_sticky'],
		),
		array(
			'id'       => 'jwsthemes_header_phone',
			'type'     => 'text',
			'title'    => __( 'Phone number', 'voyara' ),
			'subtitle' => __( 'Shown in the header. Empty hides it.', 'voyara' ),
			'sanitize' => array( 'sanitize_text_field' ),
		),
		array(
			'id'       => 'jwsthemes_header_cta_label',
			'type'     => 'text',
			'title'    => __( 'Button label', 'voyara' ),
			'subtitle' => __( 'Button at the right of the header, for example List your property. Empty hides it.', 'voyara' ),
			'sanitize' => array( 'sanitize_text_field' ),
		),
		array(
			'id'                => 'jwsthemes_header_cta_url',
			'type'              => 'text',
			'title'             => __( 'Button link', 'voyara' ),
			'subtitle'          => __( 'Full URL or a path such as /become-a-local-expert/.', 'voyara' ),
			'validate_callback' => 'jwsthemes_voyara_validate_url',
		)
	);
	Redux::set_section(
		$opt,
		array(
			'title'  => __( 'Header', 'voyara' ),
			'id'     => 'header',
			'icon'   => 'el el-website',
			'desc'   => __( 'The phone number, button and sticky setting apply to the built-in header. Elementor header widgets read the phone number and button too.', 'voyara' ),
			'fields' => $header_fields,
		)
	);

	// Footer.
	$footer_fields = array( jwsthemes_voyara_template_field( 'footer', $has_core ) );
	foreach ( jwsthemes_voyara_social_networks() as $key => $label ) {
		$footer_fields[] = array(
			'id'                => 'jwsthemes_social_' . $key,
			'type'              => 'text',
			/* translators: %s: social network name. */
			'title'             => sprintf( __( '%s link', 'voyara' ), $label ),
			'validate_callback' => 'jwsthemes_voyara_validate_url',
		);
	}
	$footer_fields[] = array(
		'id'           => 'jwsthemes_footer_copyright',
		'type'         => 'textarea',
		'title'        => __( 'Copyright line', 'voyara' ),
		'subtitle'     => __( '{year} becomes the current year and {site} the site name. Links, bold and italic are allowed.', 'voyara' ),
		'default'      => $d['jwsthemes_footer_copyright'],
		'rows'         => 2,
		'validate'     => 'html_custom',
		'allowed_html' => jwsthemes_voyara_copyright_html(),
	);
	Redux::set_section(
		$opt,
		array(
			'title'  => __( 'Footer', 'voyara' ),
			'id'     => 'footer',
			'icon'   => 'el el-photo',
			'fields' => $footer_fields,
		)
	);

	// Maps.
	Redux::set_section(
		$opt,
		array(
			'title'  => __( 'Maps', 'voyara' ),
			'id'     => 'maps',
			'icon'   => 'el el-map-marker',
			'desc'   => __( 'Maps on search pages, listing pages and the listing editor. OpenStreetMap needs no key. Its public tile server limits heavy use, so switch to Mapbox or Google Maps, or to another tile server, when the site gets a lot of traffic.', 'voyara' ),
			'fields' => array(
				array(
					'id'      => 'jwsthemes_map_provider',
					'type'    => 'button_set',
					'title'   => __( 'Map provider', 'voyara' ),
					'options' => array(
						'openstreetmap' => 'OpenStreetMap',
						'mapbox'        => 'Mapbox',
						'google'        => 'Google Maps',
					),
					'default' => $d['jwsthemes_map_provider'],
				),
				array(
					'id'                => 'jwsthemes_osm_tile_url',
					'type'              => 'text',
					'title'             => __( 'Tile URL', 'voyara' ),
					/* translators: %s: default tile URL. */
					'subtitle'          => sprintf( __( 'Must contain {z}, {x} and {y}. Default %s. The map always shows the OpenStreetMap credit line.', 'voyara' ), $d['jwsthemes_osm_tile_url'] ),
					'default'           => $d['jwsthemes_osm_tile_url'],
					'required'          => array( 'jwsthemes_map_provider', '=', 'openstreetmap' ),
					'validate_callback' => 'jwsthemes_voyara_validate_tile_url',
				),
				array(
					'id'       => 'jwsthemes_mapbox_token',
					'type'     => 'password',
					'title'    => __( 'Mapbox access token', 'voyara' ),
					'subtitle' => __( 'Public token that starts with pk. Create it in your Mapbox account under Tokens.', 'voyara' ),
					'required' => array( 'jwsthemes_map_provider', '=', 'mapbox' ),
					'sanitize' => array( 'sanitize_text_field' ),
				),
				array(
					'id'       => 'jwsthemes_mapbox_style',
					'type'     => 'text',
					'title'    => __( 'Mapbox style', 'voyara' ),
					/* translators: %s: default style id. */
					'subtitle' => sprintf( __( 'Style id as username/style. Default %s.', 'voyara' ), $d['jwsthemes_mapbox_style'] ),
					'default'  => $d['jwsthemes_mapbox_style'],
					'required' => array( 'jwsthemes_map_provider', '=', 'mapbox' ),
					'sanitize' => array( 'sanitize_text_field' ),
				),
				array(
					'id'       => 'jwsthemes_google_maps_key',
					'type'     => 'password',
					'title'    => __( 'Google Maps API key', 'voyara' ),
					'subtitle' => __( 'Key with the Maps JavaScript API turned on, limited to this domain.', 'voyara' ),
					'required' => array( 'jwsthemes_map_provider', '=', 'google' ),
					'sanitize' => array( 'sanitize_text_field' ),
				),
				array(
					'id'       => 'jwsthemes_google_map_id',
					'type'     => 'text',
					'title'    => __( 'Google Map ID', 'voyara' ),
					'subtitle' => __( 'Map ID from Google Cloud > Map Management. Needed for custom pins.', 'voyara' ),
					'required' => array( 'jwsthemes_map_provider', '=', 'google' ),
					'sanitize' => array( 'sanitize_text_field' ),
				),
				array(
					'id'                => 'jwsthemes_map_lat',
					'type'              => 'text',
					'title'             => __( 'Default center latitude', 'voyara' ),
					'subtitle'          => __( 'Used when a map has no listings to show. -90 to 90. Default 20.', 'voyara' ),
					'default'           => $d['jwsthemes_map_lat'],
					'validate_callback' => 'jwsthemes_voyara_validate_coordinate',
				),
				array(
					'id'                => 'jwsthemes_map_lng',
					'type'              => 'text',
					'title'             => __( 'Default center longitude', 'voyara' ),
					'subtitle'          => __( '-180 to 180. Default 0.', 'voyara' ),
					'default'           => $d['jwsthemes_map_lng'],
					'validate_callback' => 'jwsthemes_voyara_validate_coordinate',
				),
				array(
					'id'            => 'jwsthemes_map_zoom',
					'type'          => 'slider',
					'title'         => __( 'Default zoom', 'voyara' ),
					'subtitle'      => __( '1 shows the world, 18 shows a street. Default 2.', 'voyara' ),
					'default'       => $d['jwsthemes_map_zoom'],
					'min'           => 1,
					'max'           => 18,
					'step'          => 1,
					'display_value' => 'text',
				),
			),
		)
	);

	// Blog.
	Redux::set_section(
		$opt,
		array(
			'title'  => __( 'Blog', 'voyara' ),
			'id'     => 'blog',
			'icon'   => 'el el-pencil',
			'desc'   => __( 'Applies to the blog pages the theme draws. A Theme Builder archive or single template replaces them.', 'voyara' ),
			'fields' => array(
				array(
					'id'      => 'jwsthemes_blog_layout',
					'type'    => 'button_set',
					'title'   => __( 'Card style', 'voyara' ),
					'options' => array(
						'grid' => __( 'Grid', 'voyara' ),
						'list' => __( 'List', 'voyara' ),
					),
					'default' => $d['jwsthemes_blog_layout'],
				),
				array(
					'id'       => 'jwsthemes_blog_sidebar',
					'type'     => 'button_set',
					'title'    => __( 'Sidebar', 'voyara' ),
					'subtitle' => __( 'Shows the widgets from Appearance > Widgets > Blog sidebar.', 'voyara' ),
					'options'  => array(
						'right' => __( 'Right', 'voyara' ),
						'left'  => __( 'Left', 'voyara' ),
						'none'  => __( 'None', 'voyara' ),
					),
					'default'  => $d['jwsthemes_blog_sidebar'],
				),
				array(
					'id'       => 'jwsthemes_blog_related',
					'type'     => 'switch',
					'title'    => __( 'Related posts', 'voyara' ),
					'subtitle' => __( 'Three posts from the same category under each post.', 'voyara' ),
					'default'  => $d['jwsthemes_blog_related'],
				),
				array(
					'id'       => 'jwsthemes_blog_author',
					'type'     => 'switch',
					'title'    => __( 'Author box', 'voyara' ),
					'subtitle' => __( 'Name, photo and biographical info from the author profile in Users > Profile.', 'voyara' ),
					'default'  => $d['jwsthemes_blog_author'],
				),
				array(
					'id'       => 'jwsthemes_blog_progress',
					'type'     => 'switch',
					'title'    => __( 'Reading progress bar', 'voyara' ),
					'subtitle' => __( 'Thin bar at the top of the window that fills as the reader scrolls a post.', 'voyara' ),
					'default'  => $d['jwsthemes_blog_progress'],
				),
			),
		)
	);

	// 404.
	Redux::set_section(
		$opt,
		array(
			'title'  => __( '404 page', 'voyara' ),
			'id'     => 'page404',
			'icon'   => 'el el-error',
			'desc'   => __( 'Applies to the 404 page the theme draws. A Theme Builder 404 template replaces it.', 'voyara' ),
			'fields' => array(
				array(
					'id'       => 'jwsthemes_404_title',
					'type'     => 'text',
					'title'    => __( 'Title', 'voyara' ),
					'default'  => $d['jwsthemes_404_title'],
					'sanitize' => array( 'sanitize_text_field' ),
				),
				array(
					'id'       => 'jwsthemes_404_text',
					'type'     => 'textarea',
					'title'    => __( 'Description', 'voyara' ),
					'default'  => $d['jwsthemes_404_text'],
					'rows'     => 3,
					'sanitize' => array( 'sanitize_textarea_field' ),
				),
				array(
					'id'       => 'jwsthemes_404_image',
					'type'     => 'media',
					'title'    => __( 'Image', 'voyara' ),
					'subtitle' => __( 'Shown above the title. Empty shows no image.', 'voyara' ),
				),
				array(
					'id'       => 'jwsthemes_404_button_label',
					'type'     => 'text',
					'title'    => __( 'Button label', 'voyara' ),
					'default'  => $d['jwsthemes_404_button_label'],
					'sanitize' => array( 'sanitize_text_field' ),
				),
				array(
					'id'                => 'jwsthemes_404_button_url',
					'type'              => 'text',
					'title'             => __( 'Button link', 'voyara' ),
					'subtitle'          => __( 'Empty links to the home page.', 'voyara' ),
					'validate_callback' => 'jwsthemes_voyara_validate_url',
				),
			),
		)
	);
}
add_action( 'after_setup_theme', 'jwsthemes_voyara_register_theme_options', 30 );

/**
 * Default header or footer template field, or a note when Voyara Core is not active.
 *
 * @param string $location header or footer.
 * @param bool   $has_core Whether Voyara Core is active.
 * @return array Redux field.
 */
function jwsthemes_voyara_template_field( $location, $has_core ) {
	$id = 'jwsthemes_' . $location . '_template';
	if ( ! $has_core ) {
		return array(
			'id'    => $id . '_info',
			'type'  => 'info',
			'style' => 'warning',
			'desc'  => __( 'Activate the Voyara Core plugin to choose a header or footer built with Elementor.', 'voyara' ),
		);
	}
	return array(
		'id'          => $id,
		'type'        => 'select',
		'title'       => 'header' === $location ? __( 'Default header', 'voyara' ) : __( 'Default footer', 'voyara' ),
		'subtitle'    => __( 'Used on every page that no Theme Builder template matches by its display conditions. Empty uses the built-in one.', 'voyara' ),
		'desc'        => __( 'Create and edit templates in Voyara > Theme Builder.', 'voyara' ),
		'data'        => 'posts',
		'args'        => array(
			'post_type'      => JWSThemes_Voyara_Post_Types::TEMPLATE,
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => 'title',
			'order'          => 'ASC',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key, WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- admin screen only, at most 100 templates.
			'meta_key'       => JWSThemes_Voyara_Theme_Builder::META_TYPE,
			'meta_value'     => $location, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		),
		'placeholder' => __( 'Built-in', 'voyara' ),
	);
}

/**
 * Main colour: a hex colour, with a warning when white text on it is under 4.5:1.
 *
 * @param array  $field    Field.
 * @param mixed  $value    New value.
 * @param string $existing Saved value.
 * @return array
 */
function jwsthemes_voyara_validate_main_color( $field, $value, $existing ) {
	$color = sanitize_hex_color( is_string( $value ) ? trim( $value ) : '' );
	if ( ! $color ) {
		$field['msg'] = __( 'Enter a colour as # and 6 hex digits, for example #0E3B36.', 'voyara' );
		return array(
			'value' => $existing,
			'error' => $field,
		);
	}
	$out = array( 'value' => strtoupper( $color ) );
	if ( jwsthemes_voyara_contrast( $color, '#FFFFFF' ) < 4.5 ) {
		$field['msg'] = __( 'Main color is too light for white button text, so buttons use black text. Choose a darker colour to keep white text.', 'voyara' );
		$out['warning'] = $field;
	}
	return $out;
}

/**
 * A full URL or a site path. An empty value is allowed.
 *
 * @param array  $field    Field.
 * @param mixed  $value    New value.
 * @param string $existing Saved value.
 * @return array
 */
function jwsthemes_voyara_validate_url( $field, $value, $existing ) {
	$value = is_string( $value ) ? trim( $value ) : '';
	if ( '' === $value ) {
		return array( 'value' => '' );
	}
	$clean = esc_url_raw( $value, array( 'http', 'https', 'mailto', 'tel' ) );
	if ( '' === $clean || ( '/' !== $value[0] && ! wp_http_validate_url( $clean ) && ! preg_match( '/^(mailto|tel):/i', $clean ) ) ) {
		$field['msg'] = __( 'Enter a full URL such as https://example.com/page/ or a path such as /contact/.', 'voyara' );
		return array(
			'value' => $existing,
			'error' => $field,
		);
	}
	return array( 'value' => $clean );
}

/**
 * Tile URL: https, with {z}, {x} and {y}.
 *
 * @param array  $field    Field.
 * @param mixed  $value    New value.
 * @param string $existing Saved value.
 * @return array
 */
function jwsthemes_voyara_validate_tile_url( $field, $value, $existing ) {
	$value = is_string( $value ) ? trim( $value ) : '';
	if ( '' === $value ) {
		return array( 'value' => jwsthemes_voyara_option_defaults()['jwsthemes_osm_tile_url'] );
	}
	$ok = 0 === stripos( $value, 'https://' ) && false !== strpos( $value, '{z}' ) && false !== strpos( $value, '{x}' ) && false !== strpos( $value, '{y}' );
	if ( ! $ok ) {
		$field['msg'] = __( 'The tile URL must start with https:// and contain {z}, {x} and {y}.', 'voyara' );
		return array(
			'value' => $existing,
			'error' => $field,
		);
	}
	// esc_url_raw() would encode the braces, so only drop characters a URL cannot hold.
	return array( 'value' => preg_replace( '/[^A-Za-z0-9\-._~:\/?#\[\]@!$&\'()*+,;=%{}]/', '', $value ) );
}

/**
 * Latitude (-90 to 90) or longitude (-180 to 180).
 *
 * @param array  $field    Field.
 * @param mixed  $value    New value.
 * @param string $existing Saved value.
 * @return array
 */
function jwsthemes_voyara_validate_coordinate( $field, $value, $existing ) {
	$limit = 'jwsthemes_map_lat' === $field['id'] ? 90 : 180;
	$value = is_string( $value ) ? str_replace( ',', '.', trim( $value ) ) : '';
	if ( ! is_numeric( $value ) || abs( (float) $value ) > $limit ) {
		/* translators: %d: 90 or 180. */
		$field['msg'] = sprintf( __( 'Enter a number from -%1$d to %1$d, for example 38.7223.', 'voyara' ), $limit );
		return array(
			'value' => $existing,
			'error' => $field,
		);
	}
	return array( 'value' => (string) round( (float) $value, 6 ) );
}

/**
 * Shows the light logo and the favicon from where WordPress keeps them.
 *
 * @param array $options Options Redux loaded.
 * @return array
 */
function jwsthemes_voyara_options_load( $options ) {
	foreach ( array(
		'jwsthemes_logo'    => (int) get_theme_mod( 'custom_logo', 0 ),
		'jwsthemes_favicon' => (int) get_option( 'site_icon', 0 ),
	) as $id => $attachment ) {
		$url            = $attachment ? wp_get_attachment_image_url( $attachment, 'full' ) : '';
		$options[ $id ] = $url ? array(
			'id'        => (string) $attachment,
			'url'       => $url,
			'thumbnail' => (string) wp_get_attachment_image_url( $attachment, 'thumbnail' ),
		) : array();
	}
	return $options;
}
add_filter( 'redux/options/' . JWSTHEMES_VOYARA_OPT_NAME . '/options', 'jwsthemes_voyara_options_load' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

/**
 * Saves the light logo as the Site Logo and the favicon as the Site Icon, instead of
 * keeping copies as theme mods.
 *
 * @param array $options Options about to be saved (by reference).
 */
function jwsthemes_voyara_options_save( &$options ) {
	if ( array_key_exists( 'jwsthemes_logo', $options ) ) {
		$logo = is_array( $options['jwsthemes_logo'] ) ? absint( $options['jwsthemes_logo']['id'] ?? 0 ) : 0;
		if ( $logo && wp_attachment_is_image( $logo ) ) {
			set_theme_mod( 'custom_logo', $logo );
		} else {
			remove_theme_mod( 'custom_logo' );
		}
		unset( $options['jwsthemes_logo'] );
	}
	if ( array_key_exists( 'jwsthemes_favicon', $options ) ) {
		$icon = is_array( $options['jwsthemes_favicon'] ) ? absint( $options['jwsthemes_favicon']['id'] ?? 0 ) : 0;
		if ( current_user_can( 'manage_options' ) ) {
			update_option( 'site_icon', $icon && wp_attachment_is_image( $icon ) ? $icon : 0 );
		}
		unset( $options['jwsthemes_favicon'] );
	}
}
add_action( 'redux/options/' . JWSTHEMES_VOYARA_OPT_NAME . '/validate', 'jwsthemes_voyara_options_save' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
