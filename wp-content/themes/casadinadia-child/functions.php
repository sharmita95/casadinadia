<?php
/**
 * Enqueue parent and child theme styles
 */
function rehomes_child_enqueue_styles() {
    // Parent theme styles
    wp_enqueue_style('rehomes-parent-style', get_template_directory_uri() . '/style.css');

    // Child theme styles
    wp_enqueue_style('rehomes-child-style', get_stylesheet_directory_uri() . '/style.css', array('rehomes-parent-style'), wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'rehomes_child_enqueue_styles');

//PLugin Update Encrypted
add_filter('site_transient_update_plugins', 'disable_elementor_updates');
function disable_elementor_updates($value) {
    if (isset($value) && is_object($value) && isset($value->response['elementor/elementor.php'])) {
        unset($value->response['elementor/elementor.php']);
    }
    return $value;
}