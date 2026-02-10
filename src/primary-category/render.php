<?php
/**
 * Primary Category Block - Render Template.
 *
 * Displays the primary category link for a post.
 *
 * @package HM_Primary_Category_Block
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

namespace HM_Primary_Category_Block\Blocks\Primary_Category;

$post_id = $block->context['postId'] ?? $attributes['postId'] ?? get_the_ID();
$is_editor = $attributes['isEditor'] ?? false;

if ( ! $post_id ) {
	return;
}

$primary_category = get_primary_category( $post_id );

if ( ! is_a( $primary_category, 'WP_Term' ) ) {
	if ( $is_editor ) {
		?>
		<div class="components-placeholder">
			<div class="components-placeholder__label">Primary Category</div>
			<div class="components-placeholder__instructions">Please select a category for this post.</div>
		</div>
		<?php
	}
	return;
}

primary_category( $post_id, 'hentry__category' );
