<?php
/**
 * nrelate Plugin Status
 *
 * Activation, Deactivation and Upgrade functions
 *
 * @package nrelate
 * @subpackage Functions
 */
 

global $nr_rc_std_options, $nr_rc_ad_options, $nr_rc_layout_options, $nr_rc_old_checkbox_options, $nr_rc_ad_old_checkbox_options;

// Default Options
// ALL options must be listed
$nr_rc_std_options = array(
		"related_version" => NRELATE_RELATED_PLUGIN_VERSION,
		"related_number_of_posts"=> 3,
		"related_bar" => "Low",
		"related_title" => "You may also like -",
		"related_max_age_num" => "10",
		"related_max_age_frame" => "Year(s)",
		"related_loc_top" => "",
		"related_loc_bottom" => "on",
		"related_display_logo" => false,
		"related_reset" => "",
		"related_blogoption" => array(),
		"related_show_post_title" => 'on',
		"related_max_chars_per_line" => 100,
		"related_show_post_excerpt" => "",
		"related_max_chars_post_excerpt" => 25,		
		"related_thumbnail" => "Thumbnails",
		"related_thumbnail_size" => 110,
		"related_default_image" => NULL,
		"related_number_of_posts_ext" => 3,
		"related_where_to_show" => array( "is_single" ),
		"related_nonjs" => 0
	);
$nr_rc_ad_options = array(
		"related_display_ad" => false,
		"related_ad_animation" => "on",
		"related_validate_ad" => NULL,
		"related_number_of_ads" => 1,
		"related_ad_placement" => "Last",
		"related_ad_title" => "More from the Web -"
	);
		
$nr_rc_layout_options = array(		
		"related_thumbnails_style" => "default",
		"related_thumbnails_style_separate" => "default-2col",
		"related_text_style" => "default",
		"related_text_style_separate" => "default-text-2col"
);


/**
 * Backwards compatibility
 * Stores all checkbox options for versions <= 0.46.0
 * This should never have to be changed.
 */ 
$nr_rc_old_checkbox_options = array(
		"related_show_post_title" => "",	// Since 0.46.0 default on
		"related_show_post_excerpt" => "",
		"related_reset" => "",
		"related_loc_top" => "",
		"related_loc_bottom" => "",
		"related_display_logo" => ""
);
$nr_rc_ad_old_checkbox_options = array(
		"related_ad_animation" => "",		// Since 0.46.0 default on
		"related_display_ad" => ""
);



/**
 * Upgrade function
 *
 * @since 0.46.0
 */
