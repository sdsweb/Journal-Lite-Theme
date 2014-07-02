<?php
/**
 * Template Name: Landing Page
 * This template is used for the display of landing pages.
 */

get_header( 'landing-page' ); ?>

	<section class="page-content index cf">
		<?php get_template_part( 'loop', 'page-full-width' ); // Loop - Page - Full Width ?>

		<?php comments_template(); // Comments ?>
	</section>

<?php get_footer( 'landing-page' ); ?>