<?php

namespace VABio\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin class to handle any additions for the plugin
 *
 * @since  1.0.0
 * @author VanboDevelops | Ivan Andreev
 *
 *        Copyright: (c) 2019 VanboDevelops
 *        License: GNU General Public License v3.0
 *        License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
class Admin {
	
	/**
	 * @var Admin_Notices
	 */
	public $notices;
	/**
	 * @var User_Profile
	 */
	public $user_profile;
	
	public function __construct() {
		$this->load_admin_notices();
		$this->load_user_profile();
	}
	
	/**
	 * Loads the admin notices
	 *
	 * @since 1.0.0
	 */
	public function load_admin_notices() {
		// Load admin notices
		$this->notices = new Admin_Notices();
		$this->notices->hooks();
	}
	
	public function load_user_profile() {
		// Load admin notices
		$this->user_profile = new User_Profile();
		$this->user_profile->hooks();
	}
}