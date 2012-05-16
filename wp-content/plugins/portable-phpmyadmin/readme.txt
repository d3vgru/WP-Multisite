=== Portable phpMyAdmin ===
Contributors: butterflymedia, getbutterfly
Tags: phpmyadmin, php, portable, database, mysql, sql, admin
Requires at least: 2.7
Tested up to: 3.3
Stable tag: 1.2.9.3

== Description ==

Portable phpMyAdmin allows a user to access the phpMyAdmin section straight from the Dashboard. If the user doesn't know the MySQL credentials, the plugin extracts them straight from wp-config.php. This plugin requires PHP 5+. Also, MySQL 5+ is recommended.

Check the [official homepage](http://getbutterfly.com/wordpress-plugins/portable-phpmyadmin/ "getButterfly") for feedback and support.

== Installation ==

Upload the Portable phpMyAdmin plugin to your blog, activate it, then go to Tools | Portable PMA to use it.

== Changelog ==

= 1.2.9.3 =
* GENERAL: Removed useless MySQL info, also conflicting with other plugins
* GENERAL: Small tweaks
* IMPROVEMENT: Added a small help section

= 1.2.9.2 =
* GENERAL: Changed author URL address

= 1.2.9.1 =
* GENERAL: Changed author URL address

= 1.2.9 =
* SECURITY: Removed some setup/upgrade files for phpMyAdmin, as they were no longer needed and posed a security threat (thanks Joost de Valk)

= 1.2.8 =
* SECURITY: Removed database info and credentials from the plugin screen (a WordPress administrator is not necessarily a database administrator)
* PERFORMANCE: Improved rendering speed of phpMyAdmin navigation frame
* PERFORMANCE: More steps into the new UI framework

= 1.2.7 =
* PERFORMANCE: Removed IE6 support
* PERFORMANCE: More steps into the new UI framework
* FIX: Fixed a conflict with Server Info plugin
* FIX: Fixed 2 uninitialized variables

= 1.2.6 =
* FIX: Fixed phpMyAdmin path for non-traditional WordPress installations or custom directories
* FIX: Fixed database host for non-localhost MySQL installations
* FIX: Fixed the dreaded "Headers already sent" error
* FIX: Removed some unused code

= 1.2.5 =
* PERFORMANCE: Removed old version of phpMyAdmin
* PERFORMANCE: Removed old, unused functions from the wrapper function

= 1.2.4 =
* FEATURE: Added menu icon
* FIX: Implemented several phpMyAdmin fixes
* FIX: Fixed lots of bugs
* FIX: Fixed phpMyAdmin to work on certain configuration
* FIX: Fixed a deprecated ereg_replace in phpMyAdmin

= 1.2.3 =
* IMPROVEMENT: Removed phpinfo()

= 1.2.2 =
* FIX: Removed several server lines to circumvent the "No input file specified" bug in IIS servers
* FIX: Removed a redundant line to fix any output bufferring errors

= 1.2.1 =
* IMPROVEMENT: General code cleanup
* FIX: Fix phpMyAdmin login issue

= 1.2 =
* FEATURE: Moved the menu from Tools section to its own top-level menu
* FEATURE: Various UI improvements and tweaks
* INFORMATION: Added general server information
* INFORMATION: Added PHP information
* INFORMATION: Added MySQL information

= 1.1 =
* SECURITY: Removed the setup folder as it posed a high security risk
* SECURITY: Implemented several 'security through obscurity' actions
* SECURITY: Changed phpMyAdmin authentication mode for increased security
* PERFORMANCE: Increased performance by removing compatibility checks
* PERFORMANCE: Doubled blobstreaming limits

= 1.0.8 =
* Updated deprecated code for WordPress 3.x
* Updated version and author
* Updated WordPress compatibility
* Modified phpMyAdmin theme for space reusability
* Removed 'darkblue_orange' theme

= 1.0.7 =
* Added database size variable

= 1.0.6a =
* Repack (SVN error)

= 1.0.6 =
* Updated phpMyAdmin to 3.2.4
* Added all languages distributed with phpMyAdmin 3.2.4

= 1.0.5 =
* Updated for WordPress 2.9

= 1.0.4 =
* Fixed several phpMyAdmin cookie configuration bugs
* Fixed a couple of typos
* User interface additions
* Extracted database information from wp-config.php

= 1.0.3 =
* phpMyAdmin now opens in iframe (as initially intended)

= 1.0.2 =
* Added plugin URL to repository

= 1.0.1 =
* Fixed wrong URL being passed to plugin

= 1.0.0 =
* First release
* Based on phpMyAdmin 3.2.3 English
