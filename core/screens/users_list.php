<?php //print $numrows."::".$pageno."::".$lastpage; ?>
<? //This stuff is hidden, but the jQuery moves it to the top ?>
<div class="hidden hide-if-no-js screen-meta-toggle" id="screen-options-link-wrap">
	<a class="show-settings" id="show-settings-link" href="#screen-options">Screen Options</a>
</div>
<div class="hidden" id="screen-options-wrap">
	<form method="post" action="" id="adv-settings">
		<h5>Options</h5>
		<div class="metabox-prefs">
			<?=wp_nonce_field('wp_mui_insert_users_page')?>
			<label for="rows_per_page">Rows Per Page</label><input type="text" value="<?=$wp_mui->settings['rows_per_page']?>" id="rows_per_page" name="rows_per_page" /><br />
			<input type="checkbox" value="true" id="show_only_wp_mui_users" name="show_only_wp_mui_users" <?php if($wp_mui->settings['show_only_wp_mui_users']) echo "CHECKED"; ?> /> <label for="show_only_wp_mui_users">Show only users entered using WP-MUI</label>
		</div>
	</form>
</div>
<? // End hidden ?>

<div class="wrap">
	<div class="icon32" id="icon-users"><br/></div>
	<h2>Previously Added Users <a class="button add-new-h2" href="users.php?page=wp_mui_insert_users_page">Back to User Entry</a></h2>
	
	<?php if($numrows == 0 && $usersearch == ""){ ?>
	<div id="wp_mui_result" class="error">There have been no users entered at this time.</div>
	<?php } else { ?>
	
	<?php if($numrows == 0 && $usersearch != ""){ ?>
	<div id="wp_mui_result" class="error">There have been no users entered at this time with the following characters: '<?=$usersearch?>'. <a href="#" id="clear_filter">Clear Filter</a></div>
	<?php } else { ?>
	<div id="wp_mui_result"></div>
	
	<p class="search-box">
		<input type="text" value="<?=$usersearch?>" name="usersearch" id="usersearch"/>
		<input type="submit" class="button" value="Filter By Username" name="usersearch_btn" id="usersearch_btn" />
	</p>

	<div class="tablenav">
		<div class="alignleft actions">
			
			<input type="submit" class="button-secondary" id="export_btn" name="export_btn" value="Export All to CSV"/>
			<?php if($usersearch != ""){ ?>
				<input type="submit" class="button-secondary" id="clear_filter" name="clear_filter" value="Clear Filter"/>
			<?php } ?>
		</div>
		<br class="clear"/>
	</div>
	
	<?php if($numrows != 0) { ?>
	<table cellspacing="0" class="widefat" style="width:100%;">
		<thead>
			<tr class="thead">
				<th style="" class="" id="user_login" scope="col">Username</th>
				<th style="" class="manage-column" id="password" scope="col">Temporary Password</th>
				<?php if($wp_mui->settings['visible_fields']['first_name']){?><th style="" class="manage-column" id="first_name" scope="col">First Name</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['middle_initial']){?><th style="" class="manage-column" id="middle_initial" scope="col">MI</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['last_name']){?><th style="" class="manage-column" id="last_name" scope="col">Last Name</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['streetaddress']){?><th style="" class="manage-column" id="streetaddress" scope="col">Address</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['city']){?><th style="" class="manage-column" id="city" scope="col">City</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['state']){?><th style="" class="manage-column" id="state" scope="col">State</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['zip']){?><th style="" class="manage-column" id="zip" scope="col">Zip</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['phonenumber']){?><th style="" class="manage-column" id="phonenumber" scope="col">Phone</th><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['role']){?><th style="" class="manage-column" id="role" scope="col">Role</th><?php } ?>
			</tr>
		</thead>

		<tbody class="list:user user-list" id="users">
			<?php 
				$class = 'alternate';
				foreach($data as $row){
					$class = ($class == 'alternate' ? $class = "" : $class = "alternate");
					//Get the user info
					$user = get_userdata( $row['ID'] );
					//Determine the role info
					$roles = array_keys($user->{$wpdb->prefix.'capabilities'});
					$role = $roles[0];
			?>
			<tr class="<?=$class?>" id="user-<?=$user->ID?>">
				<td><strong><a href="user-edit.php?user_id=<?=$user->ID?>"><?=$user->user_login?></a></strong></td>
				<td><?=$user->wp_mui_initial_pass?></td>
				<?php if($wp_mui->settings['visible_fields']['first_name']){?><td><?=$user->first_name?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['middle_initial']){?><td><?=$user->middle_initial?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['last_name']){?><td><?=$user->last_name?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['streetaddress']){?><td><?=$user->streetaddress?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['city']){?><td><?=$user->city?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['state']){?><td><?=$user->state?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['zip']){?><td><?=$user->zip?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['phonenumber']){?><td><?=$user->phonenumber?></td><?php } ?>
				<?php if($wp_mui->settings['visible_fields']['role']){?><td><?=$role?></td><?php } ?>
			</tr>
			<?php } //end foreach ?>
		</tbody>
	</table>
	
	<table style="width:100%;">
		<thead>
			<tr class="thead">
				<th>
				<div class="alignleft" style="color: #8F8F8F; font-weight: normal;">
				<?php if($wp_mui->settings['show_only_wp_mui_users']) { ?>
				Displaying only users added with WP-MUI.
				<?php } else { ?>
				Displaying all users.
				<?php } ?>
				</div>
				<div class="alignright actions" style="font-weight: normal;">
					<?php if($pagination_necessary) { ?>
					Choose Page
					<select name="choose_page" id="choose_page">
						<?php
							for($x2 = 1;$x2<=$lastpage;$x2++)
								echo "<option ".($x2 == $pageno ? 'selected="selected"' : '').">$x2</option>";
						?>
					</select>
					<?php $link = ($pageno != 1 ? 'javascript:change_page("users.php?page=wp_mui_insert_users_page&wp_mui_page=users_list&pageno='.($pageno-1).($usersearch != "" ? "&usersearch=$usersearch" : "").'")' : ''); ?>
					<input type="button" class="button-secondary action" onclick='<?=$link?>' value="&lt;" />
					<?php $link = ($pageno != $lastpage ? 'javascript:change_page("users.php?page=wp_mui_insert_users_page&wp_mui_page=users_list&pageno='.($pageno+1).($usersearch != "" ? "&usersearch=$usersearch" : "").'")' : ''); ?>
					<input type="button" class="button-secondary action" onclick='<?=$link?>' value="&gt;" />
					<?php } ?>
				</th>
			</tr>
		</thead>
	</table>
	<?php } ?>
	<?php } ?>
	<?php } ?>
