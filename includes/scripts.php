<?php

namespace VABio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles script loading
 *
 * @since  3.2.0
 * @author VanboDevelops | Ivan Andreev
 *
 *        Copyright: (c) 2018 VanboDevelops
 *        License: GNU General Public License v3.0
 *        License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
class Scripts {
	
	public function __construct() {
		$this->suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$this->version = $this->suffix ? VAB_PLUGIN_VERSION : rand( 1, 999 );
	}
	
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
	}
	
	/**
	 * Adds admin scripts
	 *
	 * @since 3.2.0
	 */
	public function admin_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		
		if ( is_admin()
		     && vab_instance()->admin->user_profile->is_author_or_above()
		     && ( 'profile' == $screen_id
		          || 'edit-user' == $screen_id
		     )
		) {
			global $post;
			wp_enqueue_script( 'admin-bar' );
			wp_enqueue_media( array( 'post' => $post ) );
			wp_register_script( 'vabio-admin', Author_Bio::plugin_url() . '/assets/js/admin' . $this->suffix . '.js', array(
				'jquery',
				'media-editor'
			), $this->version, true );
			
			wp_enqueue_script( 'vabio-admin' );
			wp_localize_script( 'vabio-admin', 'vabio_params', array(
				'defaultImage' => '<img src="' . Author_Bio::plugin_url() . '/assets/images/avatar-default.png" />',
			) );
		}
	}
	
	/**
	 * Include frontend styles and scripts
	 * @since 1.0.0
	 */
	public function frontend_scripts() {
		if ( is_single() ) {
			wp_enqueue_style( 'vabio-styles', Author_Bio::plugin_url() . '/assets/css/frontend' . $this->suffix . '.css', array(), $this->version );
		}
	}
}