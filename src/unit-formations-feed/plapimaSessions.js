import {Spinner} from '@wordpress/components';
import {Component} from '@wordpress/element';

export default class PlapimaSessions extends Component {
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
			<div>
				{this.state.loading ? (
					<Spinner/>
				) : (

					<div className="d-flex flex-row flex-wrap">
						{this.state.formations.map(currentFormation => {
							let src = currentFormation.illustration ? currentFormation.illustration.src : '';
							let alt = currentFormation.illustration ? currentFormation.illustration.alt : '';
							let cat = ((currentFormation.type === 'base') ? '<p>{currentFormation.categorie}</p>' :'');
							return (
								<div key={currentFormation.formation.ID} className='formation-home-card d-flex flex-column py-4 rounded'>
									<h3 className='rounded'>{currentFormation.formation.post_title}</h3>
									<div className='image rounded'>
										<img className='rounded' src={src} alt={alt} />
									</div>
									<div className="content d-flex flex-column rounded">
									{cat}
									</div>
								</div>
							);
						})}
					</div>
				)}
			</div>
		);
	}
}
