<?php
// front-page.php
get_header();

$front_page_id = get_mount_point('front_page', 'react-front-page');
?>
<div id="<?php echo esc_attr($front_page_id); ?>"></div>

<?php
get_footer();
