<?php
/**
* s2Member Pro Remote Operations API ( inner processing routines ).
*
* Copyright: © 2009-2011
* {@link http://www.websharks-inc.com/ WebSharks, Inc.}
* ( coded in the USA )
*
* This WordPress® plugin ( s2Member Pro ) is comprised of two parts:
*
* o (1) Its PHP code is licensed under the GPL license, as is WordPress®.
* 	You should have received a copy of the GNU General Public License,
* 	along with this software. In the main directory, see: /licensing/
* 	If not, see: {@link http://www.gnu.org/licenses/}.
*
* o (2) All other parts of ( s2Member Pro ); including, but not limited to:
* 	the CSS code, some JavaScript code, images, and design;
* 	are licensed according to the license purchased.
* 	See: {@link http://www.s2member.com/prices/}
*
* Unless you have our prior written consent, you must NOT directly or indirectly license,
* sub-license, sell, resell, or provide for free; part (2) of the s2Member Pro Module;
* or make an offer to do any of these things. All of these things are strictly
* prohibited with part (2) of the s2Member Pro Module.
*
* Your purchase of s2Member Pro includes free lifetime upgrades via s2Member.com
* ( i.e. new features, bug fixes, updates, improvements ); along with full access
* to our video tutorial library: {@link http://www.s2member.com/videos/}
*
* @package s2Member\API_Remote_Ops
* @since 110713
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_remote_ops_in"))
	{
		/**
		* s2Member Pro Remote Operations API ( inner processing routines ).
		*
		* @package s2Member\API_Remote_Ops
		* @since 110713
		*/
		class c_ws_plugin__s2member_pro_remote_ops_in
			{
				/**
				* Creates a new User.
				*
				* @package s2Member\API_Remote_Ops
				* @since 110713
				*
				* @param array An input array of Remote Operation parameters.
				* @return str Returns a serialized array with an `ID` element object on success,
				* 	else returns a string beginning with `Error:` on failure; which will include details regarding the error.
				*/
				public static function create_user ($op = FALSE)
					{
						if (!empty ($op["op"]) && $op["op"] === "create_user" && !empty ($op["data"]) && is_array ($op["data"]))
							{
								$GLOBALS["ws_plugin__s2member_registration_vars"] = /* Array. */ array ();
								$v = /* Shorter reference. */ &$GLOBALS["ws_plugin__s2member_registration_vars"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_user_login"] = (string)@$op["data"]["user_login"];
								$v["ws_plugin__s2member_custom_reg_field_user_email"] = (string)@$op["data"]["user_email"];
								/**/
								if (empty ($op["data"]["user_pass"]) || !is_string ($op["data"]["user_pass"]))
									$op["data"]["user_pass"] = /* Auto-generate. */ wp_generate_password ();
								$GLOBALS["ws_plugin__s2member_generate_password_return"] = $op["data"]["user_pass"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_first_name"] = (string)@$op["data"]["first_name"];
								$v["ws_plugin__s2member_custom_reg_field_last_name"] = (string)@$op["data"]["last_name"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_s2member_level"] = (string)@$op["data"]["s2member_level"];
								$v["ws_plugin__s2member_custom_reg_field_s2member_ccaps"] = (string)@$op["data"]["s2member_ccaps"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_s2member_registration_ip"] = (string)@$op["data"]["s2member_registration_ip"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_s2member_subscr_gateway"] = (string)@$op["data"]["s2member_subscr_gateway"];
								$v["ws_plugin__s2member_custom_reg_field_s2member_subscr_id"] = (string)@$op["data"]["s2member_subscr_id"];
								$v["ws_plugin__s2member_custom_reg_field_s2member_custom"] = (string)@$op["data"]["s2member_custom"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_s2member_auto_eot_time"] = (string)@$op["data"]["s2member_auto_eot_time"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_s2member_notes"] = (string)@$op["data"]["s2member_notes"];
								/**/
								$v["ws_plugin__s2member_custom_reg_field_opt_in"] = (string)@$op["data"]["opt_in"];
								/**/
								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"])
									foreach (json_decode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
										{
											$field_var = preg_replace ("/[^a-z0-9]/i", "_", strtolower ($field["id"]));
											$field_id_class = preg_replace ("/_/", "-", $field_var);
											/**/
											if (isset /* Has this Registration/Profile Field been set? */ ($op["data"]["custom_fields"][$field_var]))
												$v["ws_plugin__s2member_custom_reg_field_" . $field_var] = $op["data"]["custom_fields"][$field_var];
										}
								/**/
								$create = array ("user_login" => (string)@$op["data"]["user_login"], "user_pass" => (string)@$op["data"]["user_pass"], "user_email" => (string)@$op["data"]["user_email"]);
								/**/
								if (((is_multisite () && ($new = $user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create["user_login"], $create["user_email"], $create["user_pass"]))) || ($new = $user_id = wp_create_user ($create["user_login"], $create["user_pass"], $create["user_email"]))) && !is_wp_error ($new))
									{
										if (is_object ($user = new WP_User ($user_id)) && !empty ($user->ID) && ($user_id = $user->ID))
											{
												if ( /* Send New User Notification? */!empty ($op["data"]["notification"]))
													wp_new_user_notification ($user_id, $op["data"]["user_pass"]);
												/**/
												return serialize /* Serialized array. */ (array ("ID" => $user_id));
											}
										else /* Else the creation of the User account may have failed. */
											return "Error: Creation may have failed. Unable to obtain WP_User ID.";
									}
								else if (is_wp_error ($new) && $new->get_error_code ()) /* Error message? */
									return "Error: " . $new->get_error_message (); /* Return message. */
								/**/
								else /* Else we really don't know why creation failed. Return generic error. */
									return "Error: User creation failed for an unknown reason. Please try again.";
							}
						else /* Empty request, or calling upon wrong Remote Op. */
							return "Error: Empty or invalid request ( `create_user` ). Please try again.";
					}
				/**
				* Modifies an existing User.
				*
				* @package s2Member\API_Remote_Ops
				* @since 110713
				*
				* @param array An input array of Remote Operation parameters.
				* @return str Returns a serialized array with an `ID` element object on success,
				* 	else returns a string beginning with `Error:` on failure; which will include details regarding the error.
				*/
				public static function modify_user ($op = FALSE)
					{
						if (!empty ($op["op"]) && $op["op"] === "modify_user" && !empty ($op["data"]) && is_array ($op["data"]))
							{
								return "Error: Empty or invalid request ( `modify_user` ). Please try again.";
							}
						else /* Empty request, or calling upon wrong Remote Op. */
							return "Error: Empty or invalid request ( `modify_user` ). Please try again.";
					}
				/**
				* Deletes an existing User.
				*
				* @package s2Member\API_Remote_Ops
				* @since 110713
				*
				* @param array An input array of Remote Operation parameters.
				* @return str Returns a serialized array with an `ID` element object on success,
				* 	else returns a string beginning with `Error:` on failure; which will include details regarding the error.
				*/
				public static function delete_user ($op = FALSE)
					{
						if (!empty ($op["op"]) && $op["op"] === "delete_user" && !empty ($op["data"]) && is_array ($op["data"]))
							{
								return "Error: Empty or invalid request ( `delete_user` ). Please try again.";
							}
						else /* Empty request, or calling upon wrong Remote Op. */
							return "Error: Empty or invalid request ( `delete_user` ). Please try again.";
					}
			}
	}
?>