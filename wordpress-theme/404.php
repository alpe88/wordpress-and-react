<?php

get_header();

// Grab the "not_found" ID from mount-points.json, defaulting to "react-not-found"
$not_found_id = get_mount_point('not_found', 'react-not-found');
?>
<div id="<?php echo esc_attr($not_found_id); ?>"></div>
<?php
get_footer();