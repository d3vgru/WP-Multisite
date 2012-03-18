<?php
/**
* Authorize.Net® Cancellation Form handler ( inner processing routines ).
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
* @package s2Member\AuthNet
* @since 1.5
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_authnet_cancellation_in"))
	{
		/**
		* Authorize.Net® Cancellation Form handler ( inner processing routines ).
		*
		* @package s2Member\AuthNet
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_authnet_cancellation_in
			{
				/**
				* Handles processing of Pro Form cancellations.
				*
				* @package s2Member\AuthNet
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after a custom URL redirection.
				*/
				public static function authnet_cancellation ()
					{
						if (!empty ($_POST["s2member_pro_authnet_cancellation"]["nonce"]) && ($nonce = $_POST["s2member_pro_authnet_cancellation"]["nonce"]) && wp_verify_nonce ($nonce, "s2member-pro-authnet-cancellation"))
							{
								$GLOBALS["ws_plugin__s2member_pro_authnet_cancellation_response"] = array (); /* This holds the global response details. */
								$global_response = &$GLOBALS["ws_plugin__s2member_pro_authnet_cancellation_response"]; /* This is a shorter reference. */
								/**/
								$post_vars = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST["s2member_pro_authnet_cancellation"]));
								$post_vars["attr"] = unserialize (c_ws_plugin__s2member_utils_encryption::decrypt ($post_vars["attr"])); /* And run a Filter. */
								$post_vars["attr"] = apply_filters ("ws_plugin__s2member_pro_authnet_cancellation_post_attr", $post_vars["attr"], get_defined_vars ());
								/**/
								$post_vars["recaptcha_challenge_field"] = (!$post_vars["recaptcha_challenge_field"]) ? trim (stripslashes ($_POST["recaptcha_challenge_field"])) : $post_vars["recaptcha_challenge_field"];
								$post_vars["recaptcha_response_field"] = (!$post_vars["recaptcha_response_field"]) ? trim (stripslashes ($_POST["recaptcha_response_field"])) : $post_vars["recaptcha_response_field"];
								/**/
								if (!c_ws_plugin__s2member_pro_authnet_responses::authnet_form_attr_validation_errors ($post_vars["attr"])) /* Must NOT have any attr errors. */
									{
										if (!($error = c_ws_plugin__s2member_pro_authnet_responses::authnet_form_submission_validation_errors ("cancellation", $post_vars)))
											{
												if (is_user_logged_in () && is_object ($user = wp_get_current_user ()) && ($user_id = $user->ID)) /* Are they logged in? */
													{
														if (($authnet = array ("x_method" => "status")) && ($authnet["x_subscription_id"] = $cur__subscr_id = get_user_option ("s2member_subscr_id")))
															{
																if (($authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_arb_response ($authnet)) && empty ($authnet["__error"]) && $authnet["subscription_status"])
																	{
																		if (preg_match ("/^(active|suspended)$/i", $authnet["subscription_status"])) /* Still active/suspended? */
																			{
																				if (!($ipn = array ())) /* With Authorize.Net®, we need their IPN signup vars. */
																					if (is_array ($ipn_signup_vars = c_ws_plugin__s2member_utils_users::get_user_ipn_signup_vars ()))
																						{
																							$ipn["txn_type"] = "subscr_cancel";
																							$ipn["subscr_id"] = $ipn_signup_vars["subscr_id"];
																							$ipn["custom"] = $ipn_signup_vars["custom"];
																							/**/
																							$ipn["period1"] = $ipn_signup_vars["period1"];
																							$ipn["period3"] = $ipn_signup_vars["period3"];
																							/**/
																							$ipn["payer_email"] = $ipn_signup_vars["payer_email"];
																							$ipn["first_name"] = $ipn_signup_vars["first_name"];
																							$ipn["last_name"] = $ipn_signup_vars["last_name"];
																							/**/
																							$ipn["option_name1"] = $ipn_signup_vars["option_name1"];
																							$ipn["option_selection1"] = $ipn_signup_vars["option_selection1"];
																							/**/
																							$ipn["option_name2"] = $ipn_signup_vars["option_name2"];
																							$ipn["option_selection2"] = $ipn_signup_vars["option_selection2"];
																							/**/
																							$ipn["item_name"] = $ipn_signup_vars["item_name"];
																							$ipn["item_number"] = $ipn_signup_vars["item_number"];
																							/**/
																							$ipn_q = "&s2member_paypal_proxy=authnet&s2member_paypal_proxy_use=pro-emails";
																							$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																							/**/
																							c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																						}
																				/**/
																				if (($authnet = array ("x_method" => "cancel", "x_subscription_id" => $cur__subscr_id)))
																					{
																						c_ws_plugin__s2member_pro_authnet_utilities::authnet_arb_response ($authnet);
																						/**/
																						$global_response = array ("response" => _x ('<strong>Billing termination confirmed.</strong> Your account has been cancelled.', "s2member-front", "s2member"));
																						/**/
																						if ($post_vars["attr"]["success"] && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $post_vars["attr"]["success"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																							wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																					}
																			}
																		else /* Else, account already terminated. */
																			{
																				$global_response = array ("response" => _x ('<strong>Billing terminated.</strong> Your account has been cancelled.', "s2member-front", "s2member"));
																				/**/
																				if ($post_vars["attr"]["success"] && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $post_vars["attr"]["success"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																					wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																			}
																	}
																else /* Else, there is no Billing Profile. */
																	{
																		$global_response = array ("response" => _x ('<strong>Billing terminated.</strong> Your account has been cancelled.', "s2member-front", "s2member"));
																		/**/
																		if ($post_vars["attr"]["success"] && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $post_vars["attr"]["success"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																			wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																	}
															}
														else /* Else, there is no Billing Profile. */
															{
																$global_response = array ("response" => _x ('<strong>Billing terminated.</strong> Your account has been cancelled.', "s2member-front", "s2member"));
																/**/
																if ($post_vars["attr"]["success"] && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $post_vars["attr"]["success"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																	wp_redirect(c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
															}
													}
												else /* Else, an error. Not logged in. */
													{
														$global_response = array ("response" => _x ('You\'re <strong>NOT</strong> logged in.', "s2member-front", "s2member"), "error" => true);
													}
											}
										else /* Else, an error. */
											{
												$global_response = $error;
											}
									}
							}
					}
			}
	}
?>