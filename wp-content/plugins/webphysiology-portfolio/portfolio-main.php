<?php
/*
Plugin Name: WEBphysiology Portfolio
Plugin URI: http://webphysiology.com/redir/webphysiology-portfolio/
Version: 1.4.1
Description: Provides a clean Portfolio listing with image, details and portfolio type taxonomy. A [portfolio] shortcode is used to include the portfolio on any page.
Author: Jeff Lambert
Author URI: http://webphysiology.com/redir/webphysiology-portfolio/author/
*/

/*	License: GPL2
	
	Copyright 2010-2011  JVHM, Inc.  (email : info@jvhm.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
	ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
	ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/*  UPDATES

	1.4.1 - * removed un-used conditionally_add_scripts_and_styles function as there was another plugin, my-record-collection, that also had this function defined.
			* added custom Portfolio Tag taxonomy and added update code to convert any existing post tags to this custom Portfolio tag
			* added Portfolio Tag Cloud widget
			* by default any custom Portfolio tags are included in the standard Tag Cloud widget, but an option to override this behavior is available within the plugin options
			* added ability to create single-webphys-portfolio.php template for use when displaying a single Portfolio record
			* added ability to create archive-webphysiology_portfolio_tag.php template for use in displaying Portfolios associated with a Portfolio tag
			* added ability to change thumbnail cache folder permissions to 0777 to deal with some instances where 0755 default permissions don't work with timthumb.php, resulting in no image being displayed
	1.4.0 - * changed the action that the has_shortcode function is called in to cover for single portfolio screen displaying and thickbox contentions
			* added back the ability to preview a single portfolio record
	1.3.2 - * in response to an issue with Thesis, changed the hook used to call function that sets css and scripts on pages with the webphysiology_shortcode
			* moved the instantiation of the webphysiology shortcode to just the non-admin area of the plugin
	1.3.1 - * removed deprecated #portfolios ID from embedded stylesheet; was overlooked in version 1.2.7 when the stylesheet was updated
			* updated file_loader.js script to allow a user to insert media into the content area of the portfolio
	        * adjusted code to try and further reduce the <head> overhead on pages when not in the Admin area
			* changed default "isAllowed" sites to not specify any original out-of-the-box sites and require users to enter the sites so they are fully in control
			* renamed "thumb/timthumb.php" and updated to the new 2.0 version
			* separated out functions and consolidated all code that deals with Admin areas. the functions are now in a separate function.php file
			* further targeted query adjustments to touch only WEBphysiology Portfolio queries
			* added a button for clearing all image caches (temp, stw/cache, timthumb/cache)
			* added second save and donate button to the top of the Portfolio Options admin page
	1.3.0 - * updated code so that the admin css stylesheet is only called when on the WEBphysiology Portfolios Options page
			* removed width styling for the individual portfolio context menus on the portfolio listing
			* added ability to place the Portfolio Description below the Portfolio Meta Data output
			* fixed issue where Portfolio counts in the Portfolio Type listing weren't being updated
			* deprecated the "portfolio" shortcode as it was replaced with "webphysiology_portfolio" in v1.2.4
			* added additional Options header comments from the plugin authors
			* removed the "Post Tags" sub-menu from the Portfolio menu block
			* removed the "Post Tags" and "Portfolio Types" fields from within the Quick Edit area of the Portfolio Listing
			* added inclusion of a new stylesheet for use when on the edit screen of a Portfolio
	1.2.9 - * corrected issue where new installs were not having all of the default options set
			* fixed issue where some of the release notes were not being displayed
			* fixed issue where non-ShrinkTheWeb users were not having images displayed in a thickbox
			* added "Shortcode Values Help" to Portfolio Options Admin page
			* added code to ensure that the image "temp" directory existed
			* added some process flow charts for thumbnail image generation and click behavior
	1.2.8 - * corrected a change made in 1.2.7 where the thumbnail would not display when using a non-Pro version of ShrinkTheWeb and the image click behavior was set to open the Portfolio web page URL
			* added note to image click behavior option setting to let user know that a non-pro version of ShrinkTheWeb will always result in the image click opening the Portfolio web page URL
	1.2.7 - * pushed the fancybox jquery script down into the footer
			* added new option to allow disabling the registering of the Google served jQuery code as other plugins, like MailChimp, has some sort of conflict otherwise
			* added new option to allow disabling the registering of the Fancybox script as other plugins, like Fancybox for WordPress, use an earlier version and they "break" with newer versions - this is me trying to be a good neighbor
			* updates made to support the changes to ShrinkTheWeb.com that removes local caching of thumbnails, which also led to not being able to display the ShrinkTheWeb.com images within a thickbox
			* corrected admin message system as it wasn't always displaying any crafted messages
	1.2.6 - * fixed an issue where the update notes were not being displayed
			* tried to harden the code that updates the database when upgrading from a version lower than 1.2.4
	1.2.5 - * updated the image paths to use "/wp-content/... instead of the whole path URL as some hosting companies won't allow http://www in the URL args
			* enhanced plugin messaging system to be properly formatted, which also reauired updates to portfolio_admin.css
			* include my own copy of farbtastic as I couldn't get WordPress to load the existing WP version after the google jQuery load
			* got the version notes displaying consistently in fancybox
			* updated fancybox script to version 1.3.4
			* updated jQuery to version 1.4.4
	1.2.4 - * added shortcode parameter "id" that allows for the ability to encapsulate a portfolio within a <div> of a specified id
			* added shortcode parameter "per_page" that allows for the ability to override the options setting specifying the number of portfolios to display per page
			* added shortcode parameter "thickbox" that allows for the ability to override the options setting specifying the image click behavior 
			* added shortcode parameter "credit" that allows for the ability to override the options setting specifying whether to display the plugin credit
			* found that some of the shortcode variables were not being cleared when a shortcode was used more than once on a given page - fixed
			* added support for thickbox pop-ups to display Youtube and Vimeo videos along with pulling back the thumbnail associated with them
			* moved plugin Options up within the Portfolio menu section and also moved the "Settings" link on the plugins page from the description area to under the plugin title
			* updated the custom post type from "Portfolio" to "webphys_portfolio" because v3.1 doesn't like caps and also to avoid contention with other plugins
			* started transition of shortcode from [portfolio] to [webphysiology_portfolio]
			* added important release notes to the Portfolio Options page
	1.2.3 - * added code to trap for autosave and quick edit saves such that custom Portfolio save script does not execute and, in the case of the
	          quick edit save, keep it from completing
			* removed the "view" option within the Portfolio admin listing as there is no individual Portfolio view
			* updated Portfolio Listing column labels to those set in the Portfolio options
			* updated Portfolio Listing to hide Portfolio Types as QuickEdit does not utilize a select list
			* removed the "preview" button from the Portfolio edit screen
			* changed the ShrinkTheWeb secret key input to a type Password to mask the value
			* added environment check to ensure the current host meets the minimum requirements of this plugin
			* added a setting to allow clicks on links to open in a new tab (target="_blank")
	1.2.2 - * removed the forcing of the sort field to be numeric and added an option to sort alphabetically (by turning off "sort numerically")
	1.2.1 - * made some changes to the navigation control, nav_pages(), as it wasn't always accurately drawn
	        * removed an errant character from a line of code
			* added note to Portfolio edit screen when ShrinkTheWeb is used to let the user know that entering an image URL will override the use of ShrinkTheWeb for that Portfolio
	1.2.0 - * added support for ShrinkTheWeb.com
	        * am removing the empty "temp" directory from the plugin package and replacing it with code that will create it should it not exist
	        * updated the portfolio_search_where() function to handle any amount of included and excluded portfolio types and in any order
			* updated nav control code to handle multiple [portfolio] shortcodes being used on one page
			* updated embedded CSS to include new "webphysiology_portfolio" Class, which is identical to what the "portfolios" ID was. this was
			  necessary as the ID was invalid in that using the [portfolio] shortcode more than once on a page would result in duplicate IDs.
			  The first [portfolio] shortcode will still use the "portfolios" ID but subsequent ones will have a number appended to the ID and
			  at some point the #portfolios entries in the CSS files will be removed, defaulting to just the "webphysiology_portfolio" class
	1.1.5 - * updated nav_pages() method as it wasn't working when pretty permalinks were not being utilized
	        * enhanced nav control method so that it doesn't have to rebuild for the bottom nav, it just uses what was built for the top nav
			* updated code to allow for portfolio images that are hosted on sites other than the current site
	1.1.4 - fixed a bug where the plugin credit could not be turned off
	1.1.3 - added grid styling and ability to turn off portfolio title and description
    1.1.2 - updated included loop-portfolio.php file
    1.1.1 - Bug fix - a form tag around the color selector was keeping the Portfolio Settings submit button from firing on Windows machines, so, removed it as it was unnecessary
    1.1.0 - several changes to this script were made in this release, including the following:
			* Added a color picker to the Admin styling area to make color selections quicker
			* Added the ability to change the detail data labels and their width
			* Added the ability to turn off the display of all detail data items should you want to store the values but not display them
			* Added the ability to navigate to the specified "site" URL when you click on the thumbnail as opposed to opening up a larger image in a litebox
			* Added the ability to specify a missing image URL as opposed to using the plugin provided image
			* Fixed potential issue where embedded STYLE was still being included when NOT using WEBphysiology Portfolio CSS
    1.0.2 - changed image url passed to timthumb.php to exclude the content directory from the path as
    		some installs were having problems with this
    
*/

