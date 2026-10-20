<?php
/**
 * Demo list for the JWSThemes Demo Import plugin (Tools > Demo Content Install).
 * Every key is explained in the plugin's config-sample.php.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

return array(
	'server'       => 'https://gavencreative.com/import_demo/voyara/',
	'demos'        => array(
		'voyara' => array(
			'title'   => 'Voyara',
			'plugins' => array(
				'voyara-core/voyara-core.php' => 'Voyara Core',
				'elementor/elementor.php'     => 'Elementor',
				'woocommerce/woocommerce.php' => 'WooCommerce',
			),
		),
	),
	'notice'       => __( 'An install replaces the posts, pages, menus, settings and plugin tables of this site with the demo copy. Users, the site address, the theme and the active plugins stay. To add demo listings to a site you already built, use Voyara > Demo data.', 'voyara' ),
	'links'        => array(
		array(
			'label' => __( 'Add demo content instead', 'voyara' ),
			'url'   => admin_url( 'admin.php?page=voyara#/demo-data' ),
		),
	),
	'skip_uploads' => array( 'voyara-private', 'voyara-demo-cache', 'voyara-demos', 'voyara-site-demos' ),
	'skip_options' => array( 'jwsthemes_dev_seed_*', 'jwsthemes_voyara_demo_job', 'jwsthemes_voyara_demo_state' ),
	'style'        => array(
		'primary'      => '#0E3B36',
		'primary_dark' => '#155049',
		'primary_soft' => '#E7EFEC',
		'background'   => '#F6F4EF',
		'soft'         => '#FBF9F5',
		'line'         => '#E6E1D8',
	),
);
