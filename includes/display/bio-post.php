<?php

namespace VABio\Display;

use VABio\Templating;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Description
 *
 * @since  1.0.0
 * @author VanboDevelops | Ivan Andreev
 *
 *        Copyright: (c) 2019 VanboDevelops
 *        License: GNU General Public License v3.0
 *        License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
class Bio_Post {
	
	public function hooks() {
		add_action( 'storefront_single_post_bottom', array( $this, 'display' ), 4 );
	}
	
	public function display() {
		$author_id     = get_the_author_meta( 'ID' );
		$user          = new \WP_User( $author_id );
		$user_image_id = get_user_meta( $user->ID, 'vabio_avatar_image_id', true );
		
		$image = wp_get_attachment_image( $user_image_id, 'large' );
		
		Templating::get_template(
			'bio-post.php',
			array(
				'author' => $user,
				'bio'    => $user->description,
				'image'  => $image,
			)
		
		);
	}
}