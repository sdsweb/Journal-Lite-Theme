<?php
	global $multipage;

	// Loop through posts
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
?>
	<section id="post-<?php the_ID(); ?>" <?php post_class( 'post single-post cf' ); ?>>
		<section class="post-container">
			<section class="post-title-wrap cf <?php echo ( has_post_thumbnail() ) ? 'post-title-wrap-featured-image' : 'post-title-wrap-no-image'; ?>">
				<h1 class="post-title"><?php the_title(); ?></h1>
				<?php if ( $post->post_type === 'post' ) : ?>
					<p class="post-date"><?php the_time( 'F jS, Y' ); ?></p>
				<?php endif; ?>
			</section>

			<?php sds_featured_image(); ?>

			<article class="post-content cf">
				<?php the_content(); ?>

				<section class="clear"></section>

				<?php if ( $multipage ) : ?>
					<section class="single-post-navigation single-post-pagination wp-link-pages">
						<?php wp_link_pages(); ?>
					</section>
				<?php endif; ?>

				<section class="clear"></section>

				<?php edit_post_link( 'Edit Post' ); // Allow logged in users to edit ?>

				<?php if ( $post->post_type !== 'attachment' ) : // Post Meta Data (tags, categories, etc...) ?>
					<section class="post-meta">
						<?php sds_post_meta(); ?>
					</section>
				<?php endif ?>
			</article>
		</section>
	</section>

	<?php get_template_part( 'post', 'author' ); // Author Details ?>

	<section class="clear"></section>

	<?php sds_single_post_navigation(); ?>

	<section class="clear"></section>

	<section class="after-posts-widgets <?php echo ( is_active_sidebar( 'after-posts-sidebar' ) ) ? 'after-posts-widgets-active widgets' : 'no-widgets'; ?> cf">
		<?php
			// After posts Sidebar
			if ( is_active_sidebar( 'after-posts-sidebar' ) )
				dynamic_sidebar( 'after-posts-sidebar' );
		?>
	</section>
<?php
		endwhile;
	endif;
?>