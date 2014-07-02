<?php
	global $multipage;

	// Loop through posts
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
?>
	<section class="content-wrapper page-content single-content full-content-wrapper landing-page-content-wrapper cf">
		<section id="post-<?php the_ID(); ?>" <?php post_class( 'post single-page full-content cf' ); ?>>
			<section class="post-container">
				<section class="post-title-wrap cf <?php echo ( has_post_thumbnail() ) ? 'post-title-wrap-featured-image' : 'post-title-wrap-no-image'; ?>">
					<h1 class="post-title"><?php the_title(); ?></h1>
				</section>

				<?php sds_featured_image( false, 'full' ); ?>

				<article class="post-content cf">
					<?php the_content(); ?>

					<section class="clear"></section>

					<?php if ( $multipage ) : ?>
						<section class="single-post-navigation single-post-pagination wp-link-pages">
							<?php wp_link_pages(); ?>
						</section>
					<?php endif; ?>

					<section class="clear"></section>

					<?php edit_post_link( 'Edit Page' ); // Allow logged in users to edit ?>
				</article>
			</section>
		</section>
	</section>
<?php
		endwhile;
	endif;
?>