</div>

<script type="text/javascript" language="javascript">
	//Functino to change the link
	function change_page(url){ window.location = url; }
	jQuery(document).ready(function($) 
	{	
		//Attach our button to the clear filter
		$("#clear_filter").click(function(){
			change_page("users.php?page=wp_mui_insert_users_page&wp_mui_page=users_list");
		});
		//Attach our button to the 'export'
		$("#export_btn").click(function(){
			window.location = ajaxurl + "?action=wp_mui_export";
		});
		//Attach our event to the drop box for the pages
		$("#choose_page").change(function(){
			change_page("users.php?page=wp_mui_insert_users_page&wp_mui_page=users_list<?=($usersearch != "" ? "&usersearch=$usersearch" : "")?>&pageno=" + $(this).val());
		});
		//Attach our event to the usersearch for the pages and textbox
		$("#usersearch_btn").click(function(){
			change_page("users.php?page=wp_mui_insert_users_page&wp_mui_page=users_list&usersearch=" + $("#usersearch").val());
		});
		$('#usersearch').keyup(function(e) { 
			if(e.keyCode == 13)
				change_page("users.php?page=wp_mui_insert_users_page&wp_mui_page=users_list&usersearch=" + $("#usersearch").val());
		});
		//Prepend our help areas
		$("#screen-options-link-wrap").appendTo("#screen-meta-links").removeClass("hidden");
		$("#screen-options-wrap").prependTo("#screen-meta");
 	
		
		$("#adv-settings input").change(function(){
 
			var value = 'unknown';
			
			if($(this).is(":text")) {
				var value = $(this).val();
			}
		
			if($(this).is(":checkbox")) {
				if($(this).is(":checked")) {
					var value = 'on';
				} else {
					var value = 'off';
				}
			}
				
			
			jQuery.post(
				ajaxurl, 
				{
					action: 'wp_mui_update_option', 
					option_name: $(this).attr('name'),
					option_value: value,
					rand: Math.random(),
					_wpnonce: function(){return $("#_wpnonce").val();},
					_wp_http_referer: function(){return $("#_wp_http_referer").val();}
				},
				function(data){
					if(data['result'] == "success")	
						$("#wp_mui_result").removeClass('error').addClass('updated').html("<p>Display settings updated, please <a href='javascript:window.location.reload()'>refresh</a> the page.</p>");
					else
						$("#wp_mui_result").removeClass('updated').addClass('error').html("<p>Please contact your administrator. An unknown error has occured.</p>");
				},'json'
			)
		});
	});
</script>