add_action('admin_init','nr_rc_upgrade');
function nr_rc_upgrade() {
	$related_settings = get_option('nrelate_related_options');
	$related_ad_settings = get_option('nrelate_related_options_ads');
	$related_layout_settings = get_option('nrelate_related_options_styles');
	$current_version = $related_settings['related_version'];
	
	// If settings exist and we're running on old version (or version doesn't exist), then this is an upgrade
	if ( ( !empty( $related_settings ) ) && ( $current_version < NRELATE_RELATED_PLUGIN_VERSION ) )  {
	
		nrelate_system_check(); // run system check
		
		global $nr_rc_std_options, $nr_rc_ad_options, $nr_rc_layout_options, $nr_rc_old_checkbox_options, $nr_rc_ad_old_checkbox_options;
			
			// move custom field option from related settings to admin settings: v.0.42.2
			nrelate_upgrade_option('nrelate_related_options', 'related_custom_field', 'nrelate_admin_options', 'admin_custom_field');

			// move ad code field option from related settings to admin settings: v0.42.6
			nrelate_upgrade_option('nrelate_related_options', 'related_validate_ad', 'nrelate_admin_options', 'admin_validate_ad');
			
			// move all ad settings code from related settings to advertising settings: v0.50.0
			nrelate_upgrade_option('nrelate_related_options', 'related_display_ad', 'nrelate_related_options_ads', 'related_display_ad');
			nrelate_upgrade_option('nrelate_related_options', 'related_number_of_ads', 'nrelate_related_options_ads', 'related_number_of_ads');
			nrelate_upgrade_option('nrelate_related_options', 'related_ad_placement', 'nrelate_related_options_ads', 'related_ad_placement');
			nrelate_upgrade_option('nrelate_related_options', 'related_ad_animation', 'nrelate_related_options_ads', 'related_ad_animation');

			// re-get the latest since we just made changes
			$related_settings = get_option('nrelate_related_options');
			$related_ad_settings = get_option('nrelate_related_options_ads');
			$related_layout_settings = get_option('nrelate_related_options_styles'); 
			
			// Sanitize settings for versions <= 0.46.0
			if ( $current_version <= '0.46.0' ) {
				
				// User is upgrading from version < 0.46.0
				if ( $current_version < '0.46.0' ) {
					// Apply 0.46.0 defaults before running standard upgrade
					$nr_rc_old_checkbox_options["related_show_post_title"] = 'on';
					$nr_rc_ad_old_checkbox_options["related_ad_animation"] = 'on';
				}
				
				$related_settings = wp_parse_args( $related_settings, $nr_rc_old_checkbox_options );
				$related_ad_settings = wp_parse_args( $related_ad_settings, $nr_rc_ad_old_checkbox_options );
			}

			// Update new options if they don't exist
			$related_settings = wp_parse_args( $related_settings, $nr_rc_std_options );
			$related_ad_settings = wp_parse_args( $related_ad_settings, $nr_rc_ad_options );
			$related_layout_settings = wp_parse_args( $related_layout_settings, $nr_rc_layout_options );
			
			/**
			* Backwards compatibility
			* Transforms related_blogoption setting from On|Off to 
			* an array of link category ids
			*
			* @since 0.49.0
			*/
			if (isset($related_settings['related_blogoption']) && !is_array($related_settings['related_blogoption'])) 
			{
				if ( $related_settings['related_blogoption'] == 'On' ) {
					$taxonomy = 'link_category';
					$tax = get_taxonomy($taxonomy);
					$link_categories = (array) get_terms($taxonomy, array('get' => 'all'));
					foreach ( $link_categories as $category ) {
						if ( $category->name == 'Blogroll' ) {
							$related_settings['related_blogoption'] = array($category->term_id);
							break;
						}
					}
				} else {
					$related_settings['related_blogoption'] = array();
				}
			}
			
			// now update again
			update_option('nrelate_related_options', $related_settings);
			update_option('nrelate_related_options_ads', $related_ad_settings);
			update_option('nrelate_related_options_styles', $related_layout_settings);
						
			// Update version number in DB
			$related_settings = get_option('nrelate_related_options');
			$related_settings['related_version'] = NRELATE_RELATED_PLUGIN_VERSION;
			update_option('nrelate_related_options', $related_settings);
			
			// Ping nrelate servers about the upgrade
			$body=array(
				'DOMAIN'=>NRELATE_BLOG_ROOT,
				'VERSION'=>NRELATE_RELATED_PLUGIN_VERSION,
				'KEY'=>get_option('nrelate_key'),
				'PLUGIN'=>"related"
			);
			$url = 'http://api.nrelate.com/common_wp/'.NRELATE_LATEST_ADMIN_VERSION.'/versionupdate.php';

			$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
			
			// Calculate plugin file path
			$dir = substr( realpath(dirname(__FILE__) . '/..'), strlen(WP_PLUGIN_DIR) );
			$file = key( get_plugins( $dir ) );
			$plugin_file = substr($dir, 1) . '/' . $file;
			// Update the WP database with the new version number and additional info about this plugin
			nrelate_products("related",NRELATE_RELATED_PLUGIN_VERSION,NRELATE_RELATED_ADMIN_VERSION,1, $plugin_file);
	}
}


  
 /**
 * Define default options for settings
 *
 * @since 0.1
 */
 
 
