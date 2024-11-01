<?php
/**
 * Voila Contact Form Plugin file
 *
 * @since    1.0.0
 * @package   ict101s-contact-form\includes
 */

/* Checks if delete plugin was initiated */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

/* Includes the required class    */
require_once __DIR__ . '/includes/class-uninstall-ict101s-contact-form.php';

/* Fires function for cleanup of plugin data and settings */
\ict101s\Uninstall_Ict101s_Contact_Form::clean_up();
