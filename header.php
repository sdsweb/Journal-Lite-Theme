<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html><!--<![endif]-->
	<head>
		<?php wp_head(); ?>
	</head>

	<body <?php language_attributes(); ?> <?php body_class( 'cf' ); ?>>
		<!-- Header -->
		<header id="header" class="animated <?php echo journal_animation_class(); ?>">
			<!--  Logo 	-->
			<section class="logo-box <?php echo ( is_active_sidebar( 'header-call-to-action-sidebar' ) ) ? 'logo-box-header-cta': 'logo-box-no-header-cta'; ?> <?php echo ( ! is_active_sidebar( 'header-call-to-action-sidebar' ) && ! has_nav_menu( 'top_nav' ) ) ? 'logo-box-full-width': false; ?>">
				<?php sds_logo(); ?>
				<?php sds_tagline(); ?>
			</section>

			<!--  Header Call to Action -->
			<aside class="header-cta-container header-call-to-action <?php echo ( is_active_sidebar( 'header-call-to-action-sidebar' ) ) ? 'widgets' : 'no-widgets'; ?>">
				<?php sds_header_call_to_action_sidebar(); // Header CTA Sidebar ?>
			</aside>

			<section class="clear"></section>

			<button class="nav-button"><?php _e( 'Toggle Navigation', 'journal' ); ?></button>
			<nav class="primary-nav">
				<?php
					wp_nav_menu( array(
						'theme_location' => 'primary_nav',
						'container' => false,
						'menu_class' => 'primary-nav menu',
						'menu_id' => 'primary-nav',
						'fallback_cb' => 'sds_primary_menu_fallback'
					) );
				?>
			</nav>
		</header>
		<!-- End Header -->

		<!-- Content -->
		<section class="content cf">