<?php
/**
* Menu page for s2Member Pro ( AliPay® Options page ).
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
	exit("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_menu_page_alipay_ops"))
	{
		/**
		* Menu page for s2Member Pro ( AliPay® Options page ).
		*
		* @package s2Member\Menu_Pages
		* @since 110531
		*/
		class c_ws_plugin__s2member_pro_menu_page_alipay_ops
			{
				public function __construct ()
					{
						echo '<div class="wrap ws-menu-page">' . "\n";
						/**/
						echo '<div id="icon-plugins" class="icon32"><br /></div>' . "\n";
						echo '<h2>s2Member® Pro / AliPay® Options</h2>' . "\n";
						/**/
						echo '<table class="ws-menu-page-table">' . "\n";
						echo '<tbody class="ws-menu-page-table-tbody">' . "\n";
						echo '<tr class="ws-menu-page-table-tr">' . "\n";
						echo '<td class="ws-menu-page-table-l">' . "\n";
						/**/
						echo '<form method="post" name="ws_plugin__s2member_pro_options_form" id="ws-plugin--s2member-pro-options-form">' . "\n";
						echo '<input type="hidden" name="ws_plugin__s2member_options_save" id="ws-plugin--s2member-options-save" value="' . esc_attr (wp_create_nonce ("ws-plugin--s2member-options-save")) . '" />' . "\n";
						/**/
						echo '<div class="ws-menu-page-group" title="AliPay® Account Details">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-alipay-account-details-section">' . "\n";
						echo '<h3>AliPay® Account Details ( required )</h3>' . "\n";
						echo '<p><a href="http://www.s2member.com/alipay" target="_blank" rel="external">AliPay®</a> is China\'s leading independent third-party online payment platform. Established in 2004 by Alibaba Group, a leading international e-commerce service provider, AliPay® is dedicated to providing it\'s members and merchants with a "simple, secure and speedy" online payment solution. If you\'re a merchant in the USA, AliPay® is THE way to reach a population of over 1.3 billion in China. AliPay® is similar to what we call PayPal® here in the USA.</p>' . "\n";
						echo '<p>s2Member has been integrated with AliPay® for Direct Payments. In order to take advantage of this integration, you will need to have an AliPay® Merchant Account with Direct Pay enabled ( aka: 前台自助--即时到账收款 ). This can sometimes take a couple of days to acquire. Once you\'ve been approved at AliPay®, you\'ll be given a Partner ID, and a Security Code; which you\'ll need to fill in below.</p>' . "\n";
						echo '<p><em>* s2Member only integrates AliPay® "Buy Now" functionality. AliPay® does NOT support Recurring Billing or Trial Periods.</em></p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-alipay-partner-id">' . "\n";
						echo 'AliPay® Partner ID:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_alipay_partner_id" id="ws-plugin--s2member-pro-alipay-partner-id" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_partner_id"]) . '" /><br />' . "\n";
						echo 'This is provided by AliPay®. Check your AliPay® Merchant account for this information.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-alipay-seller-email">' . "\n";
						echo 'AliPay® Seller Email:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_pro_alipay_seller_email" id="ws-plugin--s2member-pro-alipay-seller-email" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_seller_email"]) . '" /><br />' . "\n";
						echo 'This is the Email Address configured in your AliPay® Merchant account.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-alipay-security-code">' . "\n";
						echo 'AliPay® Security Code:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="password" autocomplete="off" name="ws_plugin__s2member_pro_alipay_security_code" id="ws-plugin--s2member-pro-alipay-security-code" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_security_code"]) . '" /><br />' . "\n";
						echo 'This is provided by AliPay®. Check your AliPay® Merchant account for this information.' . "\n";
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
						echo '<div class="ws-menu-page-group" title="AliPay® Return-Page Template">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-pro-alipay-return-page-template">' . "\n";
						echo '<h3>AliPay® Return-Page Template ( optional, for further customization )</h3>' . "\n";
						echo '<p>With s2Member Pro installed, you have the ability to customize your <a href="' . esc_attr (site_url ("/?s2member_pro_alipay_return&s2member_paypal_return=1&s2member_paypal_proxy=alipay&s2member_paypal_proxy_use=x-preview")) . '" target="_blank" rel="external">Return-Page Template</a>. Each of your Customers are returned back to your site immediately after they complete checkout at AliPay®. Your Return-Page displays a message and instructions for the Customer. s2Member may change the message and instructions dynamically, based on what the Customer is actually doing <em>( i.e. based on the type of transaction that is taking place )</em>. So, although we do NOT recommend that you attempt to change the message and instructions presented dynamically by s2Member, you CAN certainly control the Header, and/or the overall appearance of s2Member\'s Return-Page Template.</p>' . "\n";
						echo '<p>The quickest/easiest way, is to simply add some HTML code in the box below. For instance, you might include an &lt;img&gt; tag with your logo. The box below, allows you to customize the Header section <em>( i.e. the top )</em> of s2Member\'s default Return-Page Template. Everything else, including the textual response and other important details that each Customer needs to know about, are already handled dynamically by s2Member <em>( based on the type of transaction that is taking place )</em>. All you need to do is customize the Header with your logo and anything else you feel is important. Although this Header customization is completely optional, we recommend an <a href="http://www.w3schools.com/tags/tag_img.asp" target="_blank" rel="external">&lt;img&gt; tag</a>, with a logo that is around 300px wide. After you "Save All Changes" below, you may <a href="' . esc_attr (site_url ("/?s2member_pro_alipay_return&s2member_paypal_return=1&s2member_paypal_proxy=alipay&s2member_paypal_proxy_use=x-preview")) . '" target="_blank" rel="external">click this link to see what your Header looks like</a>.</p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-pro-alipay-return-template-header">' . "\n";
						echo 'Return-Page Template Header:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<textarea name="ws_plugin__s2member_pro_alipay_return_template_header" id="ws-plugin--s2member-pro-alipay-return-template-header" rows="5" wrap="off" spellcheck="false">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["pro_alipay_return_template_header"]) . '</textarea><br />' . "\n";
						echo 'Any valid XHTML / JavaScript' . ((is_multisite () && c_ws_plugin__s2member_utils_conds::is_multisite_farm () && !is_main_site ()) ? '' : ' ( or even PHP )') . ' code will work just fine here.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '</tbody>' . "\n";
						echo '</table>' . "\n";
						/**/
						echo '<div class="ws-menu-page-hr"></div>' . "\n";
						/**/
						if (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ())
							echo '<p>It is also possible to build your own Return-Page Template, if you prefer. If you feel the need to create your own Return-Page Template, please make a copy of s2Member\'s default template: <code>' . esc_html (c_ws_plugin__s2member_utils_dirs::doc_root_path ($GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["dir"] . "/includes/templates/returns/default-template.php")) . '</code>. Place your copy of this default template, inside your active WordPress® theme directory, and name the file: <code>/alipay-return.php</code>. s2Member will automatically find your Return-Page Template in this location, and s2Member will use your template, instead of the default. Further details are provided inside s2Member\'s default template file. Once your custom template file is in place, you may <a href="' . esc_attr (site_url ("/?s2member_pro_alipay_return&s2member_paypal_return=1&s2member_paypal_proxy=alipay&s2member_paypal_proxy_use=x-preview")) . '" target="_blank" rel="external">click this link to see what it looks like</a>.</p>' . "\n";
						/**/
						echo '<p>It is also possible to bypass s2Member\'s Return-Page system all together, if you prefer. For further details, please read more about the <code>success=""</code> Shortcode Attribute for AliPay® Buttons generated by s2Member. You will find details on this inside your Dashboard, under: <code>s2Member -> AliPay® Buttons -> Shortcode Attributes ( Explained )</code>. If you use the <code>success=""</code> Attribute in your Shortcode, the initial redirection back to s2Member\'s default Return-Page handler MUST still occur. However, instead of s2Member displaying a Return-Page Template to the Customer, s2Member will silently redirect the Customer to the URL that you specified in the <code>success="http://..."</code> Attribute of your Shortcode, allowing you to take complete control over what happens next.</p>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '</div>' . "\n";
						/**/
						echo '<div class="ws-menu-page-group" title="Signup Confirmation Email ( Standard )">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-signup-confirmation-email-section">' . "\n";
						echo '<h3>Signup Confirmation Email ( required, but the default works fine )</h3>' . "\n";
						echo '<p>This email is sent to new Customers after they return from a successful signup at AliPay®. The <strong>primary</strong> purpose of this email, is to provide the Customer with instructions, along with a link to register a Username for their Membership. You may also customize this further, by providing details that are specifically geared to your site.</p>' . "\n";
						/**/
						echo '<p><em class="ws-menu-page-hilite">* This email configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under ( s2Member -> PayPal® Options ). Feel free to configure this email here; but please remember that this configuration is applied universally ( i.e. SHARED ) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-signup-email-recipients">' . "\n";
						echo 'Signup Confirmation Recipients:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_signup_email_recipients" id="ws-plugin--s2member-signup-email-recipients" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["signup_email_recipients"]) . '" /><br />' . "\n";
						echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />' . "\n";
						echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-signup-email-subject">' . "\n";
						echo 'Signup Confirmation Email Subject:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_signup_email_subject" id="ws-plugin--s2member-signup-email-subject" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["signup_email_subject"]) . '" /><br />' . "\n";
						echo 'Subject Line used in the email sent to a Customer after a successful signup has occurred through AliPay®.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-signup-email-message">' . "\n";
						echo 'Signup Confirmation Email Message:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<textarea name="ws_plugin__s2member_signup_email_message" id="ws-plugin--s2member-signup-email-message" rows="10">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["signup_email_message"]) . '</textarea><br />' . "\n";
						echo 'Message Body used in the email sent to a Customer after a successful signup has occurred through AliPay®.<br /><br />' . "\n";
						echo '<strong>You can also use these special Replacement Codes if you need them:</strong>' . "\n";
						echo '<ul>' . "\n";
						echo '<li><code>%%registration_url%%</code> = The full URL ( generated by s2Member ) where the Customer can get registered.</li>' . "\n";
						echo '<li><code>%%subscr_id%%</code> = The AliPay® Transaction/Subscription ID as recorded in your AliPay® Merchant account. [ <a href="#" onclick="alert(\'If you are selling Lifetime or Fixed-Term ( non-recurring ) access, using Buy Now functionality with AliPay®; the %%subscr_id%% is actually set to the Transaction ID for the purchase. AliPay® does not provide a specific Subscription ID for Buy Now purchases. Since Lifetime &amp; Fixed-Term Subscriptions are NOT recurring ( i.e. there is only ONE payment ), using the Transaction ID as the Subscription ID is a graceful way to deal with this minor conflict.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%initial%%</code> = The Initial Fee charged during signup. If you offered a 100% Free Trial, this will be <code>0</code>. [ <a href="#" onclick="alert(\'This will always represent the amount of money the Customer spent, whenever they initially signed up, no matter what. If a Customer signs up, under the terms of a 100% Free Trial Period, this will be 0.\\n\\n* AliPay® integration does NOT support Initial/Trial Periods at this time.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%regular%%</code> = The Regular Amount of the Subscription. This value is <code>always > 0</code>, no matter what. [ <a href="#" onclick="alert(\'This is how much the Subscription costs after an Initial Period expires. The %%regular%% rate is always > 0. If you did NOT offer an Initial Period at a different price, %%initial%% and %%regular%% will be equal to the same thing.\\n\\n* AliPay® integration does NOT support Initial/Trial Periods at this time.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%recurring%%</code> = This is the amount that will be charged on a recurring basis, or <code>0</code> if non-recurring. [ <a href="#" onclick="alert(\'If Recurring Payments have not been required, this will be equal to 0. That being said, %%regular%% &amp; %%recurring%% are usually the same value. This variable can be used in two different ways. You can use it to determine what the Regular Recurring Rate is, or to determine whether the Subscription will recur or not. If it is going to recur, %%recurring%% will be > 0.\\n\\n* AliPay® integration does NOT support Recurring Billing at this time.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%first_name%%</code> = The First Name of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%last_name%%</code> = The Last Name of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%full_name%%</code> = The Full Name ( First &amp; Last ) of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%payer_email%%</code> = The Email Address of the Customer who purchased the Membership Subscription.</li>' . "\n";
						echo '<li><code>%%user_ip%%</code> = The Customer\'s IP Address, detected during checkout via <code>$_SERVER["REMOTE_ADDR"]</code>.</li>' . "\n";
						echo '<li><code>%%item_number%%</code> = The Item Number ( colon separated <code><em>level:custom_capabilities:fixed term</em></code> ) that the Subscription is for.</li>' . "\n";
						echo '<li><code>%%item_name%%</code> = The Item Name ( as provided by the <code>desc=""</code> attribute in your Shortcode, which briefly describes the Item Number ).</li>' . "\n";
						echo '<li><code>%%initial_term%%</code> = This is the term length of the Initial Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%initial_term%% = 1 D ( this means 1 Day )\\n%%initial_term%% = 1 W ( this means 1 Week )\\n%%initial_term%% = 1 M ( this means 1 Month )\\n%%initial_term%% = 1 Y ( this means 1 Year )\\n\\nThe Initial Period never recurs, so this only lasts for the term length specified, then it is over.\\n\\n* AliPay® integration does NOT support Initial/Trial Periods at this time.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%initial_cycle%%</code> = This is the <code>%%initial_term%%</code> from above, converted to a cycle representation of: <code><em>X days/weeks/months/years</em></code>. [ <a href="#" onclick="alert(\'* AliPay® integration does NOT support Initial/Trial Periods at this time.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%regular_term%%</code> = This is the term length of the Regular Period. This will be a numeric value, followed by a space, then a single letter. [ <a href="#" onclick="alert(\'Here are some examples:\\n\\n%%regular_term%% = 1 D ( this means 1 Day )\\n%%regular_term%% = 1 W ( this means 1 Week )\\n%%regular_term%% = 1 M ( this means 1 Month )\\n%%regular_term%% = 1 Y ( this means 1 Year )\\n%%regular_term%% = 1 L ( this means 1 Lifetime )\\n\\nThe Regular Term is usually recurring. So the Regular Term value represents the period ( or duration ) of each recurring period. If %%recurring%% = 0, then the Regular Term only applies once, because it is not recurring. So if it is not recurring, the value of %%regular_term%% simply represents how long their Membership privileges are going to last after the %%initial_term%% has expired, if there was an Initial Term. The value of this variable ( %%regular_term%% ) will never be empty, it will always be at least: 1 D, meaning 1 day. No exceptions.\\n\\n* AliPay® integration does NOT support Recurring Billing at this time.\'); return false;">?</a> ]</li>' . "\n";
						echo '<li><code>%%regular_cycle%%</code> = This is the <code>%%regular_term%%</code> from above, converted to a cycle representation of: <code><em>[every] X days/weeks/months/years — OR daily, weekly, bi-weekly, monthly, bi-monthly, quarterly, yearly, or lifetime</em></code>. This is a very useful Replacment Code. Its value is dynamic; depending on term length, recurring status, and period/term lengths configured.</li>' . "\n";
						echo '<li><code>%%recurring/regular_cycle%%</code> = Example ( <code>14.95 / Monthly</code> ), or ... ( <code>0 / non-recurring</code> ); depending on the value of <code>%%recurring%%</code>. [ <a href="#" onclick="alert(\'* AliPay® integration does NOT support Recurring Billing at this time.\'); return false;">?</a> ]</li>' . "\n";
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
						echo '<div class="ws-menu-page-group" title="Specific Post/Page Confirmation Email ( Standard )">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-sp-confirmation-email-section">' . "\n";
						echo '<h3>Specific Post/Page Confirmation Email ( required, but the default works fine )</h3>' . "\n";
						echo '<p>This email is sent to new Customers after they return from a successful purchase at AliPay®, for Specific Post/Page Access. ( see: <code>s2Member -> Restriction Options -> Specific Post/Page Access</code> ). This is NOT used for Membership sales, only for Specific Post/Page Access. The <strong>primary</strong> purpose of this email, is to provide the Customer with instructions, along with a link to access the Specific Post/Page they\'ve purchased access to. If you\'ve created a Specific Post/Page Package ( with multiple Posts/Pages bundled together into one transaction ), this ONE link ( <code>%%sp_access_url%%</code> ) will automatically authenticate them for access to ALL of the Posts/Pages included in their transaction. You may customize this email further, by providing details that are specifically geared to your site.</p>' . "\n";
						/**/
						echo '<p><em class="ws-menu-page-hilite">* This email configuration is universally applied to all Payment Gateway integrations. [ <a href="#" onclick="alert(\'This configuration panel may ALSO appear under ( s2Member -> PayPal® Options ). Feel free to configure this email here; but please remember that this configuration is applied universally ( i.e. SHARED ) among all Payment Gateways integrated with s2Member.\'); return false;">?</a> ]</em></p>' . "\n";
						/**/
						echo '<table class="form-table">' . "\n";
						echo '<tbody>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-sp-email-recipients">' . "\n";
						echo 'Specific Post/Page Confirmation Recipients:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_sp_email_recipients" id="ws-plugin--s2member-sp-email-recipients" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["sp_email_recipients"]) . '" /><br />' . "\n";
						echo 'This is a semicolon ( ; ) delimited list of Recipients. Here is an example:<br />' . "\n";
						echo '<code>"%%full_name%%" &lt;%%payer_email%%&gt;; admin@example.com; "Webmaster" &lt;webmaster@example.com&gt;</code>' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-sp-email-subject">' . "\n";
						echo 'Specific Post/Page Confirmation Email Subject:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<input type="text" autocomplete="off" name="ws_plugin__s2member_sp_email_subject" id="ws-plugin--s2member-sp-email-subject" value="' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["sp_email_subject"]) . '" /><br />' . "\n";
						echo 'Subject Line used in the email sent to a Customer after a successful purchase has occurred through AliPay®, for Specific Post/Page Access.' . "\n";
						echo '</td>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<th>' . "\n";
						echo '<label for="ws-plugin--s2member-sp-email-message">' . "\n";
						echo 'Specific Post/Page Confirmation Email Message:' . "\n";
						echo '</label>' . "\n";
						echo '</th>' . "\n";
						/**/
						echo '</tr>' . "\n";
						echo '<tr>' . "\n";
						/**/
						echo '<td>' . "\n";
						echo '<textarea name="ws_plugin__s2member_sp_email_message" id="ws-plugin--s2member-sp-email-message" rows="10">' . format_to_edit ($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["sp_email_message"]) . '</textarea><br />' . "\n";
						echo 'Message Body used in the email sent to a Customer after a successful purchase has occurred through AliPay®, for Specific Post/Page Access.<br /><br />' . "\n";
						echo '<strong>You can also use these special Replacement Codes if you need them:</strong>' . "\n";
						echo '<ul>' . "\n";
						echo '<li><code>%%sp_access_url%%</code> = The full URL ( generated by s2Member ) where the Customer can gain access.</li>' . "\n";
						echo '<li><code>%%sp_access_exp%%</code> = Human readable expiration for <code>%%sp_access_url%%</code>. Ex: <em>( link expires in <code>%%sp_access_exp%%</code> )</em>.</li>' . "\n";
						echo '<li><code>%%txn_id%%</code> = The AliPay® Transaction ID. AliPay® assigns a unique identifier for every purchase.</li>' . "\n";
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
						/**/
						echo '<div class="ws-menu-page-group" title="Automatic EOT Behavior">' . "\n";
						/**/
						echo '<div class="ws-menu-page-section ws-plugin--s2member-eot-behavior-section">' . "\n";
						echo '<h3>AliPay® EOT Behavior ( required, please choose )</h3>' . "\n";
						echo '<p>EOT = End Of Term. By default, s2Member will demote a paid Member to a Free Subscriber whenever their Subscription term has ended ( i.e. expired ), been cancelled, refunded, charged back to you, etc. s2Member demotes them to a Free Subscriber, so they will no longer have Member Level Access to your site. However, in some cases, you may prefer to have Customer accounts deleted completely, instead of just being demoted. This is where you choose which method works best for your site. If you don\'t want s2Member to take ANY action at all, you can disable s2Member\'s EOT System temporarily, or even completely.</p>' . "\n";
						echo '<p>The AliPay® IPN service will notify s2Member whenever a refund or chargeback occurs. For example, if you issue a refund to an unhappy Customer through AliPay®, s2Member will eventually be notified <em>( with a 24-48 hour delay )</em>, and the account for that Customer will either be demoted to a Free Subscriber, or deleted automatically ( based on your configuration ). ~ Otherwise, under normal circumstances, s2Member will not process an EOT until the User has completely used up the time they paid for.</em></p>' . "\n";
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
						echo '<select name="ws_plugin__s2member_triggers_immediate_eot" id="ws-plugin--s2member-triggers-immediate-eot">' . "\n";
						echo '<option value="none"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "none") ? ' selected="selected"' : '') . '>Neither ( I\'ll review these two events manually )</option>' . "\n";
						echo '<option value="refunds"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "refunds") ? ' selected="selected"' : '') . '>Refunds ( refunds ALWAYS trigger an immediate EOT action )</option>' . "\n";
						echo '<option value="reversals"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "reversals") ? ' selected="selected"' : '') . '>Reversals ( chargebacks ALWAYS trigger an immediate EOT action )</option>' . "\n";
						echo '<option value="refunds,reversals"' . (($GLOBALS["WS_PLUGIN__"]["s2member"]["o"]["triggers_immediate_eot"] === "refunds,reversals") ? ' selected="selected"' : '') . '>Refunds/Reversals ( ALWAYS trigger an immediate EOT action )</option>' . "\n";
						echo '</select><br />' . "\n";
						echo 'This setting will <a href="#" onclick="alert(\'A Refund/Reversal Notification will ALWAYS be processed internally by s2Member, even if no action is taken by s2Member. This way you\\\'ll have the full ability to listen for these two events on your own; if you prefer ( optional ). For more information, check your Dashboard under: `s2Member -> API Notifications -> Refunds/Reversals`.\'); return false;">NOT affect</a> s2Member\'s internal API Notifications for Refund/Reversal events.' . "\n";
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
new c_ws_plugin__s2member_pro_menu_page_alipay_ops ();
?>