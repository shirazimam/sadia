<?php
echo '<style>.modal{display:none;position:fixed;z-index:999999999999;padding-top:100px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgb(0,0,0);background-color:rgba(0,0,0,0.4);}.modal-content{position:relative;background-color:#fefefe;margin:auto;padding:0;border:1px solid #888;width:40%;box-shadow:04px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);-webkit-animation-name:animatetop;-webkit-animation-duration:0.4s;animation-name:animatetop;animation-duration:0.4s}@media  only screen and (max-width: 767px){.modal-content{width:100%}}@-webkit-keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}@keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}.modal-header{background-color:#5cb85c;color:white;}.modal-footer{background-color:#5cb85c;color:white;}.close{float:none;text-align: right;font-size: 30px;cursor: pointer;text-shadow: 0 1px 0 #fff;line-height: 1;font-weight: 700;padding: 2px 5px 5px;}.close:hover {color: #999;}</style>';
			echo ' <div id="myModal" class="modal"><div class="modal-content"><div class="close" id="close">x</div><div class="modal-body"><div id="smsalert_message" style="margin:1em;">EMPTY</div><div id="smsalert_validate_field" style="margin:1em"><input type="number" name="order_verify" autofocus="true" placeholder="" id="smsalert_customer_validation_otp_token" required="true" class="input-text" autofocus="true" pattern="[0-9]{4,8}" title="'.$otp_range.'"/>';
			echo '<script>jQuery("#smsalert_customer_validation_otp_token").on("input",function(){
			if(jQuery("#smsalert_customer_validation_otp_token").val().match(/^\d{4,8}$/)) { jQuery("#smsalert_otp_validate_submit").removeAttr("style");} else{jQuery("#smsalert_otp_validate_submit").css({"color":"grey","pointer-events":"none"}); }}); var interval; jQuery("#close").click(function(){jQuery("#myModal").hide();clearInterval(interval);});
			
            function resendOtp()
			{
				jQuery("#smsalert_otp_token_submit").click();
			}
			</script>
			<input type="button" name="smsalert_otp_validate_submit" style="color:grey; pointer-events:none;" id="smsalert_otp_validate_submit" class="button alt" value="'.$VALIDATE_OTP.'" />
			<br /><a style="float:right" id="verify_otp" onclick="resendOtp()">'.$RESEND.'</a><span id="timer" style="min-width:80px; float:right">00:00 sec</span><br />
			</div></div></div></div>';
			
?>			