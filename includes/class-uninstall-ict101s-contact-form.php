<?php
/**
 * Plugin file for Voila Contact Form
 *
 * @since    1.0.0
 * @package   ict101s-contact-form\includes
 */

namespace ict101s;

/* Check if delete plugin was initiated */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		die;
}

/**
 * Code to delete all data created by contact from plugin
 *
 * @since 1.0.0
 */
abstract class Uninstall_Ict101s_Contact_Form {
	/**
	 * Code to run all the functions to clean up all plugin data and settings
	 *
	 * @since 1.0.0
	 */
	public static function clean_up() {
		add_action( 'init', array( self::class, 'delete_ict101s_post_type' ) );
		self::delete_ict101s_contact_form_page();
		self::delete_ict101s_contact_form_options();
	}

	/**
	 * Code to delete contact forn message table
	 *
	 * @since 1.0.0
	 */
	private static function delete_ict101s_post_type() {
		unregister_post_type( 'cfmessage' );
	}

	/**
	 * Code to delete contact form page
	 *
	 * @since 1.0.0
	 */
	private static function delete_ict101s_contact_form_page() {
		if ( get_option( 'ict101s_contact_form_op_array' ) ) {
			$cur_ict101s_contact_form_op_array = get_option( 'ict101s_contact_form_op_array' );
						wp_delete_post( $cur_ict101s_contact_form_op_array['ict101s_contact_form_page_id'], true );
		}

	}

	/**
	 * Code to delete contact form otions
	 *
	 * @since 1.0.0
	 */
	private static function delete_ict101s_contact_form_options() {
		if ( get_option( 'ict101s_contact_form_op_array' ) !== false ) {
				delete_option( 'ict101s_contact_form_op_array' );
		}
	}
}
