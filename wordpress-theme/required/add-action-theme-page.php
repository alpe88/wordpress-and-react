<?php

/**
 * Add the Theme Settings page to the WordPress admin menu.
 */
function theme_add_settings_page() {
    add_menu_page(
        'Theme Settings',              // Page title
        'Theme Settings',              // Menu title
        'manage_options',              // Capability required
        'theme-settings',         // Menu slug
        'theme_settings_page_html', // Callback function to render the page
        'dashicons-admin-customizer',  // Icon
        100                             // Position in the menu
    );
}
add_action('admin_menu', 'theme_add_settings_page');

/**
 * Register Theme Settings.
 */
function theme_register_settings() {
    // Register color settings
    register_setting('theme_settings_group', 'theme_primary_color');
    register_setting('theme_settings_group', 'theme_highlight_color');
    register_setting('theme_settings_group', 'theme_text_primary_color');
    register_setting('theme_settings_group', 'theme_text_secondary_color');
    register_setting('theme_settings_group', 'theme_background_color'); // New setting

    // Register logo setting
    register_setting('theme_settings_group', 'theme_logo');

    // Register Hide Header setting
    register_setting('theme_settings_group', 'theme_hide_header');
}
add_action('admin_init', 'theme_register_settings');

/**
 * Render the Theme Settings page HTML.
 */
function theme_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Theme Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('theme_settings_group');
            do_settings_sections('theme_settings_group');
            ?>
            <table class="form-table">
                <!-- Primary Color -->
                <tr valign="top">
                    <th scope="row">Primary Color</th>
                    <td>
                        <input type="text" name="theme_primary_color" value="<?php echo esc_attr(get_option('theme_primary_color', '#23577F')); ?>" class="color-field" />
                    </td>
                </tr>
                <!-- Highlight Color -->
                <tr valign="top">
                    <th scope="row">Highlight Color</th>
                    <td>
                        <input type="text" name="theme_highlight_color" value="<?php echo esc_attr(get_option('theme_highlight_color', '#f4942c')); ?>" class="color-field" />
                    </td>
                </tr>
                <!-- Primary Text Color -->
                <tr valign="top">
                    <th scope="row">Primary Text Color</th>
                    <td>
                        <input type="text" name="theme_text_primary_color" value="<?php echo esc_attr(get_option('theme_text_primary_color', '#000')); ?>" class="color-field" />
                    </td>
                </tr>
                <!-- Secondary Text Color -->
                <tr valign="top">
                    <th scope="row">Secondary Text Color</th>
                    <td>
                        <input type="text" name="theme_text_secondary_color" value="<?php echo esc_attr(get_option('theme_text_secondary_color', '#000')); ?>" class="color-field" />
                    </td>
                </tr>
                <!-- Background Color -->
                <tr valign="top">
                    <th scope="row">Background Color</th>
                    <td>
                        <input type="text" name="theme_background_color" value="<?php echo esc_attr(get_option('theme_background_color', '#FFFFFF')); ?>" class="color-field" />
                    </td>
                </tr>
                <!-- Logo Upload -->
                <tr valign="top">
                    <th scope="row">Logo</th>
                    <td>
                        <input type="hidden" id="theme_logo" name="theme_logo" value="<?php echo esc_attr(get_option('theme_logo')); ?>" />
                        <button type="button" class="button" id="theme_logo_button">Select Logo</button>
                        <p class="description">Upload or select a logo from the media library.</p>
                        <div id="theme_logo_preview" style="margin-top:10px;">
                            <?php if (get_option('theme_logo')) : ?>
                                <img src="<?php echo esc_url(get_option('theme_logo')); ?>" style="max-width:200px;" />
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <!-- Hide Header -->
                <tr valign="top">
                    <th scope="row">Hide Header</th>
                    <td>
                        <input type="checkbox" name="theme_hide_header" value="1" <?php checked(1, get_option('theme_hide_header', 0)); ?> />
                        <label for="theme_hide_header">Check to hide the header</label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Enqueue Color Picker and Media Uploader Scripts on the Theme Settings Page.
 *
 * @param string $hook_suffix The current admin page.
 */
function theme_enqueue_admin_scripts($hook_suffix) {
    // Check if we're on the Theme Settings page
    if ('toplevel_page_theme-settings' !== $hook_suffix) {
        return;
    }

    // Enqueue the WordPress color picker CSS and JS
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    // Enqueue the WordPress media uploader
    wp_enqueue_media();

    // Enqueue custom admin script to initialize color picker and media uploader
    wp_enqueue_script(
        'theme_admin_script',
        get_template_directory_uri() . '/assets/js/admin.js', // Path to your custom admin JS file
        array('jquery', 'wp-color-picker'),
        null,
        true
    );

    // Inline script to initialize color picker and media uploader
    wp_add_inline_script('theme_admin_script', '
        jQuery(document).ready(function($){
            $(".color-field").wpColorPicker();

            $("#theme_logo_button").click(function(e) {
                e.preventDefault();
                var mediaUploader = wp.media({
                    title: "Select Logo",
                    button: {
                        text: "Use this logo"
                    },
                    multiple: false
                }).on("select", function() {
                    var attachment = mediaUploader.state().get("selection").first().toJSON();
                    $("#theme_logo").val(attachment.url);
                    $("#theme_logo_preview").html("<img src=\'" + attachment.url + "\' style=\'max-width:200px;\' />");
                }).open();
            });
        });
    ');
}
add_action('admin_enqueue_scripts', 'theme_enqueue_admin_scripts');
