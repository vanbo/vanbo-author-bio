<?php

namespace VABio;

use VABio\Admin\Admin;
use VABio\Display\Bio_Post;

/**
 * @package Author_Bio
 */
class Author_Bio {
	
	/**
	 * The plugin version
	 */
	const VERSION = VAB_PLUGIN_VERSION;
	/**
	 * The files and folders version
	 * Should be changes every time there is a new class file added or one deleted
	 * @since 3.2.0
	 */
	const FILES_VERSION = VAB_PLUGIN_FILES_VERSION;
	/**
	 * Minimal PHP version supported
	 * @since 3.2.0
	 */
	const MIN_PHP_VERSION = '5.6.0';
	/**
	 * Plugin URL
	 * @var string
	 */
	private static $plugin_url;
	/**
	 * Plugin Path
	 * @var string
	 */
	private static $plugin_path;
	/**
	 * @var Scripts
	 */
	public $scripts;
	/**
	 * @var \VABio\Admin\Admin
	 */
	public $admin;
	/**
	 * The single instance of the class.
	 *
	 * @var \VABio\Author_Bio
	 * @since 1.0.0
	 */
	protected static $_instance = null;
	
	/**
	 * Returns main instance of the class
	 * @since 1.0.0
	 * @return \VABio\Author_Bio
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	/**
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Not allowed!', 'vanbo-author-bio' ), '1.0.0' );
	}
	
	/**
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Not allowed!', 'vanbo-author-bio' ), '1.0.0' );
	}
	
	public function __construct() {
		add_action( 'init', array( $this, 'load_text_domain' ) );
		
		add_action( 'init', array( $this, 'load_plugin_essentials' ) );
	}
	
	/**
	 * @since 1.0.0
	 */
	public function load_plugin_essentials() {
		$this->load_scripts();
		
		// Admin only
		if ( is_admin() ) {
			$this->load_admin();
		}
		
		$this->load_bio_display();
	}
	
	/**
	 * @since 1.0.0
	 */
	public function load_scripts() {
		$this->scripts = new Scripts();
		$this->scripts->hooks();
	}
	
	/**
	 * @since 1.0.0
	 */
	public function load_admin() {
		$this->admin = new Admin();
	}
	
	/**
	 * The frontend display
	 */
	public function load_bio_display() {
		$post = new Bio_Post();
		$post->hooks();
	}
	
	/**
	 * Localisation
	 **/
	public function load_text_domain() {
		load_plugin_textdomain( 'vanbo-author-bio', false, dirname( plugin_basename( VAB_PLUGIN_FILE ) ) . '/languages/' );
	}
	
	/**
	 * Safely get GET variables
	 *
	 * @since 1.0.0
	 *
	 * @param string $name GET variable name
	 * @param array  $array
	 * @param string $default
	 *
	 * @return string The variable value
	 */
	public static function get_field( $name, $array, $default = '' ) {
		if ( isset( $array[ $name ] ) ) {
			return $array[ $name ];
		}
		
		return $default;
	}
	
	/**
	 * Get the plugin url
	 *
	 * @return string
	 */
	public static function plugin_url() {
		if ( self::$plugin_url ) {
			return self::$plugin_url;
		}
		
		return self::$plugin_url = untrailingslashit( plugins_url( '/', VAB_PLUGIN_FILE ) );
	}
	
	/**
	 * Get the plugin path
	 *
	 * @return string
	 */
	public static function plugin_path() {
		if ( self::$plugin_path ) {
			return self::$plugin_path;
		}
		
		return self::$plugin_path = untrailingslashit( plugin_dir_path( VAB_PLUGIN_FILE ) );
	}
	
	public function clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( array( $this, 'clean' ), $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}