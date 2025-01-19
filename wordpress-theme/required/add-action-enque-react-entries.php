<?php
function enqueue_react_app_scripts() {
    $manifest_path = get_template_directory() . '/assets/js/.vite/manifest.json';
    if ( ! file_exists( $manifest_path ) ) {
        return;
    }

    $manifest = json_decode( file_get_contents( $manifest_path ), true );
    $script_uri = get_template_directory_uri() . '/assets/js/';
    
    // Global data object (header, footer, theme).
    // Page data will be added below only if a page-specific script is enqueued.
    $global_data = array(
        'theme'  => get_theme_settings(),
        'header' => get_menu_data('primary'),
        'footer' => get_menu_data('footer'),
    );

    // 1. Enqueue header script
    if ( isset( $manifest['src/entries/header.tsx']['file'] ) ) {
        wp_enqueue_script(
            'react-app-header',
            $script_uri . $manifest['src/entries/header.tsx']['file'],
            array('react-cdn', 'react-dom-cdn'),
            null,
            true
        );

        // Enqueue associated CSS if present
        if ( isset( $manifest['src/entries/header.tsx']['css'] ) ) {
            foreach ( $manifest['src/entries/header.tsx']['css'] as $css_file ) {
                wp_enqueue_style(
                    'react-app-header-css',
                    $script_uri . $css_file,
                    array(),
                    null
                );
            }
        }
    }

    // 2. Enqueue footer script
    if ( isset( $manifest['src/entries/footer.tsx']['file'] ) ) {
        wp_enqueue_script(
            'react-app-footer',
            $script_uri . $manifest['src/entries/footer.tsx']['file'],
            array('react-cdn', 'react-dom-cdn'),
            null,
            true
        );

        // Enqueue associated CSS if present
        if ( isset( $manifest['src/entries/footer.tsx']['css'] ) ) {
            foreach ( $manifest['src/entries/footer.tsx']['css'] as $css_file ) {
                wp_enqueue_style(
                    'react-app-footer-css',
                    $script_uri . $css_file,
                    array(),
                    null
                );
            }
        }
    }

    // 3. Page-Specific Script
    $manifest_key = '';
    $handle       = '';

    if ( is_front_page() ) {
        $manifest_key = 'src/entries/front-page.tsx';
        $handle       = 'react-app-front-page';
    } elseif ( is_page() ) {
        $manifest_key = 'src/entries/page.tsx';
        $handle       = 'react-app-page';
    }

    if ( $manifest_key && isset( $manifest[ $manifest_key ]['file'] ) ) {
        wp_enqueue_script(
            $handle,
            $script_uri . $manifest[ $manifest_key ]['file'],
            array('react-cdn', 'react-dom-cdn'),
            null,
            true
        );

        // Enqueue associated CSS if present
        if ( isset( $manifest[ $manifest_key ]['css'] ) ) {
            foreach ( $manifest[ $manifest_key ]['css'] as $css_file ) {
                wp_enqueue_style(
                    $handle . '-css',
                    $script_uri . $css_file,
                    array(),
                    null
                );
            }
        }

        // Add page-specific data
        $global_data['page'] = get_page_data();

        // Build inline JS snippet for WPGlobalData
        $inline_script = 'const WPGlobalData = ' . json_encode( $global_data ) . ';';

        // Inject inline script BEFORE this script, so WPGlobalData is defined
        wp_add_inline_script( $handle, $inline_script, 'before' );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_react_app_scripts', 999 );

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

    $featured_image_id = get_post_thumbnail_id( $post->ID );

    // Get responsive image URLs
    $responsive_images = array(
        'small'  => wp_get_attachment_image_src( $featured_image_id, 'small' )[0] ?? null,
        'medium' => wp_get_attachment_image_src( $featured_image_id, 'medium' )[0] ?? null,
        'large'  => wp_get_attachment_image_src( $featured_image_id, 'large' )[0] ?? null,
        'xlarge' => wp_get_attachment_image_src( $featured_image_id, 'xlarge' )[0] ?? null,
    );

    return array(
        'id'             => $post->ID,
        'title'          => get_the_title( $post->ID ),
        'content'        => apply_filters( 'the_content', $post->post_content ),
        'excerpt'        => get_the_excerpt( $post->ID ),
        'featured_image' => array(
            'default'     => get_the_post_thumbnail_url( $post->ID, 'full' ),
            'responsive'  => $responsive_images,
        ),
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
