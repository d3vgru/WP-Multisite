<?php
/**
* Administrative CSS/JS.
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
* @package s2Member\Admin_CSS_JS
* @since 1.5
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_paypal_admin_css_js"))
	{
		/**
		* Administrative CSS/JS.
		*
		* @package s2Member\Admin_CSS_JS
		* @since 1.5
		*/
		class c_ws_plugin__s2member_pro_paypal_admin_css_js
			{
				/**
				* Adds JavaScript for this Payment Gateway.
				*
				* @package s2Member\Admin_CSS_JS
				* @since 1.5
				*
				* @attaches-to ``add_action("ws_plugin__s2member_during_menu_pages_js");``
				*
				* @param array $vars Expects an array of defined variables to be passed in by the Action Hook.
				* @return null
				*/
				public static function paypal_menu_pages_js ($vars = FALSE)
					{
						echo "\n"; /* Add a line break before inclusion of this file. */
						include_once dirname (dirname (dirname (dirname (__FILE__)))) . "/menu-pages/paypal-menu-pages-s-min.js";
						/**/
						return; /* Return for uniformity. */
					}
			}
	}
?>