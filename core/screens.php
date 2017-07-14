<?php

// Draws setting field on General Options field
function wp_mui_draw_user_level_selection() {
	
	$use_level = get_option('wp_mui_user_level_selection');
	if(!$use_level)
		$use_level = 'level_10';
		
	?>
	<select id="wp_mui_user_level_selection" name="wp_mui_user_level_selection">
		<option value="level_10" <?php if($use_level == 'level_10') echo 'selected="selected"'; ?>>Administrators</option>
		<option value="level_7" <?php if($use_level == 'level_7') echo 'selected="selected"'; ?>>Editors</option>
		<option value="level_2" <?php if($use_level == 'level_2') echo 'selected="selected"'; ?>>Authors</option>
	</select>
	
	<span class="description">Minimum user level required for adding and exporting users via the WP-MUI plugin.</span>
	<?php
	
}
//Initializes and registers our javascript libs we will use
function wp_mui_admin_init()
{
	wp_register_script('jquery.validate', WP_PLUGIN_URL.'/wp-mui-mass-user-input/core/js/jquery.validate.min.js');
	wp_register_script('jquery.form', WP_PLUGIN_URL.'/wp-mui-mass-user-input/core/js/jquery.form.js');
	wp_register_script('jquery.maskedinput', WP_PLUGIN_URL.'/wp-mui-mass-user-input/core/js/jquery.maskedinput.js');

	add_settings_field('wp_mui_user_level_selection', 'WP MUI User Level', 'wp_mui_draw_user_level_selection', 'general', 'default');
	register_setting('general', 'wp_mui_user_level_selection'); 
}

//Adds the administrative menu to the 'users' tab
function wp_mui_admin_menu()
{
	$use_level = get_option('wp_mui_user_level_selection');
	if(!$use_level)
		$use_level = 'edit_users';
  	
	//Add the submenu item for users
	$input_users_page = add_submenu_page('users.php', 'Mass User Input', 'Mass User Input', $use_level,'wp_mui_insert_users_page', 'wp_mui_insert_users_page');
	add_action('admin_print_scripts-'.$input_users_page, 'wp_mui_admin_includes');
}
//Includes any styles or JS we will be using
function wp_mui_admin_includes()
{
	wp_enqueue_script('jquery.validate');
	wp_enqueue_script('jquery.form');
	wp_enqueue_script('jquery.maskedinput');
}
//Display the actual page
function wp_mui_insert_users_page()
{
	global $wp_mui, $wpdb;
	if(!isset($_GET['wp_mui_page'])) require_once('screens/input_users.php');
	elseif($_GET['wp_mui_page'] == "users_list")
	{
		//First determine if there is a search that needs to be done
		$search = "";
		$usersearch = "";
		if(isset($_GET['usersearch']) && $_GET['usersearch'] != "")
		{
			$usersearch = $_GET['usersearch'];
			$t = $wpdb->escape($usersearch);
			$search = " u.user_login LIKE '%$t%' AND ";
		}
		
		if($wp_mui->settings['show_only_wp_mui_users']) {
			$wp_mui_initial_pass_filter = "WHERE meta_key = 'wp_mui_initial_pass'";
		}
		
		//Do the pagination
		$pageno = (isset($_GET['pageno']) ? $_GET['pageno'] : 1);
		$numrows = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->users AS u WHERE$search u.ID IN (SELECT user_id FROM $wpdb->usermeta $wp_mui_initial_pass_filter) ORDER BY u.user_login ASC;");
		$lastpage = ceil($numrows/$wp_mui->settings['rows_per_page']);
		$pageno = (int)$pageno;
		if($pageno > $lastpage) $pageno = $lastpage;
		elseif($pageno < 1) $pageno = 1;
		$limit = 'LIMIT '.($pageno - 1) * $wp_mui->settings['rows_per_page'].','.$wp_mui->settings['rows_per_page'];
		
		// Determine if pagination is necessary
		if($numrows > $wp_mui->settings['rows_per_page'])
			$pagination_necessary = true;
		
		//Processing for the users_list page
		$data = $wpdb->get_results("SELECT u.ID FROM $wpdb->users AS u WHERE$search u.ID IN (SELECT user_id FROM $wpdb->usermeta $wp_mui_initial_pass_filter) $limit;", ARRAY_A);
		
		//Require the users_list page
		require_once('screens/users_list.php');
	}
}
?>