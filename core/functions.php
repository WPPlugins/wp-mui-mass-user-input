<?php
//CSV functions
function arr_to_csv_line($arr) 
{
	$line = array();
	foreach ($arr as $v) 
	{
		$line[] = is_array($v) ? arr_to_csv_line($v) : '"' . str_replace('"', '""', $v) . '"';
	}
	return implode(",", $line);
}
function arr_to_csv($arr) 
{
	$lines = array();
	foreach ($arr as $key => $v) 
	{
		$lines[] = arr_to_csv_line($v);
	}
	return implode("\n", $lines);
}
//End CSV functions

/*
	Run after profile update.
	Will delete temporary password if one was set, and a new password is now generated.
*/
function wp_mui_profile_update($user_id) {
	
	// make sure passwords are updated and set
	if(empty($_POST['pass1']))
		return;
		
		
	if($_POST['pass1'] != $_POST['pass2'])
		return;
	
	// Get initial password (if none set, empty return)
	$wp_mui_initial_pass = get_option($user_id,'wp_mui_initial_pass');
	
	// Delete initial pass if not an empty return
	if(!empty($wp_mui_initial_pass))
		delete_usermeta($user_id, 'wp_mui_initial_pass');

}