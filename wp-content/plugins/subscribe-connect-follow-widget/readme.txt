=== Subscribe / Connect / Follow Widget ===
Contributors: SriniG
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZMJU2J9SP836N
Tags: widget, subscribe, connect, follow, buttons, icons, image links, feed, rss, rss-feed, feedburner, twitter, facebook, blogger, delicious, diaspora, digg, deviant-art, flickr, friendfeed, google, google plus, identica, lastfm, linkedin, myspace, picasa, pinterest, podcast, posterous, reddit, slashdot, soundcloud, stumbleupon, technorati, tumblr, vimeo, xing, youtube
Requires at least: 2.8
Tested up to: 3.3
Stable tag: trunk

The widget displays image links (icon buttons) to various subscription services and social networking sites. Upto 30 services supported.

== Description ==

This plugin provides a widget that displays image links (icon buttons) to various subscription services and social networking sites in your sidebar (or any widget holder). Helps users in easily finding links to subscription services like RSS feed, email subscriptions, podcast, etc., follow the website's Facbeook and Twitter pages, etc., and connect via various social networking sites.

= Features =

* Upto 30 services supported
* The widget can display upto 5 links at a time, though this number can be extended easily (see other notes).
* Output format for the links can be one of these five:
	* 32px images (default) 
	* 24px images 
	* 16px images
	* text links with image
	* text links.
* Other widget options include
	* Widget title
	* Option to select an alignment for images
	* Option to open the links in new window
* Multiple instances of the widget can be had at the same time
* Uses WP_Widget class

= Supported subscription services and social sites =

* Blogger Blog
* Delicious
* Diaspora *(new in 0.5.6)*
* Digg
* deviantART
* Facebook
* Feedburner Email Subscription
* Feedburner Feed
* Flickr
* FriendFeed
* Google Profile
* Google Buzz
* Google Plus
* identi.ca
* Last.fm
* LinkedIn
* Myspace
* Picasa Web Albums
* Pinterest *(new in 0.5.6)*
* Podcast
* Posterous
* reddit
* RSS Feed
* RSS Feed for Posts
* RSS Feed for Comments
* Slashdot
* SoundCloud
* StumbleUpon
* Technorati
* Tumblr
* Twitter
* Vimeo
* WordPress.com Blog
* XING
* YouTube

= Credits =

The plugin uses [Vector Social Media Icons](http://icondock.com/free/vector-social-media-icons) from IconDock.

Diaspora icons by [CreativeKaizen](http://creativekaizen.deviantart.com/art/Diaspora-Icon-Set-257241499).

The new red Google Plus icons by [Abhash Bikram Thapa](http://pixelpinch.com/2011/11/new-red-google-plus-or-page-vector-icon-pack-for-free/).

== Installation ==

1. Upload `subscribe-connect-follow-widget` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to 'Appearance -> Widgets' menu in WP admin, select the 'Subscribe / Connect / Follow' widget and place it in your sidebar (or any widget holder), and choose your options.

== Frequently Asked Questions ==

= What is the difference between the 'Feedburner Feed' and 'RSS Feed' options? =

There are two differences. 

1. The 'Feedburner Feed' option displays the Feedburner icon while the 'RSS Feed' option displays the standard RSS icon (as do 'RSS Feed for Posts' and 'RSS Feed for Comments' options).

2. For 'Feedburner Feed' you only need to provide the Feedburner feed name in the widget options and the URL for the link will be automatically generated, whereas for 'RSS Feed' you need to provide the full URL. 

The general 'RSS Feed' option (or 'RSS Feed for Posts' / 'RSS Feed for Comments', as applicable) can be used for a Feedburner feed when the standard RSS icon is preferred over the Feedburner icon.

== Screenshots ==

1. Output when the '32px images' output format is selected
2. Output when the 'text links with image' output format is selected
3. Widget options

== Changelog ==
= 0.5.6 (2011-12-29) =
* Pinterest and Diaspora added
* Google+ images updated to the new red icon

= 0.5.5 (2011-08-31) =
* SoundClound added
* Google+ images updated to icondock images
* Styling fixes

= 0.5.4 (2011-07-12) =
* Google+ added
* `height` and `width` attributes added to the `<img>` tags
* Fixes, mainly for notices that crop up in debug mode

= 0.5.3 (2011-06-10) =
* Xing added to the list of networks

= 0.5.2 (2011-03-29) =
* Styling fix, other minor changes

= 0.5.1 (2011-03-25) =
* Fix

= 0.5 (2011-03-21) =
* Public release

== Hacks ==

= Number of output items =

The widget can output upto 5 different links at the same time. However, this number can be easily increased. Just change the value of the variable `$num_items` in `subscribe-connect-follow-widget.php`, line 21 (as in version 0.5) to any number you want.
