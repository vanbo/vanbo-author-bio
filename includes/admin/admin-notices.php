<?php

namespace VABio\Admin;

use VABio\Author_Bio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that represents admin notices.
 *
 * @since 1.0.0
 */
class Admin_Notices {
	
	/**
	 * All allowed notices
	 * @var array
	 */
	public $allowed_notices = array(
		'phpver',
	);
	/**
	 * Collection of all active notices
	 * @var array
	 */
	public $active_notices = array();
	protected $prefix = 'vabio';
	protected $id = 'vabio';
	protected $text_domain = 'vanbo-author-bio';
	
	/**
	 * Hooks. Should be loaded only once
	 */
	public function hooks() {
		add_action( 'admin_notices', array( $this, 'maybe_admin_notices' ) );
		add_action( 'wp_loaded', array( $this, 'hide_notices' ) );
	}
	
	/**
	 * Adds an allowed notice slug, so we can retrieve and update it
	 *
	 * @since 3.2.0
	 *
	 * @param $slug
	 */
	public function add_allowed_notice( $slug ) {
		$this->allowed_notices[] = $slug;
	}
	
	/**
	 * Adds a notice to the display list
	 *
	 * @since 3.2.0
	 *
	 * @param      $slug
	 * @param      $type
	 * @param      $message
	 * @param bool $dismissible
	 */
	public function add_notice( $slug, $type, $message, $dismissible = false ) {
		
		$map_class = array(
			'error'   => 'error',
			'notice'  => 'notice notice-warning',
			'warning' => 'notice notice-error',
		);
		
		$this->active_notices[ $slug ] = array(
			'class'       => $map_class[ $type ],
			'message'     => $message,
			'dismissible' => $dismissible,
		);
	}
	
	/**
	 * Updates the display status of a notice
	 *
	 * @since 3.2.0
	 *
	 * @param $slug
	 * @param $value
	 */
	public function update_notice( $slug, $value ) {
		update_option( $this->prefix . '_show_notice_' . $slug, $value );
	}
	
	/**
	 * Loads the notices
	 * @since 3.2.0
	 */
	public function maybe_admin_notices() {
		if ( ! current_user_can( 'delete_users' ) ) {
			return;
		}
		
		$this->perform_checks();
		
		foreach ( (array) $this->active_notices as $notice_key => $notice ) {
			echo '<div class="' . esc_attr( $notice['class'] ) . '" style="position:relative;">';
			
			if ( $notice['dismissible'] ) {
				?>
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'vabio-' . $this->id . '-hide-notice', $notice_key ), $this->prefix . '_hide_notice_nonce', $this->prefix . '_notice_nonce' ) ); ?>" class="notice-dismiss" style="position:absolute;right:1px;padding:9px;text-decoration:none;"></a>
				<?php
			}
			
			echo '<p>';
			echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) );
			echo '</p></div>';
		}
	}
	
	public function get_notices_values() {
		$values = array();
		foreach ( $this->allowed_notices as $name ) {
			$values[ $name ] = 'no' == get_option( $this->prefix . '_show_notice_' . $name ) ? false : true;
		}
		
		return $values;
	}
	
	/**
	 * Performs the plugin checks
	 * @since 3.2.0
	 */
	public function perform_checks() {
		$notices_values = $this->get_notices_values();
		
		if ( $notices_values['phpver'] ) {
			if ( version_compare( phpversion(), Author_Bio::MIN_PHP_VERSION, '<' ) ) {
				$message = __( 'Vanbo Author Bio - The minimum PHP version required for this plugin is %1$s. You are running %2$s.', $this->text_domain );
				
				$this->add_notice( 'phpver', 'error', sprintf( $message, Author_Bio::MIN_PHP_VERSION, phpversion() ), true );
				
				return;
			}
		}
		
		// Allow 3rd party to add notices
		do_action( $this->prefix . '_admin_notices_checks', $notices_values, $this );
	}
	
	/**
	 * Hides any admin notices.
	 *
	 * @since 3.2.0
	 */
	public function hide_notices() {
		if ( isset( $_GET[ 'vabio-' . $this->id . '-hide-notice' ] ) && isset( $_GET[ $this->prefix . '_notice_nonce' ] ) ) {
			if ( ! wp_verify_nonce( $_GET[ $this->prefix . '_notice_nonce' ], $this->prefix . '_hide_notice_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', $this->text_domain ) );
			}
			
			if ( ! current_user_can( 'delete_users' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', $this->text_domain ) );
			}
			
			$notice = vab_instance()->clean( $_GET[ 'vabio-' . $this->id . '-hide-notice' ] );
			
			if ( in_array( $notice, $this->allowed_notices ) ) {
				$this->update_notice( $notice, 'no' );
			}
		}
	}
}