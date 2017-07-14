<?php
/*
Plugin Name: WP Mass User Input - Add and Export WordPress Users Quickly
Version: 1.3
Description:  WP-MUI (Mass User Entry) allows a WordPress blog administrator to quickly create new users in the WordPress user database. The plugin contains an improved user interface which allows for quick data-entry using only the keyboard.  Additionally, there are functions for automatically creating usernames, passwords, and emailing new users their login information. Lastly, you will be able to export all your users into a CSV file that can include addresses, phone numbers, and more.
Author: Joseph Williams, TwinCitiesTech.com, Inc.
Plugin URI: http://twincitiestech.com/plugins/wordpress-plugin-wp-mui-mass-user-input/
Author URI: http://twincitiestech.com

	Copyright 2010 TwinCitiesTech.com

	This file is part of WP-Directory.

	WP-Directory is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	WP-Directory is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with WP-Directory.  If not, see <http://www.gnu.org/licenses/>.
	
*/

//Require mui files
require_once('core/functions.php');
require_once('core/screens.php');
require_once('core/ajax_handler.php');

//Some initial declarations
define('WP_MUI_VERSION', '1.2');

//Initialize my class and other variables
$wp_mui_debug = true;
$wp_mui = new wp_mui();

class wp_mui
{
	//Declare my class variables here
	var $path;
	var $settings = array(
		'visible_fields' => array('first_name' => 1, 'middle_initial' => 1, 'last_name' => 1, 'streetaddress' => 1, 'city' => 1, 'state' => 1, 'zip' => 1, 'phonenumber' => 1, 'role' => 1),
		'autocreate_user' => 1,
		'autocreate_pass' => 1,
		'rows_per_page' => 20,
		'show_only_wp_mui_users' => 1
	);
	
	//Utility methods
	function the_path() 
	{
		$path =	WP_PLUGIN_URL."/".basename(dirname(__FILE__));
		return $path;
	}
	//The constructor
	function wp_mui()
	{
		//Init my variables
		$this->path = $this->the_path();
		
		//Lets see if we can grab the options
		if($x = get_option('wp_mui_options'))
			$this->settings = $x;
		else
			add_option('wp_mui_options', $this->settings);
		
		//Add my actions
		//Admin Interfaces
		add_action('admin_menu', 'wp_mui_admin_menu');
		add_action('admin_init', 'wp_mui_admin_init');
		
		// Run after profile update to get rid of temporary password if one was set
		add_action('profile_update', 'wp_mui_profile_update');
		//Edit User Pages
		//add_action('edit_user_profile', 'wp_invoice_user_profile_fields');
		//add_action('show_user_profile', 'wp_invoice_user_profile_fields');
		//AJAX Functions
		add_action('wp_ajax_wp_mui_validate_user', 'wp_mui_ajax_validate_user');
		add_action('wp_ajax_wp_mui_add_user', 'wp_mui_add_user');
		add_action('wp_ajax_wp_mui_update_option', 'wp_mui_update_option');
		add_action('wp_ajax_wp_mui_export', 'wp_mui_export');
		
		// Register Settings

	}
	
	//Toggle and save a settting
	function toggle_setting($field, $is_visible_field, $option_value)
	{
		//First, toggle the setting
		if($is_visible_field)
		{
			$x = $this->settings['visible_fields'][$field];
			if($x == 1) $x = 0;
			else $x = 1;
			$this->settings['visible_fields'][$field] = $x;
			$new_value = $x;
		}
		elseif($field == "rows_per_page")
		{
			$this->settings[$field] = $option_value;
			$new_value = $option_value;
		}
		else
		{
			$x = $this->settings[$field];
			if($x == 1) $x = 0;
			else $x = 1;
			$this->settings[$field] = $x;
			$new_value = $x;
		}

		//Save the option
		update_option('wp_mui_options', $this->settings);
		//Return
		return $new_value;
	}
}


?>
