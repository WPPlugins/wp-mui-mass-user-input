<?php //print_r($wp_mui->settings); ?>
<? //This stuff is hidden, but the jQuery moves it to the top ?>
<div class="hidden hide-if-no-js screen-meta-toggle" id="screen-options-link-wrap">
	<a class="show-settings" id="show-settings-link" href="#screen-options">Screen Options</a>
</div>
<div class="hidden" id="screen-options-wrap">
	<form method="post" action="" id="adv-settings">
		<h5>Autogeneration</h5>
		<div class="metabox-prefs">
			<label for="autocreate_user"><input type="checkbox" <?php if($wp_mui->settings['autocreate_user']) echo 'checked="checked"'; ?> value="1" id="vf_autocreate_user" name="autocreate_user" class="hide-postbox-tog"/>Autogenerate username from first and last name</label>
			<label for="autocreate_pass"><input type="checkbox" <?php if($wp_mui->settings['autocreate_pass']) echo 'checked="checked"'; ?> value="1" id="vf_autocreate_pass" name="autocreate_pass" class="hide-postbox-tog"/>Autogenerate password</label>
		</div>
		<h5>Visible Fields</h5>
		<div class="metabox-prefs">
			<label for="vf_email"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['email']) echo 'checked="checked"'; ?> value="1" id="vf_email" name="email" class="hide-postbox-tog"/>Email Address</label>
			<label for="vf_first_name"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['first_name']) echo 'checked="checked"'; ?> value="1" id="vf_first_name" name="first_name" class="hide-postbox-tog"/>First Name</label>
			<label for="vf_middle_initial"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['middle_initial']) echo 'checked="checked"'; ?> value="1" id="vf_middle_initial" name="middle_initial" class="hide-postbox-tog"/>Middle Initial</label>
			<label for="vf_last_name"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['last_name']) echo 'checked="checked"'; ?> value="1" id="vf_last_name" name="last_name" class="hide-postbox-tog"/>Last Name</label>
			<label for="vf_streetaddress"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['streetaddress']) echo 'checked="checked"'; ?> value="1" id="vf_streetaddress" name="streetaddress" class="hide-postbox-tog"/>Street Address</label>
			<label for="vf_city"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['city']) echo 'checked="checked"'; ?> value="1" id="vf_city" name="city" class="hide-postbox-tog"/>City</label>
			<label for="vf_state"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['state']) echo 'checked="checked"'; ?> value="1" id="vf_state" name="state" class="hide-postbox-tog"/>State</label>
			<label for="vf_zip"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['zip']) echo 'checked="checked"'; ?> value="1" id="vf_zip" name="zip" class="hide-postbox-tog"/>Zip Code</label>
			<label for="vf_phonenumber"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['phonenumber']) echo 'checked="checked"'; ?> value="1" id="vf_phonenumber" name="phonenumber" class="hide-postbox-tog"/>Phone Number</label>
			<label for="vf_role"><input type="checkbox" <?php if($wp_mui->settings['visible_fields']['role']) echo 'checked="checked"'; ?> value="1" id="vf_role" name="role" class="hide-postbox-tog"/>Role</label>
		</div>
	</form>
</div>
<? // End hidden ?>

