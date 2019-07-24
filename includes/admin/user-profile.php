<?php

namespace VABio\Admin;

use VABio\Author_Bio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Description
 *
 * @since
 * @author VanboDevelops | Ivan Andreev
 *
 *        Copyright: (c) 2019 VanboDevelops
 *        License: GNU General Public License v3.0
 *        License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
class User_Profile {
	
	public function hooks() {
		add_action( 'show_user_profile', array( $this, 'add_user_settings' ) );
		add_action( 'edit_user_profile', array( $this, 'add_user_settings' ) );
		
		add_action( 'personal_options_update', array( $this, 'save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save' ) );
	}
	
	/**
	 * Save
	 *
	 * @param $user_id
	 */
	public function save( $user_id ) {
		$image_id = Author_Bio::get_field( 'vabio_avatar_image_id', $_POST, '' );
		
		update_user_meta( $user_id, 'vabio_avatar_image_id', sanitize_text_field( $image_id ) );
	}
	
	public function add_user_settings( $user ) {
		if ( ! $this->is_author_or_above() ) {
			return;
		}
		
		$image_id = get_user_meta( $user->ID, 'vabio_avatar_image_id', true );
		
		$image = '<img src="' . Author_Bio::plugin_url() . '/assets/images/avatar-default.png" />';
		if ( $image_id ) {
			$image = wp_get_attachment_image_src( $image_id, 'medium' );
		}
		
		?>
		<h3><?php echo 'Author Bio' ?></h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="vabio-avatar-image"><?php echo esc_html( __( 'Image', 'vanbo-author-bio' ) ); ?></label>
				</th>
				<td>
					<p class="vabio-add-images hide-if-no-js">
						<button
							class="button vabio-add-image"
							data-title=" <?php esc_attr_e( 'Choose Image', 'remonti-bg' ); ?>"
							data-choose=" <?php esc_attr_e( 'Add Image', 'remonti-bg' ); ?>"
							data-update="<?php esc_attr_e( 'Update Image', 'remonti-bg' ); ?>"
							data-delete="<?php esc_attr_e( 'Delete image', 'remonti-bg' ); ?>"
							data-text="<?php esc_attr_e( 'Delete', 'remonti-bg' ); ?>"
						>
							<?php echo esc_html( __( 'Choose Image', 'remonti-bg' ) ); ?>
						</button>
					</p>
					<p class="vabio-image-preview">
						<?php echo '<img src="' . $image[0] . '" />'; ?>
					</p>
					<input type="hidden"
					       name="vabio_avatar_image_id"
					       id="vabio-avatar-image-id"
					       value="<?php echo esc_attr( $image_id ); ?>"
					       class="vabio-avatar-image-id"
					/>
					<p id="vabio-remove-image-wrap" style="<?php echo $image_id ? '' : 'display:none;' ?>">
						<button type="button"
						        class="button"
						        id="vabio-remove-image"
						>
							<?php echo esc_html( __( 'Remove Image', 'vanbo-author-bio' ) ); ?>
						</button>
					</p>
					<br />
					<span class="description">
						<?php echo esc_html( __( 'Upload your avatar image here. The image will be used on the Author Bio', 'vanbo-author-bio' ) ); ?>
					</span>
				</td>
			</tr>
		</table>
		<?php
	}
	
	/**
	 * Check if current user has at least Author privileges
	 * @since 1.0.0
	 * @uses  current_user_can()
	 * @uses  apply_filters()
	 * @return bool
	 */
	public function is_author_or_above() {
		$is_author_or_above = ( current_user_can( 'edit_published_posts' ) && current_user_can( 'upload_files' ) && current_user_can( 'publish_posts' ) && current_user_can( 'delete_published_posts' ) ) ? true : false;
		
		return (bool) apply_filters( 'vabio_is_author_or_above', $is_author_or_above );
	}
}