=== Wordpress Font Uploader ===
Author: Pippin Williamson
Author URL: http://pippinspages.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=72HGD7SA97KPE
Tags: font, upload, custom fonts
Requires at least: 2.6
Tested up to: 3.0
Stable tag: 1.1

This plugin lets you upload your own font files and apply them to any element of your website.

== Description ==

This plugin lets you upload your own font files and apply them to any element of your website without requiring
a knowledge of html or css. All .otf and .ttf font files are supported.

== Installation ==

1. Upload the 'font-uploader' folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click 'Font Uploader' in the left admin bar of your dashboard

== Frequently Asked Questions ==

= Can I use this plugin to apply fonts to any html element, or just a select few? =

You can apply fonts to any element you wish.

== Google Fonts ==

The Google Font Directory may be accessed at http://code.google.com/webfont

To use Google Fonts on your site with Font Uploader, from your WordPress dashboard click Font Uploader -> Google Fonts
and enter the url provided by google in the box labeled "Google Font URLs", then enter the name of the font in the box labeled, for example, 
"Google Font Name - Headers".

For further instructions on how to use Google Fonts, watch the screencast located at http://pippinspages.com/

== Internet Explorer Fonts ==

Internet Explorer only supports embedded fonts if they are in the ".eot" format, which is a propietary font format by Microsoft only supported by Internet
Explorer. This means that for 100% cross browser support, unless you are using Google webFonts, you must define two fonts for every single element:
one in .otf or .ttf format, for browsers such as Chrome, Safari, and Firefox, and one font type for Internet Explorer.

To make your fonts 100% cross browser compatible, you first need to convert a regular font file (.ttf or .otf) and convert it to .eot. Sebastian Kippe has made a wonderful little tool for doing this. The ttf -> eot font converter can be found at "http://ttf2eot.sebastiankippe.com/".

Once you have converted your font, upload it with Font Uploader and choose the element you wish to apply it to in the "Internet Explorer Fonts" section.

You will also want to select the same font, except in the regular .ttf or .otf format, for the same element, but in the "Fonts" menu for display in all
other browsers.

== Further Instruction ==

For a detailed video tutorial, watch my screencast located at "http://pippinspages.com/wordpress/wordpress-font-uploader-plugin/"



