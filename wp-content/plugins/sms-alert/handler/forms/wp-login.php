<?php
	class WPLoginForm extends FormInterface
	{
		private $formSessionVar  = FormSessionVars::WP_LOGIN_REG_PHONE;
		private $formSessionVar2 = FormSessionVars::WP_DEFAULT_LOGIN;
		private $formSessionVar3 = FormSessionVars::WP_LOGIN_WITH_OTP;
		private $phoneNumberKey;

		function handleForm()
		{	
			$this->phoneNumberKey = 'billing_phone';
			add_filter( 'authenticate', array($this,'_handle_smsalert_wp_login'), 99, 4 );
			
			$this->routeData();
			$enabled_login_with_otp = smsalert_get_option( 'login_with_otp', 'smsalert_general', 'on');
			if($enabled_login_with_otp=='on')
			{
				add_action( 'woocommerce_login_form_end',  array($this,'add_login_with_otp_popup'));
				add_action( 'woocommerce_login_form_end',  array($this,'smsalert_display_login_with_otp'));
				add_action( 'um_after_login_fields',  array($this,'add_login_with_otp_popup'),1002);
				add_action( 'um_after_login_fields',  array($this,'smsalert_display_login_with_otp'),1002);
			}
		}
		
		function routeData()
		{
			if(!array_key_exists('option', $_REQUEST)) return;
			switch (trim($_REQUEST['option'])) 
			{
				case "smsalert-ajax-otp-generate":
					$this->_handle_wp_login_ajax_send_otp($_POST);				break;
				case "smsalert-ajax-otp-validate":
					$this->_handle_wp_login_ajax_form_validate_action($_POST);	break;
				case "smsalert_ajax_form_validate":
					$this->_handle_wp_login_create_user_action($_POST);			break;
				case "smsalert_ajax_login_with_otp":
					$this->handle_login_with_otp();			break;	
			}
		}
		
		
		function handle_login_with_otp()
		{
			
			if(isset($_REQUEST['smsalert_action']) && $_REQUEST['smsalert_action']=='smsalert_action_login_with_otp')
			{
				$phone_number = isset($_REQUEST['login_otp_nos']) ? $_REQUEST['login_otp_nos'] : ''; 
				
				$user_info = $this->getUserFromPhoneNumber($phone_number,$this->phoneNumberKey);
				$user_login = ($user_info) ? $user_info->data->user_login : '';
				
				if($user_login!='')
				{
					SmsAlertUtility::checkSession();
					$_SESSION[$this->formSessionVar3]=true;
					smsalert_site_challenge_otp(null,null,null,$phone_number,"phone",null,SmsAlertUtility::currentPageUrl(),true);
				}
				else
				{
					wp_send_json( SmsAlertUtility::_create_json_response( SmsAlertMessages::PHONE_NOT_FOUND,'error'));
				}
				
			}
		}
		
		
		public static function smsalert_display_login_with_otp() 
		{
			echo '<link rel="stylesheet" type="text/css" href="' . MOV_CSS_URL . '" />';
			echo '<input type="button" class="button" name="smsalert_login_with_otp" value="Login with OTP" id="smsalert_login_with_otp">';
			 
			 echo '<script>
					
							   jQuery("#smsalert_login_with_otp").click(function(o) {
								   
								   
								   //var e = jQuery("input[name=username]").val();
								   var e = jQuery(this).parents("form").find("input[type=\"text\"]:first").val();
								   if(e=="" || isNaN(e))
								   {
									   alert("'.SmsAlertMessages::showMessage('ENTER_MOB_NO').'");
									   return false;
								   }
								   
								   $mo = jQuery;
								   $mo.ajax({
										url:"'.site_url().'/?option=smsalert_ajax_login_with_otp",type:"POST",
										data:{login_otp_nos:e,smsalert_action:"smsalert_action_login_with_otp"},
										crossDomain:!0,
										dataType:"json",
										success:function(o){("success"==o.result)?($mo(".blockUI").hide(),
										$mo("#smsalert_login_message").empty().removeClass("woocommerce-error"),
										$mo("#smsalert_login_message").append(o.message),
										$mo("#smsalert_login_message").addClass("woocommerce-message"),
										$mo("#myLoginModal").show(), 
										$mo("#smsalert_validate_field").show(),
										$mo("#mo_customer_validation_otp_token").focus()):
										($mo(".blockUI").hide(),
										$mo("#smsalert_login_message").empty(),
										$mo("#smsalert_login_message").append(o.message),
										$mo("#smsalert_login_message").addClass("woocommerce-error"),
										$mo("#smsalert_validate_field").hide(),
										$mo("#myLoginModal").show())
										timerCount();
										},
										error:function(o,e,m)
										{

										}
									   });
								   
								   return false;		
								});
								
								
			 
			 </script>';
			
		}
		
		function add_login_with_otp_popup()
		{
			//if($this->guestCheckOutOnly && is_user_logged_in())  return;
			$otp_resend_timer = smsalert_get_option( 'otp_resend_timer', 'smsalert_general', '15');
			echo '<style>.modal{display:none;position:fixed;z-index:1;padding-top:100px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgb(0,0,0);background-color:rgba(0,0,0,0.4);}.modal-content{position:relative;background-color:#fefefe;margin:auto;padding:0;border:1px solid #888;width:40%;box-shadow:04px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);-webkit-animation-name:animatetop;-webkit-animation-duration:0.4s;animation-name:animatetop;animation-duration:0.4s}@media only screen and (max-width: 767px){.modal-content{width:100%}}@-webkit-keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}@keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}.modal-header{background-color:#5cb85c;color:white;}.modal-footer{background-color:#5cb85c;color:white;}.close{float: right;font-size: 30px;cursor: pointer;text-shadow: 0 1px 0 #fff;line-height: 1;font-weight: 700;padding: 2px 5px 5px;position: relative; z-index: 9999999;}.close:hover {color: #999;} #smsalert_login_customer_validation_otp_token{margin-bottom:1em}
			</style>';
			echo ' <div id="myLoginModal" class="modal"><div class="modal-content"><span class="close" id="sclose" onclick="closePopup();">x</span><div class="modal-body"><div id="smsalert_login_message" style="margin:1em;">ENTER YOUR OTP</div><div id="smsalert_validate_field" style="margin:1em">
			
			<input type="number" name="smsalert_customer_validation_otp_token" autofocus="true" placeholder="Enter OTP" id="smsalert_login_customer_validation_otp_token" class="input-text smsalert-hide" autofocus="true" pattern="[0-9]{4,8}" title="'.SmsAlertMessages::showMessage('OTP_RANGE').'"/>
			';
			
			echo '<script>jQuery("#smsalert_login_customer_validation_otp_token").keyup(function(){
			if(jQuery("#smsalert_login_customer_validation_otp_token").val().match(/^\d{4,8}$/)) { jQuery("#smsalert_login_otp_validate_submit").removeAttr("style");} else{jQuery("#smsalert_otp_validate_submit").css({"color":"grey","pointer-events":"none"}); }}); 
			var interval; function closePopup(){jQuery("#myLoginModal").hide();clearInterval(interval);}
			
			
            function resendLoginOtp()
			{
				jQuery("#smsalert_login_with_otp").trigger("click");
			}
			</script>
			<div id="login_with_otp_extra_fields">
			</div>
			<input type="submit" name="smsalert_login_otp_validate_submit" style="color:grey; pointer-events:none;" id="smsalert_login_otp_validate_submit" class="button alt" value="'.SmsAlertMessages::showMessage('VALIDATE_OTP').'" />
			<br /><a style="float:right" id="login_verify_otp" onclick="resendLoginOtp()">'.SmsAlertMessages::showMessage('RESEND').'</a><span id="login_timer" style="min-width:80px; float:right">00:00 sec</span><br />
			</div></div></div></div>
			
			<script>
			
		   	function timerCount()
			{
				var timer = function(secs){
					var sec_num = parseInt(secs, 10)    
					var hours   = Math.floor(sec_num / 3600) % 24
					var minutes = Math.floor(sec_num / 60) % 60
					var seconds = sec_num % 60    
					return [hours,minutes,seconds]
						.map(v => v < 10 ? "0" + v : v)
						.filter((v,i) => v !== "00" || i > 0)
						.join(":")
				};
				document.getElementById("login_timer").style.display = "block";
				document.getElementById("login_timer").innerHTML = timer('.$otp_resend_timer.')+" sec";
				var counter = '.$otp_resend_timer.';
				 interval = setInterval(function() {
					counter--;
					 var places = (counter < 10 ? "0" : "");
					document.getElementById("login_timer").innerHTML = timer(counter)+ " sec";
					if (counter == 0) {
						document.getElementById("login_timer").style.display = "none";
						var cssString = "pointer-events: auto; cursor: pointer; opacity: 1; float:right"; 
						document.getElementById("login_verify_otp").style.cssText = cssString;
						clearInterval(interval);
					}
					else
					{
						document.getElementById("login_verify_otp").style.cssText = "pointer-events: none; cursor: default; opacity: 1; float:right";
					}
				}, 1000);
			}
			
			function stoptimer(obj)
			{
				clearInterval(obj);
			}
			
			
			$mo = jQuery;
			$mo("#smsalert_login_otp_validate_submit").click(function(){
					if($mo("#smsalert_login_customer_validation_otp_token").val()=="")
					{
						alert("Please enter OTP");
						return false;
					}
					
					var extra_fields=\'<input type="hidden" name="smsalert_action" value="1" /><input type="hidden" name="option" value="smsalert-validate-otp-form" /><input type="hidden" name="otp_type" value="phone"><input type="hidden" name="from_both">\';
					$mo("#login_with_otp_extra_fields").html(extra_fields);	
					$mo(this).parents("form").submit();
					
					return false;		   
			});	
			</script>
			';
		}
		
		
		
		
		public static function isFormEnabled() 
		{
			return (smsalert_get_option('buyer_login_otp', 'smsalert_general')=="on") ? true : false;
		}

		function check_wp_login_register_phone() 
		{
			return true; //get_option('mo_customer_validation_wp_login_register_phone') ? true : false;
		}

		function check_wp_login_by_phone_number()                                 
		{
			return true;//get_option('mo_customer_validation_wp_login_allow_phone_login') ? true : false;
		}

		function check_wp_login_bypass_admin()                                 
		{
			return (smsalert_get_option('admin_bypass_otp_login', 'smsalert_general')=="on") ? true : false;
		}

		function byPassLogin($user_role)
		{
			return in_array('administrator',$user_role) && $this->check_wp_login_bypass_admin() ? true : false;
		}

		function check_wp_login_restrict_duplicates()
		{
			return (smsalert_get_option('allow_multiple_user', 'smsalert_general')=="on") ? true : false;
		}

		function _handle_wp_login_create_user_action($postdata)
		{
			$redirect_to = isset($postdata['redirect_to'])?$postdata['redirect_to']:null;//added this line on 28-11-2018 due to affiliate login redirect issue
			
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar]) 
				|| $_SESSION[$this->formSessionVar]!='validated') 	return;

			$user = is_email( $postdata['log'] ) ? get_user_by("email",$postdata['log']) : get_user_by("login",$postdata['log']);
			if(!$user)
				$user = is_email( $postdata['username'] ) ? get_user_by("email",$postdata['username']) : get_user_by("login",$postdata['username']);
			
			update_user_meta($user->data->ID, $this->phoneNumberKey ,$postdata['mo_phone_number']);
			//$this->login_wp_user($user->data->user_login); //commented on 28-11-2018 due to affiliate login redirect issue
			$this->login_wp_user($user->data->user_login,$redirect_to);
		}

		function login_wp_user($user_log,$extra_data=null)
		{
			$user = is_email( $user_log ) ? get_user_by("email",$user_log) : get_user_by("login",$user_log);
			wp_set_auth_cookie($user->data->ID);
			$this->unsetOTPSessionVariables();
			do_action( 'wp_login', $user->user_login, $user );	
			$redirect = SmsAlertUtility::isBlank($extra_data) ? site_url() : $extra_data;
			wp_redirect($redirect);
			exit;
		}

		function _handle_smsalert_wp_login($user,$username,$password)
		{
			$user = $this->getUserIfUsernameIsPhoneNumber($user,$username,$password,$this->phoneNumberKey);
			if(is_wp_error($user)) return $user;

			SmsAlertUtility::checkSession();		
			$user_meta 	= get_userdata($user->data->ID);
			$user_role 	= $user_meta->roles;
			$phone_number = get_user_meta($user->data->ID, $this->phoneNumberKey,true);
			if($this->byPassLogin($user_role)) return $user;
			$this->askPhoneAndStartVerification($user,$this->phoneNumberKey,$username,$phone_number);
			$this->fetchPhoneAndStartVerification($user,$this->phoneNumberKey,$username,$password,$phone_number);
			return $user;
		} 

		function getUserIfUsernameIsPhoneNumber($user,$username,$password,$key)
		{
			if(!$this->check_wp_login_by_phone_number() || !SmsAlertUtility::validatePhoneNumber($username)) return $user;
			$user_info = $this->getUserFromPhoneNumber($username,$key);
			$username = is_object($user_info) ? $user_info->data->user_login : $username; //added on 20-05-2019
			return wp_authenticate_username_password(NULL,$username,$password);
		}

		function getUserFromPhoneNumber($username,$key)
		{
			global $wpdb;
			
			$results = $wpdb->get_row("SELECT `user_id` FROM {$wpdb->base_prefix}usermeta WHERE `meta_key` = '$key' AND `meta_value` =  '$username'");
			
			$user_id = (sizeof($results)>0) ? $results->user_id : 0;
			return get_userdata($user_id);
		}

		function askPhoneAndStartVerification($user,$key,$username,$phone_number)
		{
			if(!SmsAlertUtility::isBlank($phone_number)) return;
			if(!$this->check_wp_login_register_phone() )
				smsalert_site_otp_validation_form(null,null,null, SmsAlertMessages::PHONE_NOT_FOUND,null,null);
			else
			{
				SmsAlertUtility::initialize_transaction($this->formSessionVar);
				smsalert_external_phone_validation_form(SmsAlertUtility::currentPageUrl(), $user->data->user_login, SmsAlertMessages::REGISTER_PHONE_LOGIN, $key, array('user_login'=>$username));
			}					
		}

		function fetchPhoneAndStartVerification($user,$key,$username,$password,$phone_number)
		{
			if((array_key_exists($this->formSessionVar,$_SESSION) && strcasecmp($_SESSION[$this->formSessionVar],'validated')==0)
				|| (array_key_exists($this->formSessionVar2,$_SESSION) && strcasecmp($_SESSION[$this->formSessionVar2],'validated')==0)) return;
			SmsAlertUtility::initialize_transaction($this->formSessionVar2);
			
			//smsalert_site_challenge_otp($username,null,null,$phone_number[0],"phone",$password,$_REQUEST['redirect_to'],false);
			//smsalert_site_challenge_otp($username,null,null,$phone_number[0],"phone",$password,SmsAlertUtility::currentPageUrl(),false); //commented on 03-12-2018 get_user_meta set true
			smsalert_site_challenge_otp($username,null,null,$phone_number,"phone",$password,SmsAlertUtility::currentPageUrl(),false);
		}

		function _handle_wp_login_ajax_send_otp($data)
		{
			SmsAlertUtility::checkSession();
			if($this->check_wp_login_restrict_duplicates() 
				&& !SmsAlertUtility::isBlank($this->getUserFromPhoneNumber($data['billing_phone'],$this->phoneNumberKey)))
				wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::PHONE_EXISTS,SmsAlertConstants::ERROR_JSON_TYPE));
			elseif(isset($_SESSION[$this->formSessionVar]))
			{
				smsalert_site_challenge_otp('ajax_phone','',null, trim($data['billing_phone']),"phone",null,$data, null);
			}
		}

		function _handle_wp_login_ajax_form_validate_action($data)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar])) return;
			
			if(strcmp($_SESSION['phone_number_mo'], $data['billing_phone']))
				wp_send_json( SmsAlertUtility::_create_json_response( SmsAlertMessages::PHONE_MISMATCH,'error'));
			else
				do_action('smsalert_validate_otp','phone');
		}

		function handle_failed_verification($user_login,$user_email,$phone_number)
		{
			SmsAlertUtility::checkSession();
			if(!isset($_SESSION[$this->formSessionVar]) && !isset($_SESSION[$this->formSessionVar2]) && !isset($_SESSION[$this->formSessionVar3])) return;

			if(isset($_SESSION[$this->formSessionVar])){	
				$_SESSION[$this->formSessionVar] = 'verification_failed';
				wp_send_json( SmsAlertUtility::_create_json_response(SMSAlertMessages::INVALID_OTP,'error'));
			}
			if(isset($_SESSION[$this->formSessionVar2]))
				smsalert_site_otp_validation_form($user_login,$user_email,$phone_number,SMSAlertMessages::INVALID_OTP,"phone",FALSE);
			if(isset($_SESSION[$this->formSessionVar3]))
				wc_add_notice( SmsAlertUtility::_get_invalid_otp_method(), 'error' );			
		}

		function handle_post_verification($redirect_to,$user_login,$user_email,$password,$phone_number,$extra_data)
		{
				SmsAlertUtility::checkSession();
				if(!isset($_SESSION[$this->formSessionVar]) && !isset($_SESSION[$this->formSessionVar2]) && !isset($_SESSION[$this->formSessionVar3])) return;
				if(isset($_SESSION[$this->formSessionVar])){
					$_SESSION[$this->formSessionVar] = 'validated';
					wp_send_json( SmsAlertUtility::_create_json_response('successfully validated','success') );
				}
				if(isset($_SESSION[$this->formSessionVar2]))
				{
					/*if(array_key_exists('log', $_POST))
						$this->login_wp_user($_POST['log']);
					elseif(array_key_exists('username', $_POST))
						$this->login_wp_user($_POST['username']);*/
					//$this->login_wp_user($user_login); //this commented on 16-11-2018 for affiliate login issue
					//$this->login_wp_user($user_login,$redirect_to);//this commented on 20-05-2019 when usr enters mobile no in username field
					$user_info = $this->getUserIfUsernameIsPhoneNumber(null,$user_login,$password,$this->phoneNumberKey);
					$userLogin = is_object($user_info) ? $user_info->data->user_login : $user_login;
					$this->login_wp_user($userLogin,$redirect_to);
					
				}
				
				if(isset($_SESSION[$this->formSessionVar3]))
				{
					
					$user_info = $this->getUserFromPhoneNumber($phone_number,$this->phoneNumberKey);
					unset($_SESSION[$this->formSessionVar3]);
					
					if($user_info->data->user_login!='')
					{
						//$this->login_wp_user($user_info->data->user_login);
						$this->login_wp_user($user_info->data->user_login,$redirect_to); //for ultimate member
					}
				}
		}

		public function unsetOTPSessionVariables()
		{
			unset($_SESSION[$this->txSessionId]);
			unset($_SESSION[$this->formSessionVar]);
			unset($_SESSION[$this->formSessionVar2]);
		}

		public function is_ajax_form_in_play($isAjax)
		{
			SmsAlertUtility::checkSession();
			return isset($_SESSION[$this->formSessionVar]) ? TRUE : $isAjax;
		}

		function handleFormOptions()
	    {
			update_option('mo_customer_validation_wp_login_enable',
				isset( $_POST['mo_customer_validation_wp_login_enable']) ? $_POST['mo_customer_validation_wp_login_enable'] : 0);
			update_option('mo_customer_validation_wp_login_register_phone',
				isset( $_POST['mo_customer_validation_wp_login_register_phone']) ? $_POST['mo_customer_validation_wp_login_register_phone'] : '');
			update_option('mo_customer_validation_wp_login_bypass_admin',
				isset( $_POST['mo_customer_validation_wp_login_bypass_admin']) ? $_POST['mo_customer_validation_wp_login_bypass_admin'] : '');
			update_option('mo_customer_validation_wp_login_key',
				isset( $_POST['wp_login_phone_field_key']) ? $_POST['wp_login_phone_field_key'] : '');
			update_option('mo_customer_validation_wp_login_allow_phone_login',
				isset( $_POST['mo_customer_validation_wp_login_allow_phone_login']) ? $_POST['mo_customer_validation_wp_login_allow_phone_login'] : '');
			update_option('mo_customer_validation_wp_login_restrict_duplicates',
				isset( $_POST['mo_customer_validation_wp_login_restrict_duplicates']) ? $_POST['mo_customer_validation_wp_login_restrict_duplicates'] : '');
	    }
	}
	new WPLoginForm;