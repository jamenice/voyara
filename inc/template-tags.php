<?php
/**
 * Template helpers.
 *
 * @package Voyara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lets the Voyara Core Theme Builder print a template for a location.
 *
 * Voyara Core hooks `jwsthemes_voyara_render_location` and returns true when it printed
 * an Elementor template. Without the plugin, or without a matching template, the theme
 * prints its own fallback, so the site still works when the plugin or Elementor is off.
 *
 * @param string $location header, footer, single, archive, taxonomy or 404.
 * @return bool True when the plugin printed the location.
 */
function jwsthemes_voyara_render_location( $location ) {
	return (bool) apply_filters( 'jwsthemes_voyara_render_location', false, $location );
}

/**
 * Site logo, or the site name when no logo is set.
 *
 * The light logo is the Site Logo (`custom_logo`). On a dark background the logo for
 * dark backgrounds from Voyara > Settings is used when one is set.
 *
 * @param string $background light or dark.
 */
function jwsthemes_voyara_logo( $background = 'light' ) {
	$logo = 'dark' === $background ? jwsthemes_voyara_option_image( 'jwsthemes_logo_dark' ) : 0;
	if ( ! $logo ) {
		$logo = (int) get_theme_mod( 'custom_logo', 0 );
	}
	if ( $logo && wp_attachment_is_image( $logo ) ) {
		printf(
			'<a class="vy-brand vy-brand--logo" href="%1$s" rel="home">%2$s</a>',
			esc_url( home_url( '/' ) ),
			wp_get_attachment_image(
				$logo,
				'full',
				false,
				array(
					'class'   => 'custom-logo',
					'alt'     => get_bloginfo( 'name' ),
					'loading' => false,
				)
			)
		);
		return;
	}
	printf(
		'<a class="vy-brand" href="%s" rel="home">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Phone number and button from Voyara > Settings > Header.
 */
function jwsthemes_voyara_header_actions() {
	$phone = jwsthemes_voyara_option( 'jwsthemes_header_phone' );
	$label = jwsthemes_voyara_option( 'jwsthemes_header_cta_label' );
	$url   = jwsthemes_voyara_option( 'jwsthemes_header_cta_url' );
	if ( '' === $phone && ( '' === $label || '' === $url ) ) {
		return;
	}
	echo '<div class="vy-header__actions">';
	if ( '' !== $phone ) {
		printf(
			'<a class="vy-header__phone" href="%1$s">%2$s</a>',
			esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ),
			esc_html( $phone )
		);
	}
	if ( '' !== $label && '' !== $url ) {
		printf( '<a class="vy-btn vy-btn--accent" href="%1$s">%2$s</a>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</div>';
}

/**
 * HTML allowed in the copyright line.
 *
 * @return array
 */
function jwsthemes_voyara_copyright_html() {
	return array(
		'a'      => array(
			'href'   => true,
			'rel'    => true,
			'target' => true,
		),
		'strong' => array(),
		'em'     => array(),
		'br'     => array(),
	);
}

/**
 * Copyright line from Voyara > Settings > Footer, with {year} and {site} filled in.
 */
function jwsthemes_voyara_copyright() {
	$text = strtr(
		jwsthemes_voyara_option( 'jwsthemes_footer_copyright' ),
		array(
			'{year}' => wp_date( 'Y' ),
			'{site}' => get_bloginfo( 'name' ),
		)
	);
	if ( '' === trim( $text ) ) {
		return;
	}
	echo '<p class="vy-copy">' . wp_kses( $text, jwsthemes_voyara_copyright_html() ) . '</p>';
}

/**
 * Social networks offered in Voyara > Settings > Footer.
 *
 * @return array<string,string>
 */
function jwsthemes_voyara_social_networks() {
	return array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'x'         => 'X',
		'youtube'   => 'YouTube',
		'tiktok'    => 'TikTok',
	);
}

/**
 * Social links from Voyara > Settings > Footer.
 */
function jwsthemes_voyara_social_links() {
	$links = array();
	foreach ( jwsthemes_voyara_social_networks() as $key => $label ) {
		$url = jwsthemes_voyara_option( 'jwsthemes_social_' . $key );
		if ( '' !== $url ) {
			$links[] = sprintf( '<li><a href="%1$s" rel="noopener me" target="_blank">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
		}
	}
	if ( $links ) {
		printf(
			'<ul class="vy-social" aria-label="%1$s">%2$s</ul>',
			esc_attr__( 'Social links', 'voyara' ),
			implode( '', $links ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each item escaped above.
		);
	}
}

/**
 * Blog sidebar position for the current view: right, left or none.
 *
 * @return string
 */
function jwsthemes_voyara_blog_sidebar() {
	if ( ! is_active_sidebar( 'sidebar-1' ) || is_search() || ! ( is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() ) ) {
		return 'none';
	}
	$side = jwsthemes_voyara_option( 'jwsthemes_blog_sidebar' );
	return in_array( $side, array( 'right', 'left' ), true ) ? $side : 'none';
}

/**
 * Author box under a post.
 */
function jwsthemes_voyara_author_box() {
	if ( ! jwsthemes_voyara_option( 'jwsthemes_blog_author' ) ) {
		return;
	}
	$author = (int) get_the_author_meta( 'ID' );
	$bio    = get_the_author_meta( 'description' );
	?>
	<section class="vy-author" aria-label="<?php esc_attr_e( 'About the author', 'voyara' ); ?>">
		<?php echo get_avatar( $author, 72, '', '', array( 'class' => 'vy-author__photo' ) ); ?>
		<div>
			<p class="vy-eyebrow"><?php esc_html_e( 'Written by', 'voyara' ); ?></p>
			<h2 class="vy-author__name"><a href="<?php echo esc_url( get_author_posts_url( $author ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></h2>
			<?php if ( $bio ) : ?>
				<p class="vy-author__bio"><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Three related posts from the same categories.
 */
function jwsthemes_voyara_related_posts() {
	if ( ! jwsthemes_voyara_option( 'jwsthemes_blog_related' ) ) {
		return;
	}
	$cats = wp_get_post_categories( get_the_ID() );
	if ( ! $cats ) {
		return;
	}
	$related = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'category__in'        => $cats,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( ! $related->have_posts() ) {
		return;
	}
	?>
	<section class="vy-related" aria-labelledby="vy-related-title">
		<h2 id="vy-related-title" class="vy-related__title"><?php esc_html_e( 'Related posts', 'voyara' ); ?></h2>
		<div class="vy-grid">
			<?php
			while ( $related->have_posts() ) {
				$related->the_post();
				get_template_part( 'template-parts/card' );
			}
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
}

/**
 * Date and author line for posts.
 */
function jwsthemes_voyara_posted_on() {
	printf(
		'<p class="vy-meta"><time datetime="%1$s">%2$s</time><span aria-hidden="true"> · </span>%3$s</p>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_html( get_the_author() )
	);
}

/**
 * Pagination for archives.
 */
function jwsthemes_voyara_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => esc_html__( 'Previous', 'voyara' ),
			'next_text' => esc_html__( 'Next', 'voyara' ),
		)
	);
}
