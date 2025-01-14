<?php
/**
 * The header for our theme
 *
 * Displays the `head` element and everything up until the `#main-content` element.
 *
 */

 $header_id = get_mount_point('header', 'react-header');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <!-- Skip Link for Accessibility -->
    <a href="#main-content" class="skip-link"><?php esc_html_e('Skip to content', 'yfit'); ?></a>

    <header role="banner" class="site-header">
        <!-- React Header Mount Point -->
        <div id="<?php echo esc_attr( $header_id ); ?>"></div>
    </header>

    <main id="main-content" role="main" class="site-main">
