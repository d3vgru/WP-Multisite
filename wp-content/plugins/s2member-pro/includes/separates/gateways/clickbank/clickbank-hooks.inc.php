<?php
/**
* Primary Hooks/Filters for ClickBank®.
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
* @package s2Member\ClickBank
* @since 1.5
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
/*
Add the plugin Actions/Filters here.
*/
add_action ("init", "c_ws_plugin__s2member_pro_clickbank_return::clickbank_return", 1);
add_action ("init", "c_ws_plugin__s2member_pro_clickbank_notify::clickbank_notify", 1);
/**/
add_filter ("ws_plugin__s2member_during_constants_c", "c_ws_plugin__s2member_pro_clickbank_constants::clickbank_constants", 10, 2);
/**/
add_action ("ws_plugin__s2member_during_css", "c_ws_plugin__s2member_pro_clickbank_css_js::clickbank_css");
add_action ("ws_plugin__s2member_during_js_w_globals", "c_ws_plugin__s2member_pro_clickbank_css_js::clickbank_js_w_globals");
add_action ("ws_plugin__s2member_during_menu_pages_js", "c_ws_plugin__s2member_pro_clickbank_admin_css_js::clickbank_menu_pages_js");
/**/
add_filter ("ws_plugin__s2member_return_template_support", "c_ws_plugin__s2member_pro_clickbank_utilities::clickbank_cc_reminder", 10, 2);
/**/
add_filter ("ws_plugin__s2member_during_add_admin_options_add_divider_4", "c_ws_plugin__s2member_pro_clickbank_menu_pages::clickbank_admin_options", 10, 2);
/**/
add_action ("ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants", "c_ws_plugin__s2member_pro_clickbank_menu_pages::clickbank_scripting_page_api_constants");
add_action ("ws_plugin__s2member_during_scripting_page_during_left_sections_during_list_of_api_constants_farm", "c_ws_plugin__s2member_pro_clickbank_menu_pages::clickbank_scripting_page_api_constants");
?>