<?php
/**
* Menu page for s2Member Pro ( Scripting, Remote Operations ).
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
* @since 110713
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_pro_menu_page_remote_ops_api"))
	{
		/**
		* Menu page for s2Member Pro ( Scripting, Remote Operations ).
		*
		* @package s2Member\Menu_Pages
		* @since 110713
		*/
		class c_ws_plugin__s2member_pro_menu_page_remote_ops_api
			{
				public function __construct ()
					{
						if (!is_multisite () || !c_ws_plugin__s2member_utils_conds::is_multisite_farm () || is_main_site ())
							{
								echo '<div class="ws-menu-page-group" title="Pro API For Remote Operations">' . "\n";
								/**/
								echo '<div class="ws-menu-page-section ws-plugin--s2member-api-remote-operations-section">' . "\n";
								echo '<h3>Pro API For Remote Operations ( some scripting required )</h3>' . "\n";
								echo '<p>With s2Member Pro installed, you have access to the s2Member Pro API For Remote Operations. This is made available for developers that wish to create User/Member accounts dynamically through custom scripts of their own. s2Member\'s Remote Operations API requires a secret API Key in order to POST authenticated requests to your installation of s2Member. Here is the API Key for this installation domain:</p>' . "\n";
								echo '<input type="text" autocomplete="off" value="' . format_to_edit (c_ws_plugin__s2member_pro_remote_ops::remote_ops_key_gen ()) . '" style="width:99%;" />' . "\n";
								echo '<p><em><strong>Experimental:</strong> The Remote Operations API is currently in an experimental state. The only Operation that is currently possible, is the <code>create_user</code> Operation, which is shown in the code sample below. In a future release of s2Member Pro, we will add further documentation and some additional Remote Operations to this API. Thanks for your patience.</em></p>' . "\n";
								echo '<p>' . c_ws_plugin__s2member_utils_strings::highlight_php (str_replace ("www.example.com", $_SERVER["HTTP_HOST"], str_replace ("http://www.example.com/", site_url ("/"), str_replace ("[API Key]", c_ws_plugin__s2member_pro_remote_ops::remote_ops_key_gen (), file_get_contents (dirname (__FILE__) . "/code-samples/remote-op-create-user.x-php"))))) . '</p>' . "\n";
								/**/
								echo '<div class="ws-menu-page-hr"></div>' . "\n";
								/**/
								echo '<p><strong>TIP:</strong> In addition to this documentation, you may also want to have a look at the <a href="http://www.s2member.com/codex/" target="_blank" rel="external">s2Member Codex</a>.<br />' . "\n";
								echo '<strong>See Also:</strong> <a href="http://www.s2member.com/codex/stable/s2member/api_constants/package-summary/" target="_blank" rel="external">s2Member Codex -> API Constants</a>, and <a href="http://www.s2member.com/codex/stable/s2member/api_functions/package-summary/" target="_blank" rel="external">s2Member Codex -> API Functions</a>.</p>' . "\n";
								echo '</div>' . "\n";
								/**/
								echo '</div>' . "\n";
							}
					}
			}
	}
/**/
new c_ws_plugin__s2member_pro_menu_page_remote_ops_api ();
?>