<?php
/**
 * This template is used for the display of single pages.
 */

get_header(); ?>

	<section class="content-wrapper page-content index cf">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>

		<?php get_template_part( 'loop', 'page' ); // Loop - Page ?>

		<?php comments_template(); // Comments ?>
	</section>

<?php get_footer(); ?>