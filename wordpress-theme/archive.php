<?php

get_header();

// Grab the "archive" ID from mount-points.json, defaulting to "react-archive"
$archive_id = get_mount_point('archive', 'react-archive');
?>
<div id="<?php echo esc_attr($archive_id); ?>"></div>

<?php
get_footer();