<?php

/**
 * Theme setup.
 */
function theme_setup() {
    // Add support for dynamic title tags
    add_theme_support('title-tag');

    // Add support for post thumbnails
    add_theme_support('post-thumbnails');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'yfit'),
        'footer'  => __('Footer Menu', 'yfit'),
    ));

    // Add support for HTML5 markup.
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
}
add_action('after_setup_theme', 'theme_setup');

/**
 * Enqueue scripts and styles.
 */
function scripts()
{
	wp_enqueue_style('yfit-style', get_stylesheet_uri(), array(), SITE_VERSION);
	// wp_enqueue_script('yfit-script', get_template_directory_uri() . '/js/script.min.js', array(), VERSION, true);
}
add_action('wp_enqueue_scripts', 'scripts');