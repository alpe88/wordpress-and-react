<?php
/**
 * Retrieve a mount point ID from a specified JSON file.
 *
 * @param string      $identifier The JSON key to look up (e.g., 'header', 'footer', 'page').
 * @param string      $default    The fallback ID if JSON or key not found.
 * @param string|null $jsonPath   The path to the JSON file; defaults to the theme's mount-points.json.
 *
 * @return string The mount point ID (e.g., 'react-header' or whatever is in the JSON).
 */
function get_mount_point( $identifier, $default, $jsonPath = null ) {
    // 1. If no $jsonPath provided, default to theme's mount-points.json
    if ( ! $jsonPath ) {
        $jsonPath = get_template_directory() . '/mount-points.json';
    }

    // 2. Attempt to read and decode the JSON
    if ( file_exists( $jsonPath ) ) {
        $mount_points = json_decode( file_get_contents( $jsonPath ), true );
        // 3. If it's an array and has the desired key, return it
        if ( is_array( $mount_points ) && ! empty( $mount_points[ $identifier ] ) ) {
            return $mount_points[ $identifier ];
        }
    }

    // 4. Fallback
    return $default;
}