<div id="profile-page" class="wrap">
	<div id="icon-users" class="icon32"><br /></div>
	<h2 id="add-new-user">Mass User Input <a class="button add-new-h2" href="users.php?page=wp_mui_insert_users_page&wp_mui_page=users_list">View / Export Users</a> </h2>
	
	<div id="wp_mui_result"></div>

	<form id="wp_mui_adduser" name="wp_mui_adduser" method="post" action="about:blank">
	<input type="hidden" value="wp_mui_add_user" id="action" name="action" />
	<?=wp_nonce_field('wp_mui_insert_users_page')?>
	<input type="hidden" value="<?=rand()?>" id="rand" name="rand" />
	<table class="form-table">
		<tr class="form-field <?php if($wp_mui->settings['autocreate_user']) echo 'hidden'; ?>">
			<th scope="row"><label for="user_login">Username </label></th>
			<td><input type="text" class="required" value="" id="user_login" name="user_login" <?php if($wp_mui->settings['autocreate_user']) echo 'disabled="disabled"'; ?>/></td>
		</tr>		
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['email']) echo 'hidden'; ?>">
			<th scope="row"><label for="email">Email Address</label></th>
			<td><input type="text" class="required" value="" id="email" name="email" <?php if(!$wp_mui->settings['visible_fields']['email']) echo 'disabled="disabled"'; ?>/></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['first_name']) echo 'hidden'; ?>">
			<th scope="row"><label for="first_name" class="form-required">First Name </label></th>
			<td><input type="text" class="required" value="" id="first_name" name="first_name" <?php if(!$wp_mui->settings['visible_fields']['first_name']) echo 'disabled="disabled"'; ?> /></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['middle_initial']) echo 'hidden'; ?>">
			<th scope="row"><label for="middle_initial">Middle Initial </label></th>
			<td><input type="text" class="" value="" id="middle_initial" name="middle_initial" <?php if(!$wp_mui->settings['visible_fields']['middle_initial']) echo 'disabled="disabled"'; ?> /></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['last_name']) echo 'hidden'; ?>">
			<th scope="row"><label for="last_name">Last Name </label></th>
			<td><input type="text" class="required" value="" id="last_name" name="last_name" <?php if(!$wp_mui->settings['visible_fields']['last_name']) echo 'disabled="disabled"'; ?> /></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['streetaddress']) echo 'hidden'; ?>">
			<th scope="row"><label for="streetaddress">Street Address</label></th>
			<td><input type="text" class="required" value="" id="streetaddress" name="streetaddress" <?php if(!$wp_mui->settings['visible_fields']['streetaddress']) echo 'disabled="disabled"'; ?> /></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['city']) echo 'hidden'; ?>">
			<th scope="row"><label for="city">City</label></th>
			<td><input type="text" class="required" value="" id="city" name="city" <?php if(!$wp_mui->settings['visible_fields']['city']) echo 'disabled="disabled"'; ?>/></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['state']) echo 'hidden'; ?>">
			<th scope="row"><label for="state">State</label></th>
			<td><input type="text" class="required" value="" id="state" name="state" <?php if(!$wp_mui->settings['visible_fields']['state']) echo 'disabled="disabled"'; ?>/></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['zip']) echo 'hidden'; ?>">
			<th scope="row"><label for="zip">Zip</label></th>
			<td><input type="text" class="required" value="" id="zip" name="zip" <?php if(!$wp_mui->settings['visible_fields']['zip']) echo 'disabled="disabled"'; ?>/></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['phonenumber']) echo 'hidden'; ?>">
			<th scope="row"><label for="phonenumber">Phone Number <span class="description">(555-555-5555)</span></label></th>
			<td><input type="text" class="required" value="" id="phonenumber" name="phonenumber" <?php if(!$wp_mui->settings['visible_fields']['phonenumber']) echo 'disabled="disabled"'; ?>/></td>
		</tr>
		<tr class="form-field <?php if($wp_mui->settings['autocreate_pass']) echo 'hidden'; ?>">
			<th scope="row"><label for="pass1">Password <span class="description">(twice)</span></label></th>
			<td><input type="password" class="required" autocomplete="off" id="pass1" name="pass1" <?php if($wp_mui->settings['autocreate_pass']) echo 'disabled="disabled"'; ?> /></td>
		</tr>
		<tr class="form-field <?php if($wp_mui->settings['autocreate_pass']) echo 'hidden'; ?>">
			<th scope="row"></th>
			<td><input type="password" class="required" autocomplete="off" id="pass2" name="pass2" <?php if($wp_mui->settings['autocreate_pass']) echo 'disabled="disabled"'; ?> /></td>
		</tr>
		<tr class="form-field <?php if($wp_mui->settings['autocreate_pass']) echo 'hidden'; ?>" id="password_help">
			<th scope="row"></th>
			<td><p class="description indicator-hint">Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ & ).</p></td>
		</tr>
		<tr class="form-field <?php if(!$wp_mui->settings['visible_fields']['role']) echo 'hidden'; ?>">
			<th scope="row"><label for="role">Role</label></th>
			<td><select name="role" id="role" <?php if(!$wp_mui->settings['visible_fields']['role']) echo 'disabled="disabled"'; ?>>
				<?php
				if ( !$new_user_role )
					$new_user_role = !empty($current_role) ? $current_role : get_option('default_role');
				wp_dropdown_roles($new_user_role);
				?>
				</select>
			</td>
		</tr>
		<tr class="wp_mui_send_notification form-field <?php if(!$wp_mui->settings['visible_fields']['email']) echo 'hidden'; ?>">
			<th>Send Password?</th>
			<td>
			<input style="width: 20px;" type="checkbox" id="send_user_notification" name="send_user_notification" /><label for="send_user_notification">Send this password to the new user by email.</label>
			
			</td>
		</tr>
		
	</table>
	<p class="submit">
		<input type="submit" value="Add User" class="button-primary" id="wp_mui_adduser_btn" name="wp_mui_adduser_btn"/>
	</p>
	</form>
