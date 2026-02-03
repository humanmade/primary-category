<?php

namespace HM_Primary_Category_Block\Blocks\Primary_Category;

function bootstrap() {
	add_action( 'init', __NAMESPACE__ . '\\register_block' );
	add_action( 'init', __NAMESPACE__ . '\\add_primary_category_meta_fields' );
}

function register_block() {
	$block_path = dirname( __FILE__, 3 ) . '/build/primary-category';
	register_block_type( $block_path );
}

const CATEGORY_OVERRIDE_META_KEY = '_category_override';
const CATEGORY_SHORTNAME_META_KEY = 'short_name';

function add_primary_category_meta_fields() {
	register_post_meta(
		'post',
		CATEGORY_OVERRIDE_META_KEY,
		[
			'description' => __( 'Customized title to use for displaying the post primary category', 'hm-primary-category' ),
			'single' => true,
			'type' => 'string',
			'show_in_rest' => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		]
	);

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

	register_term_meta(
		'category',
		'short_name',
		[
			'description' => __( 'Short name of category, used in some article header layouts', 'hm-primary-category' ),
			'single' => true,
			'type' => 'string',
			'show_in_rest' => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback' => function() {
				return current_user_can( 'edit_categories' );
			},
		]
	);
}

function get_short_name( $category ) : string {
	if ( is_int( $category ) ) {
		$category = get_category( $category );
	}

	if ( is_wp_error( $category ) || ! $category ) {
		return null;
	}

	return get_term_meta( $category->term_id, CATEGORY_SHORTNAME_META_KEY, true ) ?: $category->name;
}

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

function primary_category( $post_id, $classname ) {
	$primary_category = get_primary_category( $post_id );
	$category_text_override = get_post_meta( $post_id, CATEGORY_OVERRIDE_META_KEY, true );

	if ( is_a( $primary_category, 'WP_Term' ) ) {
		$category_text = $category_text_override ?: $primary_category->name;
		?>
		<a
			class="<?php echo esc_attr( $classname ); ?>"
			href="<?php echo esc_url( get_category_link( $primary_category ) ); ?>"
		>
			<?php echo esc_html( $category_text ); ?>
		</a>
		<?php
	}
}
