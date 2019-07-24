<?php
/**
 * Single Post Bio Template
 *
 * @since  1.0.0
 * @author VanboDevelops
 * @var string  $image
 * @var string  $bio
 * @var WP_User $author
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="vabio-author-bio-post">
	<h2 class="vabio-bio-header"><?php echo __( 'Author Bio!', 'vanbo-author-bio' ); ?></h2>
	<div class="panel-grid vabio-panel-grid">
		<div class="panel-grid-cell vabio-image-cell vabio-image-cell">
			<div class="bio-image-content">
				<figure class="bio-image">
					<?php echo $image; ?>
				</figure><!-- end.post-featured-image  -->
			</div> <!-- end.post-image-content -->
		</div>
		<div class="panel-grid-cell vabio-content-cell vabio-bio-cell">
			<div class="vabio-text-block">
				<div class="vabio-bio-text-container">
					<p class="entry-title">
						<?php echo __( 'Post by', 'vanbo-author-bio' ) . ' ' . $author->first_name; ?>
					</p> <!-- end.entry-title -->
					<div class="entry-content">
						<?php echo wp_kses_post( $bio ); ?>
					</div> <!-- end .entry-content -->
				</div>
			</div>
		</div>
	</div>
</div>
