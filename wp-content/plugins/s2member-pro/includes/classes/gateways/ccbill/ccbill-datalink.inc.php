<?php
/**
* ccBill® DataLink integration.
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
* @package s2Member\ccBill
* @since 1.5
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_ccbill_datalink"))
	{
		/**
		* ccBill® DataLink integration.
		*
		* @package s2Member\ccBill
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_ccbill_datalink
			{
				/**
				* Connect to and process DataLink information for ccBill®.
				*
				* s2Member's Auto EOT System must be enabled for this to work properly.
				*
				* If you have a HUGE userbase, increase the max IPNs per process.
				* But NOTE, this runs ``$per_process`` *( per Blog )* on a Multisite Network.
				* To increase, use: ``add_filter ("ws_plugin__s2member_pro_ccbill_datalink_ipns_per_process");``.
				*
				* @package s2Member\ccBill
				* @since 1.5
				*
				* @attaches-to ``add_action("ws_plugin__s2member_after_auto_eot_system");``
				*
				* @param array $vars Expects an array of defined variables passed in by the Action Hook.
				* @return null
				*/
				public static function ccbill_datalink ($vars = FALSE)
					{
						global $wpdb; /* Need global DB obj. */
						global $current_site, $current_blog; /* For Multisite support. */
						/**/
						if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_client_id"]) /* Configd? */
							{
								$datalink = "https://datalink.ccbill.com/data/main.cgi"; /* DataLink service. */
								/**/
								if (!($last = get_transient ("s2m_" . md5 ("s2member_pro_ccbill_last_datalink"))) || $last < (time () - 86400))
									{
										$start = ($last && $last >= (time () - (86400 + 43200))) ? $last : time () - 86400; /* Don't let $start time be less than 1 day + 12 hours ago. */
										$end = $last = ($start + 86400); /* Sets $end time to exactly 1 day later. This also sets $last time; which is recorded later in this routine. */
										/**/
										$qvrs = array ("startTime" => date ("YmdHis", $start), "endTime" => date ("YmdHis", $end), "transactionTypes" => "EXPIRE,REBILL,REFUND,CHARGEBACK", "clientAccnum" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_client_id"], "clientSubacc" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_client_sid"], "username" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_dl_user"], "password" => $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_ccbill_dl_pass"]);
										/**/
										if (($unprocessed_ipn_lines = trim (c_ws_plugin__s2member_utils_urls::remote ($datalink = add_query_arg (urlencode_deep ($qvrs), $datalink)))) && !preg_match ("/^Error\:/i", $unprocessed_ipn_lines))
											{
												$ccbill["s2member_log"][] = "Storing last DataLink time: " . date ("D M j, Y g:i:s a T", $last);
												/**/
												set_transient ("s2m_" . md5 ("s2member_pro_ccbill_last_datalink"), $last, 31556926);
												/**/
												$ccbill["s2member_log"][] = "Storing new DataLink IPNs into a Transient Queue.";
												$ccbill["s2member_log"][] = $datalink; /* Record the full DataLink URL as well. */
												$ccbill["s2member_log"][] = $unprocessed_ipn_lines; /* Record list in log. */
												/**/
												set_transient ("s2m_" . md5 ("s2member_pro_ccbill_datalink_ipns"), trim (trim (get_transient ("s2m_" . md5 ("s2member_pro_ccbill_datalink_ipns"))) . "\n" . $unprocessed_ipn_lines), 31556926);
											}
										else if (!preg_match ("/^Error\:/i", $unprocessed_ipn_lines)) /* In this case, there we no new IPNs. */
											{
												$ccbill["s2member_log"][] = "Storing last DataLink time: " . date ("D M j, Y g:i:s a T", $last);
												/**/
												set_transient ("s2m_" . md5 ("s2member_pro_ccbill_last_datalink"), $last, 31556926);
												/**/
												$ccbill["s2member_log"][] = "No new Datalink IPNs at this time: " . date ("D M j, Y g:i:s a T");
												$ccbill["s2member_log"][] = $datalink; /* Record the full DataLink URL as well. */
												$ccbill["s2member_log"][] = $unprocessed_ipn_lines; /* Log this; just in case. */
											}
										else /* Otherwise, we need to record any errors that were found in the DataLink response. */
											{
												$ccbill["s2member_log"][] = "Storing last DataLink time: " . date ("D M j, Y g:i:s a T", $last);
												/**/
												set_transient ("s2m_" . md5 ("s2member_pro_ccbill_last_datalink"), $last, 31556926);
												/**/
												$ccbill["s2member_log"][] = "Recording DataLink error at: " . date ("D M j, Y g:i:s a T");
												$ccbill["s2member_log"][] = "Recording server IP address: " . $_SERVER["SERVER_ADDR"];
												$ccbill["s2member_log"][] = $datalink; /* Record the full DataLink URL as well. */
												$ccbill["s2member_log"][] = $unprocessed_ipn_lines; /* Log error mesg. */
											}
										/**/
										$logv = c_ws_plugin__s2member_utilities::ver_details ();
										$logm = c_ws_plugin__s2member_utilities::mem_details ();
										$log4 = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "\nUser-Agent: " . $_SERVER["HTTP_USER_AGENT"];
										$log4 = (is_multisite () && !is_main_site ()) ? ($_log4 = $current_blog->domain . $current_blog->path) . "\n" . $log4 : $log4;
										$log2 = (is_multisite () && !is_main_site ()) ? "ccbill-dl-4-" . trim (preg_replace ("/[^a-z0-9]/i", "-", $_log4), "-") . ".log" : "ccbill-dl.log";
										/**/
										if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"])
											if (is_dir ($logs_dir = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"]))
												if (is_writable ($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files ())
													file_put_contents ($logs_dir . "/" . $log2, $logv . "\n" . $logm . "\n" . $log4 . "\n" . var_export ($ccbill, true) . "\n\n", FILE_APPEND);
									}
								/**/
								else if (($unprocessed_ipn_lines = trim (get_transient ("s2m_" . md5 ("s2member_pro_ccbill_datalink_ipns"))))) /* Anything? */
									{
										$per_process = apply_filters ("ws_plugin__s2member_pro_ccbill_datalink_ipns_per_process", $vars["per_process"], get_defined_vars ());
										/**/
										foreach (($unprocessed_lines = preg_split ("/[\r\n]+/", $unprocessed_ipn_lines)) as $line => $unprocessed_line) /* Begin processing. */
											{
												unset ($ccbill, $processing, $processed, $ipn, $ipn_q, $log4, $_log4, $log2, $logs_dir); /* Unset/reset these variables each pass. */
												/**/
												if (($unprocessed_line = trim ($unprocessed_line)) && ($counter = (int)$counter + 1)) /* Keep a count of each IPN data line. */
													{
														$ccbill["s2member_log"][] = "DataLink IPN processed on: " . date ("D M j, Y g:i:s a T");
														/**/
														$ccbill["dl_ipn"] = c_ws_plugin__s2member_utils_strings::trim_dq_deep (preg_split ("/\",\"/", $unprocessed_line));
														/**/
														if (is_array ($ccbill["dl_ipn_signup_vars"] = c_ws_plugin__s2member_utils_users::get_user_ipn_signup_vars (false, $ccbill["dl_ipn"][3])))
															{
																if (preg_match ("/^REBILL$/i", $ccbill["dl_ipn"][0])) /* Recurring payments. */
																	{
																		$ccbill["s2member_log"][] = "ccBill® transaction identified as (SUBSCRIPTION PAYMENT).";
																		$ccbill["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal® processor as txn_type (subscr_payment).";
																		$ccbill["s2member_log"][] = "Please check PayPal® IPN logs for further processing details.";
																		/**/
																		$processing = $processed = true;
																		$ipn = array (); /* Reset. */
																		/**/
																		$ipn["txn_type"] = "subscr_payment";
																		$ipn["subscr_id"] = $ccbill["dl_ipn_signup_vars"]["subscr_id"];
																		/**/
																		$ipn["custom"] = $ccbill["dl_ipn_signup_vars"]["custom"];
																		/**/
																		$ipn["txn_id"] = $ccbill["dl_ipn"][5]; /* Unique transaction ID. */
																		/**/
																		$ipn["mc_gross"] = number_format ($ccbill["dl_ipn"][6], 2, ".", "");
																		$ipn["mc_currency"] = strtoupper ("USD"); /* DataLink uses USD. */
																		$ipn["tax"] = number_format ("0.00", 2, ".", "");
																		/**/
																		$ipn["payer_email"] = $ccbill["dl_ipn_signup_vars"]["payer_email"];
																		$ipn["first_name"] = $ccbill["dl_ipn_signup_vars"]["first_name"];
																		$ipn["last_name"] = $ccbill["dl_ipn_signup_vars"]["last_name"];
																		/**/
																		$ipn["option_name1"] = $ccbill["dl_ipn_signup_vars"]["option_name1"];
																		$ipn["option_selection1"] = $ccbill["dl_ipn_signup_vars"]["option_selection1"];
																		/**/
																		$ipn["option_name2"] = $ccbill["dl_ipn_signup_vars"]["option_name2"];
																		$ipn["option_selection2"] = $ccbill["dl_ipn_signup_vars"]["option_selection2"];
																		/**/
																		$ipn["item_number"] = $ccbill["dl_ipn_signup_vars"]["item_number"];
																		$ipn["item_name"] = $ccbill["dl_ipn_signup_vars"]["item_name"];
																		/**/
																		$ipn_q = "&s2member_paypal_proxy=ccbill&s2member_paypal_proxy_use=standard-emails";
																		$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																		/**/
																		c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																	}
																/**/
																else if (preg_match ("/^EXPIRE$/i", $ccbill["dl_ipn"][0])) /* Expired Subscriptions. */
																	{
																		$ccbill["s2member_log"][] = "ccBill® transaction identified as (SUBSCRIPTION EXPIRATION).";
																		$ccbill["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal® processor as txn_type (subscr_eot).";
																		$ccbill["s2member_log"][] = "Please check PayPal® IPN logs for further processing details.";
																		/**/
																		$processing = $processed = true;
																		$ipn = array (); /* Reset. */
																		/**/
																		$ipn["txn_type"] = "subscr_eot";
																		$ipn["subscr_id"] = $ccbill["dl_ipn_signup_vars"]["subscr_id"];
																		/**/
																		$ipn["custom"] = $ccbill["dl_ipn_signup_vars"]["custom"];
																		/**/
																		$ipn["period1"] = $ccbill["dl_ipn_signup_vars"]["period1"];
																		$ipn["period3"] = $ccbill["dl_ipn_signup_vars"]["period3"];
																		/**/
																		$ipn["payer_email"] = $ccbill["dl_ipn_signup_vars"]["payer_email"];
																		$ipn["first_name"] = $ccbill["dl_ipn_signup_vars"]["first_name"];
																		$ipn["last_name"] = $ccbill["dl_ipn_signup_vars"]["last_name"];
																		/**/
																		$ipn["option_name1"] = $ccbill["dl_ipn_signup_vars"]["option_name1"];
																		$ipn["option_selection1"] = $ccbill["dl_ipn_signup_vars"]["option_selection1"];
																		/**/
																		$ipn["option_name2"] = $ccbill["dl_ipn_signup_vars"]["option_name2"];
																		$ipn["option_selection2"] = $ccbill["dl_ipn_signup_vars"]["option_selection2"];
																		/**/
																		$ipn["item_number"] = $ccbill["dl_ipn_signup_vars"]["item_number"];
																		$ipn["item_name"] = $ccbill["dl_ipn_signup_vars"]["item_name"];
																		/**/
																		$ipn_q = "&s2member_paypal_proxy=ccbill&s2member_paypal_proxy_use=standard-emails";
																		$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																		/**/
																		c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																	}
																/**/
																else if (preg_match ("/^(REFUND|CHARGEBACK)$/i", $ccbill["dl_ipn"][0])) /* Refunds/Reversals. */
																	{
																		$ccbill["s2member_log"][] = "ccBill® transaction identified as (REFUND|CHARGEBACK).";
																		$ccbill["s2member_log"][] = "IPN reformulated. Piping through s2Member's core/standard PayPal® processor as payment_status (refunded|reversed).";
																		$ccbill["s2member_log"][] = "Please check PayPal® IPN logs for further processing details.";
																		/**/
																		$processing = $processed = true;
																		$ipn = array (); /* Reset. */
																		/**/
																		$ipn["custom"] = $ccbill["dl_ipn_signup_vars"]["custom"];
																		/**/
																		$ipn["parent_txn_id"] = $ccbill["dl_ipn_signup_vars"]["subscr_id"];
																		/**/
																		$ipn["payment_status"] = (preg_match ("/^CHARGEBACK$/i", $ccbill["dl_ipn"][0])) ? "reversed" : "refunded";
																		$ipn["mc_fee"] = "-" . number_format ("0.00", 2, ".", "");
																		$ipn["mc_gross"] = "-" . number_format ($ccbill["dl_ipn"][5], 2, ".", "");
																		$ipn["mc_currency"] = strtoupper ("USD"); /* DataLink uses USD. */
																		$ipn["tax"] = "-" . number_format ("0.00", 2, ".", "");
																		/**/
																		$ipn["payer_email"] = $ccbill["dl_ipn_signup_vars"]["payer_email"];
																		$ipn["first_name"] = $ccbill["dl_ipn_signup_vars"]["first_name"];
																		$ipn["last_name"] = $ccbill["dl_ipn_signup_vars"]["last_name"];
																		/**/
																		$ipn["option_name1"] = $ccbill["dl_ipn_signup_vars"]["option_name1"];
																		$ipn["option_selection1"] = $ccbill["dl_ipn_signup_vars"]["option_selection1"];
																		/**/
																		$ipn["option_name2"] = $ccbill["dl_ipn_signup_vars"]["option_name2"];
																		$ipn["option_selection2"] = $ccbill["dl_ipn_signup_vars"]["option_selection2"];
																		/**/
																		$ipn["item_number"] = $ccbill["dl_ipn_signup_vars"]["item_number"];
																		$ipn["item_name"] = $ccbill["dl_ipn_signup_vars"]["item_name"];
																		/**/
																		$ipn_q = "&s2member_paypal_proxy=ccbill&s2member_paypal_proxy_use=standard-emails";
																		$ipn_q .= "&s2member_paypal_proxy_verification=" . urlencode (c_ws_plugin__s2member_paypal_utilities::paypal_proxy_key_gen ());
																		/**/
																		c_ws_plugin__s2member_utils_urls::remote (site_url ("/?s2member_paypal_notify=1" . $ipn_q), $ipn, array ("timeout" => 20));
																	}
																/**/
																else if (!$processed) /* Here we add a message to the logs indicating the IPN was ignored; no action taken. */
																	$ccbill["s2member_log"][] = "Ignoring this DataLink IPN. It does NOT require any action on the part of s2Member.";
															}
														/**/
														else if (!$processed) /* Here we add a message to the logs indicating that no IPN vars are available. */
															$ccbill["s2member_log"][] = "Ignoring this DataLink IPN. No IPN signup vars for Subscr. ID: " . $ccbill["dl_ipn"][3] . ".";
														/**/
														$logv = c_ws_plugin__s2member_utilities::ver_details ();
														$logm = c_ws_plugin__s2member_utilities::mem_details ();
														$log4 = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . "\nUser-Agent: " . $_SERVER["HTTP_USER_AGENT"];
														$log4 = (is_multisite () && !is_main_site ()) ? ($_log4 = $current_blog->domain . $current_blog->path) . "\n" . $log4 : $log4;
														$log2 = (is_multisite () && !is_main_site ()) ? "ccbill-dl-ipn-4-" . trim (preg_replace ("/[^a-z0-9]/i", "-", $_log4), "-") . ".log" : "ccbill-dl-ipn.log";
														/**/
														if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"])
															if (is_dir ($logs_dir = $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"]))
																if (is_writable ($logs_dir) && c_ws_plugin__s2member_utils_logs::archive_oversize_log_files ())
																	file_put_contents ($logs_dir . "/" . $log2, $logv . "\n" . $logm . "\n" . $log4 . "\n" . var_export ($ccbill, true) . "\n\n", FILE_APPEND);
													}
												/**/
												unset ($unprocessed_lines[$line]); /* Remove this line and update the list of unprocessed IPN lines. */
												set_transient ("s2m_" . md5 ("s2member_pro_ccbill_datalink_ipns"), implode ("\n", $unprocessed_lines), 31556926);
												/**/
												if ($counter >= $per_process) /* Only this many. */
													break; /* Break the loop now. */
											}
									}
							}
						/**/
						return; /* Return for uniformity. */
					}
			}
	}
?>