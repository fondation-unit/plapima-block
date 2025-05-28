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
		if (! in_array($formationID[0]->ID, $formations) && count($formations) < 4) {
			$formations[] = $formationID[0]->ID;
		}
	}

	if (count($formations) > 0):
		?>
		<div class="d-flex flex-md-row flex-column flex-wrap">
			<?php
			foreach ($formations as $formation) {
				$post = get_post($formation);
				$typeFormation = get_field('type_de_formation', $post->ID);
				if ($typeFormation['value'] === 'base')
					$terms = get_field('categorie', $post->ID)->name;
				$illustration = get_field('illustration', $post->ID);
				$resume = get_field('resume', $post->ID);

				?>
				<div class="formation-home-card d-flex flex-column py-4 rounded">
					<h3 class="rounded">
						<a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
					</h3>
					<div class="image rounded">
						<?php
						if ($illustration):
							$img = altTextForFormationImages($illustration, 'large');
							?>
							<img class="rounded" src="<?php echo $img['src']; ?>" alt="<?php echo $img['alt']; ?>">

						<?php
						endif;
						?>
					</div>
					<div class="content d-flex flex-column rounded">

						<?php
						if ($typeFormation['value'] === 'base'):
							?>
							<p><?php echo $terms; ?></p>
						<?php
						endif;
						echo $resume ? '<div class="resume">'.createNewsExcerpt(100, $resume).'</div>' : '';
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
