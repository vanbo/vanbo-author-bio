<?php

namespace VABio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Credit to WooCommerce where we took and modified the templating class from
 *
 * @since  1.0.0
 * @author VanboDevelops | Ivan Andreev
 *
 *        Copyright: (c) 2019 VanboDevelops
 *        License: GNU General Public License v3.0
 *        License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
class Templating {
	
	/**
	 * Get other templates (e.g. product attributes) passing attributes and including the file.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 */
	public static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		$cache_key = sanitize_key( implode( '-', array(
			'template',
			$template_name,
			$template_path,
			$default_path,
			VAB_PLUGIN_VERSION
		) ) );
		$template  = (string) wp_cache_get( $cache_key, 'vabio' );
		
		if ( ! $template ) {
			$template = self::locate_template( $template_name, $template_path, $default_path );
			wp_cache_set( $cache_key, $template, 'vabio' );
		}
		
		// Allow 3rd party plugin filter template file from their plugin.
		$filter_template = apply_filters( 'vabio_get_template', $template, $template_name, $args, $template_path, $default_path );
		
		if ( $filter_template !== $template ) {
			if ( ! file_exists( $filter_template ) ) {
				/* translators: %s template */
				_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'vanbo-author-bio' ), '<code>' . $template . '</code>' ), '2.1' );
				
				return;
			}
			$template = $filter_template;
		}
		
		$action_args = array(
			'template_name' => $template_name,
			'template_path' => $template_path,
			'located'       => $template,
			'args'          => $args,
		);
		
		if ( ! empty( $args ) && is_array( $args ) ) {
			if ( isset( $args['action_args'] ) ) {
				_doing_it_wrong(
					__FUNCTION__,
					__( 'action_args should not be overwritten when calling Templating::get_template.', 'vanbo-author-bio' ),
					'1.0.0'
				);
				unset( $args['action_args'] );
			}
			extract( $args ); // @codingStandardsIgnoreLine
		}
		
		do_action( 'vabio_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
		
		include $action_args['located'];
		
		do_action( 'vabio_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
	}
	
	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = self::template_path();
		}
		
		if ( ! $default_path ) {
			$default_path = Author_Bio::plugin_path() . '/templates/';
		}
		
		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
		
		// Get default template/.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		
		// Return what we found.
		return apply_filters( 'vabio_locate_template', $template, $template_name, $template_path );
	}
	
	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public static function template_path() {
		return apply_filters( 'vabio_template_path', 'vanbo-author-bio/' );
	}
}