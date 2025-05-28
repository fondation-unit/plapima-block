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

							return (
								<div key={currentFormation.formation.ID} className='col-md-6'>
									<div><img src={src} alt={alt}/></div>
									<div>{currentFormation.formation.post_title}</div>
									<div>{currentFormation.categorie}</div>
									<div>{currentFormation.module}</div>
									<div>{currentFormation.niveau}</div>
								</div>
							);
						})}
					</div>
				)}
			</div>
		);
	}
}
