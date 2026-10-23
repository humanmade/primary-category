import { useBlockProps } from '@wordpress/block-editor';
import { Disabled, Placeholder } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Edit component for the Primary Category block.
 *
 * Previews the primary category of the post from context (each post in a
 * Query Loop, or the post being edited), else the block's `postId` attribute.
 * The preview request passes that post as `post_id`, because the server reads
 * the post from the request. The wrapper already carries the block's classes
 * and supports, so the preview skips them.
 *
 * @param {Object} props            Component props.
 * @param {Object} props.attributes Block attributes.
 * @param {Object} props.context    Block context.
 * @return {Element} The edit component.
 */
function Edit( { attributes, context } ) {
	const postId = context.postId || attributes.postId;

	const blockProps = useBlockProps();

	if ( ! postId ) {
		return (
			<div { ...blockProps }>
				<Placeholder
					label="Primary Category"
					instructions="This block displays the primary category for a post. It will show the category when viewing the post."
				/>
			</div>
		);
	}

	return (
		<div { ...blockProps }>
			<Disabled>
				<ServerSideRender
					attributes={ { ...attributes, isEditor: true } }
					block="hm/primary-category"
					skipBlockSupportAttributes
					urlQueryArgs={ { post_id: postId } }
				/>
			</Disabled>
		</div>
	);
}

export default Edit;
