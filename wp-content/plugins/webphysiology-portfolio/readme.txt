=== WEBphysiology Portfolio ===
Contributors: lambje
Donate link: http://webphysiology.com/redir/webphysiology-portfolio/donate/
Tags: portfolio,website,image,screenshot,gallery,list,grid,posts,post,custom post type,custom taxonomy,pagepeeker,shrinktheweb,thumbnail,webphysiology
Requires at least: 3.1.0
Tested up to: 3.3.1
Stable tag: 1.4.1

Allows for the creation of an expanded-list styled or a grid-styled page containing images and supporting detail, perfect for a portfolio presentation.

== Description ==

The WEBphysiology Portfolio plugin was built to provide a clean, current look in situations where an expanded list-style or grid-style portfolio layout is appropriate. The plugin is implemented via a [shortcode] that supports a variety of filtering options based upon Portfolio Type.  More than one shortcode can be used on a given page, allowing for grouping portfolio records by type.  And the shortcode also supports overriding many overall option settings on a case-by-case basis.

The plugin utilizes a Custom Post Type as well as a Custom Taxonomy. It provides an Options page for specifying some customizable settings, like the number of entries to display per page. It also allows one to turn off the provided CSS in place of implementing your own.

The Portfolio entry screen is customized to include just the items that make up a Portfolio entry. Attributes that aren't populated will not be displayed on the end user interface as well as custom fields whose display has been turned off. Attaching an image to a Portfolio entry also has been made relatively painless.

To enhance the design, and also reduce page weight, TimThumb 2.0 is utilized to display thumbnails of full-size imported images.  The ability to add automatic web site thumbnails utilizing ShrinkTheWeb.com was added in version 1.2.0.  And in version 1.4.0, the PagePeeker.com thumbnail service was incorporated.

The end user interface can be adjusted using styling Portfolio Options or via your own CSS. Managing how things work within the end user interface is very customizable. Clicking thumbnail images can result in an image opening in a thickbox or it can take the visitor to the specified website URL....  The plugin also supports video media types, such that they can be played within a thickbox.

A good many hours have been poured into this plugin, so, appreciation in the form of a donation always brightens our day.

== Installation ==

This section describes how to install the plugin and get it working.

1. Extract the WEBphysiology Portfolio ZIP file and place the `webphysiology-portfolio` folder into the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the necessary Portfolio Types via it's menu under the Portfolio section
4. Add one or more Portfolios
5. Place the [webphysiology_portfolio] shortcode into the content area of your "portfolio" page

== Frequently Asked Questions ==

= Specifying a 'portfolio_type' in the shortcode is not working. Why? =

Double-check that the code you are specifying is the correct one.  For example, if you've used the same Portfolio Type as a Tag, chances are the slug on your portfolio type had a number appended, even though you didn't type it in when you added the type.

Another cause is that sometimes, when you add the shortcode in the Visual editor, it formats the entry, especially if you've copied and pasted. If this happens then the plugin is unable to parse out the various components. The easiest way to see if this is happening is to flip to HTML editor mode and see if there are tags around any of the elements within the shortcode (e.g., &lt;strong&gt;, &lt;em&gt;).  While in the HTML editor, remove any open and close formatting tags that are found or remove the formatting while in the Visual editor screen.

= Where can I get more details on using this plugin? =

More detailed information can be found here: http://webphysiology.com/plugins/webphysiology-portfolio-plugin/.

= How can I get support? =

My intention, at a minimum, is to maintain this plugin such that it is defect free.  For more detailed documentation and videos visit our <a href="http://webphysiology.com/plugins/webphysiology-portfolio-plugin/">WEBphysiology Portfolio plugin page</a>.  If you still aren't finding the answer you are seeking, use our <a href="http://webphysiology.com/helpdesk/" title="WEBphysiology Help Desk">support system</a> to log a ticket.

= The thumbnail image isn't displaying =

In some instances, when using the built-in thumbnail generator, the standard folder permissions aren't enough. After checking that the image URL specified is correct, try upping the caching folder's permission using the "Permission Image Cache to 0777" button in the options.

= The website URL I've specified is not being displayed as the ShrinkTheWeb.com generated thumbnail =

If you are specifying an inside page within your URL, then you must specifically subscribe to that level of service on your ShrinkTheWeb.com account.

= The styling of my Portfolio is not reflecting the changes I made in the Portfolio options.  Why? =

