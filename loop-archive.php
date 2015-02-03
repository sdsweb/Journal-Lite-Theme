<header class="archive-title">
	<?php sds_archive_title(); ?>
</header>

<?php
	// Loop through posts
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
?>
	<section id="post-<?php the_ID(); ?>" <?php post_class( 'post cf' ); ?>>
		<section class="post-container">
			<section class="post-title-wrap cf <?php echo ( has_post_thumbnail() ) ? 'post-title-wrap-featured-image' : 'post-title-wrap-no-image'; ?>">
				<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

				<?php if ( $post->post_type === 'post' ) : ?>
					<p class="post-date">
						<?php if ( strlen( get_the_title() ) > 0 ) : ?>
							<?php printf( __( 'Posted by %1$s on %2$s', 'journal' ) , '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author_meta( 'display_name' ) . '</a>', get_the_time( get_option( 'date_format' ) ) ); ?>
						<?php else: // No title ?>
							<a href="<?php the_permalink(); ?>">
								<?php printf( __( 'Posted by %1$s on %2$s', 'journal' ) , get_the_author_meta( 'display_name' ), get_the_time( get_option( 'date_format' ) ) ); ?>
							</a>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</section>

			<?php sds_featured_image( true ); ?>

			<article class="post-content cf">
				<?php
					// Display excerpt if one has been specifically set by post author
					if ( ! empty( $post->post_excerpt ) ) :
						the_excerpt();
				?>
						<p><a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Read More' ,'journal' ); ?></a></p>
				<?php
					else :
						the_content();
					endif;
				?>
			</article>
		</section>
	</section>
<?php
		endwhile;
	else : // No posts
?>
	<section class="no-results no-posts no-home-results post">
		<section class="post-container">
			<?php sds_no_posts(); ?>
		</section>
	</section>
<?php endif; ?>