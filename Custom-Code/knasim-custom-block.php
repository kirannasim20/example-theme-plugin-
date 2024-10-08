<?php
/**
 * Plugin Name: KNasim Data API
 */

// Enqueue block editor JavaScript and CSS
function knasim_custom_block_enqueue_scripts() {
    wp_enqueue_script(
        'data_block-js',
        plugins_url('block.js', __FILE__),
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components', 'wp-api'),
        '1.0',
        true
    );

    wp_enqueue_style(
        'data_block-css',
        plugins_url('style.css', __FILE__),
        array('wp-edit-blocks'),
        '1.0'
    );
}
add_action('enqueue_block_editor_assets', 'knasim_custom_block_enqueue_scripts');

// Register block
function knasim_custom_block_register() {
    register_block_type('data_blocks/sample-block', array(
        'editor_script' => 'data_block-js',
        'editor_style' => 'data_block-css',
    ));
}
add_action('init', 'knasim_custom_block_register');

// AJAX callback for block
function knasim_custom_block_ajax_callback() {
    // Perform API call using wp_remote_get()
    $api_url = 'https://miusage.com/v1/challenge/1'; 
    $response = wp_remote_get($api_url);

    // Check for API call success
    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $data = wp_remote_retrieve_body($response);
        $result = array('message' => 'API request successful.', 'data' => $data);
        wp_send_json_success($result);
    } else {
        $result = array('message' => 'API request failed.');
        wp_send_json_error($result);
    }
}

// Register AJAX endpoint
function knasim_custom_block_register_ajax() {
    add_action('wp_ajax_knasim_custom_block_ajax', 'knasim_custom_block_ajax_callback');
    add_action('wp_ajax_nopriv_knasim_custom_block_ajax', 'knasim_custom_block_ajax_callback');
}
add_action('init', 'knasim_custom_block_register_ajax');

// Localize AJAX URL for the block script
function knasim_custom_block_localize_script() {
    wp_localize_script(
        'data_block-js',
        'gutenbergBlockAjax',
        array('ajaxurl' => admin_url('admin-ajax.php'))
    );
}
add_action('enqueue_block_editor_assets', 'knasim_custom_block_localize_script');


