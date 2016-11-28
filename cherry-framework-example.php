<?php
/**
 * Example using Cherry Framework V.
 *
 * `your_prefix` - unique prefix for your theme.
 * `text-domain` - unique identifier for retrieving translated strings.
 *
 * @package    Cherry_Framework_Example
 * @author     Cherry Team <cherryframework@gmail.com>
 * @copyright  Copyright (c) 2012 - 2016, Cherry Team
 * @link       http://www.cherryframework.com/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 */

// Path to Cherry Framework core installer.
$setup_file = get_template_directory() . '/cherry-framework/setup.php';

if ( ! file_exists( $setup_file ) ) {
	return;
}

// Load Cherry Framework core - hooks and their priorities listed below are very important.
add_action( 'after_setup_theme', require( $setup_file ),          0 );
add_action( 'after_setup_theme', 'your_prefix_get_core',          1 );
add_action( 'after_setup_theme', 'Cherry_Core::load_all_modules', 2 );

// Load the simple widget.
add_action( 'after_setup_theme', 'your_prefix_include_widget',    9 );

// Modules Initialization.
add_action( 'after_setup_theme', 'your_prefix_init_modules',     10 );

/**
 * Core functions loading.
 *
 * These files are required before loading anything else in the
 * theme.
 */
function your_prefix_get_core() {
	global $chery_core_version;
	static $core = null;

	if ( null !== $core ) {
		return $core;
	}

	if ( 0 < sizeof( $chery_core_version ) ) {
		$core_paths = array_values( $chery_core_version );
		require_once( $core_paths[0] );

	} else {
		die( 'Class Cherry_Core not found' );
	}

	$core = new Cherry_Core( array(
		'base_dir' => get_template_directory() . '/cherry-framework',
		'base_url' => get_template_directory_uri() . '/cherry-framework',
		'modules'  => array(
			'cherry-breadcrumbs' => array(
				'autoload' => false,
			),
			'cherry-term-meta' => array(
				'autoload' => false,
			),
			'cherry-post-meta' => array(
				'autoload' => false,
			),
			'cherry-interface-builder' => array(
				'autoload' => false,
			),
			'cherry-customizer' => array(
				'autoload' => false,
			),
			'cherry-dynamic-css' => array(
				'autoload' => false,
			),
			'cherry-google-fonts-loader' => array(
				'autoload' => false,
			),
			'cherry-widget-factory' => array(
				'autoload' => false,
			),
			'cherry-js-core' => array(
				'autoload' => true,
			),
			'cherry-ui-elements' => array(
				'autoload' => false,
			),
		),
	) );

	return $core;
}

/**
 * Widget Load.
 *
 * If a feature is used in Cherry Framework functionality, it is required after core initialization.
 */
function your_prefix_include_widget() {
	require get_template_directory() . '/cherry-framework-example/inc/class-simple-widget.php';
}

/**
 * Modules Initialization.
 */
