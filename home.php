<?php
/*
 * This template is used for the display of blog posts (archive; river).
 */

get_header(); ?>

	<section class="content-wrapper blog-content home cf">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>
		<?php get_template_part( 'loop', 'home' ); // Loop - Home ?>

		<section class="clear"></section>

		<?php get_template_part( 'post', 'navigation' ); // Post Navigation ?>
	</section>

<?php get_footer(); ?>