// Add default values to nrelate_related_options in wordpress db
// After conversion, send default values to nrelate server with user's home url and rss url
// UPDATE (v.0.2.2): add nrelate ping host to ping list and enable xml-rpc ping
// UPDATE (v.0.2.2): notify nrelate server when this plugin is activated
// UPDATE (v.0.3): send the plugin version info to nrelate server
function nr_rc_add_defaults() {
	nrelate_system_check(); // run system check

	// Calculate plugin file path
	$dir = substr( realpath(dirname(__FILE__) . '/..'), strlen(WP_PLUGIN_DIR) );
	$file = key( get_plugins( $dir ) );
	$plugin_file = substr($dir, 1) . '/' . $file;

	nrelate_products("related",NRELATE_RELATED_PLUGIN_VERSION,NRELATE_RELATED_ADMIN_VERSION,1, $plugin_file); // add this product to the nrelate_products array
	
	global $nr_rc_std_options, $nr_rc_ad_options, $nr_rc_layout_options;

	$tmp = get_option('nrelate_related_options');
	// If related_reset value is on or if nrelate_related_options was never created, insert default values
    if(($tmp['related_reset']=='on')||(!is_array($tmp))) {
		
		update_option('nrelate_related_options', $nr_rc_std_options);
		update_option('nrelate_related_options_ads', $nr_rc_ad_options);		
		update_option('nrelate_related_options_styles', $nr_rc_layout_options);

		// Convert some values to send to nrelate server
		$number = 3;
		$r_bar = "Low";
		$r_title = "You may also like -";
		$r_max_age = 10;
		$r_max_frame = "Year(s)";
		$r_display_post_title = true;
		$r_max_char_per_line = 100;
		$r_max_char_post_excerpt = 100;
		$r_display_ad = "";
		$r_display_logo = "";
		$r_related_reset = "";
		$related_blogoption = array();
		$related_thumbnail = "Thumbnails";
		$backfillimage = NULL;
		$number_ext = 3;
		$related_thumbnail_size=110;
		$r_number_of_ads = 0;
		$r_ad_placement = "Last";
		$r_ad_title = "More from the Web -";
		$r_nonjs = 0;
		// Convert max age time frame to minutes
		switch ($r_max_frame)
		{
		case 'Hour(s)':
		  $maxageposts = $r_max_age * 60;
		  break;
		case 'Day(s)':
		  $maxageposts = $r_max_age * 1440;
		  break;
		case 'Week(s)':
		  $maxageposts = $r_max_age * 10080;
		  break;
		case 'Month(s)':
		  $maxageposts = $r_max_age * 44640;
		  break;
		case 'Year(s)':
		  $maxageposts = $r_max_age * 525600;
		  break;
		}

		// Convert ad parameter
		switch ($r_display_ad)
		{
		case true:
			$ad = 1;
			break;
		default:
			$ad = 0;
		}

		// Convert display post title parameter
		switch ($r_display_post_title)
		{
		case 'on':
		  $r_display_post_title = 1;
		  break;
		default:
		 $r_display_post_title = 0;
		}
		
		// Convert logo parameter
		switch ($r_display_logo)
		{
		case 'on':
		  $logo = 1;
		  break;
		default:
		 $logo = 0;
		}

		// Convert blogroll option parameter
		if ( is_array($related_blogoption) && count($related_blogoption) > 0 ) {
			$blogroll = 1;
		} else {
			$blogroll = 0;
		}

		// Convert thumbnail option parameter
		switch ($related_thumbnail)
		{
		case 'Thumbnails':
			$thumb = 1;
			break;
		default:
			$thumb = 0;
		}

		// Get the wordpress root url and the rss url
		$bloglist = nrelate_get_blogroll();
		// Write the parameters to be sent
		
		$r_show_post_title = isset($r_show_post_title) ? $r_show_post_title : null;
		$r_show_post_excerpt = isset($r_show_post_excerpt) ? $r_show_post_excerpt : null;
		$backfill = isset($backfill) ? $backfill : null;
		
		$body=array(
			'DOMAIN'=>NRELATE_BLOG_ROOT,
			'VERSION'=>NRELATE_RELATED_PLUGIN_VERSION,
			'KEY'=>get_option('nrelate_key'),
			'NUM'=>$number,
			'NUMEXT'=>$number_ext,
			'R_BAR'=>$r_bar,
			'HDR'=>$r_title,
			'BLOGOPT'=>$blogroll,
			'BLOGLI'=>$bloglist,
			'MAXPOST'=>$maxageposts,
			'SHOWPOSTTITLE'=>$r_show_post_title,
			'MAXCHAR'=>$r_max_char_per_line,
			'SHOWEXCERPT'=>$r_show_post_excerpt,
			'MAXCHAREXCERPT'=>$r_max_char_post_excerpt,
			'ADOPT'=>$ad,
			'THUMB'=>$thumb,
			'LOGO'=>$logo,
			'IMAGEURL'=>$backfill,
			'THUMBSIZE'=>$related_thumbnail_size,
			'ADNUM'=>$r_number_of_ads,
			'ADPLACE'=>$r_ad_placement,
			'ADTITLE'=>$r_ad_title,
			'NONJS'=>$r_nonjs
		);
		$url = 'http://api.nrelate.com/rcw_wp/'.NRELATE_RELATED_PLUGIN_VERSION.'/processWPrelatedAll.php';
		
		$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
	}

	// RSS mode is sent again just incase if the user already had nrelate_related_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}

	$rssurl = get_bloginfo('rss2_url');

	// Add our ping host to the ping list
	$current_ping_sites = get_option('ping_sites');
	$pingexist = strpos($current_ping_sites, "http://api.nrelate.com/rpcpinghost/");
	if($pingexist == false){
	$pinglist = <<<EOD
$current_ping_sites
http://api.nrelate.com/rpcpinghost/
EOD;
	update_option('ping_sites',$pinglist);
	}
	// Enable xmlrpc for the user
	update_option('enable_xmlrpc',1);


	//Set up a unique nrelate key, for secure feed access
	$key = get_option( 'nrelate_key' );
	if ( empty( $key ) ) {
		$key = wp_generate_password( 24, false, false );
		update_option( 'nrelate_key', $key );
	}



	// Send notification to nrelate server of activation and send us rss feed mode information
	$action = "ACTIVATE";
	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ACTION'=>$action,
		'RSSMODE'=>$rss_mode,
		'VERSION'=>NRELATE_RELATED_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_RELATED_ADMIN_VERSION,
		'PLUGIN'=>'related',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php';
	
	$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
}
 
 
// Deactivation hook callback
function nr_rc_deactivate(){
	$nrelate_active=nrelate_products("related",NRELATE_RELATED_PLUGIN_VERSION,NRELATE_RELATED_ADMIN_VERSION,0);
	
	if($nrelate_active==0){
		// Remove our ping link from ping_sites
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
	}
	
	// RSS mode is sent again just incase if the user already had nrelate_related_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}

	$rssurl = get_bloginfo('rss2_url');

	// Send notification to nrelate server of deactivation
	$action = "DEACTIVATE";

	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ACTION'=>$action,
		'RSSMODE'=>$rss_mode,
		'VERSION'=>NRELATE_RELATED_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_RELATED_ADMIN_VERSION,
		'PLUGIN'=>'related',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php';
	
	$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
}

