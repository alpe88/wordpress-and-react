<?php
/**
 * deque default WordPress css.
 */
add_action('wp_enqueue_scripts', function () {
	if (
		is_front_page()
		|| is_home()
		|| is_archive()
	) {
		wp_dequeue_style('global-styles');
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('wp-block-library-theme');
	}
}, 100);