<?php
/**
 * Plugin Name:       HM Primary Category Block
 * Description:       Displays the primary category for a post.
 * Version:           1.0.0
 * Author:            Human Made Limited
 * Author URI:        https://humanmade.com/
 * License:           GPL-2.0+
 * Text Domain:       hm-primary-category
 */

namespace HM_Primary_Category_Block;

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/src/primary-category/register.php';

Blocks\Primary_Category\bootstrap();
