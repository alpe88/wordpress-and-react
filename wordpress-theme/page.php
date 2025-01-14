<?php
// 1. Load mount-points.json to fetch the 'page' ID
$mount_points_path = get_template_directory() . '/mount-points.json';
$mount_points      = null;

if ( file_exists( $mount_points_path ) ) {
    $mount_points = json_decode( file_get_contents( $mount_points_path ), true );
}

// 2. Default to 'react-page' if JSON or key is missing
$page_id = ( is_array($mount_points) && ! empty($mount_points['page']) )
    ? $mount_points['page']
    : 'react-page';

get_header();
?>

<div id="<?php echo esc_attr($page_id); ?>"></div>

<?php
get_footer();