<?php
/**
 * Plugin file for Voila Contact Form
 *
 * @since    1.0.0
 * @package   ict101s-contact-form\includes
 */

namespace ict101s;

/**
 * Code to perform setup activities during activation
 *
 * @since 1.0.0
 */
abstract class Contact_Form_Activator {
	/**
	 * Code to run all the functions to perform activation checks and settings
	 *
	 * @since 1.0.0
	 */
	public static function setup() {
		self::check_wordpress_version();
		if ( get_option( 'ict101s_contact_form_op_array' ) === false ) {
			self::set_ict101s_contact_form_options();
			self::create_ict101s_contact_form_page();
		}
	}

	/**
	 * Code to check WordPress version
	 *
	 * @since 1.0.0
	 */
	private static function check_wordpress_version() {
		if ( version_compare( get_bloginfo( 'version' ), '5.2.2', '<' ) ) {
			wp_die(
				esc_html__( 'You must update WordPress to use this plugin.', 'ict101s-contact-form' ),
				'Activation Error',
				array( 'back_link' => true )
			);
		}
	}

	/**
	 * Code to create a page for the basic form
	 *
	 * @since 1.0.0
	 */
	private static function create_ict101s_contact_form_page() {
		$page_guid                    = site_url() . '/contact-us';
				$my_post              = array(
					'post_title'     => __( 'Contact Us', 'ict101s-contact-form' ),
					'post_type'      => 'page',
					'post_content'   => '',
					'post_status'    => 'publish',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'post_author'    => 1,
					'menu_order'     => 0,
					'guid'           => $page_guid,
				);
				$contact_form_page_id = wp_insert_post( $my_post, true );
				if ( is_wp_error( $contact_form_page_id ) ) {
					wp_die(
						esc_html__( 'Error creating a page for contact form!', 'ict101s-contact-form' ),
						'Activation  Error',
						array( 'back_link' => true )
					);
				} else {
					$cur_ict101s_contact_form_op_array                                 = get_option( 'ict101s_contact_form_op_array' );
					$cur_ict101s_contact_form_op_array['ict101s_contact_form_page_id'] = $contact_form_page_id;
					update_option( 'ict101s_contact_form_op_array', $cur_ict101s_contact_form_op_array );
				}
	}


	/**
	 * Code to set options for the basic form
	 *
	 * @since 1.0.0
	 */
	private static function set_ict101s_contact_form_options() {
				$options_array['ict101s_contact_form_version'] = '1';
				$options_array['email_form_message']           = 'no';
				$options_array['save_form_message']            = 'no';
				$options_array['save_and_email_form_message']  = 'yes';
				$options_array['ict101s_contact_form_page_id'] = '';
		if ( ! add_option( 'ict101s_contact_form_op_array', $options_array ) ) {
			wp_die(
				esc_html__( 'Error setting contact form options!', 'ict101s-contact-form' ),
				'Activation  Error',
				array( 'back_link' => true )
			);
		}
	}
}
