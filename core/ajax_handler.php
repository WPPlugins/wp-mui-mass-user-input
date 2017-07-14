<?php
//This function checks the database for the username typed in
function wp_mui_ajax_validate_user() {
	global $wpdb;
	check_admin_referer('wp_mui_insert_users_page');
	//Check to make sure the username is valid
	if(!validate_username($_POST['user_login']))
	{
		echo json_encode('This username is invalid. Please try another.');
		die();
	}
	//Do the query to get the number of rows
	$user_count = $wpdb->get_var($wpdb->prepare(
		"SELECT COUNT(*) FROM $wpdb->users WHERE user_login = %s;",
		$_POST['user_login']
	));
	echo ($user_count == 0 ? json_encode(true) : json_encode("This username is already taken. Please try another."));
	die();
}
//This function uses ajax to add the user to the DB
function wp_mui_add_user()
{
	global $wpdb, $wp_mui;
	check_admin_referer('wp_mui_insert_users_page');	
	//First, check to make sure the user doesn't exist
	if(!$wp_mui->settings['autocreate_user'] && username_exists($_POST['user_login'])) 
	{
		$res['result'] = 'nonunique_username';
		echo json_encode($res);
		die();
	}
	//Now, check to make sure the username is valid
	if(!$wp_mui->settings['autocreate_user'] && !validate_username($_POST['user_login']))
	{
		$res['result'] = 'invalid_username';
		echo json_encode($res);
		die();
	}

	//Here we generate the username
	if($wp_mui->settings['autocreate_user'])
	{
		//We do this until there is a unique name
		$cont = true;
		$x = 0;
		while($cont)
		{
			//Select from the DB to determine the number of users
			$user_count = $wpdb->get_var($wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->users WHERE user_login LIKE %s;",
				$_POST['first_name'].".".$_POST['last_name']."%"
			));
			
			if($user_count)
			{
				$user_count = $user_count + $x + 1;
				$user_login = $_POST['first_name'].".".$_POST['last_name'].$user_count;
			}
			else
			{
				$user_login = $_POST['first_name'].".".$_POST['last_name'];
			}
			
			//Recheck validity
			if(!username_exists($user_login)) $cont = false;
			else $x++;
		}
	}
	else
		$user_login = $_POST['user_login'];
	//Generate the password if we need to
	if($wp_mui->settings['autocreate_pass'])
		$password = substr(md5(md5(time()) + md5(rand())), 0, 10);
	else
		$password = $_POST['pass1'];

	// Prepare email address if set and passed
	if($wp_mui->settings['visible_fields']['email']) {
		$email = (!empty($_POST['email']) ? $_POST['email'] : false);
		if(email_exists($email)) {
			$res['result'] = 'warning';
			$res['message'] = "Error! Email address '$email' already exists, can not have users with duplicate email addresses.";
			echo json_encode($res);	
			die();
		}
	}
	
 
	//Now add the user to the DB
	if($user_id = wp_create_user($user_login, $password, $email))
	{
		// Send email to user if selected
		
		
		if($_POST['send_user_notification'] == 'on') {
		
			wp_new_user_notification($user_id, $password);
		}
	
		//Add to our final output, and then add all the user meta information
		$res['user_id'] = $user_id;
		if($wp_mui->settings['visible_fields']['role'])
			wp_update_user(array('ID'=>$user_id,'role'=>$_POST['role']));
		if($wp_mui->settings['visible_fields']['first_name'])
			update_usermeta($user_id, 'first_name', $_POST['first_name']);
		if($wp_mui->settings['visible_fields']['last_name'])
			update_usermeta($user_id, 'last_name', $_POST['last_name']);
		if($wp_mui->settings['visible_fields']['streetaddress'])
			update_usermeta($user_id, 'streetaddress', $_POST['streetaddress']);
		if($wp_mui->settings['visible_fields']['city'])
			update_usermeta($user_id, 'city', $_POST['city']);
		if($wp_mui->settings['visible_fields']['state'])
			update_usermeta($user_id, 'state', $_POST['state']);
		if($wp_mui->settings['visible_fields']['zip'])
			update_usermeta($user_id, 'zip', $_POST['zip']);
		if($wp_mui->settings['visible_fields']['phonenumber'])
			update_usermeta($user_id, 'phonenumber', $_POST['phonenumber']);
		if($wp_mui->settings['visible_fields']['middle_initial'])
			update_usermeta($user_id, 'middle_initial', $_POST['middle_initial']);
		update_usermeta($user_id, 'wp_mui_initial_pass', $password);
		$res['user_login'] = $user_login;
		$res['result'] = 'success';
	}
	else
	{
		//An error occurred
		$res['result'] = 'error';
	}
	
	//Return the json encoded message
	echo json_encode($res);	
	die();
}
//This function updates our option being sent
function wp_mui_update_option()
{
	global $wp_mui;
	check_admin_referer('wp_mui_insert_users_page');	
	$is_visible_fields = true;
	$option_value = (isset($_POST['option_value']) ? $_POST['option_value'] : "");
	if($_POST['option_name'] == "show_only_wp_mui_users" || $_POST['option_name'] == "autocreate_user" || $_POST['option_name'] == "autocreate_pass" || $_POST['option_name'] == "rows_per_page") $is_visible_fields = false;
	
	$res['new_value'] = $wp_mui->toggle_setting($_POST['option_name'], $is_visible_fields, $option_value);
	$res['result'] = "success";
	$res['option_name'] = $_POST['option_name'];
	
	echo json_encode($res);
	
	die();
}
//Handles the export for the CSV
function wp_mui_export()
{
	global $wpdb, $wp_mui;
	//Add the headers to the output
	$output = '"Username","Password",';
	if($wp_mui->settings['visible_fields']['first_name']) $output .= '"First Name",';
	if($wp_mui->settings['visible_fields']['middle_initial']) $output .= '"MI",';
	if($wp_mui->settings['visible_fields']['last_name']) $output .= '"Last Name",';
	if($wp_mui->settings['visible_fields']['streetaddress']) $output .= '"Street Address",';
	if($wp_mui->settings['visible_fields']['city']) $output .= '"City",';
	if($wp_mui->settings['visible_fields']['state']) $output .= '"State",';
	if($wp_mui->settings['visible_fields']['zip']) $output .= '"Zip",';
	if($wp_mui->settings['visible_fields']['phonenumber']) $output .= '"Phone Number",';
	if($wp_mui->settings['visible_fields']['role']) $output .= '"Role",';
	//Subtract the final comma and add the return char
	$output = substr($output, 0, strlen($output)-1)."\r\n";
	
	if($wp_mui->settings['show_only_wp_mui_users']) {
		$wp_mui_initial_pass_filter = "WHERE meta_key = 'wp_mui_initial_pass'";
	}	
	
	$data = $wpdb->get_results("SELECT u.ID FROM $wpdb->users AS u WHERE u.ID IN (SELECT user_id FROM $wpdb->usermeta $wp_mui_initial_pass_filter) ORDER BY u.user_login ASC;", ARRAY_A);
	//Update the data with the user informatin we need
	foreach($data as $row)
	{
		$x = array();
		//Get the user info
		$user = get_userdata( $row['ID'] );
		//Determine the role info
		$roles = array_keys($user->{$wpdb->prefix.'capabilities'});
		$role = $roles[0];
		//Save the array to $x
		$x['user_login'] = $user->user_login;
		$x['wp_mui_initial_pass'] = $user->wp_mui_initial_pass;
		if($wp_mui->settings['visible_fields']['first_name']) $x['first_name'] = $user->first_name;
		if($wp_mui->settings['visible_fields']['middle_initial']) $x['middle_initial'] = $user->middle_initial;
		if($wp_mui->settings['visible_fields']['last_name']) $x['last_name'] = $user->last_name;
		if($wp_mui->settings['visible_fields']['streetaddress']) $x['streetaddress'] = $user->streetaddress;
		if($wp_mui->settings['visible_fields']['city']) $x['city'] = $user->city;
		if($wp_mui->settings['visible_fields']['state']) $x['state'] = $user->state;
		if($wp_mui->settings['visible_fields']['zip']) $x['zip'] = $user->zip;
		if($wp_mui->settings['visible_fields']['phonenumber']) $x['phonenumber'] = $user->phonenumber;
		if($wp_mui->settings['visible_fields']['role']) $x['role'] = $role;
		//Add to the CSV output
		$output .= arr_to_csv(array(0=>$x))."\r\n";
	}
	//Print the output with headers
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"wp_mui_export.csv\"");
	print $output;
	die();
}
?>