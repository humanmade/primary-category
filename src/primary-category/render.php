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

if ( ! $post_id ) {
	return;
}

primary_category( $post_id, 'hentry__category' );
