=== Leaflet Maps Marker ===
Contributors:      harmr
Plugin Name:       Leaflet Maps Marker
Plugin URI:        http://www.mapsmarker.com
Tags:              map, maps, Leaflet, OpenStreetMap, geoJSON, json, jsonp, OSM, travelblog, opendata, open data, opengov, open government, ogdwien, google maps, googlemaps, gmaps, WMTS, geoRSS, location, geo, geocoding, geolocation, travel, mapnick, osmarender, cloudmade, mapquest, geotag, geocaching, gpx, OpenLayers, mapping, bikemap, coordinates, geocode, geocoding, geotagging, latitude, longitude, position, route, tracks, google maps, google earth, gmaps, ar, augmented-reality, wikitude, wms, web map service, geocache, geocaching, qr, qr code, fullscreen, marker, layer, karte, blogmap, geocms, geographic, routes, tracks, directions, navigation, routing, location plan, YOURS, yournavigation, ORS, openrouteservice
Author URI:        http://www.harm.co.at
Author:            Robert Harm (with special support from Sindre Wimberger)
Donate link:       http://www.mapsmarker.com/donations
Requires at least: 3.0 
Tested up to:      3.4-alpha-19904
Stable tag:        1.9

Pin, organize & show your favorite places through OpenStreetMap/WMTS, Google Maps/Earth (KML), GeoJSON, GeoRSS or Augmented-Reality browsers

== Description ==

= Plugin's Official Site =
http://mapsmarker.com

