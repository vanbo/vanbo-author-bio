<?php
/*
 * Plugin Name: Vanbo Author Bio
 * Plugin URI: https://vanbodevelops.com
 * Description: Adds Author Bio to Post pages
 * Version: 1.0.0
 * Author: VanboDevelops
 * Author URI: https://vanbodevelops.com
 *
 *        Copyright: (c) 2019 VanboDevelops
 *        License: GNU General Public License v3.0
 *        License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'VAB_PLUGIN_FILE' ) ) {
	define( 'VAB_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'VAB_PLUGIN_DIRECTORY' ) ) {
	define( 'VAB_PLUGIN_DIRECTORY', dirname( __FILE__ ) );
}

if ( ! defined( 'VAB_PLUGIN_VERSION' ) ) {
	define( 'VAB_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'VAB_PLUGIN_FILES_VERSION' ) ) {
	define( 'VAB_PLUGIN_FILES_VERSION', '1.0.0' );
}

try {
	// Load the autoloader
	load_vaa_autoloader();
}
catch ( Exception $e ) {
	// The plugin will not load
	return;
}

/**
 * Loads the plugin autoloader
 * @throws Exception
 * @since 1.0.0
 */
function load_vaa_autoloader() {
	if ( class_exists( '\\VABio\\Autoloader' ) ) {
		return false;
	}
	
	include_once dirname( __FILE__ ) . '/includes/autoloader.php';
	
	$loader = new \VABio\Autoloader( VAB_PLUGIN_DIRECTORY, VAB_PLUGIN_FILES_VERSION, 'includes' );
	spl_autoload_register( array( $loader, 'load_classes' ) );
}

/**
 * Returns the plugin instance.
 * Prevents the possible case for loading the class twice
 *
 * @since  1.0.0
 * @return \VABio\Author_Bio
 */
function vab_instance() {
	return \VABio\Author_Bio::instance();
}

add_action( 'plugins_loaded', 'vab_load_plugin' );
/**
 * Loads the plugin class
 * @return bool|\VABio\Author_Bio
 */
function vab_load_plugin() {
	return vab_instance();
}