</div>

<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($) 
	{
		//Prepend our help areas
		$("#screen-options-link-wrap").appendTo("#screen-meta-links").removeClass("hidden");
		$("#screen-options-wrap").prependTo("#screen-meta");
		
		//Phone number check
		jQuery.validator.addMethod("wp_mui_phone", function(value, element) { 
			return this.optional(element) || /^\d\d\d-\d\d\d-\d\d\d\d$/.test(value); 
		}, "This phone number must be in the following format: 555-555-5555");
		
		//Do the input mask
		$("#phonenumber").mask("999-999-9999");
		$("#zip").mask("99999");
		$("#state").mask("aa");
		
		//Setup Validation
		$("#wp_mui_adduser").validate({
			//debug: true,
			onkeyup: false,
			errorClass: "mui_error",
			errorElement: "div",
			rules: {
				user_login: { 
					remote: {
						url: ajaxurl,
						type: "post",
						data: {
							action: 'wp_mui_validate_user', 
							user_login: function(){return $("#user_login").val();}, 
							rand: Math.random(),
							_wpnonce: function(){return $("#_wpnonce").val();},
							_wp_http_referer: function(){return $("#_wp_http_referer").val();}
						}
					}
				},
				phonenumber: { wp_mui_phone: true },
				state: { minlength: 2, maxlength: 2},
				zip: { digits: true },
				pass1: { minlength: 7 },
				pass2: { equalTo: "#pass1" }
			},
			highlight: function(element, errorClass) {
				$(element).parent().parent().addClass("form-invalid");
			},
			unhighlight: function(element, errorClass) {
				$(element).parent().parent().removeClass("form-invalid");
			},
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					url: ajaxurl,
					dataType: 'json',
					success: function(data){
						if(data['result'] == "success")
						{
							$("#wp_mui_result").removeClass('error').addClass('updated').html("<p>Successfully addded a user with an ID of " + data['user_id'] + " and a username of " + data['user_login'] + ".</p>");
							
							// Reset all form but email notification checkbox
							if($("#send_user_notification").is(":checked")) {
								var send_user_notification = 'checked';
							}
							
							$("#wp_mui_adduser").resetForm();
							
							// Check box back if it was checked
							if(send_user_notification == 'checked') {
							
								$("#send_user_notification").attr("checked", true);
							}
							
							
							$(":text:visible:enabled:first").focus();
						}
						else if(data['result'] == 'nonunique_username')
						{
							$("#wp_mui_result").removeClass('updated').addClass('error').html("<p>Oops! Someone has taken that username, please change and resubmit.</p>");
							$("#user_login").focus();
						}
						else if(data['result'] == "warning") {
								$("#wp_mui_result").removeClass('updated').addClass('error').html("<p>"+data['message']+"</p>");				
						}						
						else
						{
							$("#wp_mui_result").removeClass('updated').addClass('error').html("<p>Please contact your administrator. An unknown error has occured.</p>");
						}
					}
				});
				return false;
			}
		});
		
		//Setup the options events
		$("#adv-settings input[type='checkbox']").change(function(){
			jQuery.post(
				ajaxurl, 
				{
					action: 'wp_mui_update_option', 
					option_name: $(this).attr('name'),
					rand: Math.random(),
					_wpnonce: function(){return $("#_wpnonce").val();},
					_wp_http_referer: function(){return $("#_wp_http_referer").val();}
				},
				function(data){
					if(data['result'] == "success")
					{
						var x = data['option_name'];
						var new_value = data['new_value'];
						//Check dependencies
						if(x == "autocreate_user" && new_value == 1)
						{
							//Check to make sure that the 'first_name' and 'last_name' are checked
							if(!$("#vf_first_name").is(':checked'))	$("#vf_first_name").attr("checked", "checked").change();
							else if(!$("#vf_last_name").is(':checked'))	$("#vf_last_name").attr("checked", "checked").change();
						}
						//Do this for a fix
						if(((x == "first_name" || x == "last_name") && new_value == 1) && $("#vf_autocreate_user").is(":checked"))
						{
							if(x == "first_name")
								if(!$("#vf_last_name").is(':checked'))	$("#vf_last_name").attr("checked", "checked").change();
							else
								if(!$("#vf_first_name").is(':checked'))	$("#vf_first_name").attr("checked", "checked").change();
						}
						if((x == "first_name" || x == "last_name") && new_value == 0)
						{
							//Turn off the auto create if it is on
							if($("#vf_autocreate_user").is(':checked')) $("#vf_autocreate_user").removeAttr("checked").change();
						}
						
						if(x == "email") {
							if(new_value == 0)
								$(".wp_mui_send_notification").hide();
							
							if(new_value == 1) {
								$(".wp_mui_send_notification").show();
								// Highlight newly appeared selection
								$(".wp_mui_send_notification").animate({ backgroundColor: "#FFF79F" }, 500).animate({ backgroundColor: "#F9F9F9" }, 500);
								$(".wp_mui_send_notification").css('background', '');								
								}
						}
						
						//Now we switch visibility and we also remove the disabled attribute
						if(x == "autocreate_pass")
						{
							if(new_value == 0)
							{
								$("#pass1").removeAttr("disabled").parent().parent().removeClass("hidden");
								$("#pass2").removeAttr("disabled").parent().parent().removeClass("hidden");
								$("#password_help").removeClass("hidden");
							}
							else
							{
								$("#pass1").attr("disabled", "disabled").parent().parent().addClass("hidden");
								$("#pass2").attr("disabled", "disabled").parent().parent().addClass("hidden");
								$("#password_help").addClass("hidden");							
							}
						}
						else if(x == "autocreate_user")
						{
							if(new_value == 0)
								$("#user_login").removeAttr("disabled").parent().parent().removeClass("hidden");
							else
								$("#user_login").attr("disabled", "disabled").parent().parent().addClass("hidden");
						}
						else
						{
						
							if(new_value == 0)  {
								$("#" + x).attr("disabled", "disabled").parent().parent().addClass("hidden"); 
						
								}
							else {
								$("#" + x).removeAttr("disabled").parent().parent().removeClass("hidden");
							}
							
;
					
						}
						// Highlight newly appeared selection
						$("#" + x).parents('tr').animate({ backgroundColor: "#FFF79F" }, 500).animate({ backgroundColor: "#F9F9F9" }, 500);
						$("#" + x).parents('tr').css('background', '');
					}
					else
						$("#wp_mui_result").removeClass('updated').addClass('error').html("<p>Please contact your administrator. An unknown error has occured.</p>");
				},
				'json'
			);
		});
	});
</script>