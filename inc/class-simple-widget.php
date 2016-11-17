<?php
/*
 * Widget Name: Simple Widget
 * Description: This is simple widget based on Cherry Framework
*/

if ( ! class_exists( '_s_simple_widget' ) ) {

	class _s_simple_widget extends Cherry_Abstract_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->widget_cssclass    = 'widget-simple';
			$this->widget_description = esc_html__( 'This is simple widget based on Cherry Framework.', '_s' );
			$this->widget_id          = '_s_widget_simple';
			$this->widget_name        = esc_html__( '_s: Simple Widget', '_s' );
			$this->settings           = array(
				'title'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Title:', '_s' ),
				),
				'media_id' => array(
					'type'               => 'media',
					'multi_upload'       => false,
					'library_type'       => 'image',
					'upload_button_text' => esc_html__( 'Upload', '_s' ),
					'value'              => '',
					'label'              => esc_html__( 'Logo:', '_s' ),
				),
				'content'  => array(
					'type'              => 'textarea',
					'placeholder'       => esc_html__( 'Text or HTML', '_s' ),
					'value'             => '',
					'label'             => esc_html__( 'Content:', '_s' ),
					'sanitize_callback' => 'wp_kses_post',
				),
			);

			parent::__construct();
		}

		/**
		 * Widget function.
		 *
		 * @see   WP_Widget
		 * @since 1.0.0
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			$_instance = array_filter( $instance );

			if ( empty( $_instance ) ) {
				return;
			}

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$title    = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$media_id = absint( $instance['media_id'] );
			$src      = wp_get_attachment_image_src( $media_id, 'medium' );

			if ( false !== $src ) {
				printf( '<figure class="widget-simple__thumbnail"><img class="widget-simple__thumbnail-img" src="%s" alt=""></figure>', esc_url( $src[0] ) );
			}

			$content = $this->use_wpml_translate( 'content' );
			$content = apply_filters( 'widget_text', $content, $instance, $this );

			if ( ! empty( $content ) ) {
				printf( '<div class="widget-simple__content">%s</div>', $content );
			}

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}
	}

	add_action( 'widgets_init', '_s_register_simple_widget' );
	function _s_register_simple_widget() {
		register_widget( '_s_simple_widget' );
	}
}
