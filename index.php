<?php
/**
 * This template is used for the display of all post types that do not have templates (used as a fallback).
 */

get_header(); ?>

	<section class="index-content index cf">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>

		<?php get_template_part( 'loop' ); // Loop ?>

		<section class="clear"></section>

		<?php comments_template(); // Comments ?>
	</section>

	<section class="clear"></section>

	<?php get_template_part( 'post', 'navigation' ); // Post Navigation ?>

<?php get_footer(); ?>