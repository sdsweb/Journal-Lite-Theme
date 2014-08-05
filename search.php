<?php
/**
 * This template is used for the display of search results.
 */

get_header(); ?>

	<section class="blog-content index cf">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>
		<?php get_template_part( 'loop', 'search' ); // Loop - Search ?>

		<section class="clear"></section>

		<?php get_template_part( 'post', 'navigation' ); // Post Navigation ?>
	</section>

<?php get_footer(); ?>