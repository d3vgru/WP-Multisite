<?php
/**
* s2Member Pro Remote Operations API.
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
if (!class_exists ("c_ws_plugin__s2member_pro_remote_ops"))
	{
		/**
		* s2Member Pro Remote Operations API.
		*
		* @package s2Member\API_Remote_Ops
		* @since 110713
		*/
		class c_ws_plugin__s2member_pro_remote_ops
			{
				/**
				* Handles Remote Operation communications.
				*
				* @package s2Member\API_Remote_Ops
				* @since 110713
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution with a serialized array on success, or a string beginning with `Error:` on failure.
				*/
				public static function remote_ops ()
					{
						if (!empty ($_GET["s2member_pro_remote_op"]) && !empty ($_POST["s2member_pro_remote_op"]))
							{
								c_ws_plugin__s2member_no_cache::no_cache_constants (true);
								/**/
								status_header(200); /* Send a 200 OK status header. */
								header("Content-Type: text/plain; charset=utf-8"); /* Content-Type text/plain with UTF-8. */
								eval('while (@ob_end_clean ());'); /* End/clean all output buffers that may or may not exist. */
								/**/
								if (is_array ($op = maybe_unserialize (c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST["s2member_pro_remote_op"])))))
									{
										if (is_array ($op = c_ws_plugin__s2member_utils_strings::trim_deep ($op))) /* Now trim again, in case of serialized array. */
											{
												if (!empty ($op["api_key"]) && $op["api_key"] === c_ws_plugin__s2member_pro_remote_ops::remote_ops_key_gen ())
													{
														if (!empty ($op["op"]) && is_callable ("c_ws_plugin__s2member_pro_remote_ops_in::" . $op["op"]))
															{
																exit(call_user_func ("c_ws_plugin__s2member_pro_remote_ops_in::" . $op["op"], $op));
															}
														else
															exit('Error: $_POST["s2member_pro_remote_op"]["op"] is empty or invalid.');
													}
												else
													exit('Error: $_POST["s2member_pro_remote_op"]["api_key"] is empty or invalid.');
											}
										else
											exit('Error: $_POST["s2member_pro_remote_op"] is NOT a serialized array.');
									}
								else
									exit('Error: $_POST["s2member_pro_remote_op"] is NOT a serialized array.');
							}
					}
				/**
				* Test if this WordPress® instance is a specific Remote Operation.
				*
				* @package s2Member\API_Remote_Ops
				* @since 110713
				*
				* @param str $_op The Remote Operation to test this instance against.
				* @return bool True if instance is the specified Operation, else false.
				*/
				public static function is_remote_op ($_op = FALSE)
					{
						if (!empty ($_GET["s2member_pro_remote_op"]) && !empty ($_POST["s2member_pro_remote_op"]))
							if (is_array ($op = maybe_unserialize (c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST["s2member_pro_remote_op"])))))
								if (is_array ($op = c_ws_plugin__s2member_utils_strings::trim_deep ($op))) /* Now trim again, in case of serialized array. */
									if (!empty ($op["api_key"]) && $op["api_key"] === c_ws_plugin__s2member_pro_remote_ops::remote_ops_key_gen ())
										if (!empty ($op["op"]) && $op["op"] === $_op)
											return true;
						/**/
						return false;
					}
				/**
				* Generates an API Key, for Remote Operations.
				*
				* @package s2Member\API_Remote_Ops
				* @since 110713
				*
				* @return str An API Key. It's an MD5 Hash, 32 chars, URL-safe.
				*/
				public static function remote_ops_key_gen () /* Generate Key. */
					{
						global $current_site, $current_blog; /* Multisite Networking. */
						/**/
						if (is_multisite () && !is_main_site ())
							$key = md5 (c_ws_plugin__s2member_utils_encryption::xencrypt ($current_blog->domain . $current_blog->path, false, false));
						/**/
						else /* Else it's a standard API Key; not on a Multisite Network, or not on the Main Site anyway. */
							$key = md5 (c_ws_plugin__s2member_utils_encryption::xencrypt (preg_replace ("/\:[0-9]+$/", "", $_SERVER["HTTP_HOST"]), false, false));
						/**/
						return (!empty ($key)) ? $key : ""; /* Else an empty string. */
					}
			}
	}
?>