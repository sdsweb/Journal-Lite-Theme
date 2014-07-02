<?php
/**
 *
 * WARNING: Please do not edit this file.
 * @see http://codex.wordpress.org/Child_Themes
 *
 * Load the theme function files (options panel, theme functions, widgets, etc...).
 */
include_once get_template_directory() . '/includes/Journal.php'; // Journal Class (main functionality, actions/filters)

include_once get_template_directory() . '/includes/class-tgm-plugin-activation.php'; // TGM Activation

include_once get_template_directory() . '/includes/theme-options.php'; // SDS Theme Options
include_once get_template_directory() . '/includes/theme-functions.php'; // SDS Theme Options Functions
include_once get_template_directory() . '/includes/class-customize-us-control.php'; // Customize Controller
include_once get_template_directory() . '/includes/widget-social-media.php'; // SDS Social Media Widget


/**
 * ---------------
 * Theme Specifics
 * ---------------
 */

/**
 * This function registers all web fonts available in this theme.
 */
if ( ! function_exists( 'sds_web_fonts' ) ) {
	function sds_web_fonts() {
		$web_fonts = array(
			// Average Sans
			'Lato:400' => array(
				'label' => 'Lato',
				'css' => 'font-family: \'Lato\', sans-serif;'
			)
		);

		return apply_filters( 'sds_theme_options_web_fonts', $web_fonts );
	}
}

/**
 * This function registers all content layouts available in this theme.
 */
if ( ! function_exists( 'sds_content_layouts' ) ) {
	function sds_content_layouts() {
		$content_layouts = array(
			'default' => array( // Name used in saved option
				'label' => __( 'Default', 'journal' ), // Label on options panel (required)
				'preview' => '<div class="cols cols-1 cols-default"><div class="col col-content" title="%1$s"><span class="label">%1$s</span></div></div>', // Preview on options panel (required; %1$s is replaced with values below on options panel if specified)
				'preview_values' => array( __( 'Default', 'journal' ) ),
				'default' => true
			),
			'cols-2' => array( // Content Left, Primary Sidebar Right
				'label' => __( 'Content Left', 'journal' ),
				'preview' => '<div class="cols cols-2"><div class="col col-content"></div><div class="col col-sidebar"></div></div>'
			)
		);

		return apply_filters( 'sds_theme_options_content_layouts', $content_layouts );
	}
}

/**
 * This function sets the default image dimensions string on the options panel.
 */
if ( ! function_exists( 'sds_theme_options_logo_dimensions' ) ) {
	add_filter( 'sds_theme_options_logo_dimensions', 'sds_theme_options_logo_dimensions' );

	function sds_theme_options_logo_dimensions( $default ) {
		return '475x150';
	}
}

/**
 * This function sets a default featured image size for use in this theme.
 */
if ( ! function_exists( 'sds_theme_options_default_featured_image_size' ) ) {
	add_filter( 'sds_theme_options_default_featured_image_size', 'sds_theme_options_default_featured_image_size' );

	function sds_theme_options_default_featured_image_size( $default ) {
		return 'journal-1044x9999';
	}
}

/**
 * This function adds the custom Theme Customizer styles to the <head> tag.
 */
if ( ! function_exists( 'journal_wp_head' ) ) {
	add_filter( 'wp_head', 'journal_wp_head', 20 );

	function journal_wp_head() {
		global $sds_theme_options;

		$sds_theme_options_instance = SDS_Theme_Options_Instance();
	?>
		<style type="text/css" id="<?php echo $sds_theme_options_instance->get_parent_theme()->get_template(); ?>-theme-customizer">
			/* Content Color */
			section.post-container, footer.post-footer, #post-author {
				color: <?php echo get_theme_mod( 'content_color' ); ?>;
			}
		</style>
	<?php
	}
}


if ( ! function_exists( 'sds_theme_options_ads' ) ) {
	add_action( 'sds_theme_options_ads', 'sds_theme_options_ads' );

	function sds_theme_options_ads() {
		?>
		<div class="sds-theme-options-ad">
			<a href="<?php echo esc_url( sds_get_pro_link( 'theme-options-ad' ) ); ?>" target="_blank" class="sds-theme-options-upgrade-ad">
				<h3><?php _e( 'Upgrade to Journal Pro!', 'journal' ); ?></h3>
				<ul>
					<li><?php _e( 'Priority Ticketing Support', 'journal' ); ?></li>
					<li><?php _e( 'Adjust Theme Colors', 'journal' ); ?></li>
					<li><?php _e( 'Fixed Sidebar', 'journal' ); ?></li>
					<li><?php _e( 'More Web Fonts', 'journal' ); ?></li>
					<li><?php _e( 'Adjust Featured Image Sizes', 'journal' ); ?></li>
					<li><?php _e( 'Easily Add Custom Scripts/Styles', 'journal' ); ?></li>
					<li><?php _e( 'and More!', 'journal' ); ?></li>
				</ul>

				<span class="sds-theme-options-btn-green"><?php _e( 'Upgrade Now!', 'journal' ); ?></span>
			</a>
		</div>
	<?php
	}
}