function your_prefix_init_modules() {
	/**
	 * Init `cherry-post-meta` - module to manage post metadata.
	 *
	 * How to use?
	 *
	 * In functions.php paste this simple code:
	 *
	 *     add_filter( 'body_class', 'your_prefix_add_layout_class' );
	 *     function your_prefix_add_layout_class( $classes ) {
	 *         $sidebar_position = get_post_meta( get_the_ID(), 'your_prefix_sidebar_position', true );
	 *
	 *         if ( ! empty( $sidebar_position ) ) {
	 *             $classes[] = 'layout--' . esc_attr( $sidebar_position );
	 *         }
	 *
	 *         return $classes;
	 *     }
	 *
	 * After this you have a CSS-class in `<body>` tag for controlling site layout:
	 *     layout--content-sidebar
	 *     layout--sidebar-content
	 *     layout--inherit
	 */
	your_prefix_get_core()->init_module( 'cherry-post-meta', array(
		'id'       => 'your_prefix-layout',
		'title'    => esc_html__( 'Layout Options', 'text-domain' ),
		'page'     => array( 'post', 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			'your_prefix_sidebar_position' => array(
				'type'    => 'select',
				'title'   => esc_html__( 'Layout', 'text-domain' ),
				'value'   => 'inherit',
				'options' => array(
					'inherit'         => esc_html__( 'Inherit', 'text-domain' ),
					'content-sidebar' => esc_html__( 'Sidebar on right side', 'text-domain' ),
					'sidebar-content' => esc_html__( 'Sidebar on left side', 'text-domain' ),
				),
			),
		),
	) );

	/**
	 * Init `cherry-term-meta` - module to manage terms metadata.
	 *
	 * How to use?
	 *
	 * In template (e.g. archive.php) paste this simple code:
	 *
	 *     $thumbnail_id = get_term_meta( get_queried_object_id(), 'your_prefix_term_thumbnail', true );
	 *
	 *     if ( ! empty( $thumbnail_id ) ) {
	 *         echo wp_get_attachment_image( $thumbnail_id );
	 *     }
	 */
	your_prefix_get_core()->init_module( 'cherry-term-meta', array(
		'tax'      => 'category',
		'priority' => 10,
		'fields'   => array(
			'your_prefix_term_thumbnail' => array(
				'type'               => 'media',
				'multi_upload'       => false,
				'library_type'       => 'image',
				'upload_button_text' => esc_html__( 'Set thumbnail', 'text-domain' ),
			),
		),
	) );

	/**
	 * Init `cherry-customizer` - simple wrapper for Customizer API.
	 *
	 * How to use? - Example below
	 */
	your_prefix_get_core()->init_module( 'cherry-customizer', array(
		'prefix'     => 'your_prefix',
		'capability' => 'edit_theme_options',
		'type'       => 'theme_mod',
		'options'    => array(

			/* Breadcrumbs panel */
			'breadcrumbs' => array(
				'title'    => esc_html__( 'Breadcrumbs', 'text-domain' ),
				'priority' => 30,
				'type'     => 'panel',
			),

			/* General section */
			'breadcrumbs_general' => array(
				'title'    => esc_html__( 'General', 'text-domain' ),
				'priority' => 1,
				'panel'    => 'breadcrumbs',
				'type'     => 'section',
			),
			'breadcrumbs_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs', 'text-domain' ),
				'section' => 'breadcrumbs_general',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_front_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs on front page', 'text-domain' ),
				'section' => 'breadcrumbs_general',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_browse_label' => array(
				'title'   => esc_html__( 'Browse label', 'text-domain' ),
				'section' => 'breadcrumbs_general',
				'default' => esc_html__( 'Browse:', 'text-domain' ),
				'field'   => 'text',
				'type'    => 'control',
			),
			'breadcrumbs_page_title' => array(
				'title'   => esc_html__( 'Enable page title in breadcrumbs area', 'text-domain' ),
				'section' => 'breadcrumbs_general',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_path_type' => array(
				'title'   => esc_html__( 'Show full/minified path', 'text-domain' ),
				'section' => 'breadcrumbs_general',
				'default' => 'full',
				'field'   => 'select',
				'choices' => array(
					'full'     => esc_html__( 'Full', 'text-domain' ),
					'minified' => esc_html__( 'Minified', 'text-domain' ),
				),
				'type' => 'control',
			),

			/* Typography section */
			'breadcrumbs_typography' => array(
				'title'    => esc_html__( 'Typography', 'text-domain' ),
				'priority' => 2,
				'panel'    => 'breadcrumbs',
				'type'     => 'section',
			),
			'breadcrumbs_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'text-domain' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'breadcrumbs_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'text-domain' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => array(
					'normal'  => esc_html__( 'Normal', 'text-domain' ),
					'italic'  => esc_html__( 'Italic', 'text-domain' ),
					'oblique' => esc_html__( 'Oblique', 'text-domain' ),
					'inherit' => esc_html__( 'Inherit', 'text-domain' ),
				),
				'type' => 'control',
			),
			'breadcrumbs_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'text-domain' ),
				'section' => 'breadcrumbs_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => array(
					'100' => '100',
					'200' => '200',
					'300' => '300',
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
					'800' => '800',
					'900' => '900',
				),
				'type' => 'control',
			),
			'breadcrumbs_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'text-domain' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '14',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'breadcrumbs_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'text-domain' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'text-domain' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '1.5',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),

			/* Colors section */
			'breadcrumbs_colors' => array(
				'title'    => esc_html__( 'Colors', 'text-domain' ),
				'priority' => 3,
				'panel'    => 'breadcrumbs',
				'type'     => 'section',
			),
			'breadcrumbs_bg_color' => array(
				'title'   => esc_html__( 'Background color', 'text-domain' ),
				'section' => 'breadcrumbs_colors',
				'default' => 'transparent',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'breadcrumbs_text_color' => array(
				'title'   => esc_html__( 'Text Color', 'text-domain' ),
				'section' => 'breadcrumbs_colors',
				'default' => 'inherit',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'breadcrumbs_link_color' => array(
				'title'   => esc_html__( 'Link Color', 'text-domain' ),
				'section' => 'breadcrumbs_colors',
				'default' => 'inherit',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
		),
	) );

	/**
	 * Init `cherry-dynamic-css` - CSS-parser which uses variables & functions for CSS code optimization.
	 *
	 * How to use? - Example below
	 */
	your_prefix_get_core()->init_module( 'cherry-dynamic-css', array(
		'type'      => 'theme_mod',
		'single'    => true,
		'css_files' => array(
			get_template_directory() . '/cherry-framework-example/css/dynamic.css', // You may put this file in your theme's CSS directory.
		),

		// These are control keys from `cherry-customizer` module.
		'options' => array(
			'breadcrumbs_font_style',
			'breadcrumbs_font_weight',
			'breadcrumbs_font_size',
			'breadcrumbs_line_height',
			'breadcrumbs_font_family',
			'breadcrumbs_bg_color',
			'breadcrumbs_text_color',
			'breadcrumbs_link_color',
		),
	) );

	/**
	 * Init `cherry-google-fonts-loader` - enqueue Google Web fonts.
	 *
	 * How to use? - Example below
	 */
	your_prefix_get_core()->init_module( 'cherry-google-fonts-loader', array(
		'type'    => 'theme_mod',
		'single'  => true,
		'options' => array(
			'breadcrumbs' => array(
				'family' => 'breadcrumbs_font_family',
				'style'  => 'breadcrumbs_font_style',
				'weight' => 'breadcrumbs_font_weight',
			),
		),
	) );
}

