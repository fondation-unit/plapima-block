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
	foreach($sessionsAVenir->posts as $session){
		$formationID = get_field('formation', $session->ID);
		if(!in_array($formationID[0]->ID, $formations) && count($formations) < 3){
			$formations[] = $formationID[0]->ID;
		}
	}

	if(count($formations) > 0):
		?>
		<div class="d-flex flex-md-row flex-column">
			<?php
				foreach($formations as $formation){
					$post = get_post($formation);
					$illustration = get_field('illustration', $post->ID);
					$terms = get_field('categorie', $post->ID)->name;

					?>
					<div class="card col-md-4 d-flex flex-column">
						<div class="image">
							<?php
							if ($illustration):
								$img = altTextForFormationImages($illustration, 'large');
								?>
								<a href="<?php echo get_permalink($post->ID); ?>">
									<img src="<?php echo $img['src']; ?>" alt="<?php echo $img['alt']; ?>">
								</a>
							<?php
							endif;
							?>
						</div>
						<div class="content">
							<h2>
								<a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
							</h2>
							<p><?php echo $terms; ?></p>
							<div class="link d-flex justify-content-end">
								<a href="<?php echo get_permalink($post->ID); ?>" class="btn btn-primary">
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