// TimThumb.php was utilized in order to resize the uploaded site images for display as "thumbnails" on the portfolio listing.
// 	Code was also "borrowed" from TimThumb for use in checking the existance of the specified image and replacing it with an "empty" image if it no longer existed.
//	  http://www.darrenhoyt.com/2008/04/02/timthumb-php-script-released/

// the "fancybox" jQuery code was utilized in this plugin in order to allow for a nicer, more modern display of the fullsize image.  http://fancybox.net/

// thanks also to http://www.webmaster-source.com/2010/01/08/using-the-wordpress-uploader-in-your-plugin-or-theme/ for getting me on the road to adding the upload image feature

// in addition to the WordPress Codex and support site, I picked up some good info while working on this plugin from the following Posts:
//     http://wptheming.com/2010/08/custom-metabox-for-post-type/
//     http://scribu.net/wordpress/custom-sortable-columns.html
//     http://shibashake.com/wordpress-theme/add-custom-post-type-columns



/**********
// ASTERISK - future tasks
// turn into a Class
// break functionality into separate scripts
// add ability to include / exlude multiple variations of Portfolio Types
// image gallery widget or shortcode
// add sort order to quick edit
**********/


// ASTERISK = make certain to update these as appropriate with new releases //

define ( 'WEBPHYSIOLOGY_VERSION', '1.4.1' );
define ( 'WEBPHYSIOLOGY_DB_VERSION', '3.3.1' );
define ( 'WEBPHYSIOLOGY_PORTFOLIO_WP_PAGE', basename($_SERVER['PHP_SELF']) );

