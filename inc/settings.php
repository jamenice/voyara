<?php
/**
 * Theme settings shown in Voyara > Settings (Voyara Core).
 *
 * The theme owns its settings: this file describes the fields, checks the values and
 * saves them as theme mods. Voyara Core reads the description through the
 * `jwsthemes_voyara_theme_settings` filter, draws the fields in Voyara Console >
 * Settings and sends the changes back to jwsthemes_voyara_settings_save(). Templates
 * read the values with jwsthemes_voyara_option() (inc/options.php), with or without
 * Voyara Core.
 *
 * Module, page and booking settings belong to Voyara Core. Currency, tax and coupons
 * stay in WooCommerce.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sections and fields.
 *
 * Field keys: id (theme mod), type (switch, seg, select, text, textarea, number, color,
 * media, secret), label, help, options (value => label), min, max, step, unit, width,
 * show (array( field id, value ) to show the field only for that value).
 *
 * @return array<int,array{key:string,title:string,desc:string,fields:array}>
 */
function jwsthemes_voyara_settings_schema() {
	$d     = jwsthemes_voyara_option_defaults();
	$fonts = wp_list_pluck( jwsthemes_voyara_fonts(), 'label' );

	$colors = array(
		'jwsthemes_color_main'    => array( __( 'Main color', 'voyara' ), __( 'Main buttons, links, the selected tab and the border of the field being typed in.', 'voyara' ) ),
		'jwsthemes_color_accent'  => array( __( 'Accent color', 'voyara' ), __( 'Book now buttons, rating stars and highlight labels.', 'voyara' ) ),
		'jwsthemes_color_label'   => array( __( 'Label color', 'voyara' ), __( 'Small capital labels above headings on light backgrounds. Use a colour that is easy to read on white.', 'voyara' ) ),
		'jwsthemes_color_overlay' => array( __( 'Photo overlay color', 'voyara' ), __( 'Laid over hero photos and photo tiles so the white text on them stays easy to read.', 'voyara' ) ),
		'jwsthemes_color_heading' => array( __( 'Heading color', 'voyara' ), __( 'Headings and main text.', 'voyara' ) ),
		'jwsthemes_color_body'    => array( __( 'Body text color', 'voyara' ), __( 'Paragraph text.', 'voyara' ) ),
		'jwsthemes_color_muted'   => array( __( 'Muted text color', 'voyara' ), __( 'Secondary text and notes.', 'voyara' ) ),
		'jwsthemes_color_border'  => array( __( 'Border color', 'voyara' ), __( 'Card borders and divider lines.', 'voyara' ) ),
		'jwsthemes_color_soft'    => array( __( 'Soft background', 'voyara' ), __( 'Chips, icon tiles and light highlight areas.', 'voyara' ) ),
		'jwsthemes_color_light'   => array( __( 'Light background', 'voyara' ), __( 'Secondary sections and table headers.', 'voyara' ) ),
		'jwsthemes_color_page'    => array( __( 'Page background', 'voyara' ), __( 'Background of every page.', 'voyara' ) ),
		'jwsthemes_color_dark'    => array( __( 'Dark section background', 'voyara' ), __( 'Dark bands and the dark footer.', 'voyara' ) ),
	);
	$color_fields = array();
	foreach ( $colors as $id => $labels ) {
		/* translators: %s: default colour, for example #0E3B36. */
		$color_fields[] = array( 'id' => $id, 'type' => 'color', 'label' => $labels[0], 'help' => $labels[1] . ' ' . sprintf( __( 'Default %s.', 'voyara' ), $d[ $id ] ) );
		if ( 'jwsthemes_color_main' === $id ) {
			$color_fields[] = array(
				'id'      => 'jwsthemes_color_on_main',
				'type'    => 'seg',
				'label'   => __( 'Text on main color', 'voyara' ),
				'help'    => __( 'Text on main buttons and the selected tab. Auto picks white when it is easy to read (4.5:1), otherwise black. Default Auto.', 'voyara' ),
				'options' => array(
					'auto'  => __( 'Auto', 'voyara' ),
					'white' => __( 'White', 'voyara' ),
					'black' => __( 'Black', 'voyara' ),
				),
			);
		}
	}

	$social = array();
	foreach ( jwsthemes_voyara_social_networks() as $key => $label ) {
		$social[] = array(
			'id'    => 'jwsthemes_social_' . $key,
			'type'  => 'text',
			/* translators: %s: social network name. */
			'label' => sprintf( __( '%s link', 'voyara' ), $label ),
			'width' => '320px',
		);
	}

	return array(
		array(
			'key'    => 'general',
			'title'  => __( 'General', 'voyara' ),
			'desc'   => __( 'Logo, favicon, page width and scroll animations.', 'voyara' ),
			'fields' => array(
				array( 'id' => 'jwsthemes_logo', 'type' => 'media', 'label' => __( 'Logo for light backgrounds', 'voyara' ), 'help' => __( 'Dark logo on a white or cream header. SVG or PNG, at least 336 x 96 px. Also the Site Logo in Appearance > Customize.', 'voyara' ) ),
				array( 'id' => 'jwsthemes_logo_dark', 'type' => 'media', 'label' => __( 'Logo for dark backgrounds', 'voyara' ), 'help' => __( 'Light logo on a dark or transparent header, and in the dark footer. Empty uses the logo above.', 'voyara' ) ),
				array( 'id' => 'jwsthemes_favicon', 'type' => 'media', 'label' => __( 'Favicon', 'voyara' ), 'help' => __( 'Square image, at least 512 x 512 px. Also the Site Icon in Settings > General. Only administrators can change it.', 'voyara' ) ),
				array( 'id' => 'jwsthemes_container', 'type' => 'number', 'label' => __( 'Container width', 'voyara' ), 'help' => __( 'Width of the page content, from 960 to 1920 px. Default 1366. Set Content Width in Elementor > Site Settings > Layout to the same value.', 'voyara' ), 'min' => 960, 'max' => 1920, 'step' => 2, 'unit' => 'px', 'width' => '100px' ),
				array( 'id' => 'jwsthemes_motion', 'type' => 'switch', 'label' => __( 'Scroll animations', 'voyara' ), 'help' => __( 'Page sections and cards fade in and rise a little as they scroll into view. Visitors who turn motion off in their device settings never see them.', 'voyara' ) ),
			),
		),
		array(
			'key'    => 'colors',
			'title'  => __( 'Colors', 'voyara' ),
			'desc'   => __( 'Every colour on the site, in the Voyara widgets and in Voyara Console comes from these fields. Hover colours and the text colour on buttons are worked out from them. Status colours stay fixed so they keep enough contrast.', 'voyara' ),
			'fields' => $color_fields,
		),
		array(
			'key'    => 'typography',
			'title'  => __( 'Typography', 'voyara' ),
			'desc'   => __( 'Marcellus and Jost are included with the theme. The system fonts load nothing.', 'voyara' ),
			'fields' => array(
				array( 'id' => 'jwsthemes_font_heading', 'type' => 'select', 'label' => __( 'Heading font', 'voyara' ), 'options' => $fonts ),
				array( 'id' => 'jwsthemes_font_body', 'type' => 'select', 'label' => __( 'Body font', 'voyara' ), 'options' => $fonts ),
				array( 'id' => 'jwsthemes_font_size', 'type' => 'number', 'label' => __( 'Base font size', 'voyara' ), 'help' => __( 'Paragraph text, from 14 to 20 px. Default 17.', 'voyara' ), 'min' => 14, 'max' => 20, 'step' => 1, 'unit' => 'px', 'width' => '80px' ),
			),
		),
		array(
			'key'    => 'header',
			'title'  => __( 'Header', 'voyara' ),
			'desc'   => __( 'The phone number, button and sticky setting apply to the built-in header. Elementor header widgets read the phone number and button too.', 'voyara' ),
			'fields' => array_merge(
				jwsthemes_voyara_template_setting( 'header' ),
				array(
					array( 'id' => 'jwsthemes_header_sticky', 'type' => 'switch', 'label' => __( 'Sticky header', 'voyara' ), 'help' => __( 'Keeps the built-in header at the top while scrolling. A Theme Builder header has its own Sticky option.', 'voyara' ) ),
					array( 'id' => 'jwsthemes_header_phone', 'type' => 'text', 'label' => __( 'Phone number', 'voyara' ), 'help' => __( 'Shown in the header. Empty hides it.', 'voyara' ) ),
					array( 'id' => 'jwsthemes_header_cta_label', 'type' => 'text', 'label' => __( 'Button label', 'voyara' ), 'help' => __( 'Button at the right of the header, for example List your property. Empty hides it.', 'voyara' ) ),
					array( 'id' => 'jwsthemes_header_cta_url', 'type' => 'text', 'label' => __( 'Button link', 'voyara' ), 'help' => __( 'Full URL or a path such as /become-a-local-expert/.', 'voyara' ), 'width' => '320px' ),
				)
			),
		),
		array(
			'key'    => 'footer',
			'title'  => __( 'Footer', 'voyara' ),
			'desc'   => __( 'Social links and the copyright line, used by the built-in footer and the Elementor footer widgets.', 'voyara' ),
			'fields' => array_merge(
				jwsthemes_voyara_template_setting( 'footer' ),
				$social,
				array(
					array( 'id' => 'jwsthemes_footer_copyright', 'type' => 'textarea', 'label' => __( 'Copyright line', 'voyara' ), 'help' => __( '{year} becomes the current year and {site} the site name. Links, bold and italic are allowed.', 'voyara' ) ),
				)
			),
		),
		array(
			'key'    => 'maps',
			'title'  => __( 'Maps', 'voyara' ),
			'desc'   => __( 'Maps on search pages, listing pages and the listing editor. OpenStreetMap needs no key. Its public tile server limits heavy use, so switch to Mapbox or Google Maps, or to another tile server, when the site gets a lot of traffic.', 'voyara' ),
			'fields' => array(
				array(
					'id'      => 'jwsthemes_map_provider',
					'type'    => 'seg',
					'label'   => __( 'Map provider', 'voyara' ),
					'options' => array(
						'openstreetmap' => 'OpenStreetMap',
						'mapbox'        => 'Mapbox',
						'google'        => 'Google Maps',
					),
				),
				/* translators: %s: default tile URL. */
				array( 'id' => 'jwsthemes_osm_tile_url', 'type' => 'text', 'label' => __( 'Tile URL', 'voyara' ), 'help' => sprintf( __( 'Must start with https:// and contain {z}, {x} and {y}. Default %s. The map always shows the OpenStreetMap credit line.', 'voyara' ), $d['jwsthemes_osm_tile_url'] ), 'show' => array( 'jwsthemes_map_provider', 'openstreetmap' ), 'width' => '420px' ),
				array( 'id' => 'jwsthemes_mapbox_token', 'type' => 'secret', 'label' => __( 'Mapbox access token', 'voyara' ), 'help' => __( 'Public token that starts with pk. Create it in your Mapbox account under Tokens.', 'voyara' ), 'show' => array( 'jwsthemes_map_provider', 'mapbox' ), 'width' => '360px' ),
				/* translators: %s: default style id. */
				array( 'id' => 'jwsthemes_mapbox_style', 'type' => 'text', 'label' => __( 'Mapbox style', 'voyara' ), 'help' => sprintf( __( 'Style id as username/style. Default %s.', 'voyara' ), $d['jwsthemes_mapbox_style'] ), 'show' => array( 'jwsthemes_map_provider', 'mapbox' ) ),
				array( 'id' => 'jwsthemes_google_maps_key', 'type' => 'secret', 'label' => __( 'Google Maps API key', 'voyara' ), 'help' => __( 'Key with the Maps JavaScript API turned on, limited to this domain.', 'voyara' ), 'show' => array( 'jwsthemes_map_provider', 'google' ), 'width' => '360px' ),
				array( 'id' => 'jwsthemes_google_map_id', 'type' => 'text', 'label' => __( 'Google Map ID', 'voyara' ), 'help' => __( 'Map ID from Google Cloud > Map Management. Needed for custom pins.', 'voyara' ), 'show' => array( 'jwsthemes_map_provider', 'google' ) ),
				array( 'id' => 'jwsthemes_map_lat', 'type' => 'text', 'label' => __( 'Default center latitude', 'voyara' ), 'help' => __( 'Used when a map has no listings to show. -90 to 90. Default 20.', 'voyara' ), 'width' => '120px' ),
				array( 'id' => 'jwsthemes_map_lng', 'type' => 'text', 'label' => __( 'Default center longitude', 'voyara' ), 'help' => __( '-180 to 180. Default 0.', 'voyara' ), 'width' => '120px' ),
				array( 'id' => 'jwsthemes_map_zoom', 'type' => 'number', 'label' => __( 'Default zoom', 'voyara' ), 'help' => __( '1 shows the world, 18 shows a street. Default 2.', 'voyara' ), 'min' => 1, 'max' => 18, 'step' => 1, 'width' => '80px' ),
			),
		),
		array(
			'key'    => 'blog',
			'title'  => __( 'Blog', 'voyara' ),
			'desc'   => __( 'Applies to the blog pages the theme draws. A Theme Builder archive or single template replaces them.', 'voyara' ),
			'fields' => array(
				array(
					'id'      => 'jwsthemes_blog_layout',
					'type'    => 'seg',
					'label'   => __( 'Card style', 'voyara' ),
					'options' => array(
						'grid' => __( 'Grid', 'voyara' ),
						'list' => __( 'List', 'voyara' ),
					),
				),
				array(
					'id'      => 'jwsthemes_blog_sidebar',
					'type'    => 'seg',
					'label'   => __( 'Sidebar', 'voyara' ),
					'help'    => __( 'Shows the widgets from Appearance > Widgets > Blog sidebar.', 'voyara' ),
					'options' => array(
						'right' => __( 'Right', 'voyara' ),
						'left'  => __( 'Left', 'voyara' ),
						'none'  => __( 'None', 'voyara' ),
					),
				),
				array( 'id' => 'jwsthemes_blog_related', 'type' => 'switch', 'label' => __( 'Related posts', 'voyara' ), 'help' => __( 'Three posts from the same category under each post.', 'voyara' ) ),
				array( 'id' => 'jwsthemes_blog_author', 'type' => 'switch', 'label' => __( 'Author box', 'voyara' ), 'help' => __( 'Name, photo and biographical info from the author profile in Users > Profile.', 'voyara' ) ),
				array( 'id' => 'jwsthemes_blog_progress', 'type' => 'switch', 'label' => __( 'Reading progress bar', 'voyara' ), 'help' => __( 'Thin bar at the top of the window that fills as the reader scrolls a post.', 'voyara' ) ),
			),
		),
		array(
			'key'    => 'page404',
			'title'  => __( '404 page', 'voyara' ),
			'desc'   => __( 'Applies to the 404 page the theme draws. A Theme Builder 404 template replaces it.', 'voyara' ),
			'fields' => array(
				array( 'id' => 'jwsthemes_404_title', 'type' => 'text', 'label' => __( 'Title', 'voyara' ), 'width' => '360px' ),
				array( 'id' => 'jwsthemes_404_text', 'type' => 'textarea', 'label' => __( 'Description', 'voyara' ) ),
				array( 'id' => 'jwsthemes_404_image', 'type' => 'media', 'label' => __( 'Image', 'voyara' ), 'help' => __( 'Shown above the title. Empty shows no image.', 'voyara' ) ),
				array( 'id' => 'jwsthemes_404_button_label', 'type' => 'text', 'label' => __( 'Button label', 'voyara' ) ),
				array( 'id' => 'jwsthemes_404_button_url', 'type' => 'text', 'label' => __( 'Button link', 'voyara' ), 'help' => __( 'Empty links to the home page.', 'voyara' ), 'width' => '320px' ),
			),
		),
	);
}

