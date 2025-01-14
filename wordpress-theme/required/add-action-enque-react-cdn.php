<?php

/**
 * Enqueue React and ReactDOM via CDN with Subresource Integrity (SRI)
 */
function enqueue_react_cdn_scripts() {
    // 1. Enqueue React
    wp_enqueue_script(
        'react-cdn',
        'https://unpkg.com/react@18/umd/react.production.min.js',
        array(),
        '18.2.0',
        true
    );

    // 2. Enqueue ReactDOM, dependent on React
    wp_enqueue_script(
        'react-dom-cdn',
        'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js',
        array('react-cdn'),
        '18.2.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_react_cdn_scripts');

/**
 * Add SRI (Subresource Integrity) and crossorigin="anonymous" to the above scripts
 */
function add_sri_attributes($tag, $handle, $src) {
    // Example SRI hashes for react@18 and react-dom@18 
    // (you should verify or generate your own from a reliable SRI tool)
    $sri_attributes = array(
        'react-cdn'     => 'sha512-QVs8Lo43F9lSuBykadDb0oSXDL/BbZ588urWVCRwSIoewQv/Ewg1f84mK3U790bZ0FfhFa1YSQUmIhG+pIRKeg==',
        'react-dom-cdn' => 'sha512-6a1107rTlA4gYpgHAqbwLAtxmWipBdJFcq8y5S/aTge3Bp+VAklABm2LO+Kg51vOWR9JMZq1Ovjl5tpluNpTeQ=='
    );

    if ( isset($sri_attributes[$handle]) ) {
        $sri = $sri_attributes[$handle];
        return '<script src="' . esc_url($src) . '" integrity="' . esc_attr($sri) . '" crossorigin="anonymous"></script>';
    }

    return $tag;
}
add_filter('script_loader_tag', 'add_sri_attributes', 10, 3);
