<?php
/*
Plugin Name: Portable phpMyAdmin
Plugin URI: http://getbutterfly.com/wordpress-plugins/portable-phpmyadmin/
Description: Portable phpMyAdmin allows a user to access the phpMyAdmin section straight from the Dashboard. If the user doesn't know the MySQL credentials, the plugin extracts them straight from wp-config.php. This plugin requires PHP 5+.
Version: 1.2.9.3
Author: Ciprian Popescu
Author URI: http://getbutterfly.com/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Copyright 2009, 2010, 2011 Ciprian Popescu (email: office@butterflymedia.ro)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

phpMyAdmin is licensed under the terms of the GNU General Public License
version 2, as published by the Free Software Foundation.
*/
define('PORTABLE_PHPMYADMIN_VERSION', '1.2.9.3');
define('ALERTLEVEL',8); // Alert level. Database size is shown in red if greater than this value (in MB), else in green.
error_reporting(0);

//
if(!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if(!defined( 'WP_PLUGIN_URL'))
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if(!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if(!defined('WP_PLUGIN_DIR'))
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

define('PMA_PLUGIN_URL', WP_PLUGIN_URL.'/portable-phpmyadmin');
define('PMA_PLUGIN_PATH', WP_PLUGIN_DIR.'/portable-phpmyadmin');
//

include(PMA_PLUGIN_PATH.'/wp-info-mod/mod_general.php');

add_action('admin_menu', 'add_option_page_portable_phpmyadmin');

// Size Categories
function file_size_info($filesize) {
	$bytes = array('KB', 'KB', 'MB', 'GB', 'TB');
	if ($filesize < 1024) $filesize = 1;

	for($i = 0; $filesize > 1024; $i++) $filesize /= 1024;
	$file_size_info['size'] = round($filesize,3);
	$file_size_info['type'] = $bytes[$i];
	return $file_size_info;
} 

// Calculate DB size by adding table size + index size
function db_size() {
	$rows = mysql_query('SHOW table STATUS');
	$dbsize = 0;
	while($row = mysql_fetch_array($rows)) {
		$dbsize += $row['Data_length'] + $row['Index_length'];
	}

	if($dbsize > ALERTLEVEL * 1024 * 1024) {
		$color = '#FF0000';
	}
	else {
		$color = '#0000FF';
	}
	$dbsize = file_size_info($dbsize);
	echo '<span style="color: '.$color.'">{'.$dbsize['size'].'} {'.$dbsize['type'].'}</span>'; 
}

function add_option_page_portable_phpmyadmin() {
	add_menu_page('Portable PMA', 'Portable PMA', 'manage_options', __FILE__, 'option_page_portable_phpmyadmin', PMA_PLUGIN_URL.'/images/icon-16.png');
	add_submenu_page(__FILE__, 'About/Help', 'About/Help', 'manage_options', 'pma_about', 'option_page_portable_pmaabout'); 
}

function option_page_portable_phpmyadmin() {
?>
<div class="wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2>Portable phpMyAdmin</h2>
	<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
		<p><strong>Important:</strong> You should have a backup of your database before modifying any data.</p>
	</div>

	<table class="widefat">
		<thead>
			<tr>
				<th>Variable Name</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Database host</td>
				<td><code><?php echo DB_HOST;?></code></td>
			</tr>
			<tr class="alternate">
				<td>Database size</td>
				<td><code><?php db_size();?></code></td>
			</tr>
			<tr class="alternate">
				<td>Portable phpMyAdmin plugin version</td>
				<td><code><?php echo PORTABLE_PHPMYADMIN_VERSION;?></code></td>
			</tr>
		</tbody>
	</table>
	<br class="clear" />

	<div class="widefat">
		<iframe width="100%" height="800" src="<?php echo PMA_PLUGIN_URL;?>/wp-pma-mod/index.php?pma_username=<?php echo DB_USER;?>&amp;pma_password=<?php echo DB_PASSWORD;?>&amp;db=<?php echo DB_NAME;?>&amp;wphost=<?php echo DB_HOST;?>&amp;wpbs=<?php echo md5(str_shuffle(get_option('blogname')));?>" frameborder="0"></iframe>
	</div>

	<?php get_portable_serverinfo();?>
</div>
<?php
}
function option_page_portable_pmaabout() {?>
	<div class="wrap">
		<div id="icon-plugins" class="icon32"><br /></div>
		<h2>About Portable phpMyAdmin</h2>
		<div class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
			<p><strong>Important:</strong> You should have a backup of your database before modifying any data.</p>
		</div>
		<p><strong>Portable phpMyAdmin</strong> allows a user to access the phpMyAdmin section straight from the Dashboard. If the user doesn't know the MySQL credentials, the plugin extracts them straight from wp-config.php. This plugin requires PHP 5+. Also, MySQL 5+ is recommended.</p>
		<p>Once activated, the plugin extracts MySQL information from the database and displays it on a separate page.</p>
		<p><strong>Remember:</strong> Always have a backup of your database before modifying any data! You should also make your blog inaccessible during database editing by activating the maintenance mode!</p>

		<h3>Help</h3>
		<p>If phpMyAdmin is unable to connect to your database, it means your host is different than <em><strong>localhost</strong></em>, the default name assumed by this plugin. Please edit <code>wp-content\plugins\portable-phpmyadmin\wp-pma-mod\config.php</code> and add your host at line 51, <code>$cfg['Servers'][$i]['host'] = WP_HOST;</code>.</p>

		<p>Check the <a href="http://getbutterfly.com/wordpress-plugins/portable-phpmyadmin/" rel="external">official homepage</a> for feedback and support, or rate it on <a href="http://wordpress.org/extend/plugins/portable-phpmyadmin/" rel="external">WordPress plugin repository.</a></p>
		<p><small>Portable phpMyAdmin is based on phpMyAdmin 2.10.3.0 (2007-07-20) with several fixes and enhancements.</small></p>
	</div>
<?php }?>
