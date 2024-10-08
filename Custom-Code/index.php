<?php

/**
 * Plugin Name: Content Design Attributes
 * Plugin URI: https://github.com/WordPress/content-design
 * Description: This is a plugin demonstrating how to register new blocks for the Gutenberg editor.
 * Version: 1.1.0
 * Author: Kiran Nasim
 *
 * @package content-design
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load all translations for our plugin from the MO file.
*/
add_action( 'init', 'content_design_attributes_load_textdomain' );

function content_design_attributes_load_textdomain() {
	load_plugin_textdomain( 'content-design', false, basename( __DIR__ ) . '/languages' );
}

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function content_design_attributes_register_block() {

	// automatically load dependencies and version
	$asset_file = include( plugin_dir_path( __FILE__ ) . 'index.asset.php');

	wp_register_script(
		'content-design-attributes-script-editor',
		plugins_url( 'index.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version']
	);

	wp_register_style(
		'content-design-attributes-editor',
		plugins_url( 'editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
	);

	wp_register_style(
		'content-design-attributes',
		plugins_url( 'style.css', __FILE__ ),
		array( ),
		filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
	);

	register_block_type( 'content-design/attributes', array(
		'style' => 'content-design-attributes',
		'editor_style' => 'content-design-attributes-editor',
		'editor_script' => 'content-design-attributes-script-editor',
	) );

  if ( function_exists( 'wp_set_script_translations' ) ) {
    /**
     * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
     * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
     * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
     */
    wp_set_script_translations( 'content-design-attributes', 'content-design' );
  }
}

add_action( 'init', 'content_design_attributes_register_block' );
