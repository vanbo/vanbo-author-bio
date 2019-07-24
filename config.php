<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Include constants.
 * This file is kept separate from the rest of the plugin files
 * because it is for constants that can change from dev to live site
 *
 * @since  1.0.0
 * @author VanboDevelops
 */

if ( ! defined( 'VAA_DEV' ) ) {
	define( 'VAA_DEV', false );
}

if ( ! defined( 'VAA_SCRIPT_DEV' ) ) {
	define( 'VAA_SCRIPT_DEV', false );
	
	if ( ! defined( 'VAA_SCRIPT_DEV_NO_CACHE' ) ) {
		define( 'VAA_SCRIPT_DEV_NO_CACHE', VAA_SCRIPT_DEV );
	}
}