include_once("function.php");

// register the Portfolio custom post type and shortcode
add_action( 'init', 'portfolio_post_type_init' );
add_filter('post_updated_messages', 'portfolio_updated_messages');

// register the Portfolio Type taxonomy
add_action( 'init', 'create_portfolio_type_taxonomy', 0 );

if ( is_admin() ) {
	
	$plugin = plugin_basename(__FILE__);
	$file = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'css/portfolio_all_admin.css';
	
	check_options();
	
	register_activation_hook(__FILE__,'portfolio_install');
	add_action('admin_menu', 'portolio_admin_menu');
	add_filter( 'plugin_action_links_' . $plugin, 'add_plugin_settings_link' );
	add_action("init","check_version");
//	add_action("admin_init","check_version");  // asterisk - should I change to this even though I'm already in the Admin (is_admin)?
	add_action('admin_print_scripts', 'portfolio_admin_scripts');
	add_action('admin_print_styles', 'portfolio_admin_styles');
	add_action('admin_notices', 'display_update_alert');
	add_action('admin_menu', 'remove_post_custom_fields');
	add_filter('manage_edit-webphys_portfolio_columns', 'add_new_portfolio_columns');
	add_action('manage_posts_custom_column', 'manage_portfolio_columns', 10, 2);
	add_action('admin_head-edit.php', 'webphysiology_portfolio_quickedit');
// 12/23/2011 - added back the viewing of a single portfolio record	add_filter('post_row_actions','remove_quick_edit',10,2);
	add_action('save_post', 'portfolio_type_taxonomy_count');
	 
	// Add the Save Metabox Data
	add_action('save_post', 'save_portfolio_meta', 1, 2); // save the custom fields
	
	register_deactivation_hook( __FILE__, 'portfolio_remove' );
	
	wp_register_style('portfolio_all_admin_css', $file);
	wp_enqueue_style('portfolio_all_admin_css');
	
	if ( WEBPHYSIOLOGY_PORTFOLIO_WP_PAGE == "plugins.php" ) {
		add_action('after_plugin_row_webphysiology-portfolio/portfolio-main.php', 'portfolio_requirements_message');
	}
	
	// Additional links on the plugin page
	add_filter('plugin_row_meta', 'RegisterPluginLinks',10,2);
	
	// Nice icon for Admin Menu (requires Ozh Admin Drop Down Plugin)
	add_filter('ozh_adminmenu_icon', 'RegisterAdminIcon');

	if ( WEBPHYSIOLOGY_PORTFOLIO_WP_PAGE == 'edit.php' ) {
		
		global $post;
		
		// as long as no one overrode this plugin's standard setting of loading jQuery from Google
		$opt_val_skip_jQuery_register = strtolower(get_option('webphysiology_portfolio_skip_jQuery_register'));
		if ( $opt_val_skip_jQuery_register == 'false' ) {
			add_action('init', 'get_google_jquery');
		}
		
		add_action('init', 'get_colorpicker_jquery');
		
		// add in support for the "clear image caches" button
		$base = esc_attr(plugin_dir_url(__FILE__) . 'scripts/');
		wp_enqueue_script('prototype');
		wp_register_script('clear_images', $base.'manage_img_caches.js');
		wp_enqueue_script('clear_images');

		// if the default behavior to load the Fancybox jQuery code has not been overwritten
		$skip_fancybox_jquery_register = strtolower(get_option('webphysiology_portfolio_skip_fancybox_register'));
		if ( $skip_fancybox_jquery_register == 'false' ) {
			// register the stylesheet if we are not on the portfolio edit page since there is another call to do that already
			if ( empty($post) ) {
				add_action('init', 'jquery_fancybox_styles');
			}
			add_action('init', 'jquery_fancybox_init');
		}
		
//		add_action('admin_head','mailchimpie');
		add_action('admin_footer', 'admin_settings_jquery');
		add_action('admin_footer', 'fancy_script', 12);
	
	}
} else {
	add_shortcode('webphysiology_portfolio', 'portfolio_loop');
	add_action ('wp','has_shortcode');
	add_filter('query_vars', 'portfolio_queryvars' );
	add_filter('posts_join', 'portfolio_search_join', 10, 2 );
	add_filter('posts_where', 'portfolio_search_where', 10, 2 );
}
?>