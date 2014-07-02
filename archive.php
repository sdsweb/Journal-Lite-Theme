<?php
/*
 * This template is used for the display of archives.
 */

get_header(); ?>

	<section class="content-wrapper blog-content index cf">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>
		<?php get_template_part( 'loop', 'archive' ); // Loop - Archive ?>

		<section class="clear"></section>

		<?php get_template_part( 'post', 'navigation' ); // Post Navigation ?>
	</section>

<?php get_footer(); ?>