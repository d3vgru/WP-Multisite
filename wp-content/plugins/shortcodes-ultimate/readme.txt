=== Plugin Name ===
Contributors: gn_themes
Donate link: http://gndev.info/donate/
Tags: shortcode, shortcodes, short code, shortcodes, tab, tabs, button, buttons, jquery, box, boxes, toggle, spoiler, column, columns, services, service, pullquote, list, lists, frame, images, image, links, fancy, fancy link, fancy links, fancy buttons, jquery tabs, accordeon, slider, nivo, nivo slider, plugin, admin, photoshop, gallery, bloginfo, list pages, sub pages, navigation, siblings pages, children pages, permalink, permalinks, feed, document, member, members, documents, jcarousel, rss
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 3.7.0

Provides support for multiple useful shortcodes


== Description ==

With this plugin you can easily create buttons, boxes, different sliders and much, much more. Turn your free theme to premiun in just a few clicks. Using Shortcodes Ultimate you can quickly and easily retrieve many premium themes features and display it on your site. See screenshots for more information.

= Features =
* 30+ amazing shortcodes
* Handy shortcodes generator
* Custom CSS editor with syntax highlight
* Frequently updates
* International

= New in this version =
* Complete support for nested shortcodes. Check the FAQ page.
* New shortcode [label]
* New style for buttons [button style="5"]
* Fixed images ordering for [custom_gallery], [jcarousel] and [nivo_slider]

= Got a bug? =
* [Support forum](http://wordpress.org/tags/shortcodes-ultimate?forum_id=10)
* [Plugin page](http://gndev.info/shortcodes-ultimate/)
* [Twitter](http://twitter.com/gn_themes)

= Translations =
* Fr - [Aurélien DENIS](http://wpchannel.com/)
* Sp - [Esteban Truelsegaard](http://www.netmdp.com/)
* De - [Matthias Wittmann](http://net-graphix.de/)
* Ru - [Vladimir Anokhin](http://gndev.info/)
* By - [Alexander Ovsov](http://webhostinggeeks.com/science/)

Have a translation? [Contact me (for translators ONLY)](mailto:ano.vladimir@gmail.com)


== Installation ==

Unzip plugin files and upload them under your '/wp-content/plugins/' directory.

Resulted names will be:
  './wp-content/plugins/shortcodes-ultimate/*'

Activate plugin at "Plugins" administration page.


== Upgrade Notice ==

Upgrade normally via your Wordpress admin -> Plugins panel.


== Screenshots ==

1. Insert shortcode in 3 easy steps.
2. Heading, spoiler, tabs, quote, button.
3. Box, note, divider (top), list.
4. List styles.


== Frequently Asked Questions ==

= What mean compatibility mode? =
This mode adds a prefix to all plugin shortcodes

* [button] => [gn_button]
* [tabs] => [gn_tabs]
* [tab] => [gn_tab]
* etc.

= Is there WYSIWYG button? =
Search it near Upload/Insert buttons. See screenshots.

= How to use: nivo_slider, jcarousel, custom_gallery =
With these shortcodes you can create different galleries from attached to post images, or from category posts.

Way 1: gallery from attachments

* Create new post
* Upload images
* Use next shortcode on pages, posts or even widgets

`[nivo_slider source="post=XX" link="image"]`

XX - ID of the post with uploaded images

Way 2: gallery from category

* Create some posts in some category
* Set the thumbnails of posts
* Use next shortcode on pages, posts or even widgets

`[nivo_slider source="cat=XX" link="image"]`

XX - ID of the category with new posts

Also, you can use [jcarousel] and [custom_gallery] according these principles.


== Changelog ==

= 3.6 =
* Descriptions for [custom_gallery]
* Custom options for jwPlayer
* Fixed size option for sliders and gallery

= 3.5 =
* New shortcode [accordion] for muliple spoilers
* Improved spoiler shortcode (check settings page)
* Multiple tabs bugfix
* Authors can also use shortcode generator
* Nested shortcodes: spoiler, column, tabs, box, note

= 3.4 =
* Belarusian translation
* New shortcode [dropcap]

= 3.3 =
* Changed: [nivo_slider] and [jcarousel] (see docs in console)
* New shortcode: [custom_gallery]
* New parameter: [members login="0|1"]
* New shortcode: guests
* German translation

= 3.0 =
* Button for WYSIWIG editor (search it near Upload/Insert buttons)
* New shortcode: private (private notes for editors)
* Patched and secure timthumb.php

= 2.7 =
* French translation
* Fixed for work with new jQuery 1.6 in WP 3.2

= 2.5 =
* Theme integration

= 2.4 =
* New shortcode: jcarousel

= 2.3 =
* New admin page: Demo

= 2.2 =
* New shortcode: document
* New shortcode: members
* New shortcode: feed
* New attr: link="caption" for [nivo_slider]
* New attr: p for [subpages]
* New tabs style (style=3)

= 2.1 =
* New option: disable any script
* New option: disable any stylesheet
* New attribute for column shortcode - style
* New attribute for spoiler shortcode - style

= 2.0 =
* New shortcode: menu
* New shortcode: subpages
* New shortcode: siblings
* Some admin fixes
* New button attribute - class
* New button attribute - target
* Different tabs styles (1 old + 1 new)

= 1.9 =
* New shortcode: permalink
* New shortcode: bloginfo

= 1.8 =
* Some small additions
* Ajax admin page
* No-js compatibility
* Multiple tabs support

= 1.7 =
* Improved settings page design
* Added shortcode nivo_slider
* Added shortcode photoshop

= 1.6 =
* New admin panel
* Custom CSS editor with syntax hughlight
* Small fixes
* Added donation forms

= 1.5 =
* Added option "Compatibility mode"
* Added new button styles
* Added new list styles
* Added new shortcode media
* Added new shortcode table

= 1.4 =
* Added shortcode "Fancy link"

= 1.3 =
* Some fixes

= 1.2 =
* Localization support

= 1.1 =
* Added options page
* Fixed options saving

= 1.0 =
* Initial release

