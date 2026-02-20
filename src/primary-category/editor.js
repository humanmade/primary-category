/**
 * Primary Category Block Registration.
 *
 * Registers the primary category block type with WordPress.
 *
 * @package HM_Primary_Category_Block
 */

import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import edit from './edit';

registerBlockType( metadata.name, {
	edit,
} );