The styling behavior of the WEBphysiology Portfolio can vary from theme to theme.  The reason for this is that the theme's styling can trump the Portfolio's styling depending upon where the styling of one or the other falls within the styling hierarchy.  If this is happening you'll have to adjust your theme's styling or add additional styling to allow for the portfolio's styling to work.

= When I close the image pop-up thickbox I still see an animated loading image. =

*** This may not be an issue any longer but has not been tested in quite awhile ***<br />There is a conflict when running WEBphysiology Portfolio and the eCommerce plugin.  This does not appear to be a fault of the WEBphysiology Portfolio plugin as no errors are returned for this plugin but quite a number of errors are being thrown by the eCommerce plugin.  If the image click option does not open the image, but instead navigates to the site URL, then this anomaly will not occur.

= I'm getting strange error messages within the Portfolio Options page. =

Try resetting all the options by using the "Revert to Default Values" button at the bottom of the form.

== Screenshots ==

1. Portfolio Page Frontend User Interface
2. Portfolio Post edit screen
3. Portfolio Page edit screen showing [shortcode] implementation
4. Portfolio options screen
5. Portfolio Tag Cloud widget

== Upgrade Notice ==

= 1.0.0 was the initial release =

== Changelog ==

= 1.4.1 =
* removed un-used conditionally_add_scripts_and_styles function as there was another plugin, my-record-collection, that also had this function defined.
* added custom Portfolio Tag taxonomy and added update code to convert any existing post tags to this custom Portfolio tag
* added Portfolio Tag Cloud widget
* by default any custom Portfolio tags are included in the standard Tag Cloud widget, but an option to override this behavior is available within the plugin options
* added ability to create single-webphys-portfolio.php template for use when displaying a single Portfolio record
* added ability to create archive-webphysiology_portfolio_tag.php template for use in displaying Portfolios associated with a given Portfolio tag
* added ability to change thumbnail cache folder permissions to 0777 to deal with some instances where 0755 default permissions don't work with timthumb.php, resulting in no image being displayed

= 1.4.0 =
* added support for displaying a single portfolio post, alleviating issues with 404 screens displaying when a Portfolio record was accessed via a search results screen
* added back the ability to preview a single portfolio record
* changed the action that the has_shortcode function is called in to cover for single portfolio screen displaying and thickbox contentions
* added PagePeeker service support for thumbnail generation
* replaced some path code in place of the plugins_url function
* added the “webphysiology_portfolio_use_full_path” option to allow for the ability to have images, and some css/js files, specify full pathnames. there seems to be instances where some hosts don’t like HTTP:// within the image pathnames and some cases where they require it
* added code to handle image paths needing adjustment in a multisite install
* updated inclusion of ShrinkTheWeb script to utilize wp_enqueue_script and specifically un-register any instances that were registered as “stw-pagepix”, which is how WordPress Portfolio Plugin registers it
* added deregister of thickbox resources to try and avoid contention with other plugins, like Auto Thickbox Plus
* added function for gathering plugin options and returning them within an array

= 1.3.2 =
* in response to an issue with Thesis, changed the hook used to call function that sets css and scripts on pages with the webphysiology_shortcode
* added support for Options page WEBphysiology social buttons
* better isolated navigation controls by adding "webphysport_nav_top" and "webphysport_nav_bottom" classes. top and bottom classes will be deprecated in a later release.
* added embedded portfolio CSS for new webphysport_odd_stripe and webphysport_even_stripe classes. odd and even classes will be deprecated in a later release.
* moved the instantiation of the webphysiology shortcode to just the non-admin area of the plugin
* enhanced the "has_shortcode" function to utilize a different method to obtain the current page's content as using "the_content()" was causing issues with some theme's page titling
* since non-Pro STW cannot be sized like other images within the portfolio, updated ShrinkTheWeb code to get the largest image from STW that is <= the image width defined within the WEBphysiology Portfolio Options
* to clean up IE7 and below Portfolio meta data layout a new stylesheet was created that defined CSS for the div's within the portfolio_meta classed div and then added negative bottom margin to these child div's to pull them together

= 1.3.1 =
* removed deprecated #portfolios ID from embedded stylesheet; was overlooked in version 1.2.7 when the stylesheet was updated
* updated file_loader.js script to allow a user to insert media into the content area of the portfolio
* adjusted code to try and further reduce the <head> overhead on pages when not in Admin
* changed default "isAllowed" sites to not specify any original out-of-the-box sites and require users to enter the sites so they are fully in control
* renamed "thumb/timthumb.php" and updated to the new 2.0 version
* separated out functions and consolidated all code that deals with Admin areas. the functions are now in a separate function.php file
* further targeted query adjustments to touch only WEBphysiology Portfolio queries
* added a button for clearing all image caches (temp, stw/cache, timthumb/cache)
* added second save and donate button to the top of the Portfolio Options admin page

