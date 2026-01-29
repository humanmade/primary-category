import { useBlockProps } from '@wordpress/block-editor';
import { Disabled, Placeholder } from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';

function Edit( props ) {
	const { attributes, context, setAttributes } = props;
	const { postId } = attributes;

	const currentPostId = useSelect( ( select ) => {
		return select( 'core/editor' )?.getCurrentPostId();
	}, [] );

	useEffect( () => {
		const contextPostId = context?.postId;
		const effectivePostId = contextPostId || currentPostId;
		
		if ( effectivePostId && effectivePostId !== postId ) {
			setAttributes( {
				postId: effectivePostId,
			} );
		}
	}, [ context, currentPostId, postId, setAttributes ] );

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
					attributes={ attributes }
					block="hm/primary-category"
				/>
			</Disabled>
		</div>
	);
}

export default Edit;
