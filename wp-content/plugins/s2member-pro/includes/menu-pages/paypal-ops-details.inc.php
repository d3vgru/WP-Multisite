<?php
/**
* Menu page for s2Member Pro ( PayPal® option details ).
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
if (!class_exists ("c_ws_plugin__s2member_pro_menu_page_paypal_ops_details"))
	{
		/**
		* Menu page for s2Member Pro ( PayPal® option details ).
		*
		* @package s2Member\Menu_Pages
		* @since 110531
		*/
		class c_ws_plugin__s2member_pro_menu_page_paypal_ops_details
			{
				public function __construct ()
					{
						echo '<h3>PayPal® Pro Integration ( <a href="#" onclick="jQuery(\'div#ws-plugin--s2member-paypal-pro-details\').toggle(); return false;" class="ws-dotted-link">please read</a> )</h3>' . "\n";
						/**/
						echo '<div id="ws-plugin--s2member-paypal-pro-details" style="display:none;">' . "\n";
						echo '<p><em><strong>*PayPal® Pro Integration*</strong> The s2Member Pro Module makes it possible for s2Member to use PayPal® Pro Forms ( instead of standard PayPal® Buttons ). PayPal® Pro Forms integrate seamlessly with WordPress® Shortcodes. This allows you to keep Customers on your site at all times, and it consolidates the Checkout / Registration steps into a single form that you can dress up just the way you like. If you would like to take advantage of PayPal® Pro integration, please supply your PayPal® API Username, Password, and Signature. You will need a <a href="http://www.s2member.com/paypal" target="_blank" rel="external">PayPal® Business Account, w/Pro Service</a>. PayPal® Pro accounts require a formal application, along with a small monthly fee. Once you have a PayPal® Pro account, you\'ll need access to your <a href="http://www.s2member.com/paypal-profile-api-access" target="_blank" rel="external">PayPal® API Credentials</a>. Log into your PayPal® Pro account, and navigate to <code>Profile -> API Access (or Request API Credentials)</code>. You\'ll choose <code>( Request API Signature )</code>.</em></p>' . "\n";
						echo '<p><em><strong>*Recurring Billing*</strong> If you plan to use any of the ( `Subscription` ) options in the s2Member Form Generator for PayPal® Pro, you will ALSO need <a href="http://www.s2member.com/paypal-pro-recurring-payments-faqs" target="_blank" rel="external">Recurring Billing</a> enabled for your PayPal® Pro account. PayPal\'s Recurring Billing service for Pro accounts is <strong>required</strong> for all types of ( `Subscriptions` ), whether you intend for them to be recurring or not. However, it is NOT required for ( `Buy Now` ) functionality. The drop-down menus in the s2Member Form Generator, have been marked ( `Subscription` ) and ( `Buy Now` ) just for this reason. See: <code>s2Member -> PayPal® Pro Forms</code>. This way you can see which options will require the use of PayPal\'s Recurring Billing service. PayPal® will charge you a small monthly fee for their Recurring Billing service; which is an add-on for PayPal® Pro accounts.</em></p>' . "\n";
						echo (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? '<p><em><strong>*Secure Server*</strong> In order to comply with PayPal® and PCI Compliance policies, as set forth by major credit card companies; you will need to host all of your PayPal® Pro Forms on an SSL enabled site. Please check with your hosting provider to ask about obtaining an SSL certificate for your domain. Please note... when you create PayPal® Pro Forms with s2Member; you\'ll be supplied with WordPress® Shortcodes, which you\'ll insert into Posts/Pages of your choosing. These special Posts/Pages will need to be displayed in SSL mode, using links that start with ( <code>https://</code> ). &mdash; You can skip the SSL certificate during Sandbox testing. SSL is not required until you officially go live. Once you\'re live, you can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page.</em></p>' . "\n" : '<p><em><strong>*Secure Server*</strong> In order to comply with PayPal® and PCI Compliance policies, as set forth by major credit card companies; you will need to host all of your PayPal® Pro Forms on an SSL enabled page. When you create PayPal® Pro Forms with s2Member; you\'ll be supplied with WordPress® Shortcodes, which you\'ll insert into Posts/Pages of your choosing. These special Posts/Pages will need to be displayed in SSL mode, using links that start with ( <code>https://</code> ). You can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page that contains a Pro Form Shortcode. This tells s2Member to force those special Posts/Pages to be viewed over SSL at all times; no matter what.</em></p>' . "\n";
						echo (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ()) ? '<p><em><strong>*SSL Compatibility*</strong> All themes available at <a href="http://www.primothemes.com/" target="_blank" rel="external">PriMoThemes.com</a> include full support for SSL, as does WordPress® itself. However, there are many themes/plugins that do NOT support SSL enabled Posts/Pages like they should. For this reason, you should be very careful when choosing a WordPress® theme to use with s2Member Pro. Otherwise, your visitors could see the famous "Secure/Insecure" warnings in Internet Explorer® browsers. With s2Member installed, you can add the Custom Field <code>s2member_force_ssl = yes</code> to any Post/Page. s2Member will buffer output on those special Posts/Pages, converting everything over to <code>https://</code> for you automatically, and forcing those specific Posts/Pages to be viewed over a secure SSL connection; so long as your server supports the https protocol.</em></p>' . "\n" : '';
						echo '<p><em><strong>*PayPal® Pro is NOT Absolutely Required*</strong> s2Member is very flexible. It is now possible to integrate Pro Forms without a PayPal® Pro account, whereby the enhanced Form Shortcodes that s2Member provides can be integrated ONLY with PayPal® Express Checkout. In other words, if you get declined for PayPal® Pro service, you can still use s2Member Pro Forms. Ask PayPal® to activate Express Checkout for you. ( it\'s free ). Once Express Checkout is enabled, you will have access to your <a href="http://www.s2member.com/paypal-profile-api-access" target="_blank" rel="external">PayPal® API Credentials</a>. Log into your PayPal® account, and navigate to <code>Profile -> API Access (or Request API Credentials)</code>. You\'ll choose <code>( Request API Signature )</code>. Now ... here is the tricky part; whenever you generate a Pro Form Shortcode with s2Member, be sure to change  <code>accept="paypal,visa,mastercard,amex,discover,maestro,solo"</code> to just <code>accept="paypal"</code>; thereby excluding the on-site credit card processing functionality; which is available only with PayPal® Pro.</em></p>' . "\n";
						echo '<p><em><strong>*PayPal® Express Checkout Limitations*</strong> If you decide NOT to acquire a PayPal® Pro account, and instead integrate ONLY with PayPal® Express Checkout, please understand the following limitation. PayPal® Express Checkout is intended to facilitate payments for PayPal® account owners and/or Customers willing to signup for PayPal® during checkout. It is NOT possible for a Customer to go through PayPal® Express Checkout without having and/or acquiring a PayPal® account, regardless of the transaction type ( i.e. Buy Now functionality is no exception to this rule ). This is because PayPal® Express Checkout is really intended for Customers that *prefer* to pay with PayPal®. So although it\'s possible to integrate Pro Forms without a PayPal® Pro account, we recommend that you acquire a PayPal® Pro account, so that you can accept credit cards on-site, and then offer PayPal® Express Checkout to Customers that *prefer* to pay with PayPal®. That\'s the way s2Member Pro Forms are designed to work, in an ideal fashion.</em></p>' . "\n";
						echo '</div>' . "\n";
						/**/
						echo '<div class="ws-menu-page-hr" style="margin-bottom:0;"></div>' . "\n";
					}
			}
	}
/**/
new c_ws_plugin__s2member_pro_menu_page_paypal_ops_details ();
?>