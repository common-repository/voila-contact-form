<?php
/**
 * Plugin file for Voila Contact Form
 *
 * @since    1.0.0
 * @package   ict101s-contact-form\includes
 */

namespace ict101s;

/**
 * Code to setup i18n
 *
 * @since 1.0.0
 */
abstract class Ict101s_Contact_Form_I18n {
		/**
		 * Code to load plugin text domain
		 *
		 * @since 1.0.0
		 */
	public static function ict101s_contact_form_load_plugin_textdomain() {
		load_plugin_textdomain( 'ict101s-contact-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}
