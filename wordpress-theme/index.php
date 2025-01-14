<?php

get_header();

// Grab the "index" (blog) ID from mount-points.json, defaulting to "react-blog"
$index_id = get_mount_point('index', 'react-blog');
?>
<div id="<?php echo esc_attr($index_id); ?>"></div>

<?php
get_footer();