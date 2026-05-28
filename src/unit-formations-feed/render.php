<?php

$today = date('Y-m-d H:i');
$sessionsAVenir = new WP_Query([
	'post_type' => 'session',
	'meta_query' => [
		[
			'key' => 'date_de_debut',
			'value' => $today,
			'compare' => '>',
		],
	],
	'meta_key' => 'date_de_debut',
	'orderby' => 'meta_value',
	'order' => 'ASC',
]);

$formations = [];
if ($sessionsAVenir->have_posts()):
	foreach ($sessionsAVenir->posts as $session) {
		$formationID = get_field('formation', $session->ID);
		if (! in_array($formationID[0]->ID, $formations) && count($formations) <= 3) {
			$formations[] = $formationID[0]->ID;
		}
	}

	if (count($formations) > 0):
		?>
		<div class="d-flex flex-md-row flex-column flex-wrap mt-4">
			<?php
			foreach ($formations as $formation) {
				$post = get_post($formation);
				$typeFormation = get_field('type_de_formation', $post->ID);
				if ($typeFormation['value'] === 'base')
					$terms = get_field('categorie', $post->ID)->name;
				$illustration = get_field('illustration', $post->ID);
				$resume = get_field('libelle_de_la_formation', $post->ID);

				$size = wp_is_mobile() ? 'medium' : 'large';
				$img = $illustration
					? altTextForFormationImages($illustration, $size)
					: getBasicImage('2025/06',
						'img-bis-formations.png', wp_is_mobile() ? 'medium' : 'medium_large');

				?>
				<div class="formation-home-card d-flex flex-column rounded mb-4">
					<div class="image">
						<img src="<?php echo $img['src']; ?>" alt="">
					</div>

					<div class="content d-flex flex-column">
						<div class="themes d-flex flex-row">
							<?php
							$taxos = get_the_terms($post->ID, 'theme_formation');
							if ($taxos) {
								foreach ($taxos as $taxo):

									$classe = get_term_meta($taxo->term_id, 'classe');
									//						dd($classe);
									?>
									<div class="theme">
										<i class="icon-<?php echo $classe[0]; ?>"></i>
									</div>
								<?php
								endforeach;
							}
							?>
						</div>
						<h3>
							<a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
						</h3>
						<hr>
						<?php
						if ($typeFormation['value'] === 'base'):
							?>
							<p><?php echo $terms; ?></p>
						<?php
						endif;
						echo $resume ? '<div class="resume">' . createNewsExcerpt(100, $resume) . '</div>' : '';
						?>
						<div class="link">
							<a href="<?php echo get_permalink($post->ID); ?>">
								Détail de la formation
							</a>
						</div>
					</div>

				</div>
				<?php
			}

			?>
		</div>
	<?php

	else:
		?>
		<div class="d-flex flex-md-row flex-column">
			<p>Aucune session de formation à venir pour le moment</p>
		</div>
	<?php

	endif;

endif;
