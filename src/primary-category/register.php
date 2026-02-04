<?php

namespace HM_Primary_Category_Block\Blocks\Primary_Category;

/**
 * Bootstrap the primary category block functionality.
 *
 * Registers hooks for block registration and meta fields.
 *
 * @return void
 */
function bootstrap() {
	add_action( 'init', __NAMESPACE__ . '\\register_block' );
	add_action( 'init', __NAMESPACE__ . '\\add_primary_category_meta_fields' );
}

/**
 * Register the primary category block type.
 *
 * @return void
 */
function register_block() {
	$block_path = dirname( __FILE__, 3 ) . '/build/primary-category';
	register_block_type( $block_path );
}

/**
 * Register meta fields for primary category functionality.
 *
 * Registers the Yoast primary category meta field for REST API access
 * if Yoast SEO is available.
 *
 * @return void
 */
function add_primary_category_meta_fields() {
	if ( function_exists( 'yoast_get_primary_term_id' ) ) {
		// Re-register the Yoast "primary_category" meta field for use in REST.
		register_post_meta(
			'post',
			'_yoast_wpseo_primary_category',
			[
				'single' => true,
				'type' => 'string',
				'show_in_rest' => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			]
		);
	}
}

/**
 * Get the top-level parent category of a term.
 *
 * If the term has a parent, returns the parent category.
 * Otherwise, returns the term itself.
 *
 * @param WP_Term $term The term object.
 * @return WP_Term|null The parent category or the term itself, null on error.
 */
function get_primary_category_parent( $term ) {
	if ( $term->parent ) {
		$category_parent = get_category( $term->parent );
		if ( is_wp_error( $category_parent ) ) {
			return null;
		} else {
			return $category_parent;
		}
	} else {
		return $term;
	}
}

/**
 * Get the primary category for a post.
 *
 * Priority order:
 * 1. Yoast SEO primary category (if available)
 * 2. Single category (if post has only one)
 * 3. First category with parent resolution
 *
 * @param int $post_id The post ID.
 * @return WP_Term|null The primary category term or null if none found.
 */
function get_primary_category( $post_id ) {
	$post = get_post( $post_id );
	$categories = get_the_category( $post_id );
	$yoast_primary_id = function_exists( 'yoast_get_primary_term_id' )
		? yoast_get_primary_term_id( 'category', $post_id )
		: 0;

	if ( $yoast_primary_id ) {
		$yoast_primary = get_category( $yoast_primary_id );
		return get_primary_category_parent( $yoast_primary );
	}

	if ( empty( $categories ) ) {
		return null;
	}

	if ( 1 === count( $categories ) || 'submissions' === $post->post_type ) {
		return reset( $categories );
	}

	foreach ( $categories as $category ) {
		return get_primary_category_parent( $category );
	}
}

/**
 * Output the primary category as a linked element.
 *
 * Renders an anchor tag with the primary category name and link.
 *
 * @param int    $post_id   The post ID.
 * @param string $classname CSS class name for the anchor element.
 * @return void
 */
function primary_category( $post_id, $classname ) {
	$primary_category = get_primary_category( $post_id );

	if ( is_a( $primary_category, 'WP_Term' ) ) {
		?>
		<a
			class="<?php echo esc_attr( $classname ); ?>"
			href="<?php echo esc_url( get_category_link( $primary_category ) ); ?>"
		>
			<?php echo esc_html( $primary_category->name ); ?>
		</a>
		<?php
	}
}
