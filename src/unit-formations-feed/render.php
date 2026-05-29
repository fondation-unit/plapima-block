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
$sessionsFutures = [];
if ($sessionsAVenir->have_posts()):
	foreach ($sessionsAVenir->posts as $session) {
		$formationID = get_field('formation', $session->ID);
		if (! in_array($formationID[0]->ID, $formations) && count($formations) < 3) {
			$formations[] = $formationID[0]->ID;
			$sessions = new WP_Query([
				'post_type' => 'session',
				'post_status' => 'publish',
				'meta_query' => [
					'relation' => 'AND',
					[
						'relation' => 'AND',
						[
							'key' => 'formation',
							'value' => $formationID[0]->ID,
							'compare' => 'LIKE',
						],
						[
							'key' => 'date_de_debut',
							'value' => $today,
							'compare' => '>',
						],
					],
				],
				'meta_key' => 'date_de_debut',
				'posts_per_page' => 1,
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'fields' => 'ids',
			]);
			$sessionsFutures[$formationID[0]->ID] = $sessions->posts[0];
		}
	}

	if (count($formations) > 0):
		?>
		<div class="d-flex flex-md-row flex-column justify-content-between titre-formations-home align-items-end mb-4">
			<h2><?php echo $attributes['block_title']; ?></h2>
			<a href="<?php echo get_permalink(CATALOG_PAGE); ?>" class="btn">Découvrir toutes les formations</a>
		</div>
		<p class="sous-titre-formation"><?php echo $attributes['sub_title']; ?></p>
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

				$dateDebut = get_field('date_de_debut', $sessionsFutures[$formation]);
				$dateFin = get_field('date_de_fin', $sessionsFutures[$formation]);

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
							<?php echo $post->post_title; ?>
						</h3>
						<hr>
						<div class="dates mb-4 mt-2">
							<i class="icon-calendrier"></i><span class="date ms-2">Du <?php echo translateDate($dateDebut); ?>
																				   au <?php echo translateDate($dateFin,
									true); ?></span>
						</div>
						<?php

						if ($typeFormation['value'] === 'base'):
							?>
							<p><?php echo $terms; ?></p>
						<?php
						endif;
						echo $resume ? '<div class="resume">' . createNewsExcerpt(100, $resume) . '</div>' : '';
						?>
						<div class="link">
							<div class="btn btn-primary">Détail de la formation
								<i class="fa-solid fa-arrow-right ms-2"></i></div>
						</div>
						<a href="<?php echo get_permalink($post->ID); ?>" class="link-content">
							<span class="sr-only">Voir les détails de
												  l'actualité</span>
							<i class="icon-fleche-actu-projet" aria-hidden="true"></i>
						</a>
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