= 1.3.0 =
* added ability to place the Portfolio Description below the Portfolio Meta Data output
* fixed issue where Portfolio counts in the Portfolio Type listing weren't being updated
* deprecated the "portfolio" shortcode as it was replaced with "webphysiology_portfolio" in v1.2.4
* added CSS to handle positioning the Portfolio description below the meta data
* updated code so that the admin css stylesheet is only called when on the WEBphysiology Portfolios Options page
* removed width styling for the individual portfolio context menus on the portfolio listing
* added additional Options header comments from the plugin authors
* removed the "Post Tags" sub-menu from the Portfolio menu block
* removed the "Post Tags" and "Portfolio Types" fields from within the Quick Edit area of the Portfolio Listing
* added inclusion of a new stylesheet for use when on the edit screen of a Portfolio
* added new CSS file: "css\portfolio_post.css"
= 1.2.9 =
* corrected issue where new installs were not having all of the default options set
* fixed issue where some of the release notes were not being displayed
* fixed issue where non-ShrinkTheWeb users were not having images displayed in a thickbox
* added code to ensure that the image "temp" directory exists
* added "Shortcode Values Help" to Portfolio Options Admin page
* added to the Options Admin page some process flow charts to document the thumbnail image generation and click behavior logic flows
= 1.2.8 =
* corrected a change made in 1.2.7 where the thumbnail would not display when using a non-Pro version of ShrinkTheWeb and the image click behavior was set to open the Portfolio web page URL
* added note to image click behavior option setting to let user know that a non-pro version of ShrinkTheWeb will always result in the image click opening the Portfolio web page URL
= 1.2.7 =
* added a little error handling to the ShrinkTheWeb code to throw back any errors returned when trying to get a thumbnail from STW
* changed navigation styling to use borders and not images
* #portfolios   styling ID now has been deprecated and replaced with class   .webphysiology_portfolio
* admin options page has had some styling updates and option placement shifts
* pushed the fancybox jquery script down into the footer
* added new option to allow disabling the registering of the Google served jQuery code as other plugins, like MailChimp, has some sort of conflict otherwise
* added new option to allow disabling the registering of the Fancybox script as other plugins, like Fancybox for WordPress, use an earlier version and they "break" with newer versions - this is me trying to be a good neighbor
* fixed an issue where $portfolio_open_empty was not being defined for non-grid styled portfolios
* updates made to support the changes to ShrinkTheWeb.com that removes local caching of thumbnails, which also led to not being able to display the ShrinkTheWeb.com images within a thickbox
* corrected admin message system as it wasn't always displaying any crafted upgrade messages
= 1.2.6 =
* fixed an issue where the update notes were not being displayed
* tried to harden the code that updates the database when upgrading from a version lower than 1.2.4
= 1.2.5 =
* updated the image paths to use "/wp-content/... instead of the whole path URL as some hosting companies won't allow http://www in the URL args
* enhanced plugin messaging system to be properly formatted, which also reauired updates to portfolio_admin.css
* include my own copy of farbtastic as I couldn't get WordPress to load the existing WP version after the google jQuery load
* got the version notes displaying consistently in fancybox
* updated fancybox script to version 1.3.4
* updated jQuery to version 1.4.4
= 1.2.4 =
* added shortcode parameter "id" that allows for the ability to encapsulate a portfolio within a <div> of a specified id
* added shortcode parameter "per_page" that allows for the ability to override the options setting specifying the number of portfolios to display per page
* added shortcode parameter "thickbox" that allows for the ability to override the options setting specifying the image click behavior 
* added shortcode parameter "credit" that allows for the ability to override the options setting specifying whether to display the plugin credit
* found that some of the shortcode variables were not being cleared when a shortcode was used more than once on a given page - fixed
* added support for thickbox pop-ups to display Youtube and Vimeo videos along with pulling back the thumbnail associated with them
* fixed fancybox CSS file as the IE fixes had incorrect paths, which resulted in issues in IE and slowness in a site utilizing the jQuery plugin
* moved plugin Options up within the Portfolio menu block and also moved the "Settings" link on the plugins page from the description area to under the plugin title
* updated the custom post type from "Portfolio" to "webphys_portfolio" because v3.1 doesn't like caps and also to avoid contention with other plugins
* started transition of shortcode from [portfolio] to [webphysiology_portfolio]
* added important release notes to the Portfolio Options page
= 1.2.3 =
* Support for deeper page screenshot generation added for ShrinkTheWeb.com. This does require more than the basic subscription with them.
* Cleaned up some CSS validation errors
* Updated some PHP logic to utilize !empty v. !$x==''
* Updated post-id ID references to handle multiple [shortcodes] on one page as they were not necessarily unique in this instance
* Added an option that allows an admin to set the links to open in a new tab/window
* Added code to trap for autosave and quick edit saves such that the custom Portfolio save script does not execute and, in the case of the quick edit save, keep it from completing
* Removed the "preview" button from the Portfolio edit screen as there is no individual Portfolio view
* Removed the "view" option within the Portfolio admin listing as there is no individual Portfolio view
* Updated the Portfolio Listing column labels to use those set in the Portfolio options
* Updated Portfolio Listing to hide Portfolio Types as QuickEdit does not utilize a select list, which is problematic
* Changed the ShrinkTheWeb secret key input type to mask the value
* Added an environment check to ensure that the current host and WordPress version meets the minimum requirements of the WEBphysiology Portfolio plugin
= 1.2.2 =
* Removed the forcing of the sort field to be numeric and added an option to sort alphabetically (by turning off "sort numerically").
= 1.2.1 =
* Made some changes to the navigation control, nav_pages(), as it wasn't always accurately drawn
* Removed an errant character from a line of code
= 1.2.0 =
* Added support for ShrinkTheWeb.com
* Removed the empty "temp" directory from the plugin package and replaced it with code that will create it should it not exist
* Enhanced the portfolio search to handle any amount of included and excluded portfolio types and in any order
* Updated nav control code to handle multiple [portfolio] shortcodes being used on one page
* Updated CSS to handle multiple [portfolio] shortcodes being used on one page
* NOTE: The "portfolios" element ID will be deprecated in a later release.  CSS for the Class "webphysiology_portfolio" has been added in version 1.2.0 to replace the ID CSS.
= 1.1.5 =
* Updated nav_pages() method as it wasn't working when pretty permalinks were not being utilized
* Enhanced nav control method so that it doesn't have to rebuild for the bottom nav, it just uses what was built for the top nav
* Updated code to allow for portfolio images that are hosted on sites other than the current site
= 1.1.4 =
* Fixed a bug where the plugin credit could not be turned off.  Oops
= 1.1.3 =
* Added ability to suppress the display of the portfolio title and portfolio description
* Added the ability to display Portfolio items in a grid style
* Cleaned up the Admin interface
* Cleaned up some CSS styling issues
= 1.1.2 =
* Added apply_filters() to data retrieved with get_the_content() as that method does not include this, unlike the standard the_content() method
= 1.1.1 =
* Bug fix - a form tag around the color selector was keeping the Portfolio Settings submit button from firing on Windows machines
= 1.1.0 =
* Added a color picker to the Admin styling area to make color selections quicker
* Added the ability to change the detail data labels and their width
* Added the ability to turn off the display of all detail data items should you want to store the values but not display them
* Added the ability to navigate to the specified "site" URL when you click on the thumbnail as opposed to opening up a larger image in a litebox
* Added the ability to specify a missing image URL as opposed to using the plugin provided image
* Fixed potential issue where embedded STYLE was still being included when NOT using WEBphysiology Portfolio CSS
= 1.0.2 =
* Added support for WEBphysiology 80% opacity within IE
* CSS adjustments
* Updated thumbnail retrieval to change the image URL passed to timthumb to exlude the path up through the wp_content directory
= 1.0.1 =
* Minor adjustments to release (first pluginitis)
= 1.0.0 =
* Initial release.

== Support ==

NOTE: SHORTCODE DEPRECATION ALERT: The [portfolio] shortcode was replaced with [webphysiology_portfolio] in version 1.3.0

NOTE: CSS DEPRECATION ALERT: The "portfolios" element ID was be deprecated in version 1.2.7

I will do my best to correct any reported defects as soon as I can make time, but please understand that this is side work. That said, I also use this plugin and am keen to ensure it provides the intended functionality. As to requests for enhancements, feel free to make these. I'll do my best to respond to your requests and, for those requests that I feel would benefit the majority of users, I'll get them on the enhancement list. I can't say just how quickly these would be implemented but funding the request would definitely move it up in the queue.