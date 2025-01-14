<?php
function enqueue_react_app_scripts() {
    $manifest_path = get_template_directory() . '/assets/js/manifest.json';
    if ( ! file_exists( $manifest_path ) ) {
        return;
    }

    $manifest  = json_decode( file_get_contents( $manifest_path ), true );
    $base_uri  = get_template_directory_uri() . '/assets/js/';

    // 1. Always load header and footer bundles (if you want them on all pages)
    // ----------------------------------------------------------------------
    // Keys might be: 'header' or 'src/entries/header.tsx'—adjust to match your manifest.
    if ( isset($manifest['header']['file']) ) {
        wp_enqueue_script(
            'react-app-header',
            $base_uri . $manifest['header']['file'],
            array(),
            null,
            true
        );
        if ( isset($manifest['header']['css']) ) {
            foreach($manifest['header']['css'] as $css_file) {
                wp_enqueue_style('react-app-header-css', $base_uri.$css_file, array(), null);
            }
        }

        // For example, localize your header data here
        $menu_data     = get_menu_data('primary');
        $theme_settings = get_theme_settings();
        wp_localize_script('react-app-header', 'WPGlobalDataHeader', array(
            'menu'  => $menu_data,
            'theme' => $theme_settings,
        ));
    }

    if ( isset($manifest['footer']['file']) ) {
        wp_enqueue_script(
            'react-app-footer',
            $base_uri . $manifest['footer']['file'],
            array(),
            null,
            true
        );
        if ( isset($manifest['footer']['css']) ) {
            foreach($manifest['footer']['css'] as $css_file) {
                wp_enqueue_style('react-app-footer-css', $base_uri.$css_file, array(), null);
            }
        }

        // Localize any footer data if needed
        $theme_settings = get_theme_settings();
        wp_localize_script('react-app-footer', 'WPGlobalDataFooter', array(
            'theme' => $theme_settings,
        ));
    }

    // 2. Page-specific scripts using a "switch(true)" pattern
    //    This approach checks conditionals in a switch to set the right key+handle.
    // ----------------------------------------------------------------------
    $key    = '';
    $handle = '';

    switch(true) {
        case is_front_page():
            // This matches "front_page" in your rollup input
            $key    = 'front_page';
            $handle = 'react-app-front-page';
            break;

        case is_archive():
            // E.g., category, tag, custom post type archive
            $key    = 'archive';
            $handle = 'react-app-archive';
            break;

        case is_404():
            $key    = 'not_found';
            $handle = 'react-app-not-found';
            break;

        case is_home(): 
            // The blog index page (WordPress "home" setting for posts, often 'index')
            $key    = 'index'; 
            $handle = 'react-app-index';
            break;

        case is_page(): 
            // Generic page - e.g. 'page.tsx'
            $key    = 'page';
            $handle = 'react-app-page';
            break;

        default:
            // Optionally do nothing or set a fallback
            break;
    }

    // 3. Enqueue the matching script if found
    if ( $key && isset($manifest[ $key ]['file']) ) {
        wp_enqueue_script(
            $handle,
            $base_uri . $manifest[$key]['file'],
            array(),
            null,
            true
        );
        if ( isset($manifest[$key]['css']) ) {
            foreach($manifest[$key]['css'] as $css_file) {
                wp_enqueue_style(
                    $handle . '-css',
                    $base_uri . $css_file,
                    array(),
                    null
                );
            }
        }
        
        // Localize page data
        $page_data   = get_page_data();
        $theme_data  = get_theme_settings();
        wp_localize_script($handle, 'WPGlobalDataPage', array(
            'page'  => $page_data,
            'theme' => $theme_data,
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_react_app_scripts');
/**
 * Add type="module" for certain script handles.
 */
function add_module_type($tag, $handle, $src) {
    // List of handles you want as modules:
    $module_handles = array('react-app-header', 'react-app-footer', 'react-app-front-page');

    if (in_array($handle, $module_handles, true)) {
        // Example: <script type="module" src="..."></script>
        $tag = sprintf('<script type="module" src="%s"></script>' , esc_url($src));
    }

    return $tag;
}
add_filter('script_loader_tag', 'add_module_type', 10, 3);


/**
 * Retrieve Theme Settings from WordPress Options
 *
 * @return array An associative array of theme settings.
 */
function get_theme_settings() {
    return array(
        'primary_color'         => get_option( 'theme_primary_color', '#23577F' ),
        'highlight_color'       => get_option( 'theme_highlight_color', '#f4942c' ),
        'text_primary_color'    => get_option( 'theme_text_primary_color', '#000000' ),
        'text_secondary_color'  => get_option( 'theme_text_secondary_color', '#000000' ),
        'background_color'      => get_option( 'theme_background_color', '#FFFFFF' ),
        'logo'                  => get_option( 'theme_logo', null ),  // Logo URL (null if not set)
        'hide_header'           => get_option( 'theme_hide_header', 0 ),
    );
}

/**
 * Retrieve Current Page Data
 *
 * @return array An associative array of the current page's data.
 */
function get_page_data() {
    global $post;
    if ( ! $post ) {
        return null;
    }

    return array(
        'id'             => $post->ID,
        'title'          => get_the_title( $post->ID ),
        'content'        => apply_filters( 'the_content', $post->post_content ),
        'excerpt'        => get_the_excerpt( $post->ID ),
        'featured_image' => get_the_post_thumbnail_url( $post->ID, 'full' ),
        // Add more fields as needed
    );
}

/**
 * Retrieve Menu Data for a Given Location
 *
 * @param string $location The menu location identifier (e.g., 'primary').
 * @return array|null Returns an array of menu items or null if no menu or items are found.
 */
function get_menu_data( $location ) {
    // Get all registered menu locations
    $locations = get_nav_menu_locations();
    // If this location isn't set or doesn't exist, return null
    if ( ! isset( $locations[ $location ] ) ) {
        return null;
    }

    // Get the menu object from the assigned location
    $menu_id = $locations[ $location ];
    $menu = wp_get_nav_menu_object( $menu_id );
    if ( ! $menu ) {
        return null;
    }

    // Get the menu items
    $menu_items = wp_get_nav_menu_items( $menu->term_id );
    if ( empty( $menu_items ) ) {
        return null;
    }

    // Build a structured array
    $menu_data = array();
    foreach ( $menu_items as $item ) {
        // Only process top-level items (parent == 0)
        if ( $item->menu_item_parent == 0 ) {
            $children = array();
            // Find any children of this item
            foreach ( $menu_items as $child_item ) {
                if ( $child_item->menu_item_parent == $item->ID ) {
                    $children[] = array(
                        'title' => $child_item->title,
                        'url'   => $child_item->url,
                    );
                }
            }

            $menu_data[] = array(
                'title'    => $item->title,
                'url'      => $item->url,
                'children' => ! empty( $children ) ? $children : null,
            );
        }
    }

    return ! empty( $menu_data ) ? $menu_data : null;
}
