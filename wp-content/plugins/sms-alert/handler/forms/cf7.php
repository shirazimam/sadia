<?php

	class ContactForm7 extends FormInterface
	{
		private $formSessionVar 	= FormSessionVars::CF7_FORMS;
		private $formPhoneVer 		= FormSessionVars::CF7_PHONE_VER;
		private $formFinalPhoneVer 	= FormSessionVars::CF7_PHONE_SUB;
		private $phoneFormID;
		private $phoneFieldKey; 
		private $formSessionTagName;
		
		
		function handleForm()
		{
			$this->phoneFieldKey = 'billing_phone';
			$this->phoneFormID = 'input[name='.$this->phoneFieldKey.']';

			add_filter( 'wpcf7_validate_text*'	, array($this,'validateFormPost'), 1 , 2 );
			add_filter( 'wpcf7_validate_tel*'	, array($this,'validateFormPost'), 1 , 2 );
			add_shortcode('smsalert_verify_phone',array($this,'_cf7_phone_shortcode'));	
			$this->routeData();
			
			if(smsalert_get_option('allow_query_sms', 'smsalert_general')!="off") {
				add_filter( 'wpcf7_editor_panels' , array($this, 'new_menu_sms_alert'),98);
				add_action( 'wpcf7_after_save', array( &$this, 'save_form' ) );
				add_action( 'wpcf7_before_send_mail', array($this, 'sendsms_c7' ) );
			}
		}

		public static function isFormEnabled()
		{
			return (smsalert_get_option('allow_query_sms', 'smsalert_general')=="on") ? TRUE : FALSE;
			
		}

		function routeData()
		{
			if(!array_key_exists('option', $_GET)) return; 

			switch (trim($_GET['option'])) 
			{
				case "smsalert-cf7-contact":
					$this->_handle_cf7_contact_form($_POST);	break; 			
			}
		}	

		function _handle_cf7_contact_form($getdata)
		{
			SmsAlertUtility::checkSession();
			SmsAlertUtility::initialize_transaction($this->formSessionVar);

			if(array_key_exists('user_phone', $getdata) && !SmsAlertUtility::isBlank($getdata['user_phone']))
			{
				$_SESSION[$this->formPhoneVer] = trim($getdata['user_phone']);
				$message = str_replace("##phone##",$getdata['user_phone'],SmsAlertMessages::OTP_SENT_PHONE);
				smsalert_site_challenge_otp('test',null,null,trim($getdata['user_phone']),"phone",null,null,true);
			}
			else
			{
				wp_send_json( SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('ENTER_PHONE_FORMAT'),SmsAlertConstants::ERROR_JSON_TYPE) );
			}
		}
		
		function validateFormPost($result, $tag)
		{
			SmsAlertUtility::checkSession();
			$tag = new WPCF7_FormTag( $tag );
			$name = $tag->name;
			$value = isset( $_POST[$name] ) ? trim( wp_unslash( strtr( (string) $_POST[$name], "\n", " " ) ) ) : '';
			
			if ( 'tel' == $tag->basetype && $name==$this->phoneFieldKey) $_SESSION[$this->formFinalPhoneVer]  = $value;

			if ( 'text' == $tag->basetype && $name=='smsalert_customer_validation_otp_token') 
			{
				$_SESSION[$this->formSessionTagName] = $name;
				//check if the otp verification field is empty
				if($this->checkIfVerificationCodeNotEntered($name)) $result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
				//check if the session variable is not true i.e. OTP Verification flow was not started
				if($this->checkIfVerificationNotStarted()) $result->invalidate($tag, _e(SmsAlertMessages::showMessage('VALIDATE_OTP')) );
				
				// validate otp if no error
				if(empty($result->invalid_fields)) {
				if(!$this->processOTPEntered())
					$result->invalidate( $tag, SmsAlertUtility::_get_invalid_otp_method());
				else
					$this->unsetOTPSessionVariables();
				}
			}
			return $result;
		}

		function handle_failed_verification($user_login,$user_email,$phone_number)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar])) return;
			$_SESSION[$this->formSessionVar] = 'verification_failed';	
		}

		function handle_post_verification($redirect_to,$user_login,$user_email,$password,$phone_number,$extra_data)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar])) return;
			$_SESSION[$this->formSessionVar] = 'validated';	
		}

		function validateOTPRequest()
		{
			do_action('smsalert_validate_otp',$_SESSION[$this->formSessionTagName],NULL);
		}

		function processOTPEntered()
		{
			$this->validateOTPRequest();
			return strcasecmp($_SESSION[$this->formSessionVar],'validated')!=0 ? FALSE : TRUE;
		}

		function checkIfVerificationNotStarted()
		{
			return !array_key_exists($this->formSessionVar,$_SESSION); 
		}

		function checkIfVerificationCodeNotEntered($name)
		{
			return !isset($_REQUEST[$name]);
		}

		

		function _cf7_phone_shortcode()
		{
			$html  = '<script>jQuery(window).load(function(){	$mo=jQuery;$mo("#smsalert_customer_validation_otp_token").click(function(o){'; 
			$html .= 'var e=$mo("input[name='.$this->phoneFieldKey.']").val();
			if(""==e){alert("Please enter your mobile number");}
			$mo("#mo_message").empty(),$mo("#mo_message").append("Loading..!Please wait"),';
			$html .= '$mo("#mo_message").show(),$mo.ajax({url:"'.site_url().'/?option=smsalert-cf7-contact",type:"POST",data:{user_phone:e},';
			$html .= 'crossDomain:!0,dataType:"json",success:function(o){ if(o.result=="success"){$mo("#mo_message").empty(),';
			$html .= '$mo("#mo_message").append(o.message),$mo("#mo_message").css("border-top","3px solid green"),';
			$html .= '$mo("input[name=email_verify]").focus()}else{$mo("#mo_message").empty(),$mo("#mo_message").append(o.message),';
			$html .= '$mo("#mo_message").css("border-top","3px solid red"),$mo("input[name=smsalert_customer_validation_otp_token]").focus()} ;},';
			$html .= 'error:function(o,e,n){}})});});</script>';
			return $html;
		}

		public function unsetOTPSessionVariables()
		{
			unset($_SESSION[$this->txSessionId]);
			unset($_SESSION[$this->formSessionVar]);
			unset($_SESSION[$this->formPhoneVer]);
			unset($_SESSION[$this->formFinalPhoneVer]);
			unset($_SESSION[$this->formSessionTagName]);
		}

		public function is_ajax_form_in_play($isAjax)
		{
			SmsAlertUtility::checkSession();
			return isset($_SESSION[$this->formSessionVar]) ? TRUE : $isAjax;
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
		
		/*
	* Function for sms alert integration with contact form 7.
	*/
			
		public function new_menu_sms_alert ($panels) {
			$panels['sms-alert-sms-panel'] = array(
					'title' => __('SMS Alert'),
					'callback' => array($this, 'add_panel_sms_alert')
			);
			return $panels;
		}
			
		public function add_panel_sms_alert($form) { 
			if ( wpcf7_admin_has_edit_cap() ) {
			  $options = get_option( 'smsalert_sms_c7_' . (method_exists($form, 'id') ? $form->id() : $form->id) );
			  if( empty( $options ) || !is_array( $options ) ) {
				$options 		= array( 'phoneno' => '', 'text' => '', 'visitorNumber' => '','visitorMessage' => '');
			  }
			  $options['form'] 	= $form;
			  $data 			= $options; 
			  include(plugin_dir_path( __DIR__ ).'../template/sms-alert-template.php'); 
			}
		}
		
		public function save_form( $form ) {
			update_option( 'smsalert_sms_c7_' . (method_exists($form, 'id') ? $form->id() : $form->id), $_POST['wpcf7smsalert-settings'] );
		} 
	  
		public function get_cf7_tagS_To_String($value,$form){
				if(function_exists('wpcf7_mail_replace_tags')) {
					$return = wpcf7_mail_replace_tags($value); 
				} elseif(method_exists($form, 'replace_mail_tags')) {
					$return = $form->replace_mail_tags($value); 
				} else {
					return;
				}
				return $return;
		}
		
		 public function sendsms_c7($form)
		 {
			$options 			= get_option( 'smsalert_sms_c7_' . (method_exists($form, 'id') ? $form->id() : $form->id)) ;
			$sendToAdmin 		= false;
			$sendToVisitor 		= false;
			$adminNumber 		= '';
			$adminMessage 		= ''; 
			$visitorNumber 		= '';
			$visitorMessage 	= '';
			if(isset($options['phoneno']) && $options['phoneno'] != '' && isset($options['text']) && $options['text'] != ''){
				$adminNumber 	= $this->get_cf7_tagS_To_String($options['phoneno'],$form);
				$adminMessage 	= $this->get_cf7_tagS_To_String($options['text'],$form);
				$sendToAdmin 	= true; 
			}

			if(isset($options['visitorNumber']) && $options['visitorNumber'] != '' && 
			   isset($options['visitorMessage']) && $options['visitorMessage'] != ''){ 
				
				$visitorNumber 	= $this->get_cf7_tagS_To_String($options['visitorNumber'],$form);
				$visitorMessage = $this->get_cf7_tagS_To_String($options['visitorMessage'],$form);
				$sendToVisitor 	= true; 
			}
			
			if($sendToAdmin){

				$buyer_sms_data 			= array();
				$buyer_sms_data['number']   = $adminNumber;
				$buyer_sms_data['sms_body'] = $adminMessage;
				$admin_response             = SmsAlertcURLOTP::sendsms( $buyer_sms_data );	
			}
			
			if($sendToVisitor){
				$buyer_sms_data 			= array();
				$buyer_sms_data['number']   = $visitorNumber;
				$buyer_sms_data['sms_body'] = $visitorMessage;
				$buyer_response             = SmsAlertcURLOTP::sendsms( $buyer_sms_data );
			}
		}
		
	}
	new ContactForm7;