/**
 * Default header or footer template field. Empty while Voyara Core is not active,
 * because the templates are made with its Theme Builder.
 *
 * @param string $location header or footer.
 * @return array[]
 */
function jwsthemes_voyara_template_setting( $location ) {
	if ( ! class_exists( 'JWSThemes_Voyara_Theme_Builder' ) ) {
		return array();
	}
	$options = array( '0' => __( 'Built-in', 'voyara' ) );
	$posts   = get_posts(
		array(
			'post_type'      => JWSThemes_Voyara_Post_Types::TEMPLATE,
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => 'title',
			'order'          => 'ASC',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- admin screen only, at most 100 templates.
			'meta_key'       => JWSThemes_Voyara_Theme_Builder::META_TYPE,
			'meta_value'     => $location, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	foreach ( $posts as $post ) {
		/* translators: %d: template id. */
		$options[ (string) $post->ID ] = '' !== $post->post_title ? $post->post_title : sprintf( __( 'Template %d', 'voyara' ), $post->ID );
	}
	return array(
		array(
			'id'      => 'jwsthemes_' . $location . '_template',
			'type'    => 'select',
			'label'   => 'header' === $location ? __( 'Default header', 'voyara' ) : __( 'Default footer', 'voyara' ),
			'help'    => __( 'Used on every page that no Theme Builder template matches by its display conditions. Built-in uses the theme one. Create templates in Voyara > Theme Builder.', 'voyara' ),
			'options' => $options,
		),
	);
}

/**
 * Field list by id.
 *
 * @return array<string,array>
 */
function jwsthemes_voyara_settings_fields() {
	$out = array();
	foreach ( jwsthemes_voyara_settings_schema() as $section ) {
		foreach ( $section['fields'] as $field ) {
			$out[ $field['id'] ] = $field;
		}
	}
	return $out;
}

/**
 * Attachment id kept for a media setting. The light logo is the `custom_logo` theme
 * mod and the favicon is the `site_icon` option, where WordPress keeps them.
 *
 * @param string $id Field id.
 * @return int
 */
function jwsthemes_voyara_settings_media_id( $id ) {
	if ( 'jwsthemes_logo' === $id ) {
		$image = (int) get_theme_mod( 'custom_logo', 0 );
	} elseif ( 'jwsthemes_favicon' === $id ) {
		$image = (int) get_option( 'site_icon', 0 );
	} else {
		return jwsthemes_voyara_option_image( $id );
	}
	return $image && wp_attachment_is_image( $image ) ? $image : 0;
}

/**
 * Saved values for the settings screen. Keys and tokens are left out; `secrets` gives
 * their last four characters, and `media` the preview of each image.
 *
 * @return array{values:array,secrets:array,media:array}
 */
function jwsthemes_voyara_settings_values() {
	$values  = array();
	$secrets = array();
	$media   = array();
	foreach ( jwsthemes_voyara_settings_fields() as $id => $field ) {
		switch ( $field['type'] ) {
			case 'media':
				$image         = jwsthemes_voyara_settings_media_id( $id );
				$values[ $id ] = $image;
				$media[ $id ]  = $image ? (string) wp_get_attachment_image_url( $image, 'medium' ) : '';
				break;
			case 'secret':
				$key            = (string) jwsthemes_voyara_option( $id );
				$secrets[ $id ] = '' !== $key ? substr( $key, -4 ) : '';
				break;
			case 'select':
				$values[ $id ] = (string) jwsthemes_voyara_option( $id );
				break;
			default:
				$values[ $id ] = jwsthemes_voyara_option( $id );
		}
	}
	return array(
		'values'  => $values,
		'secrets' => $secrets,
		'media'   => $media,
	);
}

/**
 * Checks one value. Returns the value to save, or a WP_Error with the message to show.
 *
 * @param array $field Field.
 * @param mixed $value New value.
 * @return mixed|WP_Error
 */
function jwsthemes_voyara_settings_check( array $field, $value ) {
	$id = $field['id'];
	switch ( $field['type'] ) {
		case 'switch':
			return (bool) $value;
		case 'seg':
		case 'select':
			$value = (string) $value;
			if ( ! array_key_exists( $value, $field['options'] ) ) {
				return new WP_Error( 'jwsthemes_choice', __( 'Choose one of the options.', 'voyara' ) );
			}
			return '_template' === substr( $id, -9 ) ? (int) $value : $value;
		case 'number':
			if ( ! is_numeric( $value ) || (float) $value < $field['min'] || (float) $value > $field['max'] ) {
				/* translators: 1: minimum, 2: maximum. */
				return new WP_Error( 'jwsthemes_range', sprintf( __( 'Enter a number from %1$d to %2$d.', 'voyara' ), $field['min'], $field['max'] ) );
			}
			return (int) round( (float) $value );
		case 'color':
			$color = sanitize_hex_color( is_string( $value ) ? trim( $value ) : '' );
			if ( ! $color || 7 !== strlen( $color ) ) {
				return new WP_Error( 'jwsthemes_color', __( 'Enter a colour as # and 6 hex digits, for example #0E3B36.', 'voyara' ) );
			}
			return strtoupper( $color );
		case 'media':
			$image = absint( $value );
			if ( $image && ! wp_attachment_is_image( $image ) ) {
				return new WP_Error( 'jwsthemes_image', __( 'Choose an image from the Media Library.', 'voyara' ) );
			}
			return $image;
		case 'secret':
			return sanitize_text_field( (string) $value );
		case 'textarea':
			return 'jwsthemes_footer_copyright' === $id
				? trim( wp_kses( (string) $value, jwsthemes_voyara_copyright_html() ) )
				: sanitize_textarea_field( (string) $value );
	}

	// Text fields.
	$value = trim( sanitize_text_field( (string) $value ) );
	if ( in_array( $id, array( 'jwsthemes_header_cta_url', 'jwsthemes_404_button_url' ), true ) || 0 === strpos( $id, 'jwsthemes_social_' ) ) {
		if ( '' === $value ) {
			return '';
		}
		$clean = esc_url_raw( $value, array( 'http', 'https', 'mailto', 'tel' ) );
		if ( '' === $clean || ( '/' !== $value[0] && ! wp_http_validate_url( $clean ) && ! preg_match( '/^(mailto|tel):/i', $clean ) ) ) {
			return new WP_Error( 'jwsthemes_url', __( 'Enter a full URL such as https://example.com/page/ or a path such as /contact/.', 'voyara' ) );
		}
		return $clean;
	}
	if ( 'jwsthemes_osm_tile_url' === $id ) {
		if ( '' === $value ) {
			return jwsthemes_voyara_option_defaults()[ $id ];
		}
		if ( 0 !== stripos( $value, 'https://' ) || false === strpos( $value, '{z}' ) || false === strpos( $value, '{x}' ) || false === strpos( $value, '{y}' ) ) {
			return new WP_Error( 'jwsthemes_tiles', __( 'The tile URL must start with https:// and contain {z}, {x} and {y}.', 'voyara' ) );
		}
		// esc_url_raw() would encode the braces, so only drop characters a URL cannot hold.
		return preg_replace( '/[^A-Za-z0-9\-._~:\/?#\[\]@!$&\'()*+,;=%{}]/', '', $value );
	}
	if ( in_array( $id, array( 'jwsthemes_map_lat', 'jwsthemes_map_lng' ), true ) ) {
		$limit = 'jwsthemes_map_lat' === $id ? 90 : 180;
		$value = str_replace( ',', '.', $value );
		if ( ! is_numeric( $value ) || abs( (float) $value ) > $limit ) {
			/* translators: %d: 90 or 180. */
			return new WP_Error( 'jwsthemes_coordinate', sprintf( __( 'Enter a number from -%1$d to %1$d, for example 38.7223.', 'voyara' ), $limit ) );
		}
		return (string) round( (float) $value, 6 );
	}
	if ( 'jwsthemes_mapbox_style' === $id && '' !== $value && ! preg_match( '#^[\w.-]+/[\w.-]+$#', $value ) ) {
		return new WP_Error( 'jwsthemes_style', __( 'Enter the style id as username/style, for example mapbox/streets-v12.', 'voyara' ) );
	}
	return $value;
}

/**
 * Checks and saves changed values.
 *
 * Nothing is saved when one value fails. A secret saved as false is removed; an empty
 * secret keeps the saved key. The favicon is only changed for users who can manage
 * options, as in Settings > General.
 *
 * @param array $changes Field id => new value.
 * @param bool  $save    False only checks the values.
 * @return array{errors:array<string,string>,warnings:array<string,string>}
 */
function jwsthemes_voyara_settings_save( array $changes, $save = true ) {
	$fields   = jwsthemes_voyara_settings_fields();
	$errors   = array();
	$warnings = array();
	$clean    = array();
	foreach ( $changes as $id => $value ) {
		if ( ! isset( $fields[ $id ] ) ) {
			continue;
		}
		if ( 'secret' === $fields[ $id ]['type'] && false === $value ) {
			$clean[ $id ] = null;
			continue;
		}
		if ( 'secret' === $fields[ $id ]['type'] && '' === trim( (string) $value ) ) {
			continue;
		}
		if ( 'jwsthemes_favicon' === $id && ! current_user_can( 'manage_options' ) ) {
			$errors[ $id ] = __( 'Only administrators can change the favicon.', 'voyara' );
			continue;
		}
		$checked = jwsthemes_voyara_settings_check( $fields[ $id ], $value );
		if ( is_wp_error( $checked ) ) {
			$errors[ $id ] = $checked->get_error_message();
			continue;
		}
		$clean[ $id ] = $checked;
		if ( 'jwsthemes_color_main' === $id && 'auto' === ( $changes['jwsthemes_color_on_main'] ?? jwsthemes_voyara_option( 'jwsthemes_color_on_main' ) ) && jwsthemes_voyara_contrast( $checked, '#FFFFFF' ) < 4.5 ) {
			$warnings[ $id ] = __( 'Main color is too light for white button text, so buttons use black text. Choose a darker colour to keep white text.', 'voyara' );
		}
	}
	if ( $errors || ! $save ) {
		return array(
			'errors'   => $errors,
			'warnings' => $errors ? array() : $warnings,
		);
	}

	foreach ( $clean as $id => $value ) {
		if ( 'jwsthemes_logo' === $id ) {
			$value ? set_theme_mod( 'custom_logo', $value ) : remove_theme_mod( 'custom_logo' );
		} elseif ( 'jwsthemes_favicon' === $id ) {
			update_option( 'site_icon', $value );
		} elseif ( null === $value ) {
			remove_theme_mod( $id );
		} elseif ( 'media' === $fields[ $id ]['type'] ) {
			// The same shape as before (id and url), which jwsthemes_voyara_option_image() reads.
			$value ? set_theme_mod(
				$id,
				array(
					'id'  => (string) $value,
					'url' => (string) wp_get_attachment_url( $value ),
				)
			) : remove_theme_mod( $id );
		} else {
			set_theme_mod( $id, $value );
		}
	}

	/**
	 * Fires after theme settings are saved from Voyara > Settings.
	 *
	 * @param array $clean Saved values by field id.
	 */
	do_action( 'jwsthemes_voyara_settings_saved', $clean );

	return array(
		'errors'   => array(),
		'warnings' => $warnings,
	);
}

/**
 * Hands the settings to Voyara Core, which shows them in Voyara > Settings.
 *
 * @return array{schema:callable,values:callable,save:callable}
 */
function jwsthemes_voyara_theme_settings() {
	return array(
		'schema' => 'jwsthemes_voyara_settings_schema',
		'values' => 'jwsthemes_voyara_settings_values',
		'save'   => 'jwsthemes_voyara_settings_save',
	);
}
add_filter( 'jwsthemes_voyara_theme_settings', 'jwsthemes_voyara_theme_settings' );

/**
 * Sends the old Appearance > Theme Options address to Voyara > Settings. WordPress
 * refuses the old page before admin_init because it no longer exists, so this runs
 * on `admin_page_access_denied`, just before the "not allowed" message.
 */
function jwsthemes_voyara_old_options_page() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only redirect.
	if ( ! isset( $_GET['page'] ) || JWSTHEMES_VOYARA_OPTIONS_PAGE !== $_GET['page'] || ! class_exists( 'JWSThemes_Voyara_Console' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	wp_safe_redirect( admin_url( 'admin.php?page=voyara#/settings?section=general' ) );
	exit;
}
add_action( 'admin_page_access_denied', 'jwsthemes_voyara_old_options_page' );