/**
 * Callback-function to display breadcrumbs.
 *
 * You'll have to add it manually in your template file (recommended in header.php).
 */
function your_prefix_site_breadcrumbs() {
	$customizer  = your_prefix_get_core()->modules['cherry-customizer'];
	$visibillity = get_theme_mod( 'breadcrumbs_visibillity', $customizer->get_default( 'breadcrumbs_visibillity' ) );

	if ( ! $visibillity ) {
		return;
	}

	$browse_label  = get_theme_mod( 'breadcrumbs_browse_label', $customizer->get_default( 'breadcrumbs_browse_label' ) );
	$show_title    = get_theme_mod( 'breadcrumbs_page_title', $customizer->get_default( 'breadcrumbs_page_title' ) );
	$path_type     = get_theme_mod( 'breadcrumbs_path_type', $customizer->get_default( 'breadcrumbs_path_type' ) );
	$show_on_front = get_theme_mod( 'breadcrumbs_front_visibillity', $customizer->get_default( 'breadcrumbs_front_visibillity' ) );

	your_prefix_get_core()->init_module( 'cherry-breadcrumbs', array(
		'wrapper_format' => '<div class="breadcrumbs__title">%1$s</div><div class="breadcrumbs__items">%2$s</div>',
		'show_title'     => $show_title,
		'path_type'      => $path_type,
		'show_on_front'  => $show_on_front,
		'action'         => 'your_prefix_breadcrumbs_render',
		'labels'         => array(
			'browse' => $browse_label,
		),
		'css_namespace' => array(
			'module'    => 'breadcrumbs',
			'content'   => 'breadcrumbs__content',
			'wrap'      => 'breadcrumbs__wrap',
			'browse'    => 'breadcrumbs__browse',
			'item'      => 'breadcrumbs__item',
			'separator' => 'breadcrumbs__item-sep',
			'link'      => 'breadcrumbs__item-link',
			'target'    => 'breadcrumbs__item-target',
		),
	) );

	// Let's show breadcrumbs in your site!
	do_action( 'your_prefix_breadcrumbs_render' );
}