// Uninstallation hook callback
function nr_rc_uninstall(){
	// Delete nrelate related options from user's wordpress db
	delete_option('nrelate_related_options');
	delete_option('nrelate_related_options_ads');
	delete_option('nrelate_related_options_styles');
	
	$nrelate_active=nrelate_products("related",NRELATE_RELATED_PLUGIN_VERSION,NRELATE_RELATED_ADMIN_VERSION,-1);
	
	if ($nrelate_active<0){
		// This occurs if the user is deleting all of nrelate's products
		
		// Remove our ping link from ping_sites
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace(array("\nhttp://api.nrelate.com/rpcpinghost/","http://api.nrelate.com/rpcpinghost/"), "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
		
		// Delete nrelate admin options from users wordpress db
		delete_option('nrelate_products');
		delete_option('nrelate_admin_msg');
		delete_option('nrelate_admin_options');
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
	}
	
	// RSS mode is sent again just incase if the user already had nrelate_popular_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}
	
	$rssurl = get_bloginfo('rss2_url');
	
	// Send notification to nrelate server of uninstallation
	$action = "UNINSTALL";
	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ACTION'=>$action,
		'RSSMODE'=>$rss_mode,
		'VERSION'=>NRELATE_RELATED_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_RELATED_ADMIN_VERSION,
		'PLUGIN'=>'related',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_RELATED_ADMIN_VERSION.'/wordpressnotify_activation.php';
	
	$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
}

?>