* [Demo](http://www.mapsmarker.com/demo/) - [FAQ](http://www.mapsmarker.com/faq/) - [Docs](http://www.mapsmarker.com/docs/) - [Support](http://mapsmarker.com/support/) - [Github](https://github.com/robertharm/Leaflet-Maps-Marker) - [Donations](http://mapsmarker.com/donations) - [Twitter](http://twitter.com/mapsmarker) - [Facebook](http://facebook.com/mapsmarker)

= Main features = 

Leaflet Maps Marker allows you to

* pin your favorites places with markers,
* use integrated address search (Google Places API) for quickly finding your places,
* choose from up to 700 custom free map icons from [Maps Icons Collection](http://mapicons.nicolasmollet.com),
* add popup description text or images for each marker,
* choose individual basemap, size and zoom level for each marker/layer map,
* organize your markers in layers and
* show them thanks to the [Leaflet library from Cloudmade](http://leaflet.cloudmade.com/)
* by adding a shortcode (e.g. mapsmarker marker="1")] to posts or pages
* through OSM/OpenStreetMap, MapQuest, Cloudmade, [OGD Vienna Maps](http://data.wien.gv.at)
* or any custom WMTS-map

to the visitors of your website.

= Additional features =

* show directions for your locations using Google Maps, yournavigation.org or openrouteservice.org
* configure up to 10 WMS servers to display additional information from external geodata providers (like the European Environment Agency) on your maps
* organize markers from different layers in multi-layer-maps
* export your markers as KML file for displaying in Google Earth or Google Maps
* export your markers as GeoJSON file for embedding in external websites or apps
* export your markers as GeoRSS for embedding in external websites or apps
* export your markers as ARML for displaying in the augmented-reality browser from Wikitude
* export your markers as csv-file
* configure up to 3 basemaps from Cloudmade with custom styles
* TinyMCE button for easily searching and adding maps on post/pages edit screen
* display a list of markers below layer maps
* show standalone maps in fullscreen mode
* support for microformat geo-markup to make your maps machine-readable
* support for custom marker timestamps for more precise KML animations
* create QR code images for standalone maps in fullscreen mode
* automatically add meta-tags with location information to maps
* automatically add microformat geo-markup to maps
* option to set Wordpress roles (administrator, editor, author, contributor) which are allowed to add/edit/delete markers and layers
* option to add marker directly to posts or pages without saving them to your database
* audit log for changes on markers & layers (saving first created by/on and last updated by/on info)
* search within your marker list
* mass actions for markers (assignment to layers, deletions)
* option to reset plugin settings to defaults
* option to change the default shortcode '[mapsmarker...]'
* dynamic preview of maps in backend (no need to reload)
* WordPress Admin Bar integration to quickly access plugins features (can be disabled)
* global stats for marker/layer count on mapsmarker.com (can be disabled)
* "OGD Vienna selector": if a place within boundaries of Vienna/Austria is chosen, OGD Vienna basemaps are automatically selected
* integrated [donation links](http://www.mapsmarker.com/donations) to show your support for this plugin :-)

= Technical details =

* Wordpress Multisite compatibility
* full UTF8-support for cyrillic, chinese or other alphabets on marker/layername and marker popup text
* support for other languages through .po/.mo-files (please see http://mapsmarker.com/languages for details if you want to contribute a new translation)
* GeoJSON feeds for every marker and layer with [JSONP support](http://www.mapsmarker.com/geojson)
* use of Wordpress settings API for storing options
* TinyMCE editor on backend for editing popuptext
* version check for minimum Wordpress (3.0) and PHP (5.2) requirements
* use of prepared SQL statements to prevent SQL injections
* use of Wordpress nounces on forms to prevent attacks and input mistakes
* use of custom function names and enque plugin scripts/css only on plugin pages to prevent conflicts with other plugins
* update functions implemented for smooth updates of the plugin
* uninstall function to completely remove the plugin and its data (also on WordPress Multisite installations)

Please let me know which feature you think is missing by adding your ideas at [http://www.mapsmarker.com/ideas](http://www.mapsmarker.com/ideas)

= Available translations =

* English (en_US)
* German (de_DE)
* Japanese (ja) thanks to [Shu Higashi](http://twitter.com/higa4)
* Spanish (es_ES) thanks to David Ramirez, [http://www.hiperterminal.com](http://www.hiperterminal.com)
* Dutch (nl_NL) thanks to Marijke Metz, [http://www.mergenmetz.nl](http://www.mergenmetz.nl)
* French (fr_FR) thanks to Vincèn Pujol, [http://www.skivr.com](http://www.skivr.com)

For more information on translations of the plugin and how to contribute a new translation, please visit [http://www.mapsmarker.com/languages](http://www.mapsmarker.com/languages).

= Leaflet Maps Marker Needs Your Support =

It is hard to continue development and support for this plugin without contributions from users like you. If you enjoy using Leaflet Maps Marker - particularly within a commercial context - please consider [__making a donation__](http://www.mapsmarker.com/donations). Your donation will help keeping the plugin free for everyone and allow me to spend more time on developing, maintaining and support. I´d be happy to accept your donation! Thanks! [Robert Harm](http://www.harm.co.at)

== Installation ==

Note: plugin requires at least PHP 5.2 and Wordpress 3.0!

1. Upload leaflet-maps-marker folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Optional: change default settings (you are getting redirected to plugin settings page automatically on first activation)

After installation you will find a 'Leaflet Maps Marker' menu in your WordPress admin panel and in WordPress Admin Bar.
For basic usage and tutorials, you can also have a look at [http://www.mapsmarker.com/docs](http://www.mapsmarker.com/docs "Docs").

== Frequently Asked Questions ==

Do you have questions or issues with Leaflet Maps Marker? Please use these support channels appropriately:

1. [FAQ](www.mapsmarker.com/faq/)
2. [Docs](http://www.mapsmarker.com/docs/)
3. [Ideas (for feature requests)](www.mapsmarker.com/ideas/)
4. [Wordpress Support Forum](http://wordpress.org/tags/leaflet-maps-marker?forum_id=10) (free community support)
5. [WP Questions](http://wpquestions.com/affiliates/register/name/robertharm) (paid community support)
6. [WordPress HelpCenter](http://wphelpcenter.com/) (paid professional support)

[More info on support](http://mapsmarker.com/support/)

== Screenshots ==

For demo maps please visit [http://www.mapsmarker.com/demo](http://www.mapsmarker.com/demo).

1. Frontend: marker map (with open popup and image, basemap: OGD Vienna satellite, overlay: OGD Vienna addresses, controlbox: expanded)
2. Frontend: layer map (5 marker, different icons, basemap: OpenStreetMap, controlbox: collapsed)
3. Frontend: map with WMS layer enabled and additional marker
4. Frontend: layer map in Google Earth (via KML export)
5. Frontend: layer map in Google Maps (via KML export)
6. Frontend: showing marker in Wikitude (via Augmented-Reality API)
7. Backend: add/edit marker-screen - allows you to fully customize the marker map (used basemap & overlays, map size, zoom, controlbox status, marker icon, popup-text and behaviour etc).
8. Backend: add/edit layer - allows you to fully customize the layer map (used basemap & overlays, set center, map size, zoom, controlbox status etc).
9. Backend: markerlist - for easy administration of all your markers
10. Backend: layerlist - for easy administration of all your layers
11. Backend: plugin settings page allows you to easily set all necessary settings & restore the defaults if you messed something up
12. Backend: csv-export of all markers - just copy and paste into your favorite spreadsheet application for use in other applications
13. Backend: tools section - allows mass-actions more markers (assignements, deletions)

== Other Notes ==

= Licence =

Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you can thank me and leave a small donation for the time I've spent writing and supporting this plugin. Please see [http://www.mapsmarker.com/donations](http://www.mapsmarker.com/donations) for details.
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License included with this plugin for more details. 

= Licenses for used libraries, services and images =

* Leaflet - Copyright (c) 2010-2011, CloudMade, Vladimir Agafonkin [http://leaflet.cloudmade.com](http://leaflet.cloudmade.com)
* OpenStreetMap - The Free Wiki World Map: [OpenStreetMap License](http://wiki.openstreetmap.org/wiki/OpenStreetMap_License) 
* Map Icons Collection by Nicolas Mollet - [http://mapicons.nicolasmollet.com](http://mapicons.nicolasmollet.com)
* Datasource OGD Vienna maps: Stadt Wien, Creative Commons Attribution (by) [http://data.wien.gv.at](http://data.wien.gv.at)
* Jquery TimePicker, by Trent Richardson, [http://trentrichardson.com/examples/timepicker/](http://trentrichardson.com/examples/timepicker/), licence: GPL
* Adress autocompletion powered by [Google Places API](http://code.google.com/intl/de-AT/apis/maps/documentation/places/autocomplete.html)
* Map center icon [Joseph Wain](http://glyphish.com/) - Creative Commons Attribution (by)
* Plus, json & csv-export icon by [Yusuke Kamiyamane](http://www.pinvoke.com/) - Creative Commons Attribution (by)
* Question Mark Icon by [RandomJabber](http://www.randomjabber.com/)
= Credits & special thanks to =
* Sindre Wimberger ([http://www.sindre.at](http://www.sindre.at)) for help with bugfixing & geo-consulting
* Susanne Mandl ([http://www.greenflamingomedia.com](http://www.greenflamingomedia.com)) for plugin logo
* Wordpress-Settings-API-Class by Aliso the geek ([http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/](http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/))
* [Hind](http://www.nanodesu.ru) who originally release a basic [Leaflet plugin](https://wordpress.org/extend/plugins/leaflet/) which I used partly as a basis for Leaflet Maps Marker plugin

For more information on translations of the plugin and how to contribute a new translation, please visit [http://www.mapsmarker.com/languages](http://www.mapsmarker.com/languages).

== Upgrade Notice ==
= v1.9 =
added tinymce-button for inserting maps, removed OSM osmarender basemap

= v1.8 =
added timestamp support for more precise KML animations and option to set default icon, Dutch translation, bugfixes

= v1.7 =
added multi-layer-maps, Wikitude enhancements, added Spanish translation

= v1.6 =
update leaflet to 0.3.1 stable, support for Cloudmade maps with styles, bugfix for Wikitude API

= v1.5.1 =
important bugfixes for multisite installations fixing layer map and API bugs

= v1.5 =
added option to display a list of markers below layer maps, add GeoRSS feed to head, bugfixes

= v1.4.3 =
added WMS support for KML files, bugfix for defect routing link attached to popup-text

= v1.4.2 =
fix for bug causing custom marker icons not to show up on certain hosts

= v1.4.1 =
important bugfix for installations where markers and layers could not be saved to database

= v1.4 =
added support for routing providers and more mass-actions for markers - see http://www.mapsmarker.com/v1.4 for more details

= v1.3 =
added marker mass actions and browser/template compatibility bugfixes - see http://www.mapsmarker.com/v1.3 for more details

= 1.2.2 =
Fix for custom marker icons not showing on certain hosting providers - see http://www.mapsmarker.com/v1.2.2 for more details

= 1.2.1 =
Important bugfixes - see http://www.mapsmarker.com/v1.2.1 for more details

= 1.2 =
Important bugfixes and new feature: GeoRSS-Support - see http://www.mapsmarker.com/v1.2 for more details

= 1.1 =
Added new features and bugfixes - see http://www.mapsmarker.com/v1.1 for more details

= 1.0 =
Initial release - see http://www.mapsmarker.com/v1.0 for more details

== Changelog ==
= v1.9 - 05.03.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.9)
* NEW: added TinyMCE-button for easily searching and inserting maps on post/pages-edit screen
* NEW: added French translation thanks to Vincèn Pujol, [http://www.skivr.com](http://www.skivr.com)
* UPDATED Dutch translation thanks to [Marijke](http://www.mergenmetz.nl)
* UPDATED Japanes translations thanks to [Shu Higashi](http://twitter.com/higa4)
* REMOVED support for OSM Osmarender basemaps (service has been discontinued)

= v1.8 - 29.02.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.8)
* NEW: added option to add a timestamp for each marker for more precise KML animations
* NEW: added option to change the default marker icon for new marker maps
* NEW: option to configure output of names for KML (show, hide, put in front of popup-text)
* NEW: added Dutch translation thanks to [Marijke](http://www.mergenmetz.nl)
* OPTIMIZED: reduced load for GeoJSON feeds up to 75% (full list of attributes can be shown by adding &full=yes to URL)
* CHANGED: updated columns for CSV export file (custom overlay & WMS status, kml timestamp)
* CHANGED: KML links are now opened in the same window (removed target="_blank")
* BUGFIX: UTC offset calculations for KML timestamp was wrong if UTC was < 0
* BUGFIX: markers are not clickable anymore if there is no popup text 
* BUGFIX: styles for each marker icon in KML output are now unique (SELECT DISTINCT...)
* BUGFIX: output of multiple markers as KML did not work (leaflet-kml.php?marker/layer=1,2,3)
* BUGFIX: output of multiple markers as GeoRSS did not work (leaflet-georss.php?marker/layer=1,2,3)
* BUGFIX: output of multiple markers as ARML did not work (leaflet-wikitude.php?marker/layer=1,2,3)
* BUGFIX: if single layer was changed into multi layer map, list of markers was still displayed below map
* BUGFIX: button "add to layer" did not work on new layers

= v1.7 - 22.02.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.7)
* NEW: added multi-layer support allowing you to combine markers from different layer maps
* NEW: Wikitude World Browser now displays custom marker icons instead of standard icon from settings
* NEW: option to set the maximum number of markers you want to display in the list below layer maps
* NEW: Spanish translation thanks to David Ramírez, [http://www.hiperterminal.com](http://www.hiperterminal.com)
* OPTIMIZED: added with & height attributes to custom marker-image-tags on marker edit page to speed up page load time
* CHANGED: default font color in popups to black due to incompabilities with several themes

= v1.6 - 14.02.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.6)
* NEW: added support for Cloudmade maps with styles as basemaps
* UPDATE from leaflet 0.3 beta to 0.3.1 stable [changelog](https://github.com/CloudMade/Leaflet/blob/master/CHANGELOG.md)
* BUGFIX: markers did not show up in Wikitude World Browser due to a bug with different provider name
* BUGFIX: lat/lon values for layer and marker maps were rounded on some installations
* CHANGED: added updated Japanese translation (thanks to Shu Higashi, @higa4)
* CHANGED: added updated German translation

= v1.5.1 - 12.02.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.5.1)
* BUGFIX: layer maps and API links did not work on multisite installations
* BUGFIX: legend link for WMS layer did not work
* BUGFIX: links in panel had a border with some templates
* BUGFIX: removed double slashes from LEAFLET_PLUGIN_URL-links
* BUGFIX: uninstall didnt remove marker-icon-directory on some installations
* BUGFIX: admin pages for map/layer edit screens broken on WordPress 3.0 installations
* OPTIMIZED: javascript variable definitions for wms layers and custom overlays get added to sourcecode only when they are active on the current map

= v1.5 - 09.02.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.5)
* NEW: added option to display a list of markers below layer maps (enabled for new layer maps, disabled for existing layer maps)
* NEW: included option to add GeoRSS feed for all markers to &lt;head&gt; to allow users subscribing to your markers easily
* NEW: add mass actions for layer maps
* CHANGED: database structure for boolean values from tinyint(4) to tinyint(1)
* BUGFIX: overlay status for layer maps wasnt displayed in backend preview

= v1.4.3 - 29.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.4.3)
* NEW: added WMS support for KML-files via networklink
* BUGFIX: routing link attached to popup text did not work
* BUGFIX: missing KML schema declaration causing KML file not to work with scribblemaps.com

= v1.4.2 - 25.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.4.2)
* BUGFIX: custom marker icons not showing up on maps on certain hosts (using directory separators different to / ) 
* BUGFIX: css styling for <label>-tag in controlbox got overriden by some templates

= v1.4.1 - 24.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.4.1)
* BUGFIX: markers & layers could not be added on some hosting providers (changed updatedby & updatedon column on both tables to NULL instead of NOT NULL)
* CHANGED: added updated Japanese translation (thanks to Shu Higashi, @higa4)

= v1.4 - 23.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.4)
* NEW: added support for routing service from Google Maps
* NEW: added support for routing service from yournavigation.org
* NEW: added support for routing service from openrouteservice.org
* NEW: mass-actions for changing default values for existing markers (map size, icon, panel status, zoom, basemap...)
* CHANGED: panel status can now also be selected as column for marker/layer listing page
* CHANGED: controlbox status column for markers/layers list view now displays text instead of 0/1/2
* BUGFIX: method for adding markers/layers as some users reported that new markers/layers were not saved to database
* BUGFIX: method for plugin active-check as some users reported that API links did not work
* BUGFIX: marker/layer name in fullscreen panel did not support UTF8-characters
* BUGFIX: text width in tinymce editor was not the same as in popup text
* BUGFIX: several German translation text strings
* BUGFIX: markers added directly with shortcode caused error on frontend

= v1.3 - 17.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.3)
* NEW: add mass actions for makers (assign markers to layer, delete markers)
* CHANGED: flattr now embedded as static image as long loadtimes decrease usability because Google Places scripts starts only afterwards
* CHANGED: marker-/layername for panel in backend now gets refreshed dynamically after entering in form field
* CHANGED: geo microformat tags are now also added to maps added directly via shortcode
* OPTIMIZED: div structure and order for maps on frontend
* BUGFIX: map/panel width were not the same due to css inheritance
* BUGFIX: map css partially broken in IE < 9 when viewing backend maps
* BUGFIX: links in maps were underlined on some templates
* BUGFIX: panel API link images had borders on some templates
* BUGFIX: text in layer controlbox was centered on some templates
* REMOVED: global stats for plugin installs, marker/layer edits and deletions
* REMOVED: featured sponsor in admin header
* REMOVED: developers comments from css- and js-files

= v1.2.2 - 14.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.2.2)
* BUGFIX: custom marker icons were not shown on certain hosts due to different wp-upload-directories

= v1.2.1 - 13.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.2.1)
* BUGFIX: plugin installation failed on certain hosting providers due to path/directory issues
* BUGFIX: (interactive) maps do not get display in RSS feeds (which is not possible), so now a static image with a link to the fullscreen standalone map is displayed
* BUGFIX: removed redundant slashes from paths
* BUGFIX: fullscreen maps did not get loaded if WordPress is installed in subdirectory
* BUGFIX: API images in panel did show a border on some templates

= v1.2 - 11.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.2)
* NEW: added [GeoRSS-feeds for marker- and layer maps](http://www.mapsmarker.com/georss) (RSS 2.0 & ATOM 1.0)
* NEW: added microformat geo-markup to maps, to make your maps machine-readable
* CHANGE: Default custom overlay (OGD Vienna Addresses) is not active anymore by default on new markers/layers (but still gets active when an address through search by Google Places is selected)
* CHANGE: added attribution text for default custom overlay (OGD Vienna Addresses) to see if overlay has accidently been activated
* CHANGE: added sanitization for wikitude provider name 
* BUGFIX: plugin conflict with Google Analytics for WordPress resulting in maps not showing up
* BUGFIX: plugin did not work on several hosts as path to wp-load.php for API links could not be constructed
* BUGFIX: reset settings to default values did only reset values from v1.0
* BUGFIX: when default custom overlay for new markers/layers got unchecked, the map in backend did not show up anymore
* BUGFIX: fullscreen standalone maps didnt work in Internet Explorer
* BUGFIX: maps did not show up in Internet Explorer 7 at all
* BUGFIX: attribution box on standalone maps did not show up if windows size is too small
* BUGFIX: slashes were not stripped from marker/layer name on frontend maps
* BUGFIX: quotes were not shown on marker/layer names (note: double quotes are replaced with single quotes automatically due to compatibility reasons)

= v1.1 - 08.01.2012 =
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.1)
* NEW: [show standalone maps in fullscreen mode](http://www.mapsmarker.com/wp-content/plugins/leaflet-maps-marker/leaflet-fullscreen.php?marker=1)
* NEW: [create QR code images for standalone maps in fullscreen mode](https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http://www.mapsmarker.com/wp-content/plugins/leaflet-maps-marker/leaflet-fullscreen.php?marker=1)
* NEW: API links (KML, GeoJSON, Fullscreen, QR Code, Wikitude) now only work if plugin is active
* NEW: German translation
* NEW: Japanese translation thanks to Shu Higashi ([@higa4](http://twitter.com/higa4))
* NEW: option to show/hide WMS layer legend link
* NEW: option to disable global statistics
* CHANGED: added more default marker icons, based on the top 100 icons from the Map Icons Collection
* CHANGED: added attribution text field in settings for custom overlays
* CHANGED: removed settings for Wikitude debug lon/lat -> now marker lat/lon respectively layer center lat/lon are used when Wikitude API links are called without explicit parameters &latitude= and &longitude=
* CHANGED: default setting fields can now be changed by focusing with mouse click
* CHANGED: added icons to API links on backend for better usability
* BUGFIX: dynamic preview of marker/layer panel in backend not working as designed
* BUGFIX: language pot-file didn´t include all text strings for translations
* BUGFIX: active translations made setting tabs unaccessible

= v1.0 - 01.01.2012 = 
* [Blog post with more details about this release](http://www.mapsmarker.com/v1.0)
* NEW: Initial release