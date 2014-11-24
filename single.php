<?php
/*
 * This template is used for the display of single posts.
 */

get_header(); ?>

	<section class="content-wrapper single-content index cf">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>

		<?php get_template_part( 'loop' ); // Loop ?>

		<section class="clear"></section>

		<?php comments_template(); // Comments ?>
	</section>

<?php get_footer(); ?>