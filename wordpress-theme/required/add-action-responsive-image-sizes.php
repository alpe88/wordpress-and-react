<?php

// Define custom image sizes based on MUI breakpoints
function register_image_sizes() {
    add_image_size('mui-xs', 600, 0, false);    // MUI 'xs': Up to 600px
    add_image_size('mui-sm', 900, 0, false);   // MUI 'sm': Up to 900px
    add_image_size('mui-md', 1200, 0, false);  // MUI 'md': Up to 1200px
    add_image_size('mui-lg', 1536, 0, false);  // MUI 'lg': Up to 1536px
    add_image_size('mui-xl', 1920, 0, false);  // MUI 'xl': Anything larger than 1536px (optional)
}
add_action('after_setup_theme', 'register_image_sizes');
