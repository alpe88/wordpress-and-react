<?php

/**
 * include favicons
 */
function theme_add_favicons()
{
	// Path to your favicon files
	$favicon_path = get_template_directory_uri() . '/assets/favicons/';

	// Favicon links
	echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($favicon_path) . 'apple-touch-icon.png">';
	echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url($favicon_path) . 'favicon-32x32.png">';
	echo '<link rel="icon" type="image/png" sizes="16x16" href="' . esc_url($favicon_path) . 'favicon-16x16.png">';
	echo '<link rel="manifest" href="' . esc_url($favicon_path) . 'site.webmanifest">';
}
add_action('wp_head', 'theme_add_favicons');