<?php
/**
 * The plugin creates a page and displays a contact form.
 *
 * This file is read by WordPress to do the following:
 * Perform activation
 * Setup i18n
 * Require all the required classes for the plugin
 * Add action and filter hooks
 *
 * @since             1.0.0
 * @package           ict101s-contact-form
 *
 * @wordpress-plugin
 * Plugin Name: Voila Contact Form
 * Plugin URI: https://itcrackteam.com/101series/wordpress/plugins/ict101s-contact-form
 * Description: Creates "Contact Us" page and inserts a working contact form.
 * Version: 1.0.0
 * Author: Babatope (Ben) Babajide
 * Author URI: https://itcrackteam.com/ben
 * Text Domain: ict101s-contact-form
 * Domain Path: /languages
 * License: GPL3
 *
 * Copyright (c) 2019  Babatope (Ben) Babajide (E-mail: Ben@itcrackteam.com)
 *
 * Voila Contact Form   is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Voila Contact Form   is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Add Contributors. If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 */

/* includes all required classes    */
require_once __DIR__ . '/admin/class-ict101s-contact-form-post-type.php';
require_once __DIR__ . '/public/class-ict101s-contact-form-public.php';
require_once __DIR__ . '/includes/class-contact-form-activator.php';
require_once __DIR__ . '/admin/class-ict101s-contact-form-admin.php';
require_once __DIR__ . '/includes/class-ict101s-contact-form-i18n.php';

/* Setup the custom post type for the contact form */
add_action( 'init', array( '\ict101s\Ict101s_Contact_Form_Post_Type', 'create_ict101s_contact_form_post_type' ) );
\ict101s\Ict101s_Contact_Form_Post_Type::setup();

/* Performs activation activities */
register_activation_hook( __FILE__, [ '\ict101s\Contact_Form_Activator', 'setup' ] );

/* Add form html to page content */
add_filter( 'the_content', [ '\ict101s\Ict101s_Contact_Form_Public', 'create_ict101s_contact_form_html' ] );

/* Setup internationalization */
add_action( 'plugins_loaded', [ '\ict101s\Ict101s_Contact_Form_I18n', 'ict101s_contact_form_load_plugin_textdomain' ] );

/* Loads actions and filters - Admin area. */
add_action( 'admin_menu', [ '\ict101s\Ict101s_Contact_Form_Admin', 'setup_admin_menu' ] );
add_action( 'admin_post_ict101s_cform_settings_form_submit', [ '\ict101s\Ict101s_Contact_Form_Admin', 'ict101s_cform_settings_form_handler' ] );


/* Loads actions and filters - Public area. */
add_action( 'wp_enqueue_scripts', [ '\ict101s\Ict101s_Contact_Form_Public', 'enqueue_plugin_styles' ] );
add_action( 'wp_ajax_nopriv_ict101s_contact_form_submit', [ '\ict101s\Ict101s_Contact_Form_Public', 'ict101s_contact_form_handler' ] );
add_action( 'wp_ajax_ict101s_contact_form_submit', [ '\ict101s\Ict101s_Contact_Form_Public', 'ict101s_contact_form_handler' ] );
