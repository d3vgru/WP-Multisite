<?php
/**
* Shortcode `[s2Member-Pro-AuthNet-Form /]` ( inner processing routines ).
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
if (!class_exists ("c_ws_plugin__s2member_pro_authnet_form_in"))
	{
		/**
		* Shortcode `[s2Member-Pro-AuthNet-Form /]` ( inner processing routines ).
		*
		* @package s2Member\AuthNet
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_authnet_form_in
			{
				/**
				* Shortcode `[s2Member-Pro-AuthNet-Form /]`.
				*
				* @package s2Member\AuthNet
				* @since 1.5
				*
				* @attaches-to ``add_shortcode("s2Member-Pro-AuthNet-Form");``
				*
				* @param array $attr An array of Attributes.
				* @param str $content Content inside the Shortcode.
				* @param str $shortcode The actual Shortcode name itself.
				* @return str The resulting Form Code, HTML markup.
				*/
				public static function sc_authnet_form ($attr = FALSE, $content = FALSE, $shortcode = FALSE)
					{
						eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("ws_plugin__s2member_pro_before_sc_authnet_form", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
						/**/
						c_ws_plugin__s2member_no_cache::no_cache_constants /* No caching on pages that contain a Pro Form. */ (true);
						/**/
						$attr = /* Force array. Trim quote entities. */ c_ws_plugin__s2member_utils_strings::trim_qts_deep ((array)$attr);
						/**/
						$attr = shortcode_atts (array ("ids" => "0", "exp" => "72", "level" => (($attr["register"]) ? "0" : "1"), "ccaps" => "", "desc" => "", "cc" => "USD", "custom" => $_SERVER["HTTP_HOST"], "ta" => "0", "tp" => "0", "tt" => "D", "ra" => "0.01", "rp" => "1", "rt" => "M", "rr" => "1", "modify" => "0", "cancel" => "0", "sp" => "0", "register" => "0", "update" => "0", "accept" => "visa,mastercard,amex,discover", "coupon" => "", "accept_coupons" => "0", "default_country_code" => "US", "captcha" => "", "template" => "", "success" => ""), $attr);
						/**/
						$attr["tt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper ($attr["tt"]);
						$attr["rt"] = /* Term lengths absolutely must be provided in upper-case format. Only after running shortcode_atts(). */ strtoupper ($attr["rt"]);
						$attr["rr"] = /* Must be provided in upper-case format. Numerical, or BN value. Only after running shortcode_atts(). */ strtoupper ($attr["rr"]);
						$attr["ccaps"] = /* Custom Capabilities must be typed in lower-case format. Only after running shortcode_atts(). */ strtolower ($attr["ccaps"]);
						$attr["rr"] = /* Lifetime Subscriptions require Buy Now. Only after running shortcode_atts(). */ ($attr["rt"] === "L") ? "BN" : $attr["rr"];
						$attr["rr"] = /* Independent Ccaps require Buy Now. Only after running shortcode_atts(). */ ($attr["level"] === "*") ? "BN" : $attr["rr"];
						$attr["rr"] = /* No Trial / non-recurring. Only after running shortcode_atts(). */ (!$attr["tp"] && !$attr["rr"]) ? "BN" : $attr["rr"];
						$attr["cc"] = /* Authorize.Net® always processes in USD. International transactions are converted automatically to USD. */ "USD";
						$attr["default_country_code"] = /* This MUST be in uppercase format. */ strtoupper ($attr["default_country_code"]);
						$attr["success"] = /* Normalize ampersands. */ c_ws_plugin__s2member_utils_urls::n_amps ($attr["success"]);
						/**/
						$attr["accept"] = (trim ($attr["accept"])) ? preg_split ("/[;,]+/", preg_replace ("/[\r\n\t\s]+/", "", strtolower ($attr["accept"]))) : array ();
						$attr["accept"] = (empty ($attr["accept"])) ? array_merge ($attr["accept"], array ("visa")) : $attr["accept"];
						/**/
						$attr["coupon"] = ($_GET["s2p-coupon"]) ? trim (strip_tags (stripslashes ($_GET["s2p-coupon"]))) : $attr["coupon"];
						/**/
						$attr["singular"] = /* Collect the Singular ID for this Post/Page. */ get_the_ID ();
						/**/
						eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("ws_plugin__s2member_pro_before_sc_authnet_form_after_shortcode_atts", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
						/**/
						if /* Cancellations. */ ($attr["cancel"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST));
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_authnet_responses::authnet_cancellation_response ($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array (): $_p;
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if ($attr["captcha"]) /* Is a captcha being used on this form? */
									{
										$captcha = '<div id="s2member-pro-authnet-cancellation-form-captcha-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-cancellation-form-section s2member-pro-authnet-form-captcha-section s2member-pro-authnet-cancellation-form-captcha-section">' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-cancellation-form-captcha-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-cancellation-form-section-title s2member-pro-authnet-form-captcha-section-title s2member-pro-authnet-cancellation-form-captcha-section-title">' . "\n";
										$captcha .= _x ("Security Code", "s2member-front", "s2member") . "\n";
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-cancellation-form-captcha-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-cancellation-form-div s2member-pro-authnet-form-captcha-div s2member-pro-authnet-cancellation-form-captcha-div">' . "\n";
										/**/
										$captcha .= '<label id="s2member-pro-authnet-cancellation-form-captcha-label" class="s2member-pro-authnet-form-captcha-label s2member-pro-authnet-cancellation-form-captcha-label">' . "\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag ($attr["captcha"], 10) . "\n";
										$captcha .= '</label>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_authnet_cancellation[nonce]" id="s2member-pro-authnet-cancellation-nonce" value="' . esc_attr (wp_create_nonce ("s2member-pro-authnet-cancellation")) . '" />';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_authnet_cancellation[attr]" id="s2member-pro-authnet-cancellation-attr" value="' . esc_attr (c_ws_plugin__s2member_utils_encryption::encrypt (serialize ($attr))) . '" />';
								/*
								Get the form template.
								*/
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-cancellation-form.php")) ? TEMPLATEPATH . "/authnet-cancellation-form.php" : false;
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-cancellation-form.html")) ? TEMPLATEPATH . "/authnet-cancellation-form.html" : $custom_template;
								$custom_template = ($attr["template"] && file_exists (TEMPLATEPATH . "/" . $attr["template"])) ? TEMPLATEPATH . "/" . $attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && file_exists (WP_CONTENT_DIR . "/" . $attr["template"])) ? WP_CONTENT_DIR . "/" . $attr["template"] : $custom_template;
								/**/
								$code = trim (file_get_contents ((($custom_template) ? $custom_template : dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/forms/authnet-cancellation-form.php")));
								$code = trim (((!$custom_template || !is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? c_ws_plugin__s2member_utilities::evl ($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace ("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_SERVER["REQUEST_URI"])), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace ("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($response["response"]), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace ("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($attr["desc"]), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace ("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($captcha), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace ("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($hidden_inputs), $code);
								/**/
								eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
								do_action ("ws_plugin__s2member_pro_during_sc_authnet_cancellation_form", get_defined_vars ());
								unset ($__refs, $__v); /* Unset defined __refs, __v. */
							}
						else if /* Free registrations. */ ($attr["register"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST));
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_authnet_responses::authnet_registration_response ($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array (): $_p;
								/*
								Build all of the custom fields.
								*/
								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"]) /* Only display Custom Fields if configured. */
									if (($fields_applicable = c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level ($attr["level"], "registration")))
										{
											$tabindex = 99; /* Start tabindex at 99 ( +1 below = 100 ). */
											/**/
											$custom_fields = '<div id="s2member-pro-authnet-registration-form-custom-fields-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-registration-form-section s2member-pro-authnet-form-custom-fields-section s2member-pro-authnet-registration-form-custom-fields-section">' . "\n";
											/**/
											$custom_fields .= '<div id="s2member-pro-authnet-registration-form-custom-fields-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-registration-form-section-title s2member-pro-authnet-form-custom-fields-section-title s2member-pro-authnet-registration-form-custom-fields-section-title">' . "\n";
											$custom_fields .= _x ("Additional Info", "s2member-front", "s2member") . "\n";
											$custom_fields .= '</div>' . "\n";
											/**/
											foreach (json_decode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
												{
													if (in_array ($field["id"], $fields_applicable)) /* Field is applicable to Level 0? */
														{
															$field_var = preg_replace ("/[^a-z0-9]/i", "_", strtolower ($field["id"]));
															$field_id_class = preg_replace ("/_/", "-", $field_var);
															/**/
															if (!empty ($field["section"]) && $field["section"] === "yes") /* Starts a new section? */
																$custom_fields .= '<div id="s2member-pro-authnet-registration-form-custom-reg-field-' . $field_id_class . '-divider-section" class="s2member-pro-authnet-form-div s2member-pro-authnet-registration-form-div s2member-pro-authnet-form-custom-reg-field-divider-section' . ((!empty ($field["sectitle"])) ? '-title' : '') . ' s2member-pro-authnet-form-custom-reg-field-' . $field_id_class . '-divider-section' . ((!empty ($field["sectitle"])) ? '-title' : '') . ' s2member-pro-authnet-registration-form-custom-reg-field-' . $field_id_class . '-divider-section' . ((!empty ($field["sectitle"])) ? '-title' : '') . '">' . ((!empty ($field["sectitle"])) ? $field["sectitle"] : '') . '</div>';
															/**/
															$custom_fields .= '<div id="s2member-pro-authnet-registration-form-custom-reg-field-' . $field_id_class . '-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-registration-form-div s2member-pro-authnet-form-custom-reg-field-' . $field_id_class . '-div s2member-pro-authnet-registration-form-custom-reg-field-' . $field_id_class . '-div">' . "\n";
															/**/
															$custom_fields .= '<label for="s2member-pro-authnet-registration-custom-reg-field-' . esc_attr ($field_id_class) . '" id="s2member-pro-authnet-registration-form-custom-reg-field-' . $field_id_class . '-label" class="s2member-pro-authnet-form-custom-reg-field-' . $field_id_class . '-label s2member-pro-authnet-registration-form-custom-reg-field-' . $field_id_class . '-label">' . "\n";
															$custom_fields .= '<span' . ((preg_match ("/^(checkbox|pre_checkbox)$/", $field["type"])) ? ' style="display:none;"' : '') . '>' . $field["label"] . (($field["required"] === "yes") ? ' *' : '') . '</span></label>' . ((preg_match ("/^(checkbox|pre_checkbox)$/", $field["type"])) ? '' : '<br />') . "\n";
															$custom_fields .= c_ws_plugin__s2member_custom_reg_fields::custom_field_gen (__FUNCTION__, $field, "s2member_pro_authnet_registration[custom_fields][", "s2member-pro-authnet-registration-custom-reg-field-", "s2member-pro-authnet-custom-reg-field-" . $field_id_class . " s2member-pro-authnet-registration-custom-reg-field-" . $field_id_class, "", ($tabindex = $tabindex + 1), "", $_p, $_p["s2member_pro_authnet_registration"]["custom_fields"][$field_var], "registration");
															/**/
															$custom_fields .= '</div>' . "\n";
														}
												}
											/**/
											$custom_fields .= '</div>' . "\n";
										}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if ($attr["captcha"]) /* Is a captcha being used on this form? */
									{
										$captcha = '<div id="s2member-pro-authnet-registration-form-captcha-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-registration-form-section s2member-pro-authnet-form-captcha-section s2member-pro-authnet-registration-form-captcha-section">' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-registration-form-captcha-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-registration-form-section-title s2member-pro-authnet-form-captcha-section-title s2member-pro-authnet-registration-form-captcha-section-title">' . "\n";
										$captcha .= _x ("Security Code", "s2member-front", "s2member") . "\n";
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-registration-form-captcha-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-registration-form-div s2member-pro-authnet-form-captcha-div s2member-pro-authnet-registration-form-captcha-div">' . "\n";
										/**/
										$captcha .= '<label id="s2member-pro-authnet-registration-form-captcha-label" class="s2member-pro-authnet-form-captcha-label s2member-pro-authnet-registration-form-captcha-label">' . "\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag ($attr["captcha"], 200) . "\n";
										$captcha .= '</label>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
									}
								/*
								Build the opt-in checkbox.
								*/
								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] && c_ws_plugin__s2member_list_servers::list_servers_integrated ())
									{
										$opt_in = '<div id="s2member-pro-authnet-registration-form-custom-reg-field-opt-in-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-registration-form-div s2member-pro-authnet-form-custom-reg-field-opt-in-div s2member-pro-authnet-registration-form-custom-reg-field-opt-in-div">' . "\n";
										/**/
										$opt_in .= '<label for="s2member-pro-authnet-registration-form-custom-reg-field-opt-in" id="s2member-pro-authnet-registration-form-custom-reg-field-opt-in-label" class="s2member-pro-authnet-form-custom-reg-field-opt-in-label s2member-pro-authnet-registration-form-custom-reg-field-opt-in-label">' . "\n";
										$opt_in .= '<input type="checkbox" name="s2member_pro_authnet_registration[custom_fields][opt_in]" id="s2member-pro-authnet-registration-form-custom-reg-field-opt-in" class="s2member-pro-authnet-form-custom-reg-field-opt-in s2member-pro-authnet-registration-form-custom-reg-field-opt-in" value="1"' . (((empty ($_p) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] == 1) || $_p["s2member_pro_authnet_registration"]["custom_fields"]["opt_in"]) ? ' checked="checked"' : '') . ' tabindex="300" />' . "\n";
										$opt_in .= $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in_label"] . "\n";
										$opt_in .= '</label>' . "\n";
										/**/
										$opt_in .= '</div>' . "\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_authnet_registration[nonce]" id="s2member-pro-authnet-registration-nonce" value="' . esc_attr (wp_create_nonce ("s2member-pro-authnet-registration")) . '" />';
								$hidden_inputs .= (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_names"]) ? '<input type="hidden" id="s2member-pro-authnet-registration-names-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"]) ? '<input type="hidden" id="s2member-pro-authnet-registration-password-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_authnet_registration[attr]" id="s2member-pro-authnet-registration-attr" value="' . esc_attr (c_ws_plugin__s2member_utils_encryption::encrypt (serialize ($attr))) . '" />';
								/*
								Get the form template.
								*/
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-registration-form.php")) ? TEMPLATEPATH . "/authnet-registration-form.php" : false;
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-registration-form.html")) ? TEMPLATEPATH . "/authnet-registration-form.html" : $custom_template;
								$custom_template = ($attr["template"] && file_exists (TEMPLATEPATH . "/" . $attr["template"])) ? TEMPLATEPATH . "/" . $attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && file_exists (WP_CONTENT_DIR . "/" . $attr["template"])) ? WP_CONTENT_DIR . "/" . $attr["template"] : $custom_template;
								/**/
								$code = trim (file_get_contents ((($custom_template) ? $custom_template : dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/forms/authnet-registration-form.php")));
								$code = trim (((!$custom_template || !is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? c_ws_plugin__s2member_utilities::evl ($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace ("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_SERVER["REQUEST_URI"])), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace ("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($response["response"]), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace ("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($attr["desc"]), $code);
								/*
								Fill in the registration section.
								*/
								$code = preg_replace ("/%%first_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_p["s2member_pro_authnet_registration"]["first_name"])), $code);
								$code = preg_replace ("/%%last_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_p["s2member_pro_authnet_registration"]["last_name"])), $code);
								$code = preg_replace ("/%%email_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_registration"]["email"])), $code);
								$code = preg_replace ("/%%username_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_registration"]["username"])), $code);
								$code = preg_replace ("/%%password1_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_registration"]["password1"])), $code);
								$code = preg_replace ("/%%password2_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_registration"]["password2"])), $code);
								/*
								Fill in the custom fields section.
								*/
								$code = preg_replace ("/%%custom_fields%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($custom_fields), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace ("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($captcha), $code);
								/*
								Fill the opt-in box.
								*/
								$code = preg_replace ("/%%opt_in%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($opt_in), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace ("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($hidden_inputs), $code);
								/**/
								eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
								do_action ("ws_plugin__s2member_pro_during_sc_authnet_registration_form", get_defined_vars ());
								unset ($__refs, $__v); /* Unset defined __refs, __v. */
							}
						else if /* Billing information updates. */ ($attr["update"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST));
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_authnet_responses::authnet_update_response ($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array (): $_p;
								/*
								Build the list of card type options.
								*/
								foreach (array ("Visa" => _x ("Visa®", "s2member-front", "s2member"), "MasterCard" => _x ("MasterCard®", "s2member-front", "s2member"), "Discover" => _x ("Discover®", "s2member-front", "s2member"), "Amex" => _x ("American Express®", "s2member-front", "s2member") /* , "Maestro" => _x ("Maestro®", "s2member-front", "s2member"), "Solo" => _x ("Solo®", "s2member-front", "s2member") */) as $card_type_v => $card_type_l)
									$card_type_options .= '<label for="s2member-pro-authnet-update-card-type-' . esc_attr (strtolower ($card_type_v)) . '" id="s2member-pro-authnet-update-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label" class="s2member-pro-authnet-form-card-type-label s2member-pro-authnet-update-form-card-type-label s2member-pro-authnet-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label s2member-pro-authnet-update-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label' . ((!in_array (strtolower ($card_type_v), $attr["accept"])) ? ' disabled' : '') . '">' . "\n" ./**/
									'<input type="radio" aria-required="true" name="s2member_pro_authnet_update[card_type]" id="s2member-pro-authnet-update-card-type-' . esc_attr (strtolower ($card_type_v)) . '" class="s2member-pro-authnet-card-type-' . esc_attr (strtolower ($card_type_v)) . ' s2member-pro-authnet-update-card-type-' . esc_attr (strtolower ($card_type_v)) . '" value="' . esc_attr ($card_type_v) . '"' . ((!empty ($_p["s2member_pro_authnet_update"]["card_type"]) && in_array (strtolower ($_p["s2member_pro_authnet_update"]["card_type"]), $attr["accept"]) && $_p["s2member_pro_authnet_update"]["card_type"] === $card_type_v) ? ' checked="checked"' : '') . ((!in_array (strtolower ($card_type_v), $attr["accept"])) ? ' disabled="disabled"' : '') . ' tabindex="10" />' . "\n" .
										'</label>';
								/*
								Build the list of country code options.
								$country_default_by_currency = false. Authorize.Net® always processes in USD, so the country is empty by default.
								Authorize.Net® always processes in USD. International transactions are converted automatically through banking institutions.
								*/
								$country_default_by_currency = (!$_p["s2member_pro_authnet_update"]["country"] && $attr["cc"] === "USD") ? "US" : $country_default_by_currency;
								$country_default_by_currency = (!$_p["s2member_pro_authnet_update"]["country"] && $attr["cc"] === "CAD") ? "CA" : $country_default_by_currency;
								$country_default_by_currency = (!$_p["s2member_pro_authnet_update"]["country"] && $attr["cc"] === "GBP") ? "GB" : $country_default_by_currency;
								$country_default_by_currency = apply_filters ("ws_plugin__s2member_pro_authnet_default_country", false, get_defined_vars ());
								/**/
								$default_country_v = ($attr["default_country_code"]) ? $attr["default_country_code"] : $country_default_by_currency;
								/**/
								$country_options = '<option value=""></option>'; /* Start with an empty option value. */
								/**/
								foreach (preg_split ("/[\r\n]+/", file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/iso-3166-1.txt")) as $country)
									{
										list ($country_l, $country_v) = preg_split ("/;/", $country, 2);
										/**/
										if ($country_l && $country_v) /* Here we also check on the default pre-selected country; as determined above; based on currency. */
											$country_options .= '<option value="' . esc_attr (strtoupper ($country_v)) . '"' . (($_p["s2member_pro_authnet_update"]["country"] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '') . '>' . esc_html (ucwords (strtolower ($country_l))) . '</option>';
									}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if ($attr["captcha"]) /* Is a captcha being used on this form? */
									{
										$captcha = '<div id="s2member-pro-authnet-update-form-captcha-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-update-form-section s2member-pro-authnet-form-captcha-section s2member-pro-authnet-update-form-captcha-section">' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-update-form-captcha-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-update-form-section-title s2member-pro-authnet-form-captcha-section-title s2member-pro-authnet-update-form-captcha-section-title">' . "\n";
										$captcha .= _x ("Security Code", "s2member-front", "s2member") . "\n";
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-update-form-captcha-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-update-form-div s2member-pro-authnet-form-captcha-div s2member-pro-authnet-update-form-captcha-div">' . "\n";
										/**/
										$captcha .= '<label id="s2member-pro-authnet-update-form-captcha-label" class="s2member-pro-authnet-form-captcha-label s2member-pro-authnet-update-form-captcha-label">' . "\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag ($attr["captcha"], 200) . "\n";
										$captcha .= '</label>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_authnet_update[nonce]" id="s2member-pro-authnet-update-nonce" value="' . esc_attr (wp_create_nonce ("s2member-pro-authnet-update")) . '" />';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_authnet_update[attr]" id="s2member-pro-authnet-update-attr" value="' . esc_attr (c_ws_plugin__s2member_utils_encryption::encrypt (serialize ($attr))) . '" />';
								/*
								Get the form template.
								*/
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-update-form.php")) ? TEMPLATEPATH . "/authnet-update-form.php" : false;
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-update-form.html")) ? TEMPLATEPATH . "/authnet-update-form.html" : $custom_template;
								$custom_template = ($attr["template"] && file_exists (TEMPLATEPATH . "/" . $attr["template"])) ? TEMPLATEPATH . "/" . $attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && file_exists (WP_CONTENT_DIR . "/" . $attr["template"])) ? WP_CONTENT_DIR . "/" . $attr["template"] : $custom_template;
								/**/
								$code = trim (file_get_contents ((($custom_template) ? $custom_template : dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/forms/authnet-update-form.php")));
								$code = trim (((!$custom_template || !is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? c_ws_plugin__s2member_utilities::evl ($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace ("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_SERVER["REQUEST_URI"])), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace ("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($response["response"]), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace ("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($attr["desc"]), $code);
								/*
								Fill in the billing method section.
								*/
								$code = preg_replace ("/%%card_type_options%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($card_type_options), $code);
								$code = preg_replace ("/%%card_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["card_number"])), $code);
								$code = preg_replace ("/%%card_expiration_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["card_expiration"])), $code);
								$code = preg_replace ("/%%card_verification_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["card_verification"])), $code);
								$code = preg_replace ("/%%card_start_date_issue_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["card_start_date_issue_number"])), $code);
								/*
								Fill in the billing address section.
								*/
								$code = preg_replace ("/%%street_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["street"])), $code);
								$code = preg_replace ("/%%city_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["city"])), $code);
								$code = preg_replace ("/%%state_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["state"])), $code);
								$code = preg_replace ("/%%country_options%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($country_options), $code);
								$code = preg_replace ("/%%zip_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_update"]["zip"])), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace ("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($captcha), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace ("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($hidden_inputs), $code);
								/**/
								eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
								do_action ("ws_plugin__s2member_pro_during_sc_authnet_update_form", get_defined_vars ());
								unset ($__refs, $__v); /* Unset defined __refs, __v. */
							}
						else if /* Specific Post/Page Access. */ ($attr["sp"])
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST));
								/*
								Configure internal attributes.
								*/
								$attr["sp_ids_exp"] = "sp:" . $attr["ids"] . ":" . $attr["exp"]; /* Combined `sp:ids:expiration hours`. */
								$attr["coupon"] = ($_p["s2member_pro_authnet_sp_checkout"]["coupon"]) ? $_p["s2member_pro_authnet_sp_checkout"]["coupon"] : $attr["coupon"];
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_authnet_responses::authnet_sp_checkout_response ($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array (): $_p;
								/*
								Build the list of card type options.
								*/
								foreach (array ("Visa" => _x ("Visa®", "s2member-front", "s2member"), "MasterCard" => _x ("MasterCard®", "s2member-front", "s2member"), "Discover" => _x ("Discover®", "s2member-front", "s2member"), "Amex" => _x ("American Express®", "s2member-front", "s2member") /* , "Maestro" => _x ("Maestro®", "s2member-front", "s2member"), "Solo" => _x ("Solo®", "s2member-front", "s2member") */) as $card_type_v => $card_type_l)
									$card_type_options .= '<label for="s2member-pro-authnet-sp-checkout-card-type-' . esc_attr (strtolower ($card_type_v)) . '" id="s2member-pro-authnet-sp-checkout-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label" class="s2member-pro-authnet-form-card-type-label s2member-pro-authnet-sp-checkout-form-card-type-label s2member-pro-authnet-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label s2member-pro-authnet-sp-checkout-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label' . ((!in_array (strtolower ($card_type_v), $attr["accept"])) ? ' disabled' : '') . '">' . "\n" ./**/
									'<input type="radio" aria-required="true" name="s2member_pro_authnet_sp_checkout[card_type]" id="s2member-pro-authnet-sp-checkout-card-type-' . esc_attr (strtolower ($card_type_v)) . '" class="s2member-pro-authnet-card-type-' . esc_attr (strtolower ($card_type_v)) . ' s2member-pro-authnet-sp-checkout-card-type-' . esc_attr (strtolower ($card_type_v)) . '" value="' . esc_attr ($card_type_v) . '"' . ((!empty ($_p["s2member_pro_authnet_sp_checkout"]["card_type"]) && in_array (strtolower ($_p["s2member_pro_authnet_sp_checkout"]["card_type"]), $attr["accept"]) && $_p["s2member_pro_authnet_sp_checkout"]["card_type"] === $card_type_v) ? ' checked="checked"' : '') . ((!in_array (strtolower ($card_type_v), $attr["accept"])) ? ' disabled="disabled"' : '') . ' tabindex="100" />' . "\n" .
										'</label>';
								/*
								Build the list of country code options.
								$country_default_by_currency = false. Authorize.Net® always processes in USD, so the country is empty by default.
								Authorize.Net® always processes in USD. International transactions are converted automatically through banking institutions.
								*/
								$country_default_by_currency = (!$_p["s2member_pro_authnet_sp_checkout"]["country"] && $attr["cc"] === "USD") ? "US" : $country_default_by_currency;
								$country_default_by_currency = (!$_p["s2member_pro_authnet_sp_checkout"]["country"] && $attr["cc"] === "CAD") ? "CA" : $country_default_by_currency;
								$country_default_by_currency = (!$_p["s2member_pro_authnet_sp_checkout"]["country"] && $attr["cc"] === "GBP") ? "GB" : $country_default_by_currency;
								$country_default_by_currency = apply_filters ("ws_plugin__s2member_pro_authnet_default_country", false, get_defined_vars ());
								/**/
								$default_country_v = ($attr["default_country_code"]) ? $attr["default_country_code"] : $country_default_by_currency;
								/**/
								$country_options = '<option value=""></option>'; /* Start with an empty option value. */
								/**/
								foreach (preg_split ("/[\r\n]+/", file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/iso-3166-1.txt")) as $country)
									{
										list ($country_l, $country_v) = preg_split ("/;/", $country, 2);
										/**/
										if ($country_l && $country_v) /* Here we also check on the default pre-selected country; as determined above; based on currency. */
											$country_options .= '<option value="' . esc_attr (strtoupper ($country_v)) . '"' . (($_p["s2member_pro_authnet_sp_checkout"]["country"] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '') . '>' . esc_html (ucwords (strtolower ($country_l))) . '</option>';
									}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if ($attr["captcha"]) /* Is a captcha being used on this form? */
									{
										$captcha = '<div id="s2member-pro-authnet-sp-checkout-form-captcha-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-sp-checkout-form-section s2member-pro-authnet-form-captcha-section s2member-pro-authnet-sp-checkout-form-captcha-section">' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-sp-checkout-form-captcha-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-sp-checkout-form-section-title s2member-pro-authnet-form-captcha-section-title s2member-pro-authnet-sp-checkout-form-captcha-section-title">' . "\n";
										$captcha .= _x ("Security Code", "s2member-front", "s2member") . "\n";
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-sp-checkout-form-captcha-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-sp-checkout-form-div s2member-pro-authnet-form-captcha-div s2member-pro-authnet-sp-checkout-form-captcha-div">' . "\n";
										/**/
										$captcha .= '<label id="s2member-pro-authnet-sp-checkout-form-captcha-label" class="s2member-pro-authnet-form-captcha-label s2member-pro-authnet-sp-checkout-form-captcha-label">' . "\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag ($attr["captcha"], 300) . "\n";
										$captcha .= '</label>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
									}
								/*
								Build the opt-in checkbox.
								*/
								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] && c_ws_plugin__s2member_list_servers::list_servers_integrated ())
									{
										$opt_in = '<div id="s2member-pro-authnet-sp-checkout-form-custom-reg-field-opt-in-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-sp-checkout-form-div s2member-pro-authnet-form-custom-reg-field-opt-in-div s2member-pro-authnet-sp-checkout-form-custom-reg-field-opt-in-div">' . "\n";
										/**/
										$opt_in .= '<label for="s2member-pro-authnet-sp-checkout-form-custom-reg-field-opt-in" id="s2member-pro-authnet-sp-checkout-form-custom-reg-field-opt-in-label" class="s2member-pro-authnet-form-custom-reg-field-opt-in-label s2member-pro-authnet-sp-checkout-form-custom-reg-field-opt-in-label">' . "\n";
										$opt_in .= '<input type="checkbox" name="s2member_pro_authnet_sp_checkout[custom_fields][opt_in]" id="s2member-pro-authnet-sp-checkout-form-custom-reg-field-opt-in" class="s2member-pro-authnet-form-custom-reg-field-opt-in s2member-pro-authnet-sp-checkout-form-custom-reg-field-opt-in" value="1"' . (((empty ($_p) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] == 1) || $_p["s2member_pro_authnet_sp_checkout"]["custom_fields"]["opt_in"]) ? ' checked="checked"' : '') . ' tabindex="400" />' . "\n";
										$opt_in .= $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in_label"] . "\n";
										$opt_in .= '</label>' . "\n";
										/**/
										$opt_in .= '</div>' . "\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_authnet_sp_checkout[nonce]" id="s2member-pro-authnet-sp-checkout-nonce" value="' . esc_attr (wp_create_nonce ("s2member-pro-authnet-sp-checkout")) . '" />';
								$hidden_inputs .= (!$attr["accept_coupons"]) ? '<input type="hidden" id="s2member-pro-authnet-sp-checkout-coupons-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!c_ws_plugin__s2member_pro_authnet_utilities::authnet_tax_may_apply ()) ? '<input type="hidden" id="s2member-pro-authnet-sp-checkout-tax-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_authnet_sp_checkout[attr]" id="s2member-pro-authnet-sp-checkout-attr" value="' . esc_attr (c_ws_plugin__s2member_utils_encryption::encrypt (serialize ($attr))) . '" />';
								/*
								Get the form template.
								*/
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-sp-checkout-form.php")) ? TEMPLATEPATH . "/authnet-sp-checkout-form.php" : false;
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-sp-checkout-form.html")) ? TEMPLATEPATH . "/authnet-sp-checkout-form.html" : $custom_template;
								$custom_template = ($attr["template"] && file_exists (TEMPLATEPATH . "/" . $attr["template"])) ? TEMPLATEPATH . "/" . $attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && file_exists (WP_CONTENT_DIR . "/" . $attr["template"])) ? WP_CONTENT_DIR . "/" . $attr["template"] : $custom_template;
								/**/
								$code = trim (file_get_contents ((($custom_template) ? $custom_template : dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/forms/authnet-sp-checkout-form.php")));
								$code = trim (((!$custom_template || !is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? c_ws_plugin__s2member_utilities::evl ($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace ("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_SERVER["REQUEST_URI"])), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace ("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($response["response"]), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace ("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($attr["desc"]), $code);
								/*
								Fill in the coupon value.
								*/
								$code = preg_replace ("/%%coupon_response%%/", c_ws_plugin__s2member_utils_strings::esc_ds (c_ws_plugin__s2member_pro_authnet_utilities::authnet_apply_coupon ($attr, $attr["coupon"], "response", array ("affiliates-1px-response"))), $code);
								$code = preg_replace ("/%%coupon_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($attr["coupon"])), $code);
								/*
								Fill in the registration section.
								*/
								$code = preg_replace ("/%%first_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_p["s2member_pro_authnet_sp_checkout"]["first_name"])), $code);
								$code = preg_replace ("/%%last_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_p["s2member_pro_authnet_sp_checkout"]["last_name"])), $code);
								$code = preg_replace ("/%%email_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["email"])), $code);
								/*
								Fill in the billing method section.
								*/
								$code = preg_replace ("/%%card_type_options%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($card_type_options), $code);
								$code = preg_replace ("/%%card_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["card_number"])), $code);
								$code = preg_replace ("/%%card_expiration_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["card_expiration"])), $code);
								$code = preg_replace ("/%%card_verification_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["card_verification"])), $code);
								$code = preg_replace ("/%%card_start_date_issue_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["card_start_date_issue_number"])), $code);
								/*
								Fill in the billing address section.
								*/
								$code = preg_replace ("/%%street_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["street"])), $code);
								$code = preg_replace ("/%%city_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["city"])), $code);
								$code = preg_replace ("/%%state_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["state"])), $code);
								$code = preg_replace ("/%%country_options%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($country_options), $code);
								$code = preg_replace ("/%%zip_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_sp_checkout"]["zip"])), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace ("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($captcha), $code);
								/*
								Fill the opt-in box.
								*/
								$code = preg_replace ("/%%opt_in%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($opt_in), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace ("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($hidden_inputs), $code);
								/**/
								eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
								do_action ("ws_plugin__s2member_pro_during_sc_authnet_sp_form", get_defined_vars ());
								unset ($__refs, $__v); /* Unset defined __refs, __v. */
							}
						else /* Signups and Modifications. */
							{
								$_p = c_ws_plugin__s2member_utils_strings::trim_deep (stripslashes_deep ($_POST));
								/*
								Configure internal attributes.
								*/
								$attr["level_ccaps_eotper"] = ($attr["rr"] === "BN" && $attr["rt"] !== "L") ? $attr["level"] . ":" . $attr["ccaps"] . ":" . $attr["rp"] . " " . $attr["rt"] : $attr["level"] . ":" . $attr["ccaps"];
								$attr["level_ccaps_eotper"] = rtrim ($attr["level_ccaps_eotper"], ":"); /* Clean any trailing separators from this string. */
								$attr["coupon"] = ($_p["s2member_pro_authnet_checkout"]["coupon"]) ? $_p["s2member_pro_authnet_checkout"]["coupon"] : $attr["coupon"];
								/*
								Obtain a possible response and/or validation error.
								*/
								$response = c_ws_plugin__s2member_pro_authnet_responses::authnet_checkout_response ($attr);
								/*
								Empty post vars on successful response.
								*/
								$_p = ($response["response"] && !$response["error"]) ? array (): $_p;
								/*
								Build the list of card type options.
								*/
								foreach (array ("Visa" => _x ("Visa®", "s2member-front", "s2member"), "MasterCard" => _x ("MasterCard®", "s2member-front", "s2member"), "Discover" => _x ("Discover®", "s2member-front", "s2member"), "Amex" => _x ("American Express®", "s2member-front", "s2member") /* , "Maestro" => _x ("Maestro®", "s2member-front", "s2member"), "Solo" => _x ("Solo®", "s2member-front", "s2member") */) as $card_type_v => $card_type_l)
									$card_type_options .= '<label for="s2member-pro-authnet-checkout-card-type-' . esc_attr (strtolower ($card_type_v)) . '" id="s2member-pro-authnet-checkout-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label" class="s2member-pro-authnet-form-card-type-label s2member-pro-authnet-checkout-form-card-type-label s2member-pro-authnet-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label s2member-pro-authnet-checkout-form-card-type-' . esc_attr (strtolower ($card_type_v)) . '-label' . ((!in_array (strtolower ($card_type_v), $attr["accept"])) ? ' disabled' : '') . '">' . "\n" ./**/
									'<input type="radio" aria-required="true" name="s2member_pro_authnet_checkout[card_type]" id="s2member-pro-authnet-checkout-card-type-' . esc_attr (strtolower ($card_type_v)) . '" class="s2member-pro-authnet-card-type-' . esc_attr (strtolower ($card_type_v)) . ' s2member-pro-authnet-checkout-card-type-' . esc_attr (strtolower ($card_type_v)) . '" value="' . esc_attr ($card_type_v) . '"' . ((!empty ($_p["s2member_pro_authnet_checkout"]["card_type"]) && in_array (strtolower ($_p["s2member_pro_authnet_checkout"]["card_type"]), $attr["accept"]) && $_p["s2member_pro_authnet_checkout"]["card_type"] === $card_type_v) ? ' checked="checked"' : '') . ((!in_array (strtolower ($card_type_v), $attr["accept"])) ? ' disabled="disabled"' : '') . ' tabindex="200" />' . "\n" .
										'</label>';
								/*
								Build the list of country code options.
								$country_default_by_currency = false. Authorize.Net® always processes in USD, so the country is empty by default.
								Authorize.Net® always processes in USD. International transactions are converted automatically through banking institutions.
								*/
								$country_default_by_currency = (!$_p["s2member_pro_authnet_checkout"]["country"] && $attr["cc"] === "USD") ? "US" : $country_default_by_currency;
								$country_default_by_currency = (!$_p["s2member_pro_authnet_checkout"]["country"] && $attr["cc"] === "CAD") ? "CA" : $country_default_by_currency;
								$country_default_by_currency = (!$_p["s2member_pro_authnet_checkout"]["country"] && $attr["cc"] === "GBP") ? "GB" : $country_default_by_currency;
								$country_default_by_currency = apply_filters ("ws_plugin__s2member_pro_authnet_default_country", false, get_defined_vars ());
								/**/
								$default_country_v = ($attr["default_country_code"]) ? $attr["default_country_code"] : $country_default_by_currency;
								/**/
								$country_options = '<option value=""></option>'; /* Start with an empty option value. */
								/**/
								foreach (preg_split ("/[\r\n]+/", file_get_contents (dirname (dirname (dirname (dirname (__FILE__)))) . "/iso-3166-1.txt")) as $country)
									{
										list ($country_l, $country_v) = preg_split ("/;/", $country, 2);
										/**/
										if ($country_l && $country_v) /* Here we also check on the default pre-selected country; as determined above; based on currency. */
											$country_options .= '<option value="' . esc_attr (strtoupper ($country_v)) . '"' . (($_p["s2member_pro_authnet_checkout"]["country"] === $country_v || $default_country_v === $country_v) ? ' selected="selected"' : '') . '>' . esc_html (ucwords (strtolower ($country_l))) . '</option>';
									}
								/*
								Build all of the custom fields.
								*/
								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"]) /* Only display Custom Fields if configured. */
									if (($fields_applicable = c_ws_plugin__s2member_custom_reg_fields::custom_fields_configured_at_level ((($attr["level"] === "*") ? "auto-detection" : $attr["level"]), "registration")))
										{
											$tabindex = 99; /* Start tabindex at 99 ( +1 below = 100 ). */
											/**/
											$custom_fields = '<div id="s2member-pro-authnet-checkout-form-custom-fields-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-custom-fields-section s2member-pro-authnet-checkout-form-custom-fields-section">' . "\n";
											/**/
											$custom_fields .= '<div id="s2member-pro-authnet-checkout-form-custom-fields-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-checkout-form-section-title s2member-pro-authnet-form-custom-fields-section-title s2member-pro-authnet-checkout-form-custom-fields-section-title">' . "\n";
											$custom_fields .= _x ("Additional Info", "s2member-front", "s2member") . "\n";
											$custom_fields .= '</div>' . "\n";
											/**/
											foreach (json_decode ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_fields"], true) as $field)
												{
													if (in_array ($field["id"], $fields_applicable)) /* Field is applicable to this Level? */
														{
															$field_var = preg_replace ("/[^a-z0-9]/i", "_", strtolower ($field["id"]));
															$field_id_class = preg_replace ("/_/", "-", $field_var);
															/**/
															if (!empty ($field["section"]) && $field["section"] === "yes") /* Starts a new section? */
																$custom_fields .= '<div id="s2member-pro-authnet-checkout-form-custom-reg-field-' . $field_id_class . '-divider-section" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-custom-reg-field-divider-section' . ((!empty ($field["sectitle"])) ? '-title' : '') . ' s2member-pro-authnet-form-custom-reg-field-' . $field_id_class . '-divider-section' . ((!empty ($field["sectitle"])) ? '-title' : '') . ' s2member-pro-authnet-checkout-form-custom-reg-field-' . $field_id_class . '-divider-section' . ((!empty ($field["sectitle"])) ? '-title' : '') . '">' . ((!empty ($field["sectitle"])) ? $field["sectitle"] : '') . '</div>';
															/**/
															$custom_fields .= '<div id="s2member-pro-authnet-checkout-form-custom-reg-field-' . $field_id_class . '-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-custom-reg-field-' . $field_id_class . '-div s2member-pro-authnet-checkout-form-custom-reg-field-' . $field_id_class . '-div">' . "\n";
															/**/
															$custom_fields .= '<label for="s2member-pro-authnet-checkout-custom-reg-field-' . esc_attr ($field_id_class) . '" id="s2member-pro-authnet-checkout-form-custom-reg-field-' . $field_id_class . '-label" class="s2member-pro-authnet-form-custom-reg-field-' . $field_id_class . '-label s2member-pro-authnet-checkout-form-custom-reg-field-' . $field_id_class . '-label">' . "\n";
															$custom_fields .= '<span' . ((preg_match ("/^(checkbox|pre_checkbox)$/", $field["type"])) ? ' style="display:none;"' : '') . '>' . $field["label"] . (($field["required"] === "yes") ? ' *' : '') . '</span></label>' . ((preg_match ("/^(checkbox|pre_checkbox)$/", $field["type"])) ? '' : '<br />') . "\n";
															$custom_fields .= c_ws_plugin__s2member_custom_reg_fields::custom_field_gen (__FUNCTION__, $field, "s2member_pro_authnet_checkout[custom_fields][", "s2member-pro-authnet-checkout-custom-reg-field-", "s2member-pro-authnet-custom-reg-field-" . $field_id_class . " s2member-pro-authnet-checkout-custom-reg-field-" . $field_id_class, "", ($tabindex = $tabindex + 1), "", $_p, $_p["s2member_pro_authnet_checkout"]["custom_fields"][$field_var], "registration");
															/**/
															$custom_fields .= '</div>' . "\n";
														}
												}
											/**/
											$custom_fields .= '</div>' . "\n";
										}
								/*
								Build the reCaptcha box via JavaScript.
								*/
								if ($attr["captcha"]) /* Is a captcha being used on this form? */
									{
										$captcha = '<div id="s2member-pro-authnet-checkout-form-captcha-section" class="s2member-pro-authnet-form-section s2member-pro-authnet-checkout-form-section s2member-pro-authnet-form-captcha-section s2member-pro-authnet-checkout-form-captcha-section">' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-checkout-form-captcha-section-title" class="s2member-pro-authnet-form-section-title s2member-pro-authnet-checkout-form-section-title s2member-pro-authnet-form-captcha-section-title s2member-pro-authnet-checkout-form-captcha-section-title">' . "\n";
										$captcha .= _x ("Security Code", "s2member-front", "s2member") . "\n";
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '<div id="s2member-pro-authnet-checkout-form-captcha-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-captcha-div s2member-pro-authnet-checkout-form-captcha-div">' . "\n";
										/**/
										$captcha .= '<label id="s2member-pro-authnet-checkout-form-captcha-label" class="s2member-pro-authnet-form-captcha-label s2member-pro-authnet-checkout-form-captcha-label">' . "\n";
										$captcha .= c_ws_plugin__s2member_utils_captchas::recaptcha_script_tag ($attr["captcha"], 400) . "\n";
										$captcha .= '</label>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
										/**/
										$captcha .= '</div>' . "\n";
									}
								/*
								Build the opt-in checkbox.
								*/
								if ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] && c_ws_plugin__s2member_list_servers::list_servers_integrated ())
									{
										$opt_in = '<div id="s2member-pro-authnet-checkout-form-custom-reg-field-opt-in-div" class="s2member-pro-authnet-form-div s2member-pro-authnet-checkout-form-div s2member-pro-authnet-form-custom-reg-field-opt-in-div s2member-pro-authnet-checkout-form-custom-reg-field-opt-in-div">' . "\n";
										/**/
										$opt_in .= '<label for="s2member-pro-authnet-checkout-form-custom-reg-field-opt-in" id="s2member-pro-authnet-checkout-form-custom-reg-field-opt-in-label" class="s2member-pro-authnet-form-custom-reg-field-opt-in-label s2member-pro-authnet-checkout-form-custom-reg-field-opt-in-label">' . "\n";
										$opt_in .= '<input type="checkbox" name="s2member_pro_authnet_checkout[custom_fields][opt_in]" id="s2member-pro-authnet-checkout-form-custom-reg-field-opt-in" class="s2member-pro-authnet-form-custom-reg-field-opt-in s2member-pro-authnet-checkout-form-custom-reg-field-opt-in" value="1"' . (((empty ($_p) && $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in"] == 1) || $_p["s2member_pro_authnet_checkout"]["custom_fields"]["opt_in"]) ? ' checked="checked"' : '') . ' tabindex="500" />' . "\n";
										$opt_in .= $GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_opt_in_label"] . "\n";
										$opt_in .= '</label>' . "\n";
										/**/
										$opt_in .= '</div>' . "\n";
									}
								/*
								Build the hidden input variables.
								*/
								$hidden_inputs = '<input type="hidden" name="s2member_pro_authnet_checkout[nonce]" id="s2member-pro-authnet-checkout-nonce" value="' . esc_attr (wp_create_nonce ("s2member-pro-authnet-checkout")) . '" />';
								$hidden_inputs .= (!$attr["accept_coupons"]) ? '<input type="hidden" id="s2member-pro-authnet-checkout-coupons-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["custom_reg_password"]) ? '<input type="hidden" id="s2member-pro-authnet-checkout-password-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= (!c_ws_plugin__s2member_pro_authnet_utilities::authnet_tax_may_apply ()) ? '<input type="hidden" id="s2member-pro-authnet-checkout-tax-not-required-or-not-possible" value="1" />' : '';
								$hidden_inputs .= '<input type="hidden" name="s2member_pro_authnet_checkout[attr]" id="s2member-pro-authnet-checkout-attr" value="' . esc_attr (c_ws_plugin__s2member_utils_encryption::encrypt (serialize ($attr))) . '" />';
								/*
								Get the form template.
								*/
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-checkout-form.php")) ? TEMPLATEPATH . "/authnet-checkout-form.php" : false;
								$custom_template = (file_exists (TEMPLATEPATH . "/authnet-checkout-form.html")) ? TEMPLATEPATH . "/authnet-checkout-form.html" : $custom_template;
								$custom_template = ($attr["template"] && file_exists (TEMPLATEPATH . "/" . $attr["template"])) ? TEMPLATEPATH . "/" . $attr["template"] : $custom_template;
								$custom_template = ($attr["template"] && file_exists (WP_CONTENT_DIR . "/" . $attr["template"])) ? WP_CONTENT_DIR . "/" . $attr["template"] : $custom_template;
								/**/
								$code = trim (file_get_contents ((($custom_template) ? $custom_template : dirname (dirname (dirname (dirname (__FILE__)))) . "/templates/forms/authnet-checkout-form.php")));
								$code = trim (((!$custom_template || !is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? c_ws_plugin__s2member_utilities::evl ($code) : $code));
								/*
								Fill in the action.
								*/
								$code = preg_replace ("/%%action%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_SERVER["REQUEST_URI"])), $code);
								/*
								Fill in the response.
								*/
								$code = preg_replace ("/%%response%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($response["response"]), $code);
								/*
								Fill in the description.
								*/
								$code = preg_replace ("/%%description%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($attr["desc"]), $code);
								/*
								Fill in the coupon value.
								*/
								$code = preg_replace ("/%%coupon_response%%/", c_ws_plugin__s2member_utils_strings::esc_ds (c_ws_plugin__s2member_pro_authnet_utilities::authnet_apply_coupon ($attr, $attr["coupon"], "response", array ("affiliates-1px-response"))), $code);
								$code = preg_replace ("/%%coupon_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($attr["coupon"])), $code);
								/*
								Fill in the registration section.
								*/
								$code = preg_replace ("/%%first_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_p["s2member_pro_authnet_checkout"]["first_name"])), $code);
								$code = preg_replace ("/%%last_name_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (esc_attr ($_p["s2member_pro_authnet_checkout"]["last_name"])), $code);
								$code = preg_replace ("/%%email_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["email"])), $code);
								$code = preg_replace ("/%%username_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["username"])), $code);
								$code = preg_replace ("/%%password1_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["password1"])), $code);
								$code = preg_replace ("/%%password2_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["password2"])), $code);
								/*
								Fill in the custom fields section.
								*/
								$code = preg_replace ("/%%custom_fields%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($custom_fields), $code);
								/*
								Fill in the billing method section.
								*/
								$code = preg_replace ("/%%card_type_options%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($card_type_options), $code);
								$code = preg_replace ("/%%card_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["card_number"])), $code);
								$code = preg_replace ("/%%card_expiration_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["card_expiration"])), $code);
								$code = preg_replace ("/%%card_verification_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["card_verification"])), $code);
								$code = preg_replace ("/%%card_start_date_issue_number_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["card_start_date_issue_number"])), $code);
								/*
								Fill in the billing address section.
								*/
								$code = preg_replace ("/%%street_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["street"])), $code);
								$code = preg_replace ("/%%city_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["city"])), $code);
								$code = preg_replace ("/%%state_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["state"])), $code);
								$code = preg_replace ("/%%country_options%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($country_options), $code);
								$code = preg_replace ("/%%zip_value%%/", c_ws_plugin__s2member_utils_strings::esc_ds (format_to_edit ($_p["s2member_pro_authnet_checkout"]["zip"])), $code);
								/*
								Fill the captcha section.
								*/
								$code = preg_replace ("/%%captcha%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($captcha), $code);
								/*
								Fill the opt-in box.
								*/
								$code = preg_replace ("/%%opt_in%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($opt_in), $code);
								/*
								Fill hidden inputs.
								*/
								$code = preg_replace ("/%%hidden_inputs%%/", c_ws_plugin__s2member_utils_strings::esc_ds ($hidden_inputs), $code);
								/**/
								eval('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
								($attr["modify"]) ? do_action ("ws_plugin__s2member_pro_during_sc_authnet_modification_form", get_defined_vars ()) : do_action ("ws_plugin__s2member_pro_during_sc_authnet_form", get_defined_vars ());
								unset ($__refs, $__v); /* Unset defined __refs, __v. */
							}
						/**/
						return apply_filters ("ws_plugin__s2member_pro_sc_authnet_form", $code, get_defined_vars ());
					}
			}
	}
?>