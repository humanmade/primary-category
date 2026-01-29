<?php
/**
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

$post_id = $block->context['postId'] ?? $attributes['postId'] ?? get_the_ID();

if ( ! $post_id ) {
	return;
}

HM_Primary_Category_Block\Blocks\Primary_Category\primary_category( $post_id, 'hentry__category' );
