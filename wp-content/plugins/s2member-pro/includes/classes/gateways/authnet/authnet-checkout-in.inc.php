<?php
/**
* Authorize.Net® Checkout Form handler ( inner processing routines ).
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
	exit ("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_authnet_checkout_in"))
	{
		/**
		* Authorize.Net® Checkout Form handler ( inner processing routines ).
		*
		* @package s2Member\AuthNet
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_authnet_checkout_in
			{
				/**
				* Handles processing of Pro Form checkouts.
				*
				* @package s2Member\AuthNet
				* @since 1.5
				*
				* @attaches-to ``add_action("init");``
				*
				* @return null Or exits script execution after a custom URL redirection.
				*
				* @todo Add support for `rrt=""` Attribute.
				* @todo Build in routine to void first payment if Recurring Profile creation fails for some reason? Seems logical, even though highly unlikely.
				*/
				public static function authnet_checkout ()
					{
						if (!empty ($_POST["s2member_pro_authnet_checkout"]["nonce"]) && ($nonce = $_POST["s2member_pro_authnet_checkout"]["nonce"]) && wp_verify_nonce ($nonce, "s2member-pro-authnet-checkout"))
							{
								$GLOBALS["ws_plugin__s2member_pro_authnet_checkout_response"] = array (); /* This holds the global response details. */
								$global_response = &$GLOBALS["ws_plugin__s2member_pro_authnet_checkout_response"]; /* This is a shorter reference. */
								/**/
								$post_vars = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST["s2member_pro_authnet_checkout"]));
								$post_vars["attr"] = unserialize (c_ws_plugin__s2member_utils_encryption::decrypt ($post_vars["attr"])); /* And Filter. */
								$post_vars["attr"] = apply_filters ("ws_plugin__s2member_pro_authnet_checkout_post_attr", $post_vars["attr"], get_defined_vars ());
								/**/
								$post_vars["recaptcha_challenge_field"] = (!$post_vars["recaptcha_challenge_field"]) ? trim (stripslashes ($_POST["recaptcha_challenge_field"])) : $post_vars["recaptcha_challenge_field"];
								$post_vars["recaptcha_response_field"] = (!$post_vars["recaptcha_response_field"]) ? trim (stripslashes ($_POST["recaptcha_response_field"])) : $post_vars["recaptcha_response_field"];
								/**/
								$post_vars["name"] = trim ($post_vars["first_name"] . " " . $post_vars["last_name"]);
								$post_vars["email"] = apply_filters ("user_registration_email", sanitize_email ($post_vars["email"]), get_defined_vars ());
								$post_vars["username"] = preg_replace ("/\s+/", "", sanitize_user ($post_vars["username"], is_multisite ()));
								/**/
								if (!c_ws_plugin__s2member_pro_authnet_responses::authnet_form_attr_validation_errors ($post_vars["attr"])) /* Attr errors? */
									{
										if (!($error = c_ws_plugin__s2member_pro_authnet_responses::authnet_form_submission_validation_errors ("checkout", $post_vars)))
											{
												$cp_attr = c_ws_plugin__s2member_pro_authnet_utilities::authnet_apply_coupon ($post_vars["attr"], $post_vars["coupon"], "attr", array ("affiliates-silent-post"));
												$cost_calculations = c_ws_plugin__s2member_pro_authnet_utilities::authnet_cost ($cp_attr["ta"], $cp_attr["ra"], $post_vars["state"], $post_vars["country"], $post_vars["zip"], $cp_attr["cc"], $cp_attr["desc"]);
												/**/
												$use_recurring_profile = ($post_vars["attr"]["rr"] === "BN" || (!$post_vars["attr"]["tp"] && !$post_vars["attr"]["rr"])) ? false : true;
												$is_independent_ccaps_sale = ($post_vars["attr"]["level"] === "*") ? true : false; /* Selling Independent Custom Capabilities? */
												/**/
												if ($use_recurring_profile && is_user_logged_in () && is_object ($user = wp_get_current_user ()) && ($user_id = $user->ID))
													{
														$period1 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period1 ($post_vars["attr"]["tp"] . " " . $post_vars["attr"]["tt"]);
														$period3 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period3 ($post_vars["attr"]["rp"] . " " . $post_vars["attr"]["rt"]);
														/**/
														$start_time = ($post_vars["attr"]["tp"]) ? /* If there's an Initial/Trial Period; start when it's over. */
														c_ws_plugin__s2member_pro_authnet_utilities::authnet_start_time ($period1) : /* After Trial is over. */
														c_ws_plugin__s2member_pro_authnet_utilities::authnet_start_time ($period3); /* Or next billing cycle. */
														/**/
														$reference = $start_time . ":" . $period1 . ":" . $period3 . "~" . $_SERVER["HTTP_HOST"] . "~" . $post_vars["attr"]["level_ccaps_eotper"];
														/**/
														update_user_meta ($user_id, "first_name", $post_vars["first_name"]) . update_user_meta ($user_id, "last_name", $post_vars["last_name"]);
														/**/
														if (!($_authnet = array ()) /* A first Initial/Trial payment? */
														&& (!$post_vars["attr"]["tp"] || ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)))
															{
																$_authnet["x_type"] = "AUTH_CAPTURE";
																$_authnet["x_method"] = "CC";
																/**/
																$_authnet["x_email"] = $user->user_email;
																$_authnet["x_first_name"] = $post_vars["first_name"];
																$_authnet["x_last_name"] = $post_vars["last_name"];
																$_authnet["x_customer_ip"] = $_SERVER["REMOTE_ADDR"];
																/**/
																$_authnet["x_invoice_num"] = "s2-" . uniqid ();
																$_authnet["x_description"] = $cost_calculations["desc"];
																/**/
																$_authnet["s2_initial_payment"] = "1"; /* Initial. */
																/**/
																$_authnet["s2_invoice"] = $post_vars["attr"]["level_ccaps_eotper"];
																$_authnet["s2_custom"] = $post_vars["attr"]["custom"];
																/**/
																if ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																	{
																		$_authnet["x_tax"] = $cost_calculations["trial_tax"];
																		$_authnet["x_amount"] = $cost_calculations["trial_total"];
																	}
																else /* Otherwise, charge for the first Regular payment. */
																	{
																		$_authnet["x_tax"] = $cost_calculations["tax"];
																		$_authnet["x_amount"] = $cost_calculations["total"];
																	}
																/**/
																$_authnet["x_card_num"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																$_authnet["x_exp_date"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_exp_date ($post_vars["card_expiration"]);
																$_authnet["x_card_code"] = $post_vars["card_verification"];
																/**/
																#if (in_array ($post_vars["card_type"], array ("Maestro", "Solo")))
																#	if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																#		$_authnet["x_card_start_date"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																#	else /* Otherwise, we assume they provided an issue number instead. */
																#		$_authnet["x_card_issue_number"] = $post_vars["card_start_date_issue_number"];
																/**/
																$_authnet["x_address"] = $post_vars["street"];
																$_authnet["x_city"] = $post_vars["city"];
																$_authnet["x_state"] = $post_vars["state"];
																$_authnet["x_country"] = $post_vars["country"];
																$_authnet["x_zip"] = $post_vars["zip"];
															}
														/**/
														if (!($authnet = array ())) /* Recurring Profile. */
															{
																$authnet["x_method"] = "create";
																/**/
																$authnet["x_email"] = $user->user_email;
																$authnet["x_first_name"] = $post_vars["first_name"];
																$authnet["x_last_name"] = $post_vars["last_name"];
																$authnet["x_customer_ip"] = $_SERVER["REMOTE_ADDR"];
																/**/
																$authnet["x_invoice_num"] = ($_authnet) ? $_authnet["x_invoice_num"] : "s2-" . uniqid ();
																$authnet["x_description"] = $cost_calculations["desc"];
																$authnet["x_description"] .= " ((" . $reference . "))";
																/**/
																$authnet["x_amount"] = $cost_calculations["total"];
																/**/
																$authnet["x_start_date"] = date ("Y-m-d", $start_time);
																/**/
																$authnet["x_unit"] = "days"; /* Always calculated in days. */
																$authnet["x_length"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_per_term_2_days ($post_vars["attr"]["rp"], $post_vars["attr"]["rt"]);
																$authnet["x_total_occurrences"] = ($post_vars["attr"]["rr"]) ? "9999" : "1"; /* Recurring or not? */
																/**/
																$authnet["x_card_num"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																$authnet["x_exp_date"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_exp_date ($post_vars["card_expiration"]);
																$authnet["x_card_code"] = $post_vars["card_verification"];
																/**/
																#if (in_array ($post_vars["card_type"], array ("Maestro", "Solo")))
																#	if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																#		$authnet["x_card_start_date"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																#	else /* Otherwise, we assume they provided an issue number instead. */
																#		$authnet["x_card_issue_number"] = $post_vars["card_start_date_issue_number"];
																/**/
																$authnet["x_address"] = $post_vars["street"];
																$authnet["x_city"] = $post_vars["city"];
																$authnet["x_state"] = $post_vars["state"];
																$authnet["x_country"] = $post_vars["country"];
																$authnet["x_zip"] = $post_vars["zip"];
															}
														/**/
														if (!$_authnet || (($_authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_aim_response ($_authnet)) && empty ($_authnet["__error"])))
															{
																if (($authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_arb_response ($authnet)) && (empty ($authnet["__error"]) || ($_authnet && !empty ($_authnet["transaction_id"]) && $authnet["response_reason_code"] === "E00018")))
																	{
																		/* $authnet["response_reason_code"] === "E00018" ... Card expires before start time. */
																		/**/
																		$new__txn_id = ($_authnet && !empty ($_authnet["transaction_id"])) ? $_authnet["transaction_id"] : false;
																		$new__subscr_id = ($_authnet && !empty ($_authnet["transaction_id"]) && $authnet["response_reason_code"] === "E00018") ? $new__txn_id : $authnet["subscription_id"];
																		$old__subscr_or_wp_id = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ();
																		$old__subscr_id = get_user_option ("s2member_subscr_id");
																		/**/
																		if (!($ipn = array ())) /* Simulated PayPal® IPN. */
																			{
																				$ipn["txn_type"] = "subscr_signup";
																				$ipn["subscr_id"] = $new__subscr_id;
																				$ipn["custom"] = $post_vars["attr"]["custom"];
																				/**/
																				$ipn["txn_id"] = ($new__txn_id) ? $new__txn_id : $new__subscr_id;
																				/**/
																				$ipn["period1"] = $period1;
																				$ipn["period3"] = $period3;
																				/**/
																				$ipn["mc_amount1"] = $cost_calculations["trial_total"];
																				$ipn["mc_amount3"] = $cost_calculations["total"];
																				/**/
																				$ipn["mc_gross"] = (preg_match ("/^[1-9]/", $ipn["period1"])) ? $ipn["mc_amount1"] : $ipn["mc_amount3"];
																				/**/
																				$ipn["mc_currency"] = $cost_calculations["cur"];
																				$ipn["tax"] = $cost_calculations["tax"];
																				/**/
																				$ipn["recurring"] = ($post_vars["attr"]["rr"]) ? "1" : "";
																				/**/
																				$ipn["payer_email"] = $user->user_email;
																				$ipn["first_name"] = $post_vars["first_name"];
																				$ipn["last_name"] = $post_vars["last_name"];
																				/**/
																				$ipn["option_name1"] = "Referencing Customer ID";
																				$ipn["option_selection1"] = $old__subscr_or_wp_id;
																				/**/
																				$ipn["option_name2"] = "Customer IP Address";
																				$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];
																				/**/
																				$ipn["item_name"] = $cost_calculations["desc"];
																				$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];
																				/**/
																				$ipn_q = "&s2member_paypal_proxy=authnet&s2member_paypal_proxy_use=pro-emails";
																				$ipn_q .= ($ipn["mc_gross"] > 0) ? ",subscr-signup-as-subscr-payment" : ""; /* Use as first payment? */
																				$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																				$ipn_q .= "&s2member_paypal_proxy_return_url=" . rawurlencode ($post_vars["attr"]["success"]);
																				/**/
																				$ipn["s2member_authnet_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20)));
																			}
																		/**/
																		if ($_authnet && !empty ($_authnet["transaction_id"]) && $authnet["response_reason_code"] === "E00018")
																			{
																				update_user_option ($user_id, "s2member_auto_eot_time", $start_time);
																			}
																		/**/
																		if (($authnet = array ("x_method" => "cancel")) && ($authnet["x_subscription_id"] = $old__subscr_id))
																			{
																				c_ws_plugin__s2member_pro_authnet_utilities::authnet_arb_response ($authnet);
																			}
																		/**/
																		$global_response = array ("response" => sprintf (_x ('<strong>Thank you.</strong> Your account has been updated.<br />&mdash; Please <a href="%s" rel="nofollow">log back in</a> now.', "s2member-front", "s2member"), esc_attr (wp_login_url ())));
																		/**/
																		if ($post_vars["attr"]["success"] && substr ($ipn["s2member_authnet_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_authnet_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																			wp_redirect (c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																	}
																else /* Else, an error. */
																	{
																		$global_response = array ("response" => $authnet["__error"], "error" => true);
																	}
															}
														else /* Else, an error. */
															{
																$global_response = array ("response" => $_authnet["__error"], "error" => true);
															}
													}
												/**/
												else if ($use_recurring_profile && !is_user_logged_in ()) /* Create a new account. */
													{
														$period1 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period1 ($post_vars["attr"]["tp"] . " " . $post_vars["attr"]["tt"]);
														$period3 = c_ws_plugin__s2member_paypal_utilities::paypal_pro_period3 ($post_vars["attr"]["rp"] . " " . $post_vars["attr"]["rt"]);
														/**/
														$start_time = ($post_vars["attr"]["tp"]) ? /* If there's an Initial/Trial Period; start when it's over. */
														c_ws_plugin__s2member_pro_authnet_utilities::authnet_start_time ($period1) : /* After Trial is over. */
														c_ws_plugin__s2member_pro_authnet_utilities::authnet_start_time ($period3); /* Or next billing cycle. */
														/**/
														$reference = $start_time . ":" . $period1 . ":" . $period3 . "~" . $_SERVER["HTTP_HOST"] . "~" . $post_vars["attr"]["level_ccaps_eotper"];
														/**/
														if (!($_authnet = array ()) /* A first Initial/Trial payment? */
														&& (!$post_vars["attr"]["tp"] || ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)))
															{
																$_authnet["x_type"] = "AUTH_CAPTURE";
																$_authnet["x_method"] = "CC";
																/**/
																$_authnet["x_email"] = $post_vars["email"];
																$_authnet["x_first_name"] = $post_vars["first_name"];
																$_authnet["x_last_name"] = $post_vars["last_name"];
																$_authnet["x_customer_ip"] = $_SERVER["REMOTE_ADDR"];
																/**/
																$_authnet["x_invoice_num"] = "s2-" . uniqid ();
																$_authnet["x_description"] = $cost_calculations["desc"];
																/**/
																$_authnet["s2_initial_payment"] = "1"; /* Initial. */
																/**/
																$_authnet["s2_invoice"] = $post_vars["attr"]["level_ccaps_eotper"];
																$_authnet["s2_custom"] = $post_vars["attr"]["custom"];
																/**/
																if ($post_vars["attr"]["tp"] && $cost_calculations["trial_total"] > 0)
																	{
																		$_authnet["x_tax"] = $cost_calculations["trial_tax"];
																		$_authnet["x_amount"] = $cost_calculations["trial_total"];
																	}
																else /* Otherwise, charge for the first Regular payment. */
																	{
																		$_authnet["x_tax"] = $cost_calculations["tax"];
																		$_authnet["x_amount"] = $cost_calculations["total"];
																	}
																/**/
																$_authnet["x_card_num"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																$_authnet["x_exp_date"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_exp_date ($post_vars["card_expiration"]);
																$_authnet["x_card_code"] = $post_vars["card_verification"];
																/**/
																#if (in_array ($post_vars["card_type"], array ("Maestro", "Solo")))
																#	if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																#		$_authnet["x_card_start_date"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																#	else /* Otherwise, we assume they provided an issue number instead. */
																#		$_authnet["x_card_issue_number"] = $post_vars["card_start_date_issue_number"];
																/**/
																$_authnet["x_address"] = $post_vars["street"];
																$_authnet["x_city"] = $post_vars["city"];
																$_authnet["x_state"] = $post_vars["state"];
																$_authnet["x_country"] = $post_vars["country"];
																$_authnet["x_zip"] = $post_vars["zip"];
															}
														/**/
														if (!($authnet = array ())) /* Recurring Profile. */
															{
																$authnet["x_method"] = "create";
																/**/
																$authnet["x_email"] = $post_vars["email"];
																$authnet["x_first_name"] = $post_vars["first_name"];
																$authnet["x_last_name"] = $post_vars["last_name"];
																$authnet["x_customer_ip"] = $_SERVER["REMOTE_ADDR"];
																/**/
																$authnet["x_invoice_num"] = ($_authnet) ? $_authnet["x_invoice_num"] : "s2-" . uniqid ();
																$authnet["x_description"] = $cost_calculations["desc"];
																$authnet["x_description"] .= " ((" . $reference . "))";
																/**/
																$authnet["x_amount"] = $cost_calculations["total"];
																/**/
																$authnet["x_start_date"] = date ("Y-m-d", $start_time);
																/**/
																$authnet["x_unit"] = "days"; /* Always calculated in days. */
																$authnet["x_length"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_per_term_2_days ($post_vars["attr"]["rp"], $post_vars["attr"]["rt"]);
																$authnet["x_total_occurrences"] = ($post_vars["attr"]["rr"]) ? "9999" : "1"; /* Recurring or not? */
																/**/
																$authnet["x_card_num"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																$authnet["x_exp_date"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_exp_date ($post_vars["card_expiration"]);
																$authnet["x_card_code"] = $post_vars["card_verification"];
																/**/
																#if (in_array ($post_vars["card_type"], array ("Maestro", "Solo")))
																#	if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																#		$authnet["x_card_start_date"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																#	else /* Otherwise, we assume they provided an issue number instead. */
																#		$authnet["x_card_issue_number"] = $post_vars["card_start_date_issue_number"];
																/**/
																$authnet["x_address"] = $post_vars["street"];
																$authnet["x_city"] = $post_vars["city"];
																$authnet["x_state"] = $post_vars["state"];
																$authnet["x_country"] = $post_vars["country"];
																$authnet["x_zip"] = $post_vars["zip"];
															}
														/**/
														if (!$_authnet || (($_authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_aim_response ($_authnet)) && empty ($_authnet["__error"])))
															{
																if (($authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_arb_response ($authnet)) && (empty ($authnet["__error"]) || ($_authnet && !empty ($_authnet["transaction_id"]) && $authnet["response_reason_code"] === "E00018")))
																	{
																		/* $authnet["response_reason_code"] === "E00018" ... Card expires before start time. */
																		/**/
																		$new__txn_id = ($_authnet && !empty ($_authnet["transaction_id"])) ? $_authnet["transaction_id"] : false;
																		$new__subscr_id = ($_authnet && !empty ($_authnet["transaction_id"]) && $authnet["response_reason_code"] === "E00018") ? $new__txn_id : $authnet["subscription_id"];
																		/**/
																		if (!($ipn = array ())) /* Simulated PayPal® IPN. */
																			{
																				$ipn["txn_type"] = "subscr_signup";
																				$ipn["subscr_id"] = $new__subscr_id;
																				$ipn["custom"] = $post_vars["attr"]["custom"];
																				/**/
																				$ipn["txn_id"] = ($new__txn_id) ? $new__txn_id : $new__subscr_id;
																				/**/
																				$ipn["period1"] = $period1;
																				$ipn["period3"] = $period3;
																				/**/
																				$ipn["mc_amount1"] = $cost_calculations["trial_total"];
																				$ipn["mc_amount3"] = $cost_calculations["total"];
																				/**/
																				$ipn["mc_gross"] = (preg_match ("/^[1-9]/", $ipn["period1"])) ? $ipn["mc_amount1"] : $ipn["mc_amount3"];
																				/**/
																				$ipn["mc_currency"] = $cost_calculations["cur"];
																				$ipn["tax"] = $cost_calculations["tax"];
																				/**/
																				$ipn["recurring"] = ($post_vars["attr"]["rr"]) ? "1" : "";
																				/**/
																				$ipn["payer_email"] = $post_vars["email"];
																				$ipn["first_name"] = $post_vars["first_name"];
																				$ipn["last_name"] = $post_vars["last_name"];
																				/**/
																				$ipn["option_name1"] = "Originating Domain";
																				$ipn["option_selection1"] = $_SERVER["HTTP_HOST"];
																				/**/
																				$ipn["option_name2"] = "Customer IP Address";
																				$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];
																				/**/
																				$ipn["item_name"] = $cost_calculations["desc"];
																				$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];
																				/**/
																				$ipn_q = "&s2member_paypal_proxy=authnet&s2member_paypal_proxy_use=pro-emails";
																				$ipn_q .= ($ipn["mc_gross"] > 0) ? ",subscr-signup-as-subscr-payment" : ""; /* Use as first payment? */
																				$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																				$ipn_q .= "&s2member_paypal_proxy_return_url=" . rawurlencode ($post_vars["attr"]["success"]);
																			}
																		/**/
																		if (!($create_user = array ())) /* Build post fields for registration configuration, and then the creation array. */
																			{
																				$_POST["ws_plugin__s2member_custom_reg_field_user_pass1"] = $post_vars["password1"]; /* Fake this for registration configuration. */
																				$_POST["ws_plugin__s2member_custom_reg_field_first_name"] = $post_vars["first_name"]; /* Fake this for registration configuration. */
																				$_POST["ws_plugin__s2member_custom_reg_field_last_name"] = $post_vars["last_name"]; /* Fake this for registration configuration. */
																				$_POST["ws_plugin__s2member_custom_reg_field_opt_in"] = $post_vars["custom_fields"]["opt_in"]; /* Fake this too. */
																				/**/
																				if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"])
																					foreach (json_decode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
																						{
																							$field_var = preg_replace ("/[^a-z0-9]/i", "_", strtolower ($field["id"]));
																							$field_id_class = preg_replace ("/_/", "-", $field_var);
																							/**/
																							if (isset ($post_vars["custom_fields"][$field_var]))
																								$_POST["ws_plugin__s2member_custom_reg_field_" . $field_var] = $post_vars["custom_fields"][$field_var];
																						}
																				/**/
																				$_COOKIE["s2member_subscr_gateway"] = c_ws_plugin__s2member_utils_encryption::encrypt ("authnet"); /* Fake this for registration configuration. */
																				$_COOKIE["s2member_subscr_id"] = c_ws_plugin__s2member_utils_encryption::encrypt ($new__subscr_id); /* Fake this for registration configuration. */
																				$_COOKIE["s2member_custom"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["custom"]); /* Fake this for registration configuration. */
																				$_COOKIE["s2member_item_number"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["level_ccaps_eotper"]); /* Fake this too. */
																				/**/
																				$create_user["user_login"] = $post_vars["username"]; /* Copy this into a separate array for `wp_create_user()`. */
																				$create_user["user_pass"] = wp_generate_password (); /* Which may fire `c_ws_plugin__s2member_registrations::generate_password()`. */
																				$create_user["user_email"] = $post_vars["email"]; /* Copy this into a separate array for `wp_create_user()`. */
																			}
																		/**/
																		if ($post_vars["password1"] && $post_vars["password1"] === $create_user["user_pass"]) /* A custom Password is being used? */
																			{
																				if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																					{
																						wp_new_user_notification ($new__user_id, $create_user["user_pass"]);
																						/**/
																						$ipn["s2member_authnet_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20)));
																						/**/
																						if ($_authnet && !empty ($_authnet["transaction_id"]) && $authnet["response_reason_code"] === "E00018")
																							update_user_option ($new__user_id, "s2member_auto_eot_time", $start_time);
																						/**/
																						$global_response = array ("response" => sprintf (_x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; Please <a href="%s" rel="nofollow">login</a>.', "s2member-front", "s2member"), esc_attr (wp_login_url ())));
																						/**/
																						if ($post_vars["attr"]["success"] && substr ($ipn["s2member_authnet_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_authnet_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																							wp_redirect (c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																					}
																				else /* Else, an error reponse should be given. */
																					{
																						c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																						/**/
																						$global_response = array ("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																					}
																			}
																		else /* Otherwise, they'll need to check their email for the auto-generated Password. */
																			{
																				if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																					{
																						update_user_option ($new__user_id, "default_password_nag", true, true); /* Password nag. */
																						wp_new_user_notification ($new__user_id, $create_user["user_pass"]);
																						/**/
																						$ipn["s2member_authnet_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20)));
																						/**/
																						if ($_authnet && !empty ($_authnet["transaction_id"]) && $authnet["response_reason_code"] === "E00018")
																							update_user_option ($new__user_id, "s2member_auto_eot_time", $start_time);
																						/**/
																						$global_response = array ("response" => _x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; You\'ll receive an email momentarily.', "s2member-front", "s2member"));
																						/**/
																						if ($post_vars["attr"]["success"] && substr ($ipn["s2member_authnet_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_authnet_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																							wp_redirect (c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																					}
																				else /* Else, an error reponse should be given. */
																					{
																						c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																						/**/
																						$global_response = array ("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																					}
																			}
																	}
																else /* Else, an error. */
																	{
																		$global_response = array ("response" => $authnet["__error"], "error" => true);
																	}
															}
														else /* Else, an error. */
															{
																$global_response = array ("response" => $_authnet["__error"], "error" => true);
															}
													}
												/**/
												else if (!$use_recurring_profile && is_user_logged_in () && is_object ($user = wp_get_current_user ()) && ($user_id = $user->ID))
													{
														update_user_meta ($user_id, "first_name", $post_vars["first_name"]) . update_user_meta ($user_id, "last_name", $post_vars["last_name"]);
														/**/
														if (!($authnet = array ())) /* Direct payments. */
															{
																$authnet["x_type"] = "AUTH_CAPTURE";
																$authnet["x_method"] = "CC";
																/**/
																$authnet["x_email"] = $user->user_email;
																$authnet["x_first_name"] = $post_vars["first_name"];
																$authnet["x_last_name"] = $post_vars["last_name"];
																$authnet["x_customer_ip"] = $_SERVER["REMOTE_ADDR"];
																/**/
																$authnet["x_invoice_num"] = "s2-" . uniqid ();
																$authnet["x_description"] = $cost_calculations["desc"];
																/**/
																$authnet["s2_invoice"] = $post_vars["attr"]["level_ccaps_eotper"];
																$authnet["s2_custom"] = $post_vars["attr"]["custom"];
																/**/
																$authnet["x_tax"] = $cost_calculations["tax"];
																$authnet["x_amount"] = $cost_calculations["total"];
																/**/
																$authnet["x_card_num"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																$authnet["x_exp_date"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_exp_date ($post_vars["card_expiration"]);
																$authnet["x_card_code"] = $post_vars["card_verification"];
																/**/
																#if (in_array ($post_vars["card_type"], array ("Maestro", "Solo")))
																#	if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																#		$authnet["x_card_start_date"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																#	else /* Otherwise, we assume they provided an issue number instead. */
																#		$authnet["x_card_issue_number"] = $post_vars["card_start_date_issue_number"];
																/**/
																$authnet["x_address"] = $post_vars["street"];
																$authnet["x_city"] = $post_vars["city"];
																$authnet["x_state"] = $post_vars["state"];
																$authnet["x_country"] = $post_vars["country"];
																$authnet["x_zip"] = $post_vars["zip"];
															}
														/**/
														if (($authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_aim_response ($authnet)) && empty ($authnet["__error"]))
															{
																$old__subscr_or_wp_id = c_ws_plugin__s2member_utils_users::get_user_subscr_or_wp_id ();
																$old__subscr_id = get_user_option ("s2member_subscr_id");
																$new__subscr_id = $new__txn_id = $authnet["transaction_id"];
																/**/
																if (!($ipn = array ())) /* Simulated PayPal® IPN. */
																	{
																		$ipn["txn_type"] = "web_accept";
																		$ipn["txn_id"] = $new__subscr_id;
																		$ipn["custom"] = $post_vars["attr"]["custom"];
																		/**/
																		$ipn["mc_gross"] = $cost_calculations["total"];
																		$ipn["mc_currency"] = $cost_calculations["cur"];
																		$ipn["tax"] = $cost_calculations["tax"];
																		/**/
																		$ipn["payer_email"] = $user->user_email;
																		$ipn["first_name"] = $post_vars["first_name"];
																		$ipn["last_name"] = $post_vars["last_name"];
																		/**/
																		$ipn["option_name1"] = "Referencing Customer ID";
																		$ipn["option_selection1"] = $old__subscr_or_wp_id;
																		/**/
																		$ipn["option_name2"] = "Customer IP Address";
																		$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];
																		/**/
																		$ipn["item_name"] = $cost_calculations["desc"];
																		$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];
																		/**/
																		$ipn_q = "&s2member_paypal_proxy=authnet&s2member_paypal_proxy_use=pro-emails";
																		$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																		$ipn_q .= "&s2member_paypal_proxy_return_url=" . rawurlencode ($post_vars["attr"]["success"]);
																		/**/
																		$ipn["s2member_authnet_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20)));
																	}
																/**/
																if (!$is_independent_ccaps_sale) /* Independent? */
																	if (($authnet = array ("x_method" => "cancel")) && ($authnet["x_subscription_id"] = $old__subscr_id))
																		{
																			c_ws_plugin__s2member_pro_authnet_utilities::authnet_arb_response ($authnet);
																		}
																/**/
																if ($is_independent_ccaps_sale) /* Independent? */
																	setcookie ("s2member_tracking", ($s2member_tracking = c_ws_plugin__s2member_utils_encryption::encrypt ($new__txn_id)), time () + 31556926, COOKIEPATH, COOKIE_DOMAIN) . setcookie ("s2member_tracking", $s2member_tracking, time () + 31556926, SITECOOKIEPATH, COOKIE_DOMAIN) . ($_COOKIE["s2member_tracking"] = $s2member_tracking);
																/**/
																$global_response = array ("response" => sprintf (_x ('<strong>Thank you.</strong> Your account has been updated.<br />&mdash; Please <a href="%s" rel="nofollow">log back in</a> now.', "s2member-front", "s2member"), esc_attr (wp_login_url ())));
																/**/
																if ($post_vars["attr"]["success"] && substr ($ipn["s2member_authnet_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_authnet_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																	wp_redirect (c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
															}
														else /* Else, an error. */
															{
																$global_response = array ("response" => $authnet["__error"], "error" => true);
															}
													}
												/**/
												else if (!$use_recurring_profile && !is_user_logged_in ()) /* Create a new account. */
													{
														if (!($authnet = array ())) /* Direct payments. */
															{
																$authnet["x_type"] = "AUTH_CAPTURE";
																$authnet["x_method"] = "CC";
																/**/
																$authnet["x_email"] = $post_vars["email"];
																$authnet["x_first_name"] = $post_vars["first_name"];
																$authnet["x_last_name"] = $post_vars["last_name"];
																$authnet["x_customer_ip"] = $_SERVER["REMOTE_ADDR"];
																/**/
																$authnet["x_invoice_num"] = "s2-" . uniqid ();
																$authnet["x_description"] = $cost_calculations["desc"];
																/**/
																$authnet["s2_invoice"] = $post_vars["attr"]["level_ccaps_eotper"];
																$authnet["s2_custom"] = $post_vars["attr"]["custom"];
																/**/
																$authnet["x_tax"] = $cost_calculations["tax"];
																$authnet["x_amount"] = $cost_calculations["total"];
																/**/
																$authnet["x_card_num"] = preg_replace ("/[^0-9]/", "", $post_vars["card_number"]);
																$authnet["x_exp_date"] = c_ws_plugin__s2member_pro_authnet_utilities::authnet_exp_date ($post_vars["card_expiration"]);
																$authnet["x_card_code"] = $post_vars["card_verification"];
																/**/
																#if (in_array ($post_vars["card_type"], array ("Maestro", "Solo")))
																#	if (preg_match ("/^[0-9]{2}\/[0-9]{4}$/", $post_vars["card_start_date_issue_number"]))
																#		$authnet["x_card_start_date"] = preg_replace ("/[^0-9]/", "", $post_vars["card_start_date_issue_number"]);
																#	else /* Otherwise, we assume they provided an issue number instead. */
																#		$authnet["x_card_issue_number"] = $post_vars["card_start_date_issue_number"];
																/**/
																$authnet["x_address"] = $post_vars["street"];
																$authnet["x_city"] = $post_vars["city"];
																$authnet["x_state"] = $post_vars["state"];
																$authnet["x_country"] = $post_vars["country"];
																$authnet["x_zip"] = $post_vars["zip"];
															}
														/**/
														if (($authnet = c_ws_plugin__s2member_pro_authnet_utilities::authnet_aim_response ($authnet)) && empty ($authnet["__error"]))
															{
																$new__subscr_id = $authnet["transaction_id"];
																/**/
																if (!($ipn = array ())) /* Simulated PayPal® IPN. */
																	{
																		$ipn["txn_type"] = "web_accept";
																		$ipn["txn_id"] = $new__subscr_id;
																		$ipn["custom"] = $post_vars["attr"]["custom"];
																		/**/
																		$ipn["mc_gross"] = $cost_calculations["total"];
																		$ipn["mc_currency"] = $cost_calculations["cur"];
																		$ipn["tax"] = $cost_calculations["tax"];
																		/**/
																		$ipn["payer_email"] = $post_vars["email"];
																		$ipn["first_name"] = $post_vars["first_name"];
																		$ipn["last_name"] = $post_vars["last_name"];
																		/**/
																		$ipn["option_name1"] = "Originating Domain";
																		$ipn["option_selection1"] = $_SERVER["HTTP_HOST"];
																		/**/
																		$ipn["option_name2"] = "Customer IP Address";
																		$ipn["option_selection2"] = $_SERVER["REMOTE_ADDR"];
																		/**/
																		$ipn["item_name"] = $cost_calculations["desc"];
																		$ipn["item_number"] = $post_vars["attr"]["level_ccaps_eotper"];
																		/**/
																		$ipn_q = "&s2member_paypal_proxy=authnet&s2member_paypal_proxy_use=pro-emails";
																		$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																		$ipn_q .= "&s2member_paypal_proxy_return_url=" . rawurlencode ($post_vars["attr"]["success"]);
																	}
																/**/
																if (!($create_user = array ())) /* Build post fields for registration configuration, and then the creation array. */
																	{
																		$_POST["ws_plugin__s2member_custom_reg_field_user_pass1"] = $post_vars["password1"]; /* Fake this for registration configuration. */
																		$_POST["ws_plugin__s2member_custom_reg_field_first_name"] = $post_vars["first_name"]; /* Fake this for registration configuration. */
																		$_POST["ws_plugin__s2member_custom_reg_field_last_name"] = $post_vars["last_name"]; /* Fake this for registration configuration. */
																		$_POST["ws_plugin__s2member_custom_reg_field_opt_in"] = $post_vars["custom_fields"]["opt_in"]; /* Fake this too. */
																		/**/
																		if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"])
																			foreach (json_decode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
																				{
																					$field_var = preg_replace ("/[^a-z0-9]/i", "_", strtolower ($field["id"]));
																					$field_id_class = preg_replace ("/_/", "-", $field_var);
																					/**/
																					if (isset ($post_vars["custom_fields"][$field_var]))
																						$_POST["ws_plugin__s2member_custom_reg_field_" . $field_var] = $post_vars["custom_fields"][$field_var];
																				}
																		/**/
																		$_COOKIE["s2member_subscr_gateway"] = c_ws_plugin__s2member_utils_encryption::encrypt ("authnet"); /* Fake this for registration configuration. */
																		$_COOKIE["s2member_subscr_id"] = c_ws_plugin__s2member_utils_encryption::encrypt ($new__subscr_id); /* Fake this for registration configuration. */
																		$_COOKIE["s2member_custom"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["custom"]); /* Fake this for registration configuration. */
																		$_COOKIE["s2member_item_number"] = c_ws_plugin__s2member_utils_encryption::encrypt ($post_vars["attr"]["level_ccaps_eotper"]); /* Fake this too. */
																		/**/
																		$create_user["user_login"] = $post_vars["username"]; /* Copy this into a separate array for `wp_create_user()`. */
																		$create_user["user_pass"] = wp_generate_password (); /* Which may fire `c_ws_plugin__s2member_registrations::generate_password()`. */
																		$create_user["user_email"] = $post_vars["email"]; /* Copy this into a separate array for `wp_create_user()`. */
																	}
																/**/
																if ($post_vars["password1"] && $post_vars["password1"] === $create_user["user_pass"]) /* A custom Password is being used? */
																	{
																		if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																			{
																				wp_new_user_notification ($new__user_id, $create_user["user_pass"]);
																				/**/
																				$ipn["s2member_authnet_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20)));
																				/**/
																				$global_response = array ("response" => sprintf (_x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; Please <a href="%s" rel="nofollow">login</a>.', "s2member-front", "s2member"), esc_attr (wp_login_url ())));
																				/**/
																				if ($post_vars["attr"]["success"] && substr ($ipn["s2member_authnet_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_authnet_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																					wp_redirect (c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																			}
																		else /* Else, an error reponse should be given. */
																			{
																				c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																				/**/
																				$global_response = array ("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																			}
																	}
																else /* Otherwise, they'll need to check their email for the auto-generated Password. */
																	{
																		if (((is_multisite () && ($new__user_id = c_ws_plugin__s2member_registrations::ms_create_existing_user ($create_user["user_login"], $create_user["user_email"], $create_user["user_pass"]))) || ($new__user_id = wp_create_user ($create_user["user_login"], $create_user["user_pass"], $create_user["user_email"]))) && !is_wp_error ($new__user_id))
																			{
																				update_user_option ($new__user_id, "default_password_nag", true, true); /* Password nag. */
																				wp_new_user_notification ($new__user_id, $create_user["user_pass"]);
																				/**/
																				$ipn["s2member_authnet_proxy_return_url"] = trim (c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20)));
																				/**/
																				$global_response = array ("response" => _x ('<strong>Thank you.</strong> Your account has been approved.<br />&mdash; You\'ll receive an email momentarily.', "s2member-front", "s2member"));
																				/**/
																				if ($post_vars["attr"]["success"] && substr ($ipn["s2member_authnet_proxy_return_url"], 0, 2) === substr ($post_vars["attr"]["success"], 0, 2) && ($custom_success_url = str_ireplace (array ("%%s_response%%", /* Deprecated in v111106 ». */ "%%response%%"), array (urlencode (c_ws_plugin__s2member_utils_encryption::encrypt ($global_response["response"])), urlencode ($global_response["response"])), $ipn["s2member_authnet_proxy_return_url"])) && ($custom_success_url = trim (preg_replace ("/%%(.+?)%%/i", "", $custom_success_url))))
																					wp_redirect (c_ws_plugin__s2member_utils_urls::add_s2member_sig ($custom_success_url, "s2p-v")) . exit ();
																			}
																		else /* Else, an error reponse should be given. */
																			{
																				c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																				/**/
																				$global_response = array ("response" => _x ('<strong>Oops.</strong> A slight problem. Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
																			}
																	}
															}
														else /* Else, an error. */
															{
																$global_response = array ("response" => $authnet["__error"], "error" => true);
															}
													}
												else /* Else, we have an unknown scenario. */
													{
														$global_response = array ("response" => _x ('<strong>Unknown error.</strong> Please contact Support for assistance.', "s2member-front", "s2member"), "error" => true);
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