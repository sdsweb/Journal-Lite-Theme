<aside class="sidebar cf <?php echo ( is_active_sidebar( 'primary-sidebar' ) ) ? 'widgets' : 'no-widgets'; ?>">
	<?php
		// Primary Sidebar
		if ( is_active_sidebar( 'primary-sidebar' ) ) :
			sds_primary_sidebar();
		// Social Media Fallback
		else :
	?>
			<section class="widget">
				<?php sds_social_media(); ?>
			</section>
	<?php
		endif;
	?>
</aside>