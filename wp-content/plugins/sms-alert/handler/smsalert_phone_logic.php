<?php 

class PhoneLogic extends LogicInterface
{
	public function _handle_logic($user_login,$user_email,$phone_number,$otp_type,$form)
	{
		$match = preg_match(SmsAlertConstants::getPhonePattern(),$phone_number);
		switch ($match) 
		{
			case 0:
				$this->_handle_not_matched($phone_number,$otp_type,$form);						break;
			case 1:
				$this->_handle_matched($user_login,$user_email,$phone_number,$otp_type,$form);	break;
		}
	}

	public function _handle_matched($user_login,$user_email,$phone_number,$otp_type,$form)
	{
		$content = json_decode(SmsAlertcURLOTP::smsalert_send_otp_token($form, '', $phone_number), true);
		$content = array_key_exists('status',$content) ? $content['status'] : '';
		switch ($content) 
		{
			case 'success':
				$this->_handle_otp_sent($user_login,$user_email,$phone_number,$otp_type,$form,$content); 		break;
			default:
				$this->_handle_otp_sent_failed($user_login,$user_email,$phone_number,$otp_type,$form,$content);break;
		}
	}

	public function _handle_not_matched($phone_number,$otp_type,$form)
	{
		SmsAlertUtility::checkSession();
		$message = str_replace("##phone##",$phone_number,self::_get_otp_invalid_format_message());
		if(self::_is_ajax_form() || array_key_exists('smsalert_action',$_REQUEST))
			wp_send_json(SmsAlertUtility::_create_json_response($message,SmsAlertConstants::ERROR_JSON_TYPE));
		else
			smsalert_site_otp_validation_form(null,null,null,$message,$otp_type,$form);
	}

	public function _handle_otp_sent_failed($user_login,$user_email,$phone_number,$otp_type,$form,$content)
	{
		
		SmsAlertUtility::checkSession();
		$message = str_replace("##phone##",$phone_number,self::_get_otp_sent_failed_message());
		if(self::_is_ajax_form() || array_key_exists('smsalert_action',$_REQUEST))
			wp_send_json(SmsAlertUtility::_create_json_response($message,SmsAlertConstants::ERROR_JSON_TYPE));
		else
			smsalert_site_otp_validation_form(null,null,null,$message,$otp_type,$form);
	}

	public function _handle_otp_sent($user_login,$user_email,$phone_number,$otp_type,$form,$content)
	{
		SmsAlertUtility::checkSession();
		
		$message = str_replace("##phone##",$phone_number,self::_get_otp_sent_message());
		if(self::_is_ajax_form() || array_key_exists('smsalert_action',$_REQUEST))
			wp_send_json(SmsAlertUtility::_create_json_response($message,SmsAlertConstants::SUCCESS_JSON_TYPE));
		else
			smsalert_site_otp_validation_form($user_login, $user_email,$phone_number,$message,$otp_type,$form);
	}
	
	public function _get_otp_sent_message()
	{
		return get_option("mo_otp_success_phone_message") ? get_option('mo_otp_success_phone_message') : SmsAlertMessages::OTP_SENT_PHONE;
	}

	public function _get_otp_sent_failed_message()
	{
		return get_option("mo_otp_error_phone_message") ? get_option('mo_otp_error_phone_message') : SmsAlertMessages::ERROR_OTP_PHONE;
	}

	public function _get_otp_invalid_format_message()
	{
		return get_option("mo_otp_invalid_phone_message") ? get_option('mo_otp_invalid_phone_message') : SmsAlertMessages::ERROR_PHONE_FORMAT;
	}
}