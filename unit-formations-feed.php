<?php
/**
 * Plugin Name:       Unit Formations Feed
 * Description:       Example block scaffolded with Create Block tool.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       unit-formations-feed
 *
 * @package CreateBlock
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
/**
 * Registers the block using a `blocks-manifest.php` file, which improves the performance of block type registration.
 * Behind the scenes, it also registers all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
function create_block_unit_formations_feed_block_init()
{
	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
	 *
	 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
	 */
	if (function_exists('wp_register_block_types_from_metadata_collection')) {
		wp_register_block_types_from_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');

		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 * Added to WordPress 6.7 to improve the performance of block type registration.
	 *
	 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
	 */
	if (function_exists('wp_register_block_metadata_collection')) {
		wp_register_block_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');
	}
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
	foreach (array_keys($manifest_data) as $block_type) {
		register_block_type(__DIR__ . "/build/{$block_type}");
	}
}

add_action('init', 'create_block_unit_formations_feed_block_init');

add_action('rest_api_init', function () {
	register_rest_route('unit-plapima/v1', '/sessions', [
		'methods' => 'GET',
		'callback' => 'get_next_sessions',
	]);
});

function get_next_sessions()
{
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
		$formationReturn = [];
		foreach ($sessionsAVenir->posts as $session) {
			$formationID = get_field('formation', $session->ID);
			if (! in_array($formationID[0]->ID, $formations) && count($formations) < 3) {
				$formations[] = $formationID[0]->ID;
			}
		}

		foreach ($formations as $formation) {
			$post = get_post($formation);
			$illustration = get_field('illustration', $post->ID);
			$categorie = get_field('categorie', $post->ID)->name;
			$module = get_field('module', $post->ID)->name;
			$niveau = get_field('niveau', $post->ID)->name;

			$formationReturn[] = [
				'formation' => $post,
				'illustration' => $illustration ? altTextForFormationImages($illustration, 'large') : null,
				'categorie' => $categorie,
				'module' => $module,
				'niveau' => $niveau,
			];
		}

		return $formationReturn;
	else:
		return null;
	endif;
}
