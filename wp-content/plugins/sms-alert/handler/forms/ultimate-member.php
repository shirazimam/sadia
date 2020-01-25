<?php
	class UltimateMemberRegistrationForm extends FormInterface
	{
		
		private $formSessionVar = FormSessionVars::UM_DEFAULT_REG;
		private $phoneFormID 	= "input[name^='billing_phone']";
		

		function handleForm()
		{
			
			
			add_action( 'um_submit_form_errors_hook_', array($this,'smsalert_um_phone_validation'), 99,1);
			if (is_plugin_active( 'ultimate-member/ultimate-member.php' ) && !isset($_POST['smsalert_otp_token_submit'])) //>= UM version 2.0.17 
			{
				add_action( 'um_submit_form_register', array($this,'smsalert_um_user_registration'), 1,1);
			}
			else //< UM version 2.0.17 
			{
				add_action( 'um_before_new_user_register', array($this,'smsalert_um_user_registration'), 1,1);
			}
		}

		public static function isFormEnabled() 
		{
			return (smsalert_get_option('buyer_signup_otp', 'smsalert_general')=="on") ? true : false;
		}

			 
		function smsalert_um_user_registration($args)
		{
			
			SmsAlertUtility::checkSession();
			$errors = new WP_Error();
			SmsAlertUtility::initialize_transaction($this->formSessionVar);
			
			foreach ($args as $key => $value)
			{
				if($key=="user_login")
					$username = $value;
				elseif ($key=="user_email")
					$email = $value;
				elseif ($key=="user_password")
					$password = $value;
				elseif ($key == 'billing_phone')
					$phone_number = $value;
				else
					$extra_data[$key]=$value;
			}
			$this->startOtpTransaction($username,$email,$errors,$phone_number,$password,$extra_data);
		}

		function startOtpTransaction($username,$email,$errors,$phone_number,$password,$extra_data)
		{
			smsalert_site_challenge_otp($username,$email,$errors,$phone_number,"phone",$password,$extra_data);
		}

		function smsalert_um_phone_validation($args)
		{
			global $ultimatemember,$phoneLogic;
			foreach ($args as $key => $value) 
				if ($key == 'billing_phone')
					if(!SmsAlertUtility::validatePhoneNumber($value))
						$ultimatemember->form->add_error($key, str_replace("##phone##",$value,$phoneLogic->_get_otp_invalid_format_message()));
		}
		
		function smsalert_um_submit_args($args)
		{
			return $args;
		}
		
		function register_ultimateMember_user($user_login,$user_email,$password,$phone_number,$extra_data)
		{
			add_filter( "um_submit_form_register", array( $this, 'smsalert_um_submit_args' ), 1, 1 );
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
			$this->register_ultimateMember_user($user_login,$user_email,$password,$phone_number,$extra_data);
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

		public function getPhoneNumberSelector($selector)	
		{
			SmsAlertUtility::checkSession();
			if(self::isFormEnabled()) array_push($selector, $this->phoneFormID); 
			return $selector;
		}

		function handleFormOptions()
	    {
			
	    }
	}
	new UltimateMemberRegistrationForm;