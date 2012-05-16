<?php
/*
Plugin Name: WPMU DEV Update Notifications
Plugin URI: http://premium.wpmudev.org/project/update-notifications/
Description: Notifies the Admin or Super Admin of available updates for WPMU DEV plugins and themes, new releases, and special member only offers. Allows auto-upgrades of supported themes/plugins. Required to be installed to use WPMU DEV products.
Author: Aaron Edwards (Incsub)
Version: 2.1.3
Author URI: http://premium.wpmudev.org/
Network: true
WDP ID: 119
*/

/*
Copyright 2007-2011 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class WPMUDEV_Update_Notifications {

	//------------------------------------------------------------------------//
	//---Config---------------------------------------------------------------//
	//------------------------------------------------------------------------//

	var $version = '2.1.3';

	var $server_url = 'http://premium.wpmudev.org/wdp-un.php';

	var $plugin_dir;
	var $plugin_url;
	var $admin_url;

	function WPMUDEV_Update_Notifications() {
		global $wp_version;

		//------------------------------------------------------------------------//
		//---Hook-----------------------------------------------------------------//
		//------------------------------------------------------------------------//
		add_action( 'admin_menu', array( &$this, 'plug_pages' ) );
		add_action( 'network_admin_menu', array( &$this, 'plug_pages' ) ); //for 3.1
		add_action( 'admin_init', array( &$this, 'check' ) );
		add_action( 'admin_print_scripts-update-core.php', array( &$this, 'admin_scripts' ) );
		add_action( 'admin_print_styles-update-core.php', array( &$this, 'admin_styles' ) );

		//refresh local projects
		add_action( 'admin_init', array( &$this, 'schedule_refresh_local_projects' ) );
		add_action( 'update-core.php', array( &$this, 'refresh_local_projects' ) );
		add_action( 'load-plugins.php', array( &$this, 'refresh_local_projects' ) );
		add_action( 'load-update.php', array( &$this, 'refresh_local_projects' ) );
		add_action( 'load-update-core.php', array( &$this, 'refresh_local_projects' ) );
		add_action( 'load-themes.php', array( &$this, 'refresh_local_projects' ) );
		add_action( 'wp_update_plugins', array( &$this, 'refresh_local_projects' ) );
		add_action( 'wp_update_themes', array( &$this, 'refresh_local_projects' ) );

		add_action( 'admin_init', array( &$this, 'filter_plugin_rows' ), 15 ); //make sure it runs after WP's
		add_action( 'site_transient_update_plugins', array( &$this, 'filter_plugin_count' ) );
		add_action( 'site_transient_update_themes', array( &$this, 'filter_theme_count' ) );
		add_action( 'core_upgrade_preamble', array( &$this, 'list_updates' ) );

		//add_filter( 'plugins_api_result', array( &$this, 'filter_plugin_info' ), 10, 3 );  //need to work on this to show correct compatibility

		//localize the plugin
		add_action( 'plugins_loaded', array(&$this, 'localization') );

		//setup proper directories
		if (is_multisite() && defined('WPMU_PLUGIN_URL') && defined('WPMU_PLUGIN_DIR') && file_exists(WPMU_PLUGIN_DIR . '/' . basename(__FILE__))) {
			$this->plugin_dir = WPMU_PLUGIN_DIR . '/wpmudev-updates/';
			$this->plugin_url = WPMU_PLUGIN_URL . '/wpmudev-updates/';
		} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/wpmudev-updates/' . basename(__FILE__))) {
			$this->plugin_dir = WP_PLUGIN_DIR . '/wpmudev-updates/';
			$this->plugin_url = WP_PLUGIN_URL . '/wpmudev-updates/';
		} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . basename(__FILE__))) {
			$this->plugin_dir = WP_PLUGIN_DIR;
			$this->plugin_url = WP_PLUGIN_URL;
		} else {
			wp_die(__('There was an issue determining where WPMU DEV Update Notifications is installed. Please reinstall.', 'wpmudev'));
		}

		//get admin page location
		if ( is_multisite() ) {
			if ( version_compare($wp_version, '3.0.9', '>') )
				$this->admin_url = admin_url('network/update-core.php?page=wpmudev');
			else
				$this->admin_url = admin_url('ms-admin.php?page=wpmudev');
		} else {
			$this->admin_url = admin_url('options-general.php?page=wpmudev');
		}

	}

	//------------------------------------------------------------------------//
	//---Functions------------------------------------------------------------//
	//------------------------------------------------------------------------//

	function localization() {
		// Load up the localization file if we're using WordPress in a different language
		// Place it in this plugin's "languages" folder and name it "wpmudev-[value in wp-config].mo"
		load_plugin_textdomain( 'wpmudev', false, '/wpmudev-updates/languages/' );
	}

	/* Wrapper for backwards compatibility with 3.0
	 *
	 */
	function self_admin_url($path) {
		if ( function_exists('self_admin_url') )
			return self_admin_url($path);
		else
			return admin_url($path);
	}

	function get_id_plugin($plugin_file) {
		return get_file_data( $plugin_file, array('name' => 'Plugin Name', 'id' => 'WDP ID', 'version' => 'Version') );
	}

	function get_projects() {
		$projects = array();

		//----------------------------------------------------------------------------------//
		//plugins directory
		//----------------------------------------------------------------------------------//
		$plugins_root = WP_PLUGIN_DIR;
		if( empty($plugins_root) ) {
			$plugins_root = ABSPATH . 'wp-content/plugins';
		}

		$plugins_dir = @opendir($plugins_root);
		$plugin_files = array();
		if ( $plugins_dir ) {
			while (($file = readdir( $plugins_dir ) ) !== false ) {
				if ( substr($file, 0, 1) == '.' )
					continue;
				if ( is_dir( $plugins_root.'/'.$file ) ) {
					$plugins_subdir = @ opendir( $plugins_root.'/'.$file );
					if ( $plugins_subdir ) {
						while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
							if ( substr($subfile, 0, 1) == '.' )
								continue;
							if ( substr($subfile, -4) == '.php' )
								$plugin_files[] = "$file/$subfile";
						}
					}
				} else {
					if ( substr($file, -4) == '.php' )
						$plugin_files[] = $file;
				}
			}
		}
		@closedir( $plugins_dir );
		@closedir( $plugins_subdir );

		if ( $plugins_dir && !empty($plugin_files) ) {
			foreach ( $plugin_files as $plugin_file ) {
				if ( is_readable( "$plugins_root/$plugin_file" ) ) {

					unset($data);
					$data = $this->get_id_plugin( "$plugins_root/$plugin_file" );

					if ( $data['id'] ) {
						$projects[$data['id']]['type'] = 'plugin';
						$projects[$data['id']]['version'] = $data['version'];
						$projects[$data['id']]['filename'] = $plugin_file;
					}
				}
			}
		}

		//----------------------------------------------------------------------------------//
		// mu-plugins directory
		//----------------------------------------------------------------------------------//
		$mu_plugins_root = WPMU_PLUGIN_DIR;
		if( empty($mu_plugins_root) ) {
			$mu_plugins_root = ABSPATH . 'wp-content/mu-plugins';
		}

		if ( $mu_plugins_dir = @opendir($mu_plugins_root) ) {
			while (($file = readdir( $mu_plugins_dir ) ) !== false ) {
				if ( substr($file, -4) == '.php' ) {
					if ( is_readable( "$mu_plugins_root/$file" ) ) {

						unset($data);
						$data = $this->get_id_plugin( "$mu_plugins_root/$file" );

						if ( $data['id'] ) {
							$projects[$data['id']]['type'] = 'mu-plugin';
							$projects[$data['id']]['version'] = $data['version'];
							$projects[$data['id']]['filename'] = $file;
						}
					}
				}
			}
		}
		@closedir( $mu_plugins_dir );

		//----------------------------------------------------------------------------------//
		// wp-content directory
		//----------------------------------------------------------------------------------//
		$content_plugins_root = WP_CONTENT_DIR;
		if( empty($content_plugins_root) ) {
			$content_plugins_root = ABSPATH . 'wp-content';
		}

		$content_plugins_dir = @opendir($content_plugins_root);
		$content_plugin_files = array();
		if ( $content_plugins_dir ) {
			while (($file = readdir( $content_plugins_dir ) ) !== false ) {
				if ( substr($file, 0, 1) == '.' )
					continue;
				if ( !is_dir( $content_plugins_root.'/'.$file ) ) {
					if ( substr($file, -4) == '.php' )
						$content_plugin_files[] = $file;
				}
			}
		}
		@closedir( $content_plugins_dir );

		if ( $content_plugins_dir && !empty($content_plugin_files) ) {
			foreach ( $content_plugin_files as $content_plugin_file ) {
				if ( is_readable( "$content_plugins_root/$content_plugin_file" ) ) {
					unset($data);
					$data = $this->get_id_plugin( "$content_plugins_root/$content_plugin_file" );

					if ( $data['id'] ) {
						$projects[$data['id']]['type'] = 'drop-in';
						$projects[$data['id']]['version'] = $data['version'];
						$projects[$data['id']]['filename'] = $mu_plugin_file;
					}
				}
			}
		}
		//----------------------------------------------------------------------------------//

		//themes directory
		//----------------------------------------------------------------------------------//
		$themes_root = WP_CONTENT_DIR . '/themes';
		if( empty($themes_root) ) {
			$themes_root = ABSPATH . 'wp-content/themes';
		}

		$themes_dir = @opendir($themes_root);
		$themes_files = array();
		if ( $themes_dir ) {
			while (($file = readdir( $themes_dir ) ) !== false ) {
				if ( substr($file, 0, 1) == '.' )
					continue;
				if ( is_dir( $themes_root.'/'.$file ) ) {
					$themes_subdir = @ opendir( $themes_root.'/'.$file );
					if ( $themes_subdir ) {
						while (($subfile = readdir( $themes_subdir ) ) !== false ) {
							if ( substr($subfile, 0, 1) == '.' )
								continue;
							if ( substr($subfile, -4) == '.css' )
								$themes_files[] = "$file/$subfile";
						}
					}
				} else {
					if ( substr($file, -4) == '.css' )
						$themes_files[] = $file;
				}
			}
		}
		@closedir( $themes_dir );
		@closedir( $themes_subdir );

		if ( $themes_dir && !empty($themes_files) ) {
			foreach ( $themes_files as $themes_file ) {

				//skip child themes
				if ( strpos( $themes_file, '-child' ) !== false )
					continue;

				if ( is_readable( "$themes_root/$themes_file" ) ) {

					unset($data);
					$data = $this->get_id_plugin( "$themes_root/$themes_file" );

					if ( $data['id'] ) {
						$projects[$data['id']]['type'] = 'theme';
						$projects[$data['id']]['version'] = $data['version'];
						$projects[$data['id']]['filename'] = substr( $themes_file, 0, strpos( $themes_file, '/' ) );
					}
				}
			}
		}

		//----------------------------------------------------------------------------------//

		return $projects;
	}

	function schedule_refresh_local_projects() {

		if ( defined('WP_INSTALLING') )
			return false;

		if ( current_user_can('edit_users') ) {
			$time_ago = time() - get_site_option('wdp_un_local_projects_refreshed');
			if ( $time_ago > 300 ) { //update every 5 minutes
				$this->refresh_local_projects();
			}
		}
	}

	function refresh_local_projects() {

		$data = get_site_option('wdp_un_last_response');
		$now = time();

		if ( is_array( $data ) ) {
			$local_projects = $this->get_projects();
			$current_local_projects = get_site_option('wdp_un_local_projects');

			//check for changes
			$current_local_projects_md5 = md5(serialize($current_local_projects));
			$local_projects_md5 = md5(serialize($local_projects));
			if ( $current_local_projects_md5 != $local_projects_md5 ) {
				//refresh data as installed plugins have changed
				unset( $data );
				$data = $this->process($local_projects);
			}

			//save to be able to check for changes later
			update_site_option('wdp_un_local_projects', $local_projects);

			//save timestamp
			update_site_option('wdp_un_local_projects_refreshed', time());

			$remote_projects = isset($data['latest_versions']) ? $data['latest_versions'] : array();

			$this->calculate_upgrades($remote_projects, $local_projects);
		}
	}

	function calculate_upgrades($remote_projects, $local_projects) {

		$updates = array();

		//check for updates
		if ( is_array($remote_projects) ) {
			foreach ( $remote_projects as $id => $remote_project ) {
				if ( is_array($local_projects[$id]) ) {
					//match
					$local_version = $local_projects[$id]['version'];

					//handle wp autoupgrades
					if ($remote_project['autoupdate'] == '2') {
						if ($local_projects[$id]['type'] == 'plugin') {
							$update_plugins = get_site_transient('update_plugins');
							$remote_version = $update_plugins->response[$local_projects[$id]['filename']]->new_version;
						} else if ($local_projects[$id]['type'] == 'theme') {
							$update_themes = get_site_transient('update_themes');
							$remote_version = $update_themes->response[$local_projects[$id]['filename']]['new_version'];
						} else {
							$remote_version = $remote_project['version'];
						}
					} else {
						$remote_version = $remote_project['version'];
					}

					if ( version_compare($remote_version, $local_version, '>') ) {
						//add to array
						$updates[$id] = $local_projects[$id];
						$updates[$id]['url'] = $remote_project['url'];
						$updates[$id]['instructions_url'] = $remote_project['instructions_url'];
						$updates[$id]['support_url'] = $remote_project['support_url'];
						$updates[$id]['name'] = $remote_project['name'];
						$updates[$id]['version'] = $local_version;
						$updates[$id]['new_version'] = $remote_version;
						$updates[$id]['changelog'] = $remote_project['changelog'];
						$updates[$id]['autoupdate'] = (($local_projects[$id]['type'] == 'plugin' || $local_projects[$id]['type'] == 'theme') && get_site_option('wpmudev_apikey')) ? $remote_project['autoupdate'] : 0; //only allow autoupdates if installed in plugins
					}
				}
			}

			//record results
			update_site_option('wdp_un_updates_available', $updates);
		} else {
			return false;
		}
		
		return $updates;
	}

	function process($local_projects = false) {
		global $wpdb, $current_site;

		if ( defined( 'WP_INSTALLING' ) )
			return false;

		if ( !is_array($local_projects) )
			$local_projects = $this->get_projects();

		update_site_option('wdp_un_local_projects', $local_projects);

		$api_key = get_site_option('wpmudev_apikey');

		$url = $this->server_url . '?action=check&un-version=' . $this->version . '&domain=' . urlencode(network_site_url()) . '&key=' . urlencode($api_key) . '&p=' . implode('.', array_keys($local_projects));

		$options = array(
			'timeout' => 15,
			'user-agent' => 'UN Client/' . $this->version
		);

		$response = wp_remote_get($url, $options);
		if ( wp_remote_retrieve_response_code($response) == 200 ) {
			$data = $response['body'];
			if ( $data != 'error' ) {
				$data = unserialize($data);

				if ( is_array($data) ) {
					update_site_option('wdp_un_last_response', $data);
					update_site_option('wdp_un_last_run', time());

					//reset hiding permissions in case of membership change
					if ( !$data['membership'] || $data['membership'] == 'free' ) { //free member
						update_site_option('wdp_un_hide_upgrades', 0);
						update_site_option('wdp_un_hide_notices', 0);
						update_site_option('wdp_un_hide_releases', 0);
					} else if ( is_numeric( $data['membership'] ) ) { //single
						update_site_option('wdp_un_hide_notices', 0);
						update_site_option('wdp_un_hide_releases', 0);
					}

					$remote_projects = $data['latest_versions'];

					$this->calculate_upgrades($remote_projects, $local_projects);

					return $data;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function check() {
		if ( current_user_can('edit_users') ) {
			$time_ago = time() - get_site_option('wdp_un_last_run');
			if ( $time_ago > 43200 ) { //12 hour refreshing
				$this->process();
			}
		}
	}

	function filter_plugin_info($res, $action, $args) {
		if ($action == 'plugin_information')
			var_dump($args);

		return $res;
	}

	function filter_plugin_rows() {
		if ( !current_user_can( 'update_plugins' ) )
			return;

		$updates = get_site_option('wdp_un_updates_available');
		if ( is_array($updates) && count($updates) ) {
			foreach ( $updates as $id => $plugin ) {
				if ( $plugin['autoupdate'] != '2' ) {
					if ( $plugin['type'] == 'theme' ) {
						remove_all_actions( 'after_theme_row_' . $plugin['filename'] );
						add_action('after_theme_row_' . $plugin['filename'], array( &$this, 'plugin_row'), 9, 2 );
					} else {
						remove_all_actions( 'after_plugin_row_' . $plugin['filename'] );
						add_action('after_plugin_row_' . $plugin['filename'], array( &$this, 'plugin_row'), 9, 2 );
					}
				}
			}
		}
	}

	function filter_plugin_count( $value ) {

		$updates = get_site_option('wdp_un_updates_available');
		if ( is_array($updates) && count($updates) ) {
			$api_key = get_site_option('wpmudev_apikey');
			foreach ( $updates as $id => $plugin ) {
				if ( $plugin['type'] != 'theme' && $plugin['autoupdate'] != '2' ) {

					//build plugin class
					$object = new stdClass;
					$object->url = $plugin['url'];
					$object->upgrade_notice = $plugin['changelog'];
					$object->new_version = $plugin['new_version'];
					if ($plugin['autoupdate'] == '1')
						$object->package = $this->server_url . "?action=download&key=$api_key&pid=$id";

					//add to class
					$value->response[$plugin['filename']] = $object;
				}
			}
		}

		return $value;
	}

	function filter_theme_count( $value ) {

		$updates = get_site_option('wdp_un_updates_available');
		if ( is_array($updates) && count($updates) ) {
			$api_key = get_site_option('wpmudev_apikey');
			foreach ( $updates as $id => $plugin ) {
				if ( $plugin['type'] == 'theme' && $plugin['autoupdate'] != '2' ) {
					//build theme listing
					$value->response[$plugin['filename']]['url'] = $plugin['url'];
					$value->response[$plugin['filename']]['new_version'] = $plugin['new_version'];

					if ($plugin['autoupdate'] == '1')
						$value->response[$plugin['filename']]['package'] = $this->server_url . "?action=download&key=$api_key&pid=$id";
				}
			}
		}

		return $value;
	}

	function plugin_row( $file, $plugin_data ) {

		//get new version and update url
		$updates = get_site_option('wdp_un_updates_available');
		if ( is_array($updates) && count($updates) ) {
			foreach ( $updates as $id => $plugin ) {
				if ($plugin['filename'] == $file) {
					$project_id = $id;
					$version = $plugin['new_version'];
					$plugin_url = $plugin['url'];
					$autoupdate = $plugin['autoupdate'];
					$filename = $plugin['filename'];
					$type = $plugin['type'];
					break;
				}
			}
		} else {
			return false;
		}

		$plugins_allowedtags = array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array());
		$plugin_name = wp_kses( $plugin_data['Name'], $plugins_allowedtags );

		$update_url = $this->server_url . '?action=details&id=' . $project_id . '&TB_iframe=true&width=640&height=700';

		if ( $type == 'plugin' )
			$autoupdate_url = wp_nonce_url( $this->self_admin_url('update.php?action=upgrade-plugin&plugin=') . $filename, 'upgrade-plugin_' . $filename);
		else if ( $type == 'theme' )
			$autoupdate_url = wp_nonce_url( $this->self_admin_url('update.php?action=upgrade-theme&theme=') . $filename, 'upgrade-theme_' . $filename);

		if ( current_user_can('update_plugins') ) {
			echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message">';
			if ($autoupdate)
				printf( __('There is a new version of %1$s available on WPMU DEV. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a> or <a href="%5$s">automatically update</a>.', 'wpmudev'), $plugin_name, esc_url($update_url), esc_attr($plugin_name), $version, esc_url($autoupdate_url) );
			else
				printf( __('There is a new version of %1$s available on WPMU DEV. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a> or <a href="%5$s" target="_blank" title="Download update from WPMU DEV">download update</a>.', 'wpmudev'), $plugin_name, esc_url($update_url), esc_attr($plugin_name), $version, esc_url($plugin_url) );
		}
		echo '</div></td></tr>';
	}

	function list_updates() {

		$updates = get_site_option('wdp_un_updates_available');
		if ( !is_array( $updates ) || ( is_array( $updates ) && !count( $updates ) ) ) {
			echo '<h3>' . __( 'WPMU DEV Plugins/Themes', 'wpmudev' ) . '</h3>';
			echo '<p>' . __( 'Your plugins/themes from WPMU DEV are all up to date.', 'wpmudev' ) . '</p>';
			return;
		}
?>
    <h3><?php _e( 'WPMU DEV Plugins/Themes', 'wpmudev' ); ?></h3>
    <p><?php _e( 'The following plugins/themes from WPMU DEV have new versions available.', 'wpmudev' ); ?></p>
    <table class="widefat" cellspacing="0" id="update-plugins-table">
	<thead>
	<tr>
		<th scope="col" class="manage-column"><label><?php _e('Name', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Links', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Installed Version', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Latest Version', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Actions', 'wpmudev'); ?></label></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope="col" class="manage-column"><label><?php _e('Name', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Links', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Installed Version', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Latest Version', 'wpmudev'); ?></label></th>
		<th scope="col" class="manage-column"><label><?php _e('Actions', 'wpmudev'); ?></label></th>
	</tr>
	</tfoot>
	<tbody class="plugins">
<?php
		foreach ( (array) $updates as $id => $plugin) {
			$screenshot = "http://premium.wpmudev.org/wp-content/projects/$id/listing-image-thumb.png";

			if ( $plugin['autoupdate'] && $plugin['type'] == 'plugin' )
				$upgrade_button_code = "<a href='" . wp_nonce_url( $this->self_admin_url('update.php?action=upgrade-plugin&plugin=') . $plugin['filename'], 'upgrade-plugin_' . $plugin['filename']) . "' class='button-secondary'>".__('Auto Update', 'wpmudev')."&raquo;</a>";
			else if ( $plugin['autoupdate'] && $plugin['type'] == 'theme' )
				$upgrade_button_code = "<a href='" . wp_nonce_url( $this->self_admin_url('update.php?action=upgrade-theme&theme=') . $plugin['filename'], 'upgrade-theme_' . $plugin['filename']) . "' class='button-secondary'>".__('Auto Update', 'wpmudev')."&raquo;</a>";
			else
				$upgrade_button_code = "<a href='" . $plugin['url'] . "' class='button-secondary' target='_blank'>".__('Download Update', 'wpmudev')."&raquo;</a>";

			echo "
				<tr class='active'>
				<td class='plugin-title'><a target='_blank' href='{$plugin['url']}' title='" . __('More Information &raquo;', 'wpmudev') . "'><img src='$screenshot' width='80' height='60' style='float:left; padding: 5px' /><strong>{$plugin['name']}</strong></a>" .  sprintf(__('You have version %1$s installed. Update to %2$s.'), $plugin['version'], $plugin['new_version']) . "</td>
				<td style='vertical-align:middle;width:200px;'><a target='_blank' href='{$plugin['instructions_url']}'>" . __('Installation & Use Instructions &raquo;', 'wpmudev') . "</a><br /><a target='_blank' href='{$plugin['support_url']}'>" . __('Get Support &raquo;', 'wpmudev') . "</a></td>
				<td style='vertical-align:middle'><strong>{$plugin['version']}</strong></td>
				<td style='vertical-align:middle'><strong><a href='{$this->server_url}?action=details&id={$id}&TB_iframe=true&width=640&height=700' class='thickbox' title='" . sprintf( __('View version %s details', 'wpmudev'), $plugin['new_version'] ) . "'>{$plugin['new_version']}</a></strong></td>
				<td style='vertical-align:middle'>$upgrade_button_code</td>
				</tr>";
		}
?>
	</tbody>
    </table>
    <br />
<?php
	}

	function plug_pages() {
		global $wpdb, $wp_roles, $current_user, $wp_version;

		$updates = get_site_option('wdp_un_updates_available');
		$count = ( is_array($updates) ) ? count( $updates ) : 0;
		if ( $count > 0 ) {
			$count_output = ' <span class="updates-menu"><span class="update-plugins"><span class="updates-count count-' . $count . '">' . $count . '</span></span></span>';
		} else {
			$count_output = ' <span class="updates-menu"></span>';
		}

		if ( is_multisite() ) {
			if ( is_super_admin() ) {
				if ( version_compare($wp_version, '3.0.9', '>') )
					$page = add_submenu_page('update-core.php', __('WPMU DEV Updates', 'wpmudev'), 'WPMU DEV' . $count_output, 10, 'wpmudev', array( &$this, 'page_output') );
				else
					$page = add_submenu_page('ms-admin.php', __('WPMU DEV Updates', 'wpmudev'), 'WPMU DEV' . $count_output, 10, 'wpmudev', array( &$this, 'page_output') );
			}
		} else {
			$page = add_submenu_page('options-general.php', __('WPMU DEV Updates', 'wpmudev'), 'WPMU DEV' . $count_output, 'manage_options', 'wpmudev', array( &$this, 'page_output') );
		}
		add_action( 'admin_print_scripts-' . $page, array(&$this, 'admin_scripts') );
		add_action( 'admin_print_styles-' . $page, array(&$this, 'admin_styles') );
	}

	function admin_scripts() {
		wp_enqueue_script('thickbox');
	}

	function admin_styles() {
		wp_enqueue_style('thickbox');
	}

	function handle_dismiss() {
		if ( isset( $_REQUEST['dismiss'] ) ) {
			$dismiss = array( 'id' => intval($_REQUEST['dismiss']), 'expire' => strtotime("+1 month") );
			update_site_option( 'wdp_un_dismissed', $dismiss );
			?><div class="updated fade"><p><?php _e('Notice dismissed.', 'wpmudev'); ?></p></div><?php
		}

		if ( isset( $_REQUEST['dismiss-release'] ) ) {
			update_site_option( 'wdp_un_dismissed_release', intval($_REQUEST['dismiss-release']) );
			?><div class="updated fade"><p><?php _e('Notice dismissed.', 'wpmudev'); ?></p></div><?php
		}

		if ( isset( $_REQUEST['upgrade-dismiss'] ) ) {
			update_site_option( 'wdp_un_dismissed_upgrade', time() + 86400 );
			?><div class="updated fade"><p><?php _e('Notice dismissed.', 'wpmudev'); ?></p></div><?php
		}
	}

	//------------------------------------------------------------------------//
	//---Page Output Functions------------------------------------------------//
	//------------------------------------------------------------------------//

	function page_output() {
		global $wpdb, $current_site;

		if( !current_user_can( 'edit_users' ) ) {
			echo "<p>Nice Try...</p>";  //If accessed properly, this message doesn't appear.
			return;
		}

		$this->handle_dismiss();

		//handle forced update
		if ($_GET['action'] == 'update') {

			$result = $this->process();
			if ( is_array($result) ) {
				?><div class="updated fade"><p><?php _e('Update data successfully refreshed from WPMU DEV.', 'wpmudev'); ?></p></div><?php
			} else {
				?><div class="error fade"><p><?php _e('There was a problem refreshing data from WPMU DEV.', 'wpmudev'); ?></p></div><?php
			}

		} else {
			$this->refresh_local_projects();
		}

		if ( isset($_POST['wpmudev_apikey']) ) {
			update_site_option('wpmudev_apikey', $_POST['wpmudev_apikey']);
			$result = $this->process();
			if ( is_array($result) && !$result['membership'] ) {
				update_site_option('wpmudev_apikey', '');
				?><div class="error fade"><p><?php _e('Your API Key was invalid. Please try again.', 'wpmudev'); ?></p></div><?php
			}

			if ( $result['membership'] == 'full' ) { //free member
				update_site_option('wdp_un_hide_upgrades', $_POST['hide_upgrades']);
				update_site_option('wdp_un_hide_notices', $_POST['hide_notices']);
				update_site_option('wdp_un_hide_releases', $_POST['hide_releases']);
			} else if ( is_numeric( $result['membership'] ) ) { //single
				update_site_option('wdp_un_hide_upgrades', $_POST['hide_upgrades']);
			}

			?><div class="updated fade"><p><?php _e('Settings Saved!', 'wpmudev'); ?></p></div><?php
		}

?>
      <div class="wrap">
      <div class="icon32"><img src="<?php echo $this->plugin_url ?>/wpmudev-logo.png" /><br /></div>
      <h2><?php _e('WPMU DEV Updates', 'wpmudev') ?></h2>

<?php
		$data = get_site_option('wdp_un_last_response');
		$last_run = get_site_option('wdp_un_last_run');
?>
      <p><?php echo $data['text_page_head']; ?></p>
      <h3><?php _e('Recently Released Plugins', 'wpmudev') ?></h3>
<?php
		echo "
			<table cellpadding='3' cellspacing='3' width='100%' class='widefat'>
			<thead><tr>
			<th scope='col'>".__('Name', 'wpmudev')."</th>
			<th scope='col'>".__('Description', 'wpmudev')."</th>
			</tr></thead>
			<tbody id='the-list'>
			";
		$latest_plugins = array();
		if ( is_array( $data ) ) {
			$latest_plugins = $data['latest_plugins'];
		}
		if (count($latest_plugins) > 0){
			$class = ('alternate' == $class) ? '' : 'alternate';
			foreach ($latest_plugins as $latest_plugin){
				//=========================================================//
				echo "<tr class='" . $class . "'>";
				echo "<td valign='top'><strong><a target='_blank' href='" . $latest_plugin['url'] . "'>" . stripslashes($latest_plugin['title']) . "</a></strong></td>";
				echo "<td valign='top'>" . stripslashes($latest_plugin['short_description']) . "</td>";
				echo "</tr>";
				$class = ('alternate' == $class) ? '' : 'alternate';
				//=========================================================//
			}
		}
?>
      </tbody></table>
      <h3><?php _e('Recently Released Themes', 'wpmudev') ?></h3>
<?php
		echo "
			<table cellpadding='3' cellspacing='3' width='100%' class='widefat'>
			<thead><tr>
			<th scope='col'>".__('Name', 'wpmudev')."</th>
			<th scope='col'>".__('Description', 'wpmudev')."</th>
			</tr></thead>
			<tbody id='the-list'>
			";
		$latest_themes = array();
		if ( is_array( $data ) ) {
			$latest_themes = $data['latest_themes'];
		}
		if (count($latest_themes) > 0){
			$class = ('alternate' == $class) ? '' : 'alternate';
			foreach ($latest_themes as $latest_theme){
				//=========================================================//
				echo "<tr class='" . $class . "'>";
				echo "<td valign='top'><strong><a target='_blank' href='" . $latest_theme['url'] . "'>" . stripslashes($latest_theme['title']) . "</a></strong></td>";
				echo "<td valign='top'>" . stripslashes($latest_theme['short_description']) . "</td>";
				echo "</tr>";
				$class = ('alternate' == $class) ? '' : 'alternate';
				//=========================================================//
			}
		}
?>
      </tbody></table>
      <h3><?php _e('Installed WPMU DEV Plugins/Themes', 'wpmudev') ?></h3>
<?php
		echo "
			<table cellpadding='3' cellspacing='3' width='100%' class='widefat'>
			<thead><tr>
			<th scope='col'>".__('Name', 'wpmudev')."</th>
			<th scope='col'>".__('Links', 'wpmudev')."</th>
			<th scope='col'>".__('Installed Version', 'wpmudev')."</th>
			<th scope='col'>".__('Latest Version', 'wpmudev')."</th>
			<th scope='col'>".__('Actions', 'wpmudev')."</th>
			</tr></thead>
			<tbody id='the-list'>
			";
		$projects = array();
		if ( is_array( $data ) ) {
			$remote_projects = isset($data['latest_versions']) ? $data['latest_versions'] : array();
			$local_projects = get_site_option('wdp_un_local_projects');
			if ( is_array( $local_projects ) ) {
				foreach ( $remote_projects as $remote_id => $remote_project ) {
					$projects[$remote_id]['name'] = $remote_project['name'];
					$projects[$remote_id]['description'] = $remote_project['short_description'];
					$projects[$remote_id]['url'] = $remote_project['url'];
					$projects[$remote_id]['instructions_url'] = $remote_project['instructions_url'];
					$projects[$remote_id]['support_url'] = $remote_project['support_url'];
					$projects[$remote_id]['autoupdate'] = (($local_projects[$remote_id]['type'] == 'plugin' || $local_projects[$remote_id]['type'] == 'theme') && get_site_option('wpmudev_apikey')) ? $remote_project['autoupdate'] : 0;

					//handle wp autoupgrades
					if ($projects[$remote_id]['autoupdate'] == '2') {
						if ($local_projects[$remote_id]['type'] == 'plugin') {
							$update_plugins = get_site_transient('update_plugins');
							if ($update_plugins->response[$local_projects[$remote_id]['filename']]->new_version)
								$projects[$remote_id]['remote_version'] = $update_plugins->response[$local_projects[$remote_id]['filename']]->new_version;
							else
								$projects[$remote_id]['remote_version'] = $local_projects[$remote_id]['version'];
						} else if ($local_projects[$remote_id]['type'] == 'theme') {
							$update_themes = get_site_transient('update_themes');
							if ($update_themes->response[$local_projects[$remote_id]['filename']]['new_version'])
								$projects[$remote_id]['remote_version'] = $update_themes->response[$local_projects[$remote_id]['filename']]['new_version'];
							else
								$projects[$remote_id]['remote_version'] = $local_projects[$remote_id]['version'];
						} else {
							$projects[$remote_id]['remote_version'] = $remote_project['version'];
						}
					} else {
						$projects[$remote_id]['remote_version'] = $remote_project['version'];
					}

					$projects[$remote_id]['local_version'] = $local_projects[$remote_id]['version'];
					$projects[$remote_id]['filename'] = $local_projects[$remote_id]['filename'];
					$projects[$remote_id]['type'] = $local_projects[$remote_id]['type'];
				}
			}
		}
		if (count($projects) > 0) {
			$class = ('alternate' == $class) ? '' : 'alternate';
			foreach ($projects as $project_id => $project) {
				$local_version = $project['local_version'];
				$remote_version = $project['remote_version'];

				$check = (version_compare($remote_version, $local_version, '>')) ? "style='background-color:#FFEBE8;'" : '';

				if ( $project['autoupdate'] && $project['type'] == 'plugin' )
					$upgrade_button_code = "<a href='" . wp_nonce_url( $this->self_admin_url('update.php?action=upgrade-plugin&plugin=') . $project['filename'], 'upgrade-plugin_' . $project['filename']) . "' class='button-secondary'>".__('Auto Update', 'wpmudev')."&raquo;</a>";
				else if ( $project['autoupdate'] && $project['type'] == 'theme' )
					$upgrade_button_code = "<a href='" . wp_nonce_url( $this->self_admin_url('update.php?action=upgrade-theme&theme=') . $project['filename'], 'upgrade-theme_' . $project['filename']) . "' class='button-secondary'>".__('Auto Update', 'wpmudev')."&raquo;</a>";
				else
					$upgrade_button_code = "<a href='" . $project['url'] . "' class='button-secondary' target='_blank'>".__('Download Update', 'wpmudev')."&raquo;</a>";

				$upgrade_button = (version_compare($remote_version, $local_version, '>')) ? $upgrade_button_code : '';

				$screenshot = "http://premium.wpmudev.org/wp-content/projects/$project_id/listing-image-thumb.png";

				//=========================================================//
				echo "<tr class='" . $class . "' " . $check . " >";
				echo "<td style='vertical-align:middle'><img src='$screenshot' width='40' height='30' style='float:left; padding: 5px' /></a><strong><a target='_blank' href='{$project['url']}' title='" . __('More Information &raquo;', 'wpmudev') . "'>{$project['name']}</a></strong><br />{$project['description']}</td>";
				echo "<td style='vertical-align:middle;width:200px;'><a target='_blank' href='{$project['instructions_url']}'>" . __('Installation & Use Instructions &raquo;', 'wpmudev') . "</a><br /><a target='_blank' href='{$project['support_url']}'>" . __('Get Support &raquo;', 'wpmudev') . "</a></td>";
				echo "<td style='vertical-align:middle'><strong>" . $local_version . "</strong></td>";
				echo "<td style='vertical-align:middle'><strong><a href='{$this->server_url}?action=details&id={$project_id}&TB_iframe=true&width=640&height=700' class='thickbox' title='" . sprintf( __('View version %s details', 'wpmudev'), $remote_version ) . "'>{$remote_version}</a></strong></td>";
				echo "<td style='vertical-align:middle'>" . $upgrade_button . "</td>";
				echo "</tr>";
				$class = ('alternate' == $class) ? '' : 'alternate';
				//=========================================================//
			}
		}
?>
      </tbody></table>
      <p><?php _e('Please note that all data is updated every 12 hours.', 'wpmudev') ?> <?php _e('Last updated:', 'wpmudev'); ?> <?php echo get_date_from_gmt(date('Y-m-d H:i:s', $last_run), get_option('date_format') . ' ' . get_option('time_format')); ?> - <a href="<?php echo $this->admin_url; ?>&action=update"><?php _e('Update Now', 'wpmudev'); ?></a></p>
      <p><small>* <?php _e('Latest plugins, themes and installed plugins and themes above only refer to those provided to', 'wpmudev') ?> <a href="http://premium.wpmudev.org/join/"><?php _e('WPMU DEV members'); ?></a> <?php _e('by Incsub - other plugins and themes are not included here.', 'wpmudev'); ?></small></p>

      <h3><?php _e('API Key', 'wpmudev') ?></h3>
      <form method="post" action="<?php echo $this->admin_url; ?>">
      <table class="form-table">
<?php
		$api_key = get_site_option('wpmudev_apikey');
		if ( $api_key && $data['membership'] ) {
			$style = ' style="background-color:#ADFFAA;"';
		} else {
			$style = ' style="background-color:#FF7C7C;"';
		}
?>
	<tr valign="top">
	<th scope="row"><?php _e('WPMU DEV API Key', 'ust') ?>*</th>
	<td><input type="text" id="wpmudev_apikey" name="wpmudev_apikey"<?php echo $style; ?> value="<?php echo $api_key; ?>" size="50" /><input type="submit" name="check_key" value="<?php _e('Check Key &raquo;', 'wpmudev') ?>" />
	<br /><?php _e('Enter your API Key to enable auto-updates and special members-only offers from WPMU DEV. You can <a href="http://premium.wpmudev.org/wp-admin/profile.php?page=subscription">get your free API Key here&raquo;</a>', 'wpmudev') ?></td>
	</tr>
      </table>

      <h3><?php _e('Admin Notices', 'wpmudev') ?></h3>
      <span class="description"><?php _e('Note: Notices are only displayed to site Administrators (Super-Admins in Multisite installs). Full current WPMU DEV members can permanently disable all admin notices, though individual notices can always be dismissed by any admin.', 'wpmudev') ?></span>
      <table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e('Upgrade Notice', 'wpmudev') ?></th>
	<td>
<?php
		$disable = '';
		if ( $data['membership'] != 'full' && !is_numeric($data['membership']) )
			$disable = ' disabled="disabled"';

		$checked = (get_site_option('wdp_un_hide_upgrades')) ? 1 : 0;
?>
	  <label><input value="0"<?php echo $disable; ?> name="hide_upgrades" type="radio"<?php checked($checked, 0) ?> /> <?php _e('Show', 'mp') ?></label><br />
	  <label><input value="1"<?php echo $disable; ?> name="hide_upgrades" type="radio"<?php checked($checked, 1) ?> /> <?php _e('Hide', 'mp') ?></label>
	  <br /><?php _e('Only current WPMU DEV members can hide the upgrade notice', 'wpmudev') ?>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><?php _e('Special Offers', 'wpmudev') ?></th>
	<td>
<?php
		$disable = '';
		if ( $data['membership'] != 'full' )
			$disable = ' disabled="disabled"';

		$checked = (get_site_option('wdp_un_hide_notices')) ? 1 : 0;
?>
	  <label><input value="0"<?php echo $disable; ?> name="hide_notices" type="radio"<?php checked($checked, 0) ?> /> <?php _e('Show', 'mp') ?></label><br />
	  <label><input value="1"<?php echo $disable; ?> name="hide_notices" type="radio"<?php checked($checked, 1) ?> /> <?php _e('Hide', 'mp') ?></label>
	  <br /><?php _e('Only full WPMU DEV members can hide special offers', 'wpmudev') ?>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><?php _e('New Release Announcements', 'wpmudev') ?></th>
	<td>
<?php
		$disable = '';
		if ( $data['membership'] != 'full' )
			$disable = ' disabled="disabled"';

		$checked = (get_site_option('wdp_un_hide_releases')) ? 1 : 0;
?>
	  <label><input value="0"<?php echo $disable; ?> name="hide_releases" type="radio"<?php checked($checked, 0) ?> /> <?php _e('Show', 'mp') ?></label><br />
	  <label><input value="1"<?php echo $disable; ?> name="hide_releases" type="radio"<?php checked($checked, 1) ?> /> <?php _e('Hide', 'mp') ?></label>
	  <br /><?php _e('Only full WPMU DEV members can hide new release announcements', 'wpmudev') ?>
	</td>
	</tr>
      </table>

      <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes', 'wpmudev') ?>" />
      </p>
      </form></div>
<?php
	}

}

$GLOBALS['wpmudev_notifications'] = new WPMUDEV_Update_Notifications();

require dirname(__FILE__) . '/notifications.php';

