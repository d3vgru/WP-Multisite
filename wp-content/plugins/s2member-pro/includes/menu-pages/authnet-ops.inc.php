<?php
/**
* Menu page for s2Member Pro ( Authorize.Net® Options page ).
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
* @package s2Member\Menu_Pages
* @since 1.5
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_menu_page_authnet_ops"))
	{
		/**
		* Menu page for s2Member Pro ( Authorize.Net® Options page ).
		*
		* @package s2Member\Menu_Pages
		* @since 110531
		*/
		class c_ws_plugin__s2member_pro_menu_page_authnet_ops
			{
				public function __construct ()
					{
						echo '<div class="wrap ws-menu-page">' . "\n";
						/**/
						echo '<div id="icon-plugins" class="icon32"><br /></div>' . "\n";
						echo '<h2>s2Member® Pro / Authorize.Net® Options</h2>' . "\n";
						/**/
						echo '<table class="ws-menu-page-table">' . "\n";
						echo '<tbody class="ws-menu-page-table-tbody">' . "\n";
						echo '<tr class="ws-menu-page-table-tr">' . "\n";
						echo '<td class="ws-menu-page-table-l">' . "\n";
						/**/
						echo '<form method="post" name="ws_plugin__s2member_pro_options_form" id="ws-plugin--s2member-pro-options-form">' . "\n";
						echo '<input type="hidden" name="ws_plugin__s2member_options_save" id="ws-plugin--s2member-options-save" value="' . esc_attr (wp_create_nonce ("ws-plugin--s2member-options-save")) . '" />' . "\n";
						/**/
						echo '<div class="ws-menu-page-group" title="Authorize.Net® Account Details">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-authnet-account-details-section">' . "\n";
						echo '<h3>Authorize.Net® Account Details ( required )</h3>' . "\n";
						echo '<p><a href="http://www.s2member.com/authorize.net" target="_blank" rel="external">Authorize.Net®</a> is a leading provider of payment gateway services, managing the submission of billions of transactions to processing networks on behalf of merchant customers. Authorize.Net is a solution offered by the CyberSource Corporation, a wholly owned subsidiary of Visa (NYSE: V).</p>' . "\n";
						echo '<p>s2Member has been integrated with Authorize.Net® for Direct Payments and also for ARB (Automated Recurring Billing). In order to take advantage of this integration, you will need to have an Authorize.Net® Merchant Account. Once you have an account, all of the details below can be obtained from inside of your Authorize.Net® account. If you need assistance, please check their <a href="http://www.s2member.com/authorize.net-developers" target="_blank" rel="external">help section</a>.</p>' . "\n";
						echo '<p><em><strong>*Authorize.Net® Version ( 3.1 )*</strong> s2Member integrates with Transaction Version 3.1 for Authorize.Net®. Please log into your Authorize.Net® Merchant account and make sure your Transaction Version setting is configured as: <code>3.1</code>. You will find this inside your Authorize.Net® Merchant account, under: <code>Account -> Settings -> Transaction Version</code>.</em></p>' . "\n";
						echo '<p><em><strong>*Recurring Billing*</strong> If you plan to use any of the ( `Subscription` ) options in the s2Member Form Generator for Authorize.Net®, you will ALSO need <a href="http://www.s2member.com/authorize.net-arb" target="_blank" rel="external">ARB ( Automated Recurring Billing )</a> enabled for your Authorize.Net® account. Authorize.Net\'s Recurring Billing service is <strong>required</strong> for all types of ( `Subscriptions` ), whether you intend for them to be recurring or not. However, it is NOT required for ( `Buy Now` ) functionality. The drop-down menus in the s2Member Form Generator, have been marked ( `Subscription` ) and ( `Buy Now` ) just for this reason. See: <code>s2Member -> Authorize.Net® Pro Forms</code>. This way you can see which options will require the use of Authorize.Net\'s Recurring Billing service. Authorize.Net® will charge you a small monthly fee for their Automated Recurring Billing service.</em></p>' . "\n";
						echo (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? '<p><em><strong>*Secure Server*</strong> In order to comply with Authorize.Net® and PCI Compliance policies, as set forth by major credit card companies; you will need to host all of your Authorize.Net® Pro Forms on an SSL enabled site. Please check with your hosting provider to ask about obtaining an SSL certificate for your domain. Please note... when you create Authorize.Net® Pro Forms with s2Member; you\'ll be supplied with WordPress® Shortcodes, which you\'ll insert into Posts/Pages of your choosing. These special Posts/Pages will need to be displayed in SSL mode, using links that start with ( <code>https://</code> ). &mdash; You can skip the SSL certificate during Development/Sandbox testing. SSL is not required until you officially go live. Once you\'re live, you can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page.</em></p>' . "\n" : '<p><em><strong>*Secure Server*</strong> In order to comply with Authorize.Net® and PCI Compliance policies, as set forth by major credit card companies; you will need to host all of your Authorize.Net® Pro Forms on an SSL enabled page. When you create Authorize.Net® Pro Forms with s2Member; you\'ll be supplied with WordPress® Shortcodes, which you\'ll insert into Posts/Pages of your choosing. These special Posts/Pages will need to be displayed in SSL mode, using links that start with ( <code>https://</code> ). You can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page that contains a Pro Form Shortcode. This tells s2Member to force those special Posts/Pages to be viewed over SSL at all times; no matter what.</em></p>' . "\n";
						echo (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? '<p><em><strong>*SSL Compatibility*</strong> All themes available at <a href="http://www.primothemes.com/" target="_blank" rel="external">PriMoThemes.com</a> include full support for SSL, as does WordPress® itself. However, there are many themes/plugins that do NOT support SSL enabled Posts/Pages like they should. For this reason, you should be very careful when choosing a WordPress® theme to use with s2Member Pro. Otherwise, your visitors could see the famous "Secure/Insecure" warnings in Internet Explorer® browsers. With s2Member installed, you can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page. s2Member will buffer output on those special Posts/Pages, converting everything over to <code>https://</code> for you automatically, and forcing those specific Posts/Pages to be viewed over a secure SSL connection; so long as your server supports the https protocol.</em></p>' . "\n" : '';
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-authnet-api-login-id">' . "\n";
						echo 'Authorize.Net® API Login ID:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_authnet_api_login_id" id="ws-plugin--s2member-pro-authnet-api-login-id" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_authnet_api_login_id"]) . '" /><br />' . "\n";
						echo 'You\'ll find this in your Authorize.Net® Merchant account, under: <code>Account -> Settings</code>.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-authnet-api-trans-key">' . "\n";
						echo 'Authorize.Net® API Transaction Key:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="password" autocomplete="off" name="ws_plugin__s2member_pro_authnet_api_trans_key" id="ws-plugin--s2member-pro-authnet-api-trans-key" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_authnet_api_trans_key"]) . '" /><br />' . "\n";
						echo 'You\'ll find this in your Authorize.Net® Merchant account, under: <code>Account -> Settings</code>.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-authnet-api-salt-key">' . "\n";
						echo 'Authorize.Net® Secret MD5 Hash:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="password" autocomplete="off" name="ws_plugin__s2member_pro_authnet_api_salt_key" id="ws-plugin--s2member-pro-authnet-api-salt-key" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_authnet_api_salt_key"]) . '" /><br />' . "\n";
						echo 'You\'ll set this in your Authorize.Net® Merchant account, under: <code>Account -> Settings</code>.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						/**/
						echo '<div class="ws-menu-page-hr"></div>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th style="padding-top:0;">' . "\n";
						echo '<label for="ws-plugin--s2member-pro-authnet-sandbox">' . "\n";
						echo 'Developer/Sandbox Testing?' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="radio" name="ws_plugin__s2member_pro_authnet_sandbox" id="ws-plugin--s2member-pro-authnet-sandbox-0" value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_authnet_sandbox"]) ? ' checked="checked"' : '') . ' /> <label for="ws-plugin--s2member-pro-authnet-sandbox-0">No</label> &nbsp;&nbsp;&nbsp; <input type="radio" name="ws_plugin__s2member_pro_authnet_sandbox" id="ws-plugin--s2member-pro-authnet-sandbox-1" value="1"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_authnet_sandbox"]) ? ' checked="checked"' : '') . ' /> <label for="ws-plugin--s2member-pro-authnet-sandbox-1">Yes, enable support for Sandbox testing.</label><br />' . "\n";
						echo '<em>Only enable this if you\'ve provided Developer credentials above.<br />This puts s2Member\'s Authorize.Net® integration into Sandbox/Test mode.<br />See: <a href="http://www.s2member.com/authorize.net-test-accounts" target="_blank" rel="external">Authorize.Net® Test Accounts</a></em>' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						/**/
						if (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ())
							{
								echo '<tr>' . "\n";
								/**/
								echo '<th>' . "\n";
								echo '<label for="ws-plugin--s2member-gateway-debug-logs">' . "\n";
								echo 'Enable Logging Routines?<br />' . "\n";
								echo '<small><em class="ws-menu-page-hilite">* This setting applies universally. [ <a href="#" onclick="alert(\'This configuration option may ALSO appear under ( s2Member -> PayPal® Options ). Feel free to configure it here; but please remember that this setting is applied universally ( i.e. SHARED ) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></small>' . "\n";
								echo '</label>' . "\n";
								echo '</th>' . "\n";
								/**/
								echo '</tr>' . "\n";
								echo '<tr>' . "\n";
								/**/
								echo '<td>' . "\n";
								echo '<input type="radio" name="ws_plugin__s2member_gateway_debug_logs" id="ws-plugin--s2member-gateway-debug-logs-0" value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"]) ? ' checked="checked"' : '') . ' /> <label for="ws-plugin--s2member-gateway-debug-logs-0">No</label> &nbsp;&nbsp;&nbsp; <input type="radio" name="ws_plugin__s2member_gateway_debug_logs" id="ws-plugin--s2member-gateway-debug-logs-1" value="1"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["gateway_debug_logs"]) ? ' checked="checked"' : '') . ' /> <label for="ws-plugin--s2member-gateway-debug-logs-1">Yes, enable debugging, with API, IPN &amp; Return Page logging.</label><br />' . "\n";
								echo '<em>This enables API, IPN and Return Page logging. The log files are stored here:<br /><code>' . esc_html (c_ws_plugin__s2member_utils_dirs::doc_root_path ($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["logs_dir"])) . '</code></em>' . "\n";
								echo '</td>' . "\n";
								/**/
								echo '</tr>' . "\n";
							}
						/**/
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '</div>' . "\n";
						/**/
						echo '<div class="ws-menu-page-group" title="Authorize.Net® SP / IPN Integration">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-authnet-ipn-section">' . "\n";
						echo '<h3>Authorize.Net® Silent Post Integration ( required )<br />aka: Authorize.Net® IPN ( Instant Payment Notifications )</h3>' . "\n";
						echo '<p>Log into your Authorize.Net® Merchant account and navigate to this section:<br /><code>Account -> Settings -> Silent Post URL</code></p>' . "\n";
						echo '<p>Your Authorize.Net® Silent Post URL is:<br /><code>' . esc_html (site_url ("/?s2member_pro_authnet_notify=1")) . '</code></p>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '</div>' . "\n";
						/**/
						echo '<div class="ws-menu-page-group" title="Signup Confirmation Email ( Pro Form )">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-signup-confirmation-email-section">' . "\n";
						echo '<h3>Signup Confirmation Email ( required, but the default works fine )<br />— specifically for s2Member Pro Form integrations</h3>' . "\n";
						echo '<p>This email is sent to new Customers after they successfully complete an Authorize.Net® "Pro Form" submission on your site. The <strong>primary</strong> purpose of this email, is to provide the Customer with a receipt, and NOT to send them a <code>%%registration_url%%</code>, because s2Member\'s Authorize.Net® Pro Form integration handles that automatically; based on scenario. You may want to customize this email further, by providing details that are specifically geared to your site.</p>' . "\n";
						/**/
						echo '<p><em class="ws-menu-page-hilite">* This email configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under ( s2Member -> PayPal® Options ). Feel free to configure this email here; but please remember that this configuration is applied universally ( i.e. SHARED ) among all Payment Gateways integrated with s2Member Pro Forms.\'); return false;">?</a> ]</em></p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-signup-email-recipients">' . "\n";
						echo 'Signup Confirmation Recipients:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_signup_email_recipients" id="ws-plugin--s2member-pro-signup-email-recipients" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_signup_email_recipients"]) . '" /><br />' . "\n";
						echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />' . "\n";
						echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-signup-email-subject">' . "\n";
						echo 'Signup Confirmation Email Subject:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_signup_email_subject" id="ws-plugin--s2member-pro-signup-email-subject" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_signup_email_subject"]) . '" /><br />' . "\n";
						echo 'Subject Line used in the email sent to a Customer after a successful signup has occurred through an Authorize.Net® Pro Form.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-signup-email-message">' . "\n";
						echo 'Signup Confirmation Email Message:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<textarea name="ws_plugin__s2member_pro_signup_email_message" id="ws-plugin--s2member-pro-signup-email-message" rows="10">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_signup_email_message"]) . '</textarea><br />' . "\n";
						echo 'Message Body used in the email sent to a Customer after a successful signup has occurred through an Authorize.Net® Pro Form.<br /><br />' . "\n";
						echo '<strong>You can also use these special Replacement Codes if you need them:</strong>' . "\n";
						echo '<ul>' . "\n";
						echo '<li><code>%%registration_url%%</code> = Not needed with Authorize.Net® Pro Form integration. Pro Forms handle this automatically.</li>' . "\n";
						echo '<li><code>%%subscr_id%%</code> = The Authorize.Net® Subscription ID, which remains constant throughout any &amp; all future payments. [ <a href="#" onclick="alert(\'There is one exception. If you are selling Lifetime or Fixed-Term ( non-recurring ) access, using Buy Now functionality; the %%subscr_id%% is actually set to the Transaction ID for the purchase. Authorize.Net® does not provide a specific Subscription ID for Buy Now purchases. Since Lifetime &amp; Fixed-Term Subscriptions are NOT recurring ( i.e. there is only ONE payment ), using the Transaction ID as the Subscription ID is a graceful way to deal with this minor conflict.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%initial%%</code> = The Initial Fee charged during signup. If you offered a 100% Free Trial, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This will always represent the amount of money the Customer spent, whenever they initially signed up, no matter what. If a Customer signs up, under the terms of a 100% Free Trial Period, this will be 0.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%regular%%</code> = The Regular Amount of the Subscription. This value is <code>always > 0</code>, no matter what. [ <a href="#" onclick="alert(\'This is how much the Subscription costs after an Initial Period expires. The %%regular%% rate is always > 0. If you did NOT offer an Initial Period at a different price, %%initial%% and %%regular%% will be equal to the same thing.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%recurring%%</code> = This is the amount that will be charged on a recurring basis, or <code>0</code> if non-recurring. [ <a href="#" onclick="alert(\'If Recurring Payments have not been required, this will be equal to 0. That being said, %%regular%% &amp; %%recurring%% are usually the same value. This variable can be used in two different ways. You can use it to determine what the Regular Recurring Rate is, or to determine whether the Subscription will recur or not. If it is going to recur, %%recurring%% will be > 0.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%full_name%%</code> = The Full Name ( First &amp; Last ) of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%user_ip%%</code> = The Customer\'s IP Address, detected during checkout via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>' . "\n";
						echo '<li><code>%%item_number%%</code> = The Item Number ( colon separated <code><em>level:custom_capabilities:fixed term</em></code> ) that the Subscription is for.</li>' . "\n";
						echo '<li><code>%%item_name%%</code> = The Item Name ( as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number ).</li>' . "\n";
						echo '<li><code>%%initial_term%%</code> = This is the term length of the Initial Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%initial_term%% = 1 D ( this means 1 Day )\\n%%initial_term%% = 1 W ( this means 1 Week )\\n%%initial_term%% = 1 M ( this means 1 Month )\\n%%initial_term%% = 1 Y ( this means 1 Year )\\n\\nThe Initial Period never recurs, so this only lasts for the term length specified, then it is over.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%initial_cycle%%</code> = This is the <code>%%initial_term%%</code> from above, converted to a cycle representation of: <code><em>X days/weeks/months/years</em></code>.</li>' . "\n";
						echo '<li><code>%%regular_term%%</code> = This is the term length of the Regular Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%regular_term%% = 1 D ( this means 1 Day )\\n%%regular_term%% = 1 W ( this means 1 Week )\\n%%regular_term%% = 1 M ( this means 1 Month )\\n%%regular_term%% = 1 Y ( this means 1 Year )\\n%%regular_term%% = 1 L ( this means 1 Lifetime )\\n\\nThe Regular Term is usually recurring. So the Regular Term value represents the period ( or duration ) of each recurring period. If %%recurring%% = 0, then the Regular Term only applies once, because it is not recurring. So if it is not recurring, the value of %%regular_term%% simply represents how long their Membership privileges are going to last after the %%initial_term%% has expired, if there was an Initial Term. The value of this variable ( %%regular_term%% ) will never be empty, it will always be at least: 1 D, meaning 1 day. No exceptions.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%regular_cycle%%</code> = This is the <code>%%regular_term%%</code> from above, converted to a cycle representation of: <code><em>[every] X days/weeks/months/years — OR daily, weekly, bi-weekly, monthly, bi-monthly, quarterly, yearly, or lifetime</em></code>. This is a very useful Replacment Code. Its value is dynamic; depending on term length, recurring status, and period/term lengths configured.</li>' . "\n";
						echo '<li><code>%%recurring/regular_cycle%%</code> = Example ( <code>14.95 / Monthly</code> ), or ... ( <code>0 / non-recurring</code> ); depending on the value of <code>%%recurring%%</code>.</li>' . "\n";
						echo '</ul>' . "\n";
						/**/
						echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>' . "\n";
						echo '<ul>' . "\n";
						echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>' . "\n";
						echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="' . esc_html ($_SERVER["HTTP_HOST"]) . '|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>' . "\n";
						echo '</ul>' . "\n";
						echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />' . "\n";
						echo '<em>( The campaign ( i.e. christmas-promo ) could be referenced using <code>%%cv1%%</code> )</em><br />' . "\n";
						echo '<code>custom="' . esc_html ($_SERVER["HTTP_HOST"]) . '|christmas-promo"</code>' . "\n";
						/**/
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '</div>' . "\n";
						/**/
						echo '<div class="ws-menu-page-group" title="Specific Post/Page Confirmation Email ( Pro Form )">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-sp-confirmation-email-section">' . "\n";
						echo '<h3>Specific Post/Page Confirmation Email ( required, but the default works fine )<br />— specifically for s2Member Pro Form integrations</h3>' . "\n";
						echo '<p>This email is sent to new Customers after they successfully complete an Authorize.Net® "Pro Form" submission on your site, for Specific Post/Page Access. ( see: <code>s2Member -> Restriction Options -> Specific Post/Page Access</code> ). This is NOT used for Membership sales, only for Specific Post/Page Access. The <strong>primary</strong> purpose of this email, is to provide the Customer with a receipt, along with a link to access the Specific Post/Page they\'ve purchased access to. If you\'ve created a Specific Post/Page Package ( with multiple Posts/Pages bundled together into one transaction ), this ONE link ( <code>%%sp_access_url%%</code> ) will automatically authenticate them for access to ALL of the Posts/Pages included in their transaction. You may customize this email further, by providing details that are specifically geared to your site.</p>' . "\n";
						/**/
						echo '<p><em class="ws-menu-page-hilite">* This email configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under ( s2Member -> PayPal® Options ). Feel free to configure this email here; but please remember that this configuration is applied universally ( i.e. SHARED ) among all Payment Gateways integrated with s2Member Pro Forms.\'); return false;">?</a> ]</em></p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-sp-email-recipients">' . "\n";
						echo 'Specific Post/Page Confirmation Recipients:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_sp_email_recipients" id="ws-plugin--s2member-pro-sp-email-recipients" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_sp_email_recipients"]) . '" /><br />' . "\n";
						echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />' . "\n";
						echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-sp-email-subject">' . "\n";
						echo 'Specific Post/Page Confirmation Email Subject:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_sp_email_subject" id="ws-plugin--s2member-pro-sp-email-subject" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_sp_email_subject"]) . '" /><br />' . "\n";
						echo 'Subject Line used in the email sent to a Customer after a successful purchase has occurred through an Authorize.Net® Pro Form, for Specific Post/Page Access.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-sp-email-message">' . "\n";
						echo 'Specific Post/Page Confirmation Email Message:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<textarea name="ws_plugin__s2member_pro_sp_email_message" id="ws-plugin--s2member-pro-sp-email-message" rows="10">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_sp_email_message"]) . '</textarea><br />' . "\n";
						echo 'Message Body used in the email sent to a Customer after a successful purchase has occurred through an Authorize.Net® Pro Form, for Specific Post/Page Access.<br /><br />' . "\n";
						echo '<strong>You can also use these special Replacement Codes if you need them:</strong>' . "\n";
						echo '<ul>' . "\n";
						echo '<li><code>%%sp_access_url%%</code> = The full URL ( generated by s2Member ) where the Customer can gain access.</li>' . "\n";
						echo '<li><code>%%sp_access_exp%%</code> = Human readable expiration for <code>%%sp_access_url%%</code>. Ex: <em>( link expires in <code>%%sp_access_exp%%</code> )</em>.</li>' . "\n";
						echo '<li><code>%%txn_id%%</code> = The Authorize.Net® Transaction ID. Authorize.Net® assigns a unique identifier for every purchase.</li>' . "\n";
						echo '<li><code>%%amount%%</code> = The full Amount that you charged for Specific Post/Page Access. This value will <code>always be > 0</code>.</li>' . "\n";
						echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased Specific Post/Page Access.</li>' . "\n";
						echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased Specific Post/Page Access.</li>' . "\n";
						echo '<li><code>%%full_name%%</code> = The Full Name ( First &amp; Last ) of the Customer who purchased Specific Post/Page Access.</li>' . "\n";
						echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased Specific Post/Page Access.</li>' . "\n";
						echo '<li><code>%%user_ip%%</code> = The Customer\'s IP Address, detected during checkout via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>' . "\n";
						echo '<li><code>%%item_number%%</code> = The Item Number. Ex: <code><em>sp:13,24,36:72</em></code> ( translates to: <code><em>sp:comma-delimited IDs:expiration hours</em></code> ).</li>' . "\n";
						echo '<li><code>%%item_name%%</code> = The Item Name ( as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number ).</li>' . "\n";
						echo '</ul>' . "\n";
						/**/
						echo '<strong>Custom Replacement Codes can also be inserted using these instructions:</strong>' . "\n";
						echo '<ul>' . "\n";
						echo '<li><code>%%cv0%%</code> = The domain of your site, which is passed through the `custom` attribute in your Shortcode.</li>' . "\n";
						echo '<li><code>%%cv1%%</code> = If you need to track additional custom variables, you can pipe delimit them into the `custom` attribute; inside your Shortcode, like this: <code>custom="' . esc_html ($_SERVER["HTTP_HOST"]) . '|cv1|cv2|cv3"</code>. You can have an unlimited number of custom variables. Obviously, this is for advanced webmasters; but the functionality has been made available for those who need it.</li>' . "\n";
						echo '</ul>' . "\n";
						echo '<strong>This example uses cv1 to record a special marketing campaign:</strong><br />' . "\n";
						echo '<em>( The campaign ( i.e. christmas-promo ) could be referenced using <code>%%cv1%%</code> )</em><br />' . "\n";
						echo '<code>custom="' . esc_html ($_SERVER["HTTP_HOST"]) . '|christmas-promo"</code>' . "\n";
						/**/
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '</div>' . "\n";
						echo '<div class="ws-menu-page-group" title="Tax Rate Calculations ( Pro Form )">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-tax-rates-section">' . "\n";
						echo '<h3>Tax Rate Calculations for Authorize.Net® Pro Forms ( optional )<br />— specifically for s2Member Pro Form integrations</h3>' . "\n";
						echo '<p>With Authorize.Net®, your software ( s2Member Pro ) is solely responsible for calculating Tax Rates. In the fields below, you can set a Global Default Tax Rate, and/or a "Custom Tax Configuration File"; which can be applied to specific countries, specific states, provinces, and even to specific zip code ranges. * Tax Rate calculations are fully compatible with international currencies and locations.</p>' . "\n";
						echo '<p>When you create an Authorize.Net® Pro Form with s2Member, you\'ll be asked to supply a <em>Charge Amount</em>. Then, during checkout... s2Member calculates Tax. The calculated Tax Rate is added to the <em>Charge Amount</em> in your Authorize.Net® Pro Form Shortcode. The Tax Rate will be displayed to a Customer during checkout, <strong>after</strong> they\'ve supplied a Billing Address. For example, if you create an Authorize.Net® Pro Form that charges a Customer <strong>$24.95</strong>, and the Tax Rate is configured as 7.0%; s2Member will automatically calculate the Tax as $1.75. A Customer will pay the Total Amount ( <em>Charge</em> + Tax = <strong>$26.70</strong> ).</p>' . "\n";
						echo '<p><em><strong>*Quick Tip*</strong> If you configure Tax, it\'s good to include a note somewhere in the <code>desc=""</code> attribute of your Shortcode. Something like <code>desc="$x.xx (plus tax)"</code>.</em></p>' . "\n";
						/**/
						echo '<p><em class="ws-menu-page-hilite">* This tax configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under ( s2Member -> PayPal® Options ). Feel free to configure taxes here; but please remember that this configuration is applied universally ( i.e. SHARED ) among all Payment Gateways integrated with s2Member Pro Forms.\'); return false;">?</a> ]</em></p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-default-tax">' . "\n";
						echo 'Global Default Tax Rate:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_default_tax" id="ws-plugin--s2member-pro-default-tax" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_default_tax"]) . '" /><br />' . "\n";
						echo 'This can be a flat tax <code>( 1.75 )</code>, or a percentage <code>( 7.0% )</code>.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-tax-rates">' . "\n";
						echo 'Custom Tax Configuration File ( one rate per line )<br />' . "\n";
						echo 'Apply different Tax Rates by country, state/province, or zip code range:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<textarea name="ws_plugin__s2member_pro_tax_rates" id="ws-plugin--s2member-pro-tax-rates" rows="10" wrap="off" spellcheck="false">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_tax_rates"]) . '</textarea><br />' . "\n";
						echo 'Please use one of the following formats ( <a href="#" onclick="alert(\'US=7.0%\nCA=12.0%\nHK=0.0%\nFLORIDA/US=7.5%\nIDAHO/US=6.0%\nALBERTA/CA=5.0%\nBRITISH COLUMBIA/CA=12.0%\n32000-34999/US=7.5%\n83200-83999/US=6.0%\n32601/US=6.5%\'); return false;">click for examples</a> )<br /><br />' . "\n";
						echo '<code>2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code> — low precedence<br />' . "\n";
						echo '<code>STATE OR PROVINCE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code> — higher precedence<br />' . "\n";
						echo '<code>ZIP CODE-ZIP CODE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code> — higher precedence ( zip code range )<br />' . "\n";
						echo '<code>ZIP CODE/2-CHARACTER COUNTRY CODE = Flat rate or percentage.</code> — highest precedence ( specific zip code )' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '</div>' . "\n";
						/**/
						echo '<div class="ws-menu-page-group" title="Automatic EOT Behavior">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-eot-behavior-section">' . "\n";
						echo '<h3>Authorize.Net® EOT Behavior ( required, please choose )</h3>' . "\n";
						echo '<p>EOT = End Of Term. By default, s2Member will demote a paid Member to a Free Subscriber whenever their Subscription term has ended ( i.e. expired ) or is cancelled. s2Member demotes them to a Free Subscriber, so they will no longer have Member Level Access to your site. However, in some cases, you may prefer to have Customer accounts deleted completely, instead of just being demoted. This is where you choose which method works best for your site. If you don\'t want s2Member to take ANY action at all, you can disable s2Member\'s EOT System temporarily, or even completely.</p>' . "\n";
						echo '<p>The Authorize.Net® ARB ( Automated Recurring Billing ) service will assist in notifying s2Member whenever a Subscription expires. For example, if a Customer cancels their own Subscription; or if you cancel a Customer\'s Subscription through Authorize.Net®, s2Member will eventually be notified ( with a 24-48 hour delay ), and the account for that Customer will either be demoted to a Free Subscriber, or deleted automatically ( based on your configuration ). ~ Otherwise, under normal circumstances, s2Member will not process an EOT until the User has completely used up the time they paid for.</em></p>' . "\n";
						/**/
						echo '<p id="ws-plugin--s2member-auto-eot-system-enabled-via-cron"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["auto_eot_system_enabled"] == 2 && (!function_exists ("wp_cron") || !wp_get_schedule ("ws_plugin__s2member_auto_eot_system__schedule"))) ? '' : ' style="display:none;"') . '>If you\'d like to run s2Member\'s Auto-EOT System through a more traditional Cron Job; instead of through <code>WP-Cron</code>, you will need to configure a Cron Job through your server control panel; provided by your hosting company. Set the Cron Job to run <code>once about every 10 minutes to an hour</code>. You\'ll want to configure an HTTP Cron Job that loads this URL:<br /><code>' . esc_html (site_url ("/?s2member_auto_eot_system_via_cron=1")) . '</code></p>' . "\n";
						/**/
						echo '<p><em class="ws-menu-page-hilite">* These options are universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'These settings may ALSO appear under ( s2Member -> PayPal® Options ). Feel free to configure them here; but please remember that these configuration options are applied universally ( i.e. they\\\'re SHARED ) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-auto-eot-system-enabled">' . "\n";
						echo 'Enable s2Member\'s Auto-EOT System?' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<select name="ws_plugin__s2member_auto_eot_system_enabled" id="ws-plugin--s2member-auto-eot-system-enabled">' . "\n";
						/* Very advanced conditionals here. If the Auto-EOT System is NOT running, or NOT fully configured, this will indicate that no option is set - as sort of a built-in acknowledgment/warning in the UI panel. */
						echo (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["auto_eot_system_enabled"] == 1 && (!function_exists ("wp_cron") || !wp_get_schedule ("ws_plugin__s2member_auto_eot_system__schedule"))) || ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["auto_eot_system_enabled"] == 2 && (function_exists ("wp_cron") && wp_get_schedule ("ws_plugin__s2member_auto_eot_system__schedule"))) || (!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["auto_eot_system_enabled"] && (function_exists ("wp_cron") && wp_get_schedule ("ws_plugin__s2member_auto_eot_system__schedule")))) ? '<option value=""></option>' . "\n" : '';
						echo '<option value="1"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["auto_eot_system_enabled"] == 1 && function_exists ("wp_cron") && wp_get_schedule ("ws_plugin__s2member_auto_eot_system__schedule")) ? ' selected="selected"' : '') . '>Yes ( enable the Auto-EOT System through WP-Cron )</option>' . "\n";
						echo (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? '<option value="2"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["auto_eot_system_enabled"] == 2 && (!function_exists ("wp_cron") || !wp_get_schedule ("ws_plugin__s2member_auto_eot_system__schedule"))) ? ' selected="selected"' : '') . '>Yes ( but, I\'ll run it with my own Cron Job )</option>' . "\n" : '';
						echo '<option value="0"' . ((!$GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["auto_eot_system_enabled"] && (!function_exists ("wp_cron") || !wp_get_schedule ("ws_plugin__s2member_auto_eot_system__schedule"))) ? ' selected="selected"' : '') . '>No ( disable the Auto-EOT System )</option>' . "\n";
						echo '</select><br />' . "\n";
						echo 'Recommended setting: ( <code>Yes / enable via WP-Cron</code> )' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-membership-eot-behavior">' . "\n";
						echo 'Membership EOT Behavior ( demote or delete )?' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<select name="ws_plugin__s2member_membership_eot_behavior" id="ws-plugin--s2member-membership-eot-behavior">' . "\n";
						echo '<option value="demote"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["membership_eot_behavior"] === "demote") ? ' selected="selected"' : '') . '>Demote ( convert them to a Free Subscriber )</option>' . "\n";
						echo '<option value="delete"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["membership_eot_behavior"] === "delete") ? ' selected="selected"' : '') . '>Delete ( erase their account completely )</option>' . "\n";
						echo '</select>' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-triggers-immediate-eot">' . "\n";
						echo 'Refunds/Reversals ( trigger immediate EOT )?' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<select name="ws_plugin__s2member_triggers_immediate_eot" id="ws-plugin--s2member-triggers-immediate-eot" disabled="disabled">' . "\n";
						echo '<option value="none" selected="selected">Neither ( I\'ll review these two events manually )</option>' . "\n";
						echo '</select><br />' . "\n";
						echo 'N/A with Authorize.Net®. Authorize.Net® does NOT notify s2Member on refunds/chargebacks.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-eot-time-ext-behavior">' . "\n";
						echo 'Fixed-Term Extensions ( auto-extend )?' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<select name="ws_plugin__s2member_eot_time_ext_behavior" id="ws-plugin--s2member-eot-time-ext-behavior">' . "\n";
						echo '<option value="extend"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["eot_time_ext_behavior"] === "extend") ? ' selected="selected"' : '') . '>Yes ( default, automatically extend any existing EOT Time )</option>' . "\n";
						echo '<option value="reset"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["eot_time_ext_behavior"] === "reset") ? ' selected="selected"' : '') . '>No ( do NOT extend; s2Member should reset EOT Time completely )</option>' . "\n";
						echo '</select><br />' . "\n";
						echo 'This setting will only affect Buy Now transactions for fixed-term lengths. By default, s2Member will automatically extend any existing EOT Time that a Customer may have.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '</div>' . "\n";
						/**/
						echo '<div class="ws-menu-page-hr"></div>' . "\n";
						/**/
						echo '<p class="submit"><input type="submit" class="button-primary" value="Save All Changes" /></p>' . "\n";
						/**/
						echo '</form>' . "\n";
						/**/
						echo '</td>' . "\n";
						/**/
						echo '<td class="ws-menu-page-table-r">' . "\n";
						c_ws_plugin__s2member_menu_pages_rs::display ();
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						/**/
						echo '</div>' . "\n";
					}
			}
	}
/**/
new c_ws_plugin__s2member_pro_menu_page_authnet_ops ();
?>