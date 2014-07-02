<?php
/**
 * This template is used for the display of single image attachments.
 */

get_header(); ?>

	<section class="content-wrapper blog-content index cf">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>

		<?php get_template_part( 'loop', 'attachment-image' ); // Loop - Image Attachment ?>

		<?php comments_template(); // Comments ?>
	</section>

	<section class="clear"></section>

<?php get_footer(); ?>