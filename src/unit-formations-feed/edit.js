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
import {useBlockProps, editor} from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import {Spinner} from '@wordpress/components';
import {Component} from '@wordpress/element';

class plapimaSessions extends Component {
	constructor(props) {
		super(props);
		this.state = {
			formations: [],
			loading: true
		};
	}

	componentDidMount() {
		this.runApiFetch();
	}

	runApiFetch() {
		wp.apiFetch({
			path: 'unit-plapima/v1/sessions'
		}).then(data => {
			this.setState({
				formations: data,
				loading: false
			});
		});
	}

	render() {
		return (
			<div className='wp-block'>
				{this.state.loading ? (
					<Spinner/>
				) : (
					<div className="d-flex flex-row container">
						{this.state.formations.map(currentFormation => {
							console.log(currentFormation)
							return (
								<div key={currentFormation.formation.ID} className='col-md-4 card'>
									<div><img src={currentFormation.illustration.src} alt={currentFormation.illustration.alt}/></div>
									<div>{currentFormation.formation.post_title}</div>
									<div>{}</div>
								</div>
							);
						})}
					</div>
				)}
			</div>
		);
	}
}

export default plapimaSessions;

//export default function Edit() {
//
//	return (
//		<div {...useBlockProps()}>
//			{/*{ console.log(`formation: ${data.hasResolvedFormation}`) }*/}
//			{/*{ console.log(`module: ${data.hasResolvedModule}`) }*/}
//			{/*{ console.log(`category: ${data.hasResolvedCategory}`) }*/}
//			<FormationsList hasResolved={data.hasResolvedSession && data.hasResolvedModule
//				&& data.hasResolvedCategory}
//							sessions={data.sessions}
//							modules={data.modules}
//							categories={data.categories}/>
//		</div>
//	);
//}
//
//function FormationsList({hasResolved, sessions, modules, categories}) {
//	//	useEffect(() => {
//	//		console.log('formations :', modules);
//	//		console.log('modules :', categories);
//	//		console.log('categories :', formations);
//	//		console.log('hasResolved :', hasResolved);
//	//	}, [modules, categories, formations]);
//
//	if (! hasResolved) {
//		return <Spinner/>;
//	}
//	const formations = [];
//	sessions.forEach((session => {
//		const metas = useSelect(
//			select => {
//				return {
//					meta: select('core/editor').getEditedPostAttribute('meta')
//				};
//			});
//
//		console.log(metas);
//	}));
//
//	if (! sessions?.length) {
//		return <div>Aucune formation pour le moment</div>;
//	}
//	const cat = [];
//	const mod = [];
//
//	formations.forEach((formation => {
//		const categories = useSelect(
//			select => {
//				return {
//					categorie: select(coreDataStore).getEntityRecord('taxonomy', 'categorie', formation.categorie)
//				};
//			});
//		cat[formation.id] = categories.categorie.name;
//		const modules = useSelect(
//			select => {
//				return {
//					module: select(coreDataStore).getEntityRecord('taxonomy', 'module', formation.module)
//				};
//			});
//		mod[formation.id] = modules.module.name;
//	}));
//
//	return (
//
//	);
//}



