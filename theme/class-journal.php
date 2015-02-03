<?php
/**
 * This class manages all functionality with our Journal theme.
 */
class Journal {
	const JOURNAL_VERSION = '1.1.0';

	private static $instance; // Keep track of the instance

	/**
	 * Function used to create instance of class.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) )
			self::$instance = new Journal;

		return self::$instance;
	}


	/**
	 * This function sets up all of the actions and filters on instance
	 */
	function __construct() {
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ), 20 ); // Register image sizes, nav menus, etc...
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) ); // Add Meta Boxes
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) ); // Used to enqueue editor styles based on post type
		add_action( 'wp_head', array( $this, 'wp_head' ), 1 ); // Add <meta> tags to <head> section
		add_action( 'widgets_init', array( $this, 'widgets_init' ), 20 ); // Unregister sidebars and alter Primary Sidebar output
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) ); // Enqueue all stylesheets (Main Stylesheet, Fonts, etc...)
		add_action( 'wp_footer', array( $this, 'wp_footer' ) ); // Responsive nav

		// Theme Customizer
		add_action( 'customize_register', array( $this, 'customize_register' ), 20 ); // Switch background properties to use refresh transport method
		add_action( 'customize_controls_print_styles', array( $this, 'customize_controls_print_styles' ), 20 ); // Customizer Styles
		add_filter( 'theme_mod_content_color', array( $this, 'theme_mod_content_color' ) ); // Set the default content color

		// Theme Options
		add_filter( 'sds_web_font_css_selector', array( $this, 'sds_web_font_css_selector' ) ); // Web Font CSS Selector

		// Gravity Forms
		add_filter( 'gform_field_input', array( $this, 'gform_field_input' ), 10, 5 ); // Add placholder to newsletter form
		add_filter( 'gform_confirmation', array( $this, 'gform_confirmation' ), 10, 4 ); // Change confirmation message on newsletter form

		// WooCommerce
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 ); // Remove default WooCommerce content wrapper
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 ); // Remove default WooCommerce content wrapper
		add_action( 'woocommerce_before_main_content', array( $this, 'woocommerce_before_main_content' ) ); // Add Journal WooCommerce content wrapper
		add_action( 'woocommerce_after_main_content', array( $this, 'woocommerce_after_main_content' ) ); // Add Journal WooCommerce content wrapper
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 ); // Remove default WooCommerce pagination
		add_action( 'woocommerce_after_main_content', 'woocommerce_pagination', 20 ); // Add WooCommerce pagination
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );// Remove default WooCommerce content wrapper
		add_action( 'woocommerce_sidebar', array( $this, 'woocommerce_sidebar' ), 999 ); // Add Journal WooCommerce closing content wrapper
		add_filter( 'woocommerce_product_settings', array( $this, 'woocommerce_product_settings' ) ); // Adjust default WooCommerce product settings
		add_filter( 'loop_shop_per_page', array( $this, 'loop_shop_per_page' ), 20 ); // Adjust number of items displayed on a catalog page
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 ); // Remove default WooCommerce related products
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'woocommerce_after_single_product_summary' ), 20 ); // Add WooCommerce related products (3x3)
	}


	/************************************************************************************
	 *    Functions to correspond with actions above (attempting to keep same order)    *
	 ************************************************************************************/

	/**
	 * This function adds images sizes to WordPress, unregisters navigation areas that aren't used,
	 * adds HTML5 & Theme Customizer support, etc....
	 */
	function after_setup_theme() {
		global $content_width;

		/**
		 * Set the Content Width for embedded items.
		 */
		if ( ! isset( $content_width ) )
			$content_width = 980;

		add_image_size( 'journal-1044x9999', 1044, 9999, false ); // Used for featured images on single posts and pages

		// Remove top nav which is registered in options panel
		unregister_nav_menu( 'top_nav' );

		// Remove footer nav which is registered in options panel
		unregister_nav_menu( 'footer_nav' );

		// WooCommerce Support
		add_theme_support( 'woocommerce' );

		// Change default core markup for search form, comment form, and comments, etc... to HTML5
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list'
		) );

		// Custom Background (color/image)
		add_theme_support( 'custom-background', array(
			'default-color' => '#000000',
			'wp-head-callback' => 'journal_custom_background_cb' // functions.php
		) );

		// Theme textdomain
		load_theme_textdomain( 'journal', get_template_directory() . '/languages' );
	}

	/**
	 * This function runs when meta boxes are added.
	 */
	function add_meta_boxes() {
		// Post types
		$post_types = get_post_types(
			array(
				'public' => true,
				'_builtin' => false
			)
		);
		$post_types[] = 'post';
		$post_types[] = 'page';

		// Add the metabox for each type
		foreach ( $post_types as $type ) {
			add_meta_box(
				'journal-us-metabox',
				__( 'Layout Settings', 'journal' ),
				array( $this, 'journal_us_metabox' ),
				$type,
				'side',
				'default'
			);
		}
	}

	/**
	 * This function renders a metabox.
	 */
	function journal_us_metabox( $post ) {
		// Get the post type label
		$post_type = get_post_type_object( $post->post_type );
		$label = ( isset( $post_type->labels->singular_name ) ) ? $post_type->labels->singular_name : __( 'Post' );

		echo '<p class="howto">';
		printf(
			__( 'Looking to configure a unique layout for this %1$s? %2$s.', 'journal' ),
			esc_html( strtolower( $label ) ),
			sprintf(
				'<a href="%1$s" target="_blank">Upgrade to Pro</a>',
				esc_url( sds_get_pro_link( 'metabox-layout-settings' ) )
			)
		);
		echo '</p>';
	}

	/**
	 * This function adds editor styles based on post type, before TinyMCE is initalized.
	 * It will also enqueue the correct color scheme stylesheet to better match front-end display.
	 */
	function pre_get_posts() {
		global $sds_theme_options;

		$protocol = is_ssl() ? 'https' : 'http';

		// Admin only and if we have a post
		if ( is_admin() ) {
			add_editor_style( 'css/editor-style.css' );

			// Add correct color scheme if selected
			if ( function_exists( 'sds_color_schemes' ) && ! empty( $sds_theme_options['color_scheme'] ) && $sds_theme_options['color_scheme'] !== 'default' ) {
				$color_schemes = sds_color_schemes();
				add_editor_style( 'css/' . $color_schemes[$sds_theme_options['color_scheme']]['stylesheet'] );
			}

			// Open Sans Web Font (include only if a web font is not selected in Theme Options)
			if ( ! function_exists( 'sds_web_fonts' ) || empty( $sds_theme_options['web_font'] ) )
				add_editor_style( $protocol . '://fonts.googleapis.com/css?family=Arvo|Bitter' ); // Google WebFonts (Open Sans)
		}
	}

	/**
	 * This function unregisters extra sidebars that are not used in this theme.
	 */
	function widgets_init() {
		// Unregister unused sidebars which are registered in SDS Core
		unregister_sidebar( 'secondary-sidebar' );
		unregister_sidebar( 'footer-sidebar' );
		unregister_sidebar( 'copyright-area-sidebar' );
	}

	/**
	 * This function adds <meta> tags to the <head> element.
	 */
	function wp_head() {
	?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<?php
	}

	/**
	 * This function enqueues all styles and scripts (Main Stylesheet, Fonts, etc...).
	 * Stylesheets can be conditionally included if needed
	 */
	function wp_enqueue_scripts() {
		global $sds_theme_options, $is_IE;

		$protocol = is_ssl() ? 'https' : 'http'; // Determine current protocol

		// Journal (main stylesheet)
		wp_enqueue_style( 'journal', get_template_directory_uri() . '/style.css', false, self::JOURNAL_VERSION );

		// Enqueue the child theme stylesheet only if a child theme is active
		if ( is_child_theme() )
			wp_enqueue_style( 'journal-child', get_stylesheet_uri(), array( 'journal' ), self::JOURNAL_VERSION );

		// Arvo & Bitter (include only if a web font is not selected in Theme Options)
		if ( ! function_exists( 'sds_web_fonts' ) || empty( $sds_theme_options['web_font'] ) )
			wp_enqueue_style( 'arvo-bitter-web-fonts', $protocol . '://fonts.googleapis.com/css?family=Arvo|Bitter', false, self::JOURNAL_VERSION );

		// Animate.css
		wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.min.css', false, self::JOURNAL_VERSION );

		// Ensure jQuery is loaded on the front end for our footer script (@see wp_footer() below)
		wp_enqueue_script( 'jquery' );


		// HTML5 Shim (IE only)
		if ( $is_IE )
			wp_enqueue_script( 'html5-shim', get_template_directory_uri() . '/js/html5.js', false, self::JOURNAL_VERSION );
	}

	/**
	 * This function outputs the necessary Javascript for the responsive menus & FitVids.
	 */
	function wp_footer() {
	?>
		<script type="text/javascript">
			// <![CDATA[
			jQuery( function( $ ) {
				// Primary Nav
				$( '.nav-button' ).on( 'touch click', function( e ) {
					if ( window.innerWidth <= 740 ) {
						var $nav_button = $( this ), $primary_nav = $( 'nav.primary-nav' );
						e.stopPropagation();

						// If primary nav is open, toggle the open class on the nav button before animation
						if ( ! $primary_nav.hasClass( 'open' ) ) {
							$nav_button.addClass( 'open' );
						}
						else if ( $nav_button.hasClass( 'open' ) ) {
							$primary_nav.removeClass( 'open' );
						}

						// Slide the primary nav
						$primary_nav.slideToggle( 400, function() {
							// Toggle open class
							if( $primary_nav.is( ':visible' ) ) {
								$primary_nav.addClass( 'open' );
							}
							else {
								$primary_nav.removeClass( 'open' );
							}

							// If the nav button has the open class and the primary nav is not visible, remove it
							if ( $nav_button.hasClass( 'open' ) && ! $primary_nav.is( ':visible' ) ) {
								$nav_button.removeClass( 'open' );
							}
						} );
					}
				} );

				$( document ).on( 'touch click', function() {
					if ( window.innerWidth <= 740 ) {
						var $nav_button = $( '.nav-button' ), $primary_nav = $( 'nav.primary-nav' );

						// If the primary nav is not currently being animated
						if ( ! $primary_nav.is( ':animated' ) ) {
							// Remove the open class from the primary nav
							$primary_nav.removeClass( 'open' );

							// Slide the primary nav up
							$primary_nav.slideUp( 400, function() {
								// Remove the open class from the nav button
								$nav_button.removeClass( 'open' );
							} );
						}
					}
				} );
			} );
			// ]]>
		</script>
	<?php
	}


	/********************
	 * Theme Customizer *
	 ********************/

	/**
	 * This function is run when the Theme Customizer is loaded.
	 */
	function customize_register( $wp_customize ) {
		$wp_customize->add_section( 'journal_us', array(
			'title' => __( 'Upgrade Journal Lite', 'journal' ),
			'priority' => 1
		) );

		$wp_customize->add_setting(
			'journal_us', // IDs can have nested array keys
			array(
				'default' => false,
				'type' => 'journal_us',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_US_Control(
				$wp_customize,
				'journal_us',
				array(
					'content'  => sprintf(
						__( '<strong>Premium support</strong>, more Customizer options, color schemes, web fonts, and more! %s.', 'journal' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( sds_get_pro_link( 'customizer' ) ),
							__( 'Upgrade to Pro', 'journal' )
						)
					),
					'section' => 'journal_us',
				)
			)
		);

		$wp_customize->get_section( 'colors' )->description = sprintf(
			__( 'Looking for more color customizations? %s.', 'journal' ),
			sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( sds_get_pro_link( 'customizer-colors' ) ),
				__( 'Upgrade to Pro', 'journal' )
			)
		);
	}

	/**
	 * This function is run when the Theme Customizer is printing styles.
	 */
	function customize_controls_print_styles() {
	?>
		<style type="text/css">
			#accordion-section-journal_us .accordion-section-title,
			#customize-theme-controls #accordion-section-journal_us .accordion-section-title:focus,
			#customize-theme-controls #accordion-section-journal_us .accordion-section-title:hover,
			#customize-theme-controls #accordion-section-journal_us .control-section.open .accordion-section-title,
			#customize-theme-controls #accordion-section-journal_us:hover .accordion-section-title,
			#accordion-section-journal_us .accordion-section-title:active {
				background: #444;
				color: #fff;
			}

			#accordion-section-journal_us .accordion-section-title:after,
			#customize-theme-controls #accordion-section-journal_us .accordion-section-title:focus::after,
			#customize-theme-controls #accordion-section-journal_us .accordion-section-title:hover::after,
			#customize-theme-controls #accordion-section-journal_us.open .accordion-section-title::after,
			#customize-theme-controls #accordion-section-journal_us:hover .accordion-section-title::after {
				color: #fff;
			}
		</style>
	<?php
	}

	/**
	 * This function sets the default color for the content area in the Theme Customizer.
	 */
	function theme_mod_content_color( $color ) {
		return $color ? $color : '#000000';
	}


	/*****************
	 * Theme Options *
	 *****************/

	/**
	 * This function outputs the necessary CSS selector when web fonts are active.
	 */
	function sds_web_font_css_selector( $selector ) {
		$selector .= ' .header-call-to-action .widget, section.content, .post-title, h1, h2, h3, h4, h5, h6';
		$selector .= ' #respond input, #respond textarea, .widget, h3.widget-title, footer#footer .copyright';

		return $selector;
	}

	/*****************
	 * Gravity Forms *
	 *****************/

	/**
	 * This function adds the HTML5 placeholder attribute to forms with a CSS class of the following:
	 * .mc-gravity, .mc_gravity, .mc-newsletter, .mc_newsletter classes
	 */
	function gform_field_input( $input, $field, $value, $lead_id, $form_id ) {
		$form_meta = RGFormsModel::get_form_meta( $form_id ); // Get form meta

		// Ensure we have at least one CSS class
		if ( isset( $form_meta['cssClass'] ) ) {
			$form_css_classes = explode( ' ', $form_meta['cssClass'] );

			// Ensure the current form has one of our supported classes and alter the field accordingly if we're not on admin
			if ( ! is_admin() && array_intersect( $form_css_classes, array( 'mc-gravity', 'mc_gravity', 'mc-newsletter', 'mc_newsletter' ) ) )
				$input = '<div class="ginput_container"><input name="input_' . $field['id'] . '" id="input_' . $form_id . '_' . $field['id'] . '" type="text" value="" class="large" placeholder="' . $field['label'] . '" /></div>';
		}

		return $input;
	}

	/**
	 * This function alters the confirmation message on forms with a CSS class of the following:
	 * .mc-gravity, .mc_gravity, .mc-newsletter, .mc_newsletter classes
	 */
	function gform_confirmation( $confirmation, $form, $lead, $ajax ) {
		// Ensure we have at least one CSS class
		if ( isset( $form['cssClass'] ) ) {
			$form_css_classes = explode( ' ', $form['cssClass'] );

			// Confirmation message is set and form has one of our supported classes (alter the confirmation accordingly)
			if ( $form['confirmation']['type'] === 'message' && array_intersect( $form_css_classes, array( 'mc-gravity', 'mc_gravity', 'mc-newsletter', 'mc_newsletter' ) ) )
				$confirmation = '<div class="mc-gravity-confirmation mc_gravity-confirmation mc-newsletter-confirmation mc_newsletter-confirmation">' . $confirmation . '</div>';
		}

		return $confirmation;
	}


	/***************
	 * WooCommerce *
	 ***************/

	/**
	 * This function alters the default WooCommerce content wrapper starting element.
	 */
	function woocommerce_before_main_content() {
	?>
		<section class="woocommerce woo-commerce content-wrapper index cf">
			<section class="post woocommerce-post cf">
	<?php
	}

	/**
	 * This function alters the default WooCommerce content wrapper ending element.
	 */
	function woocommerce_after_main_content() {
	?>
			</section>
	<?php
	}

	/**
	 * This function adds to the default WooCommerce content wrapper ending element.
	 */
	function woocommerce_sidebar() {
	?>
		</section>
	<?php
	}

	/**
	 * This function adjusts the default WooCommerce Product settings.
	 */
	function woocommerce_product_settings( $settings ) {
		if ( is_array( $settings ) )
			foreach( $settings as &$setting )
				// Adjust the default value of the Catalog image size
				if( $setting['id'] === 'shop_catalog_image_size' )
					$setting['default']['width'] = $setting['default']['height'] = 300;

		return $settings;
	}

	/**
	 * This function changes the number of products output on the Catalog page.
	 */
	function loop_shop_per_page( $num_items ) {
		return 12;
	}

	/**
	 * This function changes the number of related products displayed on a single product page.
	 */
	function woocommerce_after_single_product_summary() {
		woocommerce_related_products( array(
			'posts_per_page' => 3,
			'columns' => 3
		) );
	}
}


function JournalInstance() {
	return Journal::instance();
}

// Starts Journal
JournalInstance();