/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import {__} from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {useBlockProps, InspectorControls} from '@wordpress/block-editor';

import {TextControl, PanelBody} from '@wordpress/components';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import PlapimaSessions from './plapimaSessions';

export default function Edit({attributes, setAttributes}) {

	return (
		<section {...useBlockProps()}>
			<InspectorControls>
				<PanelBody title="Titre">
					<TextControl
						label="Titre du bloc"
						onChange={(block_title) => setAttributes({block_title})}
						value={attributes.block_title}
					/>
					<TextControl
						label="Sous titre"
						onChange={(sub_title) => setAttributes({sub_title})}
						value={attributes.sub_title}
					/>
				</PanelBody>
			</InspectorControls>
			<PlapimaSessions/>
		</section>
	);
}



