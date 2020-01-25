<div id="cf7si-sms-sortables" class="meta-box-sortables ui-sortable">
	
	<h3><?php _e("Enable OTP for ContactForm7"); ?></h3>
	<fieldset>
		<legend><?php _e("Please follow the below steps to enable the SmsAlert OTP for CF7 Form:"); ?>
		</legend>
		<table class="form-table">
			<tbody>
				<tr>
					<td>
						<strong>Step 1.</strong> Add below code in your contact form7 for phone field and verification field.
						<pre style="white-space: pre-line;border:1px solid #ccc;padding:5px">
							 &lt;p&gt;Phone Number (required) &lt;br /&gt;
								[tel* billing_phone]&lt;/p&gt;

							&lt;div style="margin-bottom:3%"&gt;
							&lt;input type="button" class="button alt" style="width:100%" id="smsalert_customer_validation_otp_token" title="Please Enter a phone number to enable this." value="Click here to verify your Phone"&gt;&lt;div id="mo_message" hidden="" style="background-color: #f7f6f7;padding: 1em 2em 1em 3.5em;"&gt;&lt;/div&gt;
							&lt;/div&gt;

							&lt;p&gt;Verify Code (required)&lt;br /&gt;
								[text* smsalert_customer_validation_otp_token]&lt;/p&gt;
						</pre>
						<strong>Step 2.</strong> Add this shortcode <strong>[smsalert_verify_phone]</strong> after shortcode of contactform 7 in page.
						<pre style="white-space: pre-line;border:1px solid #ccc;padding:5px">
							Example:
								[contact-form-7 id="10" title="Contact form 1"]
								[smsalert_verify_phone]
						</pre>
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset>
	
	<hr/>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<h3><?php _e("Admin SMS Notifications"); ?></h3>
	<fieldset>
		<legend><?php _e("In the following fields, you can use these tags:"); ?>
			<br />
			<?php $data['form']->suggest_mail_tags(); ?>
		</legend>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="wpcf7-sms-recipient"><?php _e("To:"); ?></label>
					</th>
					<td>
						<input type="text" id="wpcf7-sms-recipient" name="wpcf7smsalert-settings[phoneno]" class="wide" size="70" value="<?php echo $data['phoneno']; ?>">
						<br/> <?php _e("<small>Enter Numbers By <code>,</code> for multiple</small>"); ?>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="wpcf7-mail-body"><?php _e("Message body:"); ?></label>
					</th>
					<td>
						<textarea id="wpcf7-mail-body" name="wpcf7smsalert-settings[text]" cols="100" rows="6" class="large-text code"><?php echo trim($data['text']); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset>
	
	<hr/>
	<h3><?php _e("Visitor SMS Notifications"); ?></h3>
	<fieldset>
		<legend><?php _e("In the following fields, you can use these tags:"); ?>
			<br />
			<?php $data['form']->suggest_mail_tags(); ?>
		</legend>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="wpcf7-sms-recipient"><?php _e("Visitor Mobile: "); ?></label>
					</th>
					<td>
						<input type="text" id="wpcf7-sms-recipient" name="wpcf7smsalert-settings[visitorNumber]" class="wide" size="70" value="<?php echo @$data['visitorNumber']; ?>">
						<br/> <?php _e("<small>Use <b>CF7 Tags</b> To Get Visitor Mobile Number | Enter Numbers By <code>,</code> for multiple</small>");?>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="wpcf7-mail-body"><?php _e("Message body:"); ?></label>
					</th>
					<td>
						<textarea id="wpcf7-mail-body" name="wpcf7smsalert-settings[visitorMessage]" cols="100" rows="6" class="large-text code"><?php echo @$data['visitorMessage']; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset>
</div>