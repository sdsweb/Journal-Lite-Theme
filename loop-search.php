<?php
	if ( have_posts() ) : // Search results
?>
	<header class="search-title">
		<h1 title="<?php esc_attr_e( sprintf( __( 'Search results for \'%s\'', 'journal' ), get_search_query() ) ); ?>" class="page-title"><?php printf( __( 'Search results for "%s"', 'journal' ), get_search_query() ); ?></h1>
	</header>

	<?php while ( have_posts() ) : the_post(); ?>
		<section class="content-wrapper home-content home cf">
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
								<p><a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Read More', 'journal' ); ?></a></p>
						<?php
							else :
								the_content();
							endif;
						?>
					</article>
				</section>
			</section>
		</section>
	<?php endwhile; ?>
<?php else : // No search results ?>
	<header class="search-title">
		<h1 title="<?php esc_attr_e( sprintf( __( 'No results for \'%s\'', 'journal' ), get_search_query() ) ); ?>'" class="page-title"><?php printf( __( 'No results for "%s"', 'journal' ), get_search_query() ); ?></h1>
	</header>

	<section class="content-wrapper post-content-wrapper single-content cf">
		<section class="no-results no-posts no-search-results post">

			<section class="post-container">
				<?php sds_no_posts(); ?>

				<section id="search-again" class="search-again search-block no-posts no-search-results">
					<p><?php _e( 'Would you like to search again?', 'journal' ); ?></p>
					<?php echo get_search_form(); ?>
				</section>
			</section>
		</section>
	</section>
<?php endif; ?>