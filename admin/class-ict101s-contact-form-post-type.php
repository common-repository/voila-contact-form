<?php
/**
 * Voila Contact Form plugin file
 *
 * @since             1.0.0
 * @package           ict101s-contact-form\admin
 */

namespace ict101s;

/**
 * Class used to build the custom post type for the contact form
 *
 * This code creates custom post type, adds custom columns and retrieves
 * the contents of the columns for the contact form,
 *
 * @since             1.0.0
 */
abstract class Ict101s_Contact_Form_Post_Type {
	/**
	 * Code to setup custom post type for the contact form
	 *
	 * @since 1.0.0
	 */
	public static function setup() {
		add_filter( 'manage_cfmessage_posts_columns', array( self::class, 'set_ict101s_contact_form_edit_columns' ) );
		add_action( 'manage_cfmessage_posts_custom_column', array( self::class, 'get_ict101s_contact_form_columns_content' ), 10, 2 );
	}

	/**
	 * Code to create contact form custom post type
	 *
	 * @since 1.0.0
	 */
	public static function create_ict101s_contact_form_post_type() {
			register_post_type(
				'cfmessage',
				array(
					'labels'            => array(
						'name'          => __( 'Voila Contact Form Messages', 'ict101s-contact-form' ),
						'singular_name' => __( 'Voila Contact Form Messages', 'ict101s-contact-form' ),
					),
					'public'            => false,
					'has_archive'       => false,
					'show_ui'           => true,
					'show_in_nav_menus' => false,
					'menu_position'     => 25,
					'menu_icon'         => 'dashicons-testimonial',
					'supports'          => array( 'title', 'editor', 'custom-fields' ),
				)
			);
	}
	/**
	 * Code to create Voila Contact Form   custom post type columns
	 *
	 * @param array $columns    Array of default columns.
	 * @since 1.0.0
	 */
	public static function set_ict101s_contact_form_edit_columns( $columns ) {
		unset( $columns['date'] );
				$columns['sender_first_name'] = __( 'First Name', 'ict101s-contact-form' );
				$columns['sender_last_name']  = __( 'Last Name', 'ict101s-contact-form' );
				$columns['sender_email']      = __( 'Email', 'ict101s-contact-form' );
				$columns['sender_phone']      = __( 'Phone', 'ict101s-contact-form' );
				$columns['date']              = __( 'Date', 'ict101s-contact-form' );
				$columns['message']           = __( 'Message', 'ict101s-contact-form' );
				return $columns;
	}

		/**
		 * Code to retrieve Voila Contact Form   columns' contents
		 *
		 * @param array $column    Array of default columns.
		 * @param int   $post_id    Id of the current post.
		 * @since 1.0.0
		 */
	public static function get_ict101s_contact_form_columns_content( $column, $post_id ) {
		if ( 'sender_first_name' === $column ) {
			echo esc_html( get_post_meta( $post_id, 'sender_first_name', true ) );
		}
		if ( 'sender_last_name' === $column ) {
			echo esc_html( get_post_meta( $post_id, 'sender_last_name', true ) );
		}
		if ( 'sender_email' === $column ) {
			$sender_email = get_post_meta( $post_id, 'sender_email', true );
			if ( ! empty( $sender_email ) ) {
					printf( '<a href="mailto:%s">%s</a>', esc_attr( $sender_email ), esc_html( $sender_email ) );
			}
		}
		if ( 'sender_phone' === $column ) {
			echo esc_html( get_post_meta( $post_id, 'sender_phone', true ) );
		}
		if ( 'date' === $column ) {
			echo esc_html( get_post_meta( $post_id, 'date', true ) );
		}
		if ( 'message' === $column ) {
			echo esc_html( get_the_excerpt( $post_id ) );
		}
	}
}
