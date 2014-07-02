<section id="post-author">
	<header class="author-header">
		<figure class="author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 148 ); ?>
		</figure>

		<h4><?php echo get_the_author_meta( 'display_name' ); ?></h4>
		<a href="<?php echo get_the_author_meta( 'user_url' ); ?>"><?php echo get_the_author_meta( 'user_url' ); ?></a>
	</header>

	<p><?php echo get_the_author_meta( 'description' ); ?></p>
	<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php _e( 'View more posts from this author', 'journal' ); ?></a>
</section>