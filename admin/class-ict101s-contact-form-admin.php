<?php
/**
 * Voila Contact Form plugin file
 *
 * @since             1.0.0
 * @package           ict101s-contact-form\admin
 */

namespace ict101s;

/**
 * Class used to create HTML for the contact form settings page and
 * process any selection
 *
 * This code creates HTML for "settings" page and the function to process the selected
 * options
 *
 * @since             1.0.0
 */
abstract class Ict101s_Contact_Form_Admin {
	/**
	 * Code to create admin maenu for contact form plugin
	 *
	 * @since 1.0.0
	 */
	public static function setup_admin_menu() {
		add_menu_page( __( 'Voila Contact Form settings', 'ict101s-contact-form' ), __( 'Voila Contact Form Settings', 'ict101s-contact-form' ), 'manage_options', 'ict101s-contact-form/admin/ict101s-contact-form-admin.php', [ self::class, 'create_ict101s_contact_form_settings_page_html' ] );
	}

	/**
	 * Code to create HTML for the contact form's settings page
	 *
	 * @since 1.0.0
	 */
	public static function create_ict101s_contact_form_settings_page_html() {
		?>
		<h1> <?php esc_html_e( 'Basic Settings', 'ict101s-contact-form' ); ?> </h1>
		<?php
		// Status message.
					$status = filter_input( INPUT_GET, 'status', FILTER_VALIDATE_INT );

		if ( 1 === $status ) {
			self::ict101s_contact_form_update_notice();
		}
			$settings_page_slug = filter_input( INPUT_GET, 'page' );
		?>
			<p><?php esc_html_e( 'Select an option below to let Voila Contact Form know how to handle your messages.', 'ict101s-contact-form' ); ?></p> 

		<form method = "post"  action = "admin-post.php" >
		<?php wp_nonce_field( basename( __FILE__ ), 'ict101s_settings_form_nonce' ); ?>	
		<?php
		$cur_ict101s_contact_form_op_array = get_option( 'ict101s_contact_form_op_array' );
		?>
			<input type="hidden" name="redirect_id" value=<?php echo esc_attr( $settings_page_slug ); ?>>
			<input type="hidden" name="action" value="ict101s_cform_settings_form_submit">
			<p><input type="radio" name="settings_option" value="save_and_email_form_message" 
			<?php
			if ( 'yes' === $cur_ict101s_contact_form_op_array['save_and_email_form_message'] ) {
				echo 'checked';}
			?>
			/><?php esc_html_e( 'Save and email messages', 'ict101s-contact-form' ); ?></p>
			<p><input type="radio" name="settings_option" value="email_form_message" 
			<?php
			if ( 'yes' === $cur_ict101s_contact_form_op_array['email_form_message'] ) {
				echo 'checked';}
			?>
			/><?php esc_html_e( 'Email messages', 'ict101s-contact-form' ); ?></p> 
			<p><input type="radio" name="settings_option" value="save_form_message" 
			<?php
			if ( 'yes' === $cur_ict101s_contact_form_op_array['save_form_message'] ) {
				echo 'checked';}
			?>
			/><?php esc_html_e( 'Save messages', 'ict101s-contact-form' ); ?></p>
			<br>
			<input type="submit" name="submit" value=<?php esc_attr_e( 'Submit', 'ict101s-contact-form' ); ?> class="button-primary" />

			</form>
		</div>

		<?php
	}

	/**
	 * Code to process contact form settings form
	 *
	 * @since 1.0.0
	 */
	public static function ict101s_cform_settings_form_handler() {

		/* Verify the nonce before proceeding. */
		if ( ! isset( $_POST['ict101s_settings_form_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ict101s_settings_form_nonce'] ) ), basename( __FILE__ ) ) ) {
				wp_die( esc_html__( 'Security check failed!', 'ict101s-contact-form' ) );
		}

		$post = $_POST;
		if ( ! empty( $_POST['settings_option'] ) ) {
			$new_selection = sanitize_text_field( wp_unslash( $_POST['settings_option'] ) );
		} else {
			$new_selection = 'save_form_data';
		}

		$cur_ict101s_contact_form_op_array        = get_option( 'ict101s_contact_form_op_array' );
		$selection                                = [];
		$selection['email_form_message']          = $cur_ict101s_contact_form_op_array['email_form_message'];
		$selection['save_form_message']           = $cur_ict101s_contact_form_op_array['save_form_message'];
		$selection['save_and_email_form_message'] = $cur_ict101s_contact_form_op_array['save_and_email_form_message'];
		foreach ( $selection as $key => $value ) {

			if ( $new_selection === $key ) {
				$cur_ict101s_contact_form_op_array[ $key ] = 'yes';
			} else {
				$cur_ict101s_contact_form_op_array[ $key ] = 'no';
			}
		}
		update_option( 'ict101s_contact_form_op_array', $cur_ict101s_contact_form_op_array );
		wp_safe_redirect(
			add_query_arg(
				array(
					'status' => '1',
					'page'   => $post['redirect_id'],
				),
				admin_url( 'admin.php' )
			)
		);
		exit();
	}
	/**
	 * Code to generate success message
	 *
	 * @since 1.0.0
	 */
	private static function ict101s_contact_form_update_notice() {
		?>
				<div class="updated notice is-dismissible">
						<p><?php esc_html_e( 'Option updated, excellent!', 'ict101s-contact-form' ); ?></p>
				</div>
				<?php
	}
}
