<?php
/**
 * @package My Additional S2 Fields
 * @version 1.0
 */
/*
Plugin Name: My Additional S2 Fields
Plugin URI: http://tech.occupy.net
Description: Adds Bio & Wesbite fields to Registration Process
Version: 1.0
Author URI: http://tech.occupy.net/
*/

add_filter ("ws_plugin__s2member_during_profile_during_fields_display_custom_fields", "my_additional_fields");
function my_additional_fields ()
	{
		global $current_user;
		/**/
		echo '<tr>' . "\n";
		echo '<td>' . "\n";
		echo '<label>' . "\n";
		echo '<strong>Website/URL</strong><br />' . "\n";
		echo '<input type="text" maxlength="100" name="my_additional_fields[user_url]" style="width:99%;" value="' . format_to_edit ($current_user->user_url) . '" />' . "\n";
		echo '</label>' . "\n";
		echo '</td>' . "\n";
		echo '</tr>' . "\n";
		/**/
		echo '<tr>' . "\n";
		echo '<td>' . "\n";
		echo '<label>' . "\n";
		echo '<strong>Bio/Description</strong><br />' . "\n";
		echo '<textarea name="my_additional_fields[description]" style="width:99%;">' . format_to_edit ($current_user->description) . '</textarea>' . "\n";
		echo '</label>' . "\n";
		echo '</td>' . "\n";
		echo '</tr>' . "\n";
		/**/
		return true;
	}
/*
Saves the additional Profile Fields.
*/
add_action ("ws_plugin__s2member_during_handle_profile_modifications", "save_my_additional_fields");
function save_my_additional_fields ($vars = array ())
	{
		$_post = ws_plugin__s2member_trim_deep (stripslashes_deep ($_POST));
		/**/
		$vars["userdata"]["user_url"] = $_post["my_additional_fields"]["user_url"];
		$vars["userdata"]["description"] = $_post["my_additional_fields"]["description"];
		/**/
		wp_update_user ($vars["userdata"]); /* Update. */
	}
?>
