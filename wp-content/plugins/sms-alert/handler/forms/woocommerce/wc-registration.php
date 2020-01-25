<?php
	class WooCommerceRegistrationForm extends FormInterface
	{
		private $formSessionVar = FormSessionVars::WC_DEFAULT_REG;
		private $otpType;
		private $generateUserName;
		private $generatePassword;
		private $redirectToPage;

		function handleForm()
		{
			$this->otpType = get_option('mo_customer_validation_wc_enable_type');
			$this->generateUserName = get_option( 'woocommerce_registration_generate_username' );
			$this->generatePassword = get_option( 'woocommerce_registration_generate_password' );  
			$this->redirectToPage = get_option('mo_customer_validation_wc_redirect'); 

			add_filter('woocommerce_process_registration_errors', array($this,'woocommerce_site_registration_errors'),99,4);
			add_action( 'woocommerce_register_form', array($this,'smsalert_add_phone_field') );
			add_action( 'woocommerce_created_customer', array( $this, 'wc_user_created' ), 10, 2 );
		}
		
		public static function isFormEnabled()
		{
			return (smsalert_get_option('buyer_signup_otp', 'smsalert_general')=="on") ? true : false;
		}
		
		//this function created for updating and create a hook created on 29-01-2019
		public function wc_user_created($user_id, $data)
		{
			$post_data = wp_unslash( $_POST );
			
			if(array_key_exists('billing_phone', $post_data))
			{
				$billing_phone = $post_data['billing_phone'];
				update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $billing_phone ) );
				do_action('smsalert_after_update_new_user_phone',$user_id,$billing_phone);
			}
		}
		
		function woocommerce_site_registration_errors($errors,$username,$password,$email)
		{
			if(isset($_SESSION['sa_mobile_verified']))
					return $errors;
			
			SmsAlertUtility::checkSession();
			if(!SmsAlertUtility::isBlank(array_filter($errors->errors))) return $errors; 

			SmsAlertUtility::initialize_transaction($this->formSessionVar);
			if( $this->generateUserName==='no' )
			{
				if (  SmsAlertUtility::isBlank( $username ) || ! validate_username( $username ) )
					return new WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'woocommerce' ) );
				if ( username_exists( $username ) )
					return new WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'woocommerce' ) );
			}

			if( $this->generatePassword==='no' )
			{
				if (  SmsAlertUtility::isBlank( $password ) )
					return new WP_Error( 'registration-error-invalid-password', __( 'Please enter a valid account password.', 'woocommerce' ) );
			}

			if ( SmsAlertUtility::isBlank( $email ) || ! is_email( $email ) )
				return new WP_Error( 'registration-error-invalid-email', __( 'Please enter a valid email address.', 'woocommerce' ) );
			if ( email_exists( $email ) )
				return new WP_Error( 'registration-error-email-exists', __( 'An account is already registered with your email address. Please login.', 'woocommerce' ) );
			if(smsalert_get_option('allow_multiple_user', 'smsalert_general')!="on") {
				if( sizeof(get_users(array('meta_key' => 'billing_phone', 'meta_value' => $_POST['billing_phone']))) > 0 ) {
					return new WP_Error( 'registration-error-number-exists', __( 'An account is already registered with this mobile number. Please login.', 'woocommerce' ) );
				}
			}

			do_action( 'woocommerce_register_post', $username, $email, $errors );
			if($errors->get_error_code())
				throw new Exception( $errors->get_error_message() );

			//process and start the OTP verification process
			return $this->processFormFields($username,$email,$errors,$password); 		
		}

		function processFormFields($username,$email,$errors,$password)
		{
			global $phoneLogic;
			if ( !isset( $_POST['billing_phone'] ) || !SmsAlertUtility::validatePhoneNumber($_POST['billing_phone']))
				return new WP_Error( 'billing_phone_error', str_replace("##phone##",$_POST['billing_phone'],$phoneLogic->_get_otp_invalid_format_message()) );
			
			smsalert_site_challenge_otp($username,$email,$errors,$_POST['billing_phone'],"phone",$password);
		}
		
		function smsalert_add_phone_field()
		{
			echo '<p class="form-row form-row-wide">
					<label for="reg_billing_phone">'.SmsAlertMessages::showMessage('Phone').'<span class="required">*</span></label>
					<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="'.(!empty( $_POST['billing_phone'] ) ? $_POST['billing_phone'] : "").'" />
			  	  </p>';
		}

		function handle_failed_verification($user_login,$user_email,$phone_number)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar])) return;
			smsalert_site_otp_validation_form($user_login,$user_email,$phone_number,SmsAlertUtility::_get_invalid_otp_method(),"phone",FALSE);
		}

		function handle_post_verification($redirect_to,$user_login,$user_email,$password,$phone_number,$extra_data)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar])) return;
			$_SESSION['sa_mobile_verified'] = true;
		}
		
		public function unsetOTPSessionVariables()
		{
			unset($_SESSION[$this->txSessionId]);
			unset($_SESSION[$this->formSessionVar]);
		}

		public function is_ajax_form_in_play($isAjax)
		{
			SmsAlertUtility::checkSession();
			return isset($_SESSION[$this->formSessionVar]) ? FALSE : $isAjax;
		}

		function handleFormOptions()
		{
			update_option('mo_customer_validation_wc_default_enable',
				isset( $_POST['mo_customer_validation_wc_default_enable']) ? $_POST['mo_customer_validation_wc_default_enable'] : 0);
			update_option('mo_customer_validation_wc_enable_type',
				isset( $_POST['mo_customer_validation_wc_enable_type']) ? $_POST['mo_customer_validation_wc_enable_type'] : '');
			update_option('mo_customer_validation_wc_redirect',
				isset( $_POST['page_id']) ? get_the_title($_POST['page_id']) : 'My Account');
		}
	}
	new WooCommerceRegistrationForm;