if ( ! function_exists( 'sds_theme_options_upgrade_cta' ) ) {
	add_action( 'sds_theme_options_upgrade_cta', 'sds_theme_options_upgrade_cta' );

	function sds_theme_options_upgrade_cta( $type ) {
		switch( $type ) :
			case 'web-fonts':
		?>
				<p>
					<?php
						printf( '<a href="%1$s" target="_blank">%2$s</a> %3$s',
							esc_url( sds_get_pro_link( 'theme-options-fonts' ) ),
							__( 'Upgrade to Journal Pro', 'journal' ),
							__( 'to use more web fonts!', 'journal' )
						);
					?>
				</p>
		<?php
			break;
			case 'help-support':
		?>
				<p>
					<?php
						printf( '<a href="%1$s" target="_blank">%2$s</a> %3$s',
							esc_url( sds_get_pro_link( 'theme-options-help' ) ),
							__( 'Upgrade to Journal Pro', 'journal' ),
							__( 'to receive priority ticketing support!', 'journal' )
						);
					?>
				</p>
		<?php
			break;
		endswitch;
	}
}

function sds_get_pro_link( $content ) {
	return esc_url( 'https://slocumthemes.com/wordpress-themes/journal-pro/?utm_source=journal-lite&utm_medium=link&utm_content=' . urlencode( sanitize_title_with_dashes( $content ) ) . '&utm_campaign=pro#purchase-theme' );
}

if ( ! function_exists( 'sds_theme_options_help_support_tab_content' ) ) {
	add_action( 'sds_theme_options_help_support_tab_content', 'sds_theme_options_help_support_tab_content' );

	function sds_theme_options_help_support_tab_content( ) {
		?>
		<p><?php printf( __( 'If you\'d like to create a support request, please visit the <a href="%1$s">Journal Forums on WordPress.org</a>.', 'journal' ), esc_url( 'http://wordpress.org/support/theme/journal/' ) ); ?></p>
	<?php
	}
}

if ( ! function_exists( 'sds_copyright_branding' ) ) {
	add_filter( 'sds_copyright_branding', 'sds_copyright_branding', 10, 2 );

	function sds_copyright_branding( $text, $theme_name ) {
		return sprintf( __( '<a href="%1$s">%2$s by Slocum Studio</a>', 'journal' ), esc_url( 'http://slocumthemes.com/wordpress-themes/journal/' ), $theme_name );
	}
}


/**
 * This function serves as a custom background callback for Journal.
 * It is identical to the default WordPress callback, except for the CSS
 * selector value.
 */
function journal_custom_background_cb() {
	// $background is the saved custom image, or the default image.
	$background = set_url_scheme( get_background_image() );

	// $color is the saved custom color.
	// A default has to be specified in style.css. It will not be printed here.
	$color = get_background_color();

	if ( $color === get_theme_support( 'custom-background', 'default-color' ) ) {
		$color = false;
	}

	if ( ! $background && ! $color )
		return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment;";

		$style .= $image . $repeat . $position . $attachment;
	}
	?>
	<style type="text/css" id="custom-background-css">
		<?php echo apply_filters( 'journal_custom_background_css_selector', 'body.custom-background > section.content' ); ?> { <?php echo trim( $style ); ?> }
	</style>
<?php
}

/**
 * This function determines if an animate.css CSS class should be output and which
 * CSS class to output based on content layout selection.
 */
function journal_animation_class() {
	global $sds_theme_options;

	$animation_class = 'fadeInLeft'; // Default animate.css class

	// Mobile (disable the effect)
	if ( wp_is_mobile() )
		$animation_class = '';
	// Determine if the 2 column layout was chosen
	else if ( isset( $sds_theme_options['body_class'] ) && ! empty( $sds_theme_options['body_class'] ) && $sds_theme_options['body_class'] === 'cols-2' )
		$animation_class = 'fadeInRight';

	return apply_filters( 'journal_animation_class', $animation_class );
}