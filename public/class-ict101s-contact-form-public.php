<?php
/**
 * Voila Contact Form plugin file
 *
 * @since             1.0.0
 * @package           ict101s-contact-form\public
 */

namespace ict101s;

/**
 * Main class for the public portion of the contact form plugin
 *
 * This code creats the HTML for the contact form, function to process submission and also enqueques the css
 * to format the form
 *
 * @since             1.0.0
 */
abstract class Ict101s_Contact_Form_Public {
	/**
	 * Creates HTML code for the form
	 *
	 * @param str $content   Holds the content of the contact form page.
	 * @since 1.0.0
	 * */
	public static function create_ict101s_contact_form_html( $content ) {
		global $post;
		ob_start();
		// Retrieve status and create status message.
			$status = filter_input( INPUT_GET, 'status', FILTER_VALIDATE_INT );

		if ( 1 === $status ) {
			printf( '<div class="success message"><p>%s</p></div>', esc_html__( 'Your message was submitted successfully.', 'ict101s-contact-form' ) );
		}
		?>
		<form action = <?php echo "'" . esc_url( admin_url( 'admin-ajax.php' ) ) . "'"; ?>  method = "post"> 
			<?php wp_nonce_field( basename( __FILE__ ), 'ict_contact_form_nonce' ); ?>
			<input type="hidden" name="post_redirect_id" value=<?php echo esc_attr( get_the_ID() ); ?> >
			<input type="hidden" name="action" value="ict101s_contact_form_submit">
			<?php esc_html_e( 'First name:', 'ict101s-contact-form' ); ?> <br>
			<input class="input" type="text" name="sender_first_name" placeholder="Enter your first name">
			<br>
			<?php esc_html_e( 'Last name:', 'ict101s-contact-form' ); ?><br>
			<input class="input" type="text" name="sender_last_name" placeholder="Enter your last name"><br>
			<?php esc_html_e( 'Email:', 'ict101s-contact-form' ); ?> <br>
			<input class="input" type="text" name="sender_email" placeholder="Enter your email address"><br>
			<?php esc_html_e( 'Phone:', 'ict101s-contact-form' ); ?> <br>
			<input class="input" type="text" name="sender_phone" placeholder="Enter your phone number"><br>
			<?php esc_html_e( 'Message:', 'ict101s-contact-form' ); ?> <br>
			<textarea rows="6" column="6" maxlength="500" Class="input" name="message" placeholder="Your message goes here . . ."></textarea>
			<br><br>
			<input type="submit" name="submit" value=<?php esc_attr_e( 'Submit', 'ict101s-contact-form' ); ?>><br>
		</form>	
		<?php
		$form               = ob_get_clean();
		$content_with_form  = $content . $form;
		$cur_cform_op_array = get_option( 'ict101s_contact_form_op_array' );
		if ( is_page( $cur_cform_op_array['ict101s_contact_form_page_id'] ) ) {
						return $content_with_form;
		} else {
				return $content;
		}
	}

	/**
	 * Code to handle the data submitted via contact form
	 *
	 * @since 1.0.0
	 * */
	public static function ict101s_contact_form_handler() {

		/* Verify the nonce before proceeding. */
		if ( ! isset( $_POST['ict_contact_form_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ict_contact_form_nonce'] ) ), basename( __FILE__ ) ) ) {
				wp_die( esc_html__( 'Security check failed!', 'ict101s_contact_form' ) );
		}

		$post = $_POST;

		// Verify required fileds and sanitize data.

		foreach ( $post as $key => $value ) {
			if ( empty( $post[ $key ] ) ) {
				wp_die( esc_html__( 'The First Name, Last Name, Phone, email and message fields are required.', 'ict101s-contact-form' ) );
			}
			$post[ $key ] = sanitize_text_field( $post[ $key ] );
		}

		// Retrieve options for contact form.
		$cur_ict101s_contact_form_op_array = get_option( 'ict101s_contact_form_op_array' );

		// determine how to handle the message according to the saved options.
		if ( 'yes' === $cur_ict101s_contact_form_op_array['email_form_message'] ) {
			self::ict101s_contact_form_send_email( $post );
			wp_safe_redirect( add_query_arg( 'status', '1', get_permalink( $post['post_redirect_id'] ) ) );
			exit();
		}
		if ( 'yes' === $cur_ict101s_contact_form_op_array['save_form_message'] ) {
			self::ict101s_contact_form_save_message( $post );
			wp_safe_redirect( add_query_arg( 'status', '1', get_permalink( $post['post_redirect_id'] ) ) );
			exit();
		}
		if ( 'yes' === $cur_ict101s_contact_form_op_array['save_and_email_form_message'] ) {
			self::ict101s_contact_form_save_message( $post );
			self::ict101s_contact_form_send_email( $post );
			wp_safe_redirect( add_query_arg( 'status', '1', get_permalink( $post['post_redirect_id'] ) ) );
			exit();
		}

	}

	/**
	 * Code to email message to admin
	 *
	 * @param array $post    Array of sent data from the contact form.
	 * @since 1.0.0
	 * */
	private static function ict101s_contact_form_save_message( $post ) {
		// Build post arguments.
		$postarr = array(
			'post_author'  => 1,
			'post_title'   => 'Message from ' . $post['sender_first_name'] . ' ' . $post['sender_last_name'],
			'post_content' => $post['message'],
			'post_type'    => 'cfmessage',
			'post_status'  => 'publish',
			'meta_input'   => array(
				'sender_first_name' => $post['sender_first_name'],
				'sender_last_name'  => $post['sender_last_name'],
				'sender_email'      => $post['sender_email'],
				'sender_phone'      => $post['sender_phone'],
				'message'           => $post['message'],
			),

		);
		// Insert the post.
		$post_id = wp_insert_post( $postarr, true );

		if ( is_wp_error( $post_id, true ) ) {
			wp_die(
				esc_html__( 'There was a problem sending your message. Please try again.', 'ict101s_contact-form' ),
				esc_html__( 'Message Submission Error', 'ict101s-contact-form' ),
				array( 'back_link' => true )
			);
		}
	}

	/**
	 * Code to email sent message to admin
	 *
	 * @param array $post    Array of sent data from the contact form.
	 * @since 1.0.0
	 * */
	private static function ict101s_contact_form_send_email( $post ) {

		$to      = get_option( 'admin_email' );
		$subject = 'Message from your blog';
		$body    = sprintf( '<p>%s: %s<br>', __( 'First Name', 'ict101s-contact-form' ), ( $post['sender_first_name'] ) );
		$body   .= sprintf( '<p>%s: %s<br>', __( 'Last  Name', 'ict101s-contact-form' ), ( $post['sender_last_name'] ) );
		$body   .= sprintf( '<p>%s: %s<br>', __( 'Email', 'ict101s-contact-form' ), ( $post['sender_email'] ) );
		$body   .= sprintf( '<p>%s: %s<br>', __( 'Phone', 'ict101s-contact-form' ), ( $post['sender_phone'] ) );
		$body   .= sprintf( '<p>%s: %s<br>', __( 'Message', 'ict101s-contact-form' ), ( $post['message'] ) );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		if ( ! wp_mail( $to, $subject, $body, $headers ) ) {
						wp_die(
							esc_html__( 'There was a problem seding your message. Please try again.', 'ict101s_contact-form' ),
							esc_html__( 'Message Submission Error', 'ict101s_contact-form' ),
							array( 'back_link' => true )
						);
		}
	}

	/**
	 * Registers and enqueues css styles for the public view
	 */
	public static function enqueue_plugin_styles() {
		wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ) . 'css/public-style.css', array(), '1.0' );
	}
}
