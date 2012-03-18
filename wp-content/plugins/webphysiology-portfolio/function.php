<?php
/**
 * This file contains all functions utilized by portfolio-main.php.  They have been separated to help manage files and will likely be
 * split up further in future relases to help delineate between functions and areas they impact (e.g., Admin, End-User UI).
 *
 * @package WordPress
 * @subpackage webphysiology-portfolio plugin
 * @since webphysiology-portfolio 1.3.1
 */

/*  UPDATES
	
	1.4.1 - * added custom Portfolio Tag taxonomy and added update code to convert any existing post tags to this custom Portfolio tag
			* added Portfolio Tag Cloud widget
			* by default any custom Portfolio tags are included in the standard Tag Cloud widget, but an option to override this behavior is available within the plugin options
			* added ability to create single-webphys-portfolio.php template for use when displaying a single Portfolio record
			* added ability to create archive-webphysiology_portfolio_tag.php template for use in displaying Portfolios associated with a Portfolio tag
			* added ability to change thumbnail cache folder permissions to 0777 to deal with some instances where 0755 default permissions don't work with timthumb.php, resulting in no image being displayed
	1.4.0 - * replaced some path code in place of the plugins_url function
			* added the "webphysiology_portfolio_use_full_path" option to allow for the ability to have images, and some
			  css/js files, specify full pathnames. there seem to be instances where some hosts don't like HTTP:// within
			  the image pathnames and some cases where they require it.  go figure
			* added code to handle image paths needing adjustment in a multisite install
			* updated inclusion of ShrinkTheWeb script to utilize wp_enqueue_script and specifically un-register any
			  instances that were registered as "stw-pagepix", which is how WordPress Portfolio Plugin registers it.
			* added deregister of thickbox resources to try and avoid contention with other plugins, like Auto Thickbox Plus
			* added support for displaying a single portfolio screen
			* added back the ability to preview a single portfolio record
			* added function for gathering plugin options and returning them within an array
			* added PagePeeker service support for thumbnail generation
	1.3.2 - * added support for Options page WEBphysiology social buttons
			* better isolated navigation controls by adding "webphysport_nav_top" and "webphysport_nav_bottom" classes.
			  top and bottom classes will be deprecated in a later release.
			* added embedded portfolio CSS for new webphysport_odd_stripe and webphysport_even_stripe classes.
			  odd and even classes will be deprecated in a later release.
			* enhanced the "has_shortcode" function to utilize a different method to obtain the current page's content as
			  using "the_content()" was causing issues with some theme's page titling
			* added declaration for inclusion of a new IE7 and below stylesheet
	
*/

include_once('file_functions.php');

// if the Ozh Admin Menu plugin is being used, add the JVHM icon to the menu portfolio menu item
function RegisterAdminIcon($hook) {
	if ( $hook == plugin_basename(__FILE__) && function_exists('plugins_url')) {
		return plugins_url('images/jvhm_pinwheel_bullet.png',plugin_basename(__FILE__));
	}
	return $hook;
}

// Manage Portfolio Types taxonomy counts
function portfolio_type_taxonomy_count() {
	
	global $post_ID;
	global $wpdb;
	
	$postid = wp_is_post_revision( $post_ID );
	
	if ( $postid == false ) {
		$postid = $post_ID;
	}
	
	$wpdb->query("	DELETE	FROM $wpdb->term_relationships
				 	WHERE	object_id = '".$postid."'
					AND		EXISTS (
							SELECT	1
							FROM	$wpdb->term_taxonomy stt
							WHERE	stt.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id
							AND		stt.taxonomy = 'portfolio_type')");
	
	//echo $post_ID."<br />";
	$wpdb->query("	INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order)
					SELECT	sp.id 'object_id',
						(	SELECT	ssstt.term_taxonomy_id
							FROM	$wpdb->postmeta spm INNER JOIN
									$wpdb->terms ssst ON spm.meta_value = ssst.slug INNER JOIN
									$wpdb->term_taxonomy ssstt ON ssst.term_id = ssstt.term_id AND ssstt.taxonomy = 'portfolio_type'
							WHERE	spm.meta_key = '_portfolio_type'
							AND		spm.post_id = sp.id) 'term_taxonomy_id',
							0 'term_order'
					FROM	$wpdb->posts sp
					WHERE	sp.id = '".$postid."'
					AND		sp.post_type = 'webphys_portfolio'
					AND		EXISTS (
							SELECT	1
							FROM	sss_postmeta sspm INNER JOIN
									sss_terms sssst ON sspm.meta_value = sssst.slug INNER JOIN
									sss_term_taxonomy sssstt ON sssst.term_id = sssstt.term_id AND sssstt.taxonomy = 'portfolio_type'
							WHERE	sspm.meta_key = '_portfolio_type'
							AND		sspm.post_id = sp.id)
					AND		NOT EXISTS (
							SELECT	1
							FROM	$wpdb->term_relationships str INNER JOIN
									$wpdb->term_taxonomy stt ON str.term_taxonomy_id = stt.term_taxonomy_id AND stt.taxonomy = 'portfolio_type' INNER JOIN
									$wpdb->terms st ON stt.term_id = st.term_id
							WHERE	str.object_id = sp.id)");
	
	// update the Portfolio (Post) counts on the Portfolio Types
	$wpdb->query("	UPDATE	$wpdb->term_taxonomy
					SET		count = (SELECT count(ssp.id) FROM $wpdb->posts ssp INNER JOIN $wpdb->term_relationships str ON ssp.id = str.object_id WHERE ssp.post_type = 'webphys_portfolio' AND ssp.post_status = 'publish' AND str.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
					WHERE	taxonomy = 'portfolio_type'");
	
}

// Define the Portfolio custom post type update messages
function portfolio_updated_messages( $messages ) {
	
	global $post, $post_ID;
	
	$messages['webphys_portfolio'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Portolios updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Portfolio updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Portfolio restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Portfolio published.'),
		7 => __('Portfolio saved.'),
		8 => __('Portfolio submitted.'),
		9 => sprintf( __('Portfolio scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Portfolio draft updated.'),
	);
	
	return $messages;
}

// Define and register the Portfolio custom post type
function portfolio_post_type_init() 
{
	
	$script = plugins_url('images/jvhm_pinwheel_bullet.png', __FILE__);

	$labels = array(
		'name' => __('Portfolio', 'post type general name'),
		'singular_name' => __('Portfolio', 'post type singular name'),
		'add_new' => __('Add Portfolio', 'Portfolio'),
		'add_new_item' => __('Add Portfolio'),
		'edit_item' => __('Edit Portfolio'),
		'new_item' => __('New Portfolio'),
		'view_item' => __('View Portfolio'),
		'search_items' => __('Search Portfolios'),
		'not_found' =>  __('No Portfolios found'),
		'not_found_in_trash' => __('No Portfolios found in Trash'), 
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
//		'publicly_queryable' => false,
//		'show_ui' => true,
//		'show_in_nav_menus' => true,
//		'has_archive' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array("slug" => "webphys_portfolio"), //false, // since we aren't pushing to single pages we don't need a re-write rule or permastructure.
							// if we were it would look something like 'rewrite' => array("slug" => "portfolio")
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 5,
		'menu_icon' => $script,
		'supports' => array('title','editor','author'),
		'register_meta_box_cb' => 'add_portfolio_metaboxes',
		'taxonomies' => array('portfolio_type','webphys_portfolio_tag')
	); 
	
	register_post_type('webphys_portfolio',$args);
	flush_rewrite_rules();
}

// add links to the plugin list for the Portfolio plugin such that a user can get to Settings and other links from that screen
function RegisterPluginLinks($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$links[] = '<a href="http://webphysiology.com/redir/webphysiology-portfolio/support/">' . __('Support','sitemap') . '</a>';
		$links[] = '<a href="http://webphysiology.com/redir/webphysiology-portfolio/donate/">' . __('Donate','sitemap') . '</a>';
	}
	return $links;
}

function portfolio_admin_css() {
	$file = plugins_url('css/portfolio_admin.css', __FILE__);
	wp_register_style('portfolio_admin_css', $file);
	wp_enqueue_style('portfolio_admin_css');
}

function portfolio_post_css() {
	
	global $post;
	
	// don't include the Portfolio Post CSS file if we aren't on the Portfolio Post edit screen
	if (strtolower($post->post_type) == "webphys_portfolio") {
		$file = plugins_url('css/portfolio_post.css', __FILE__);
		wp_register_style('portfolio_post_css', $file);
		wp_enqueue_style('portfolio_post_css');
	}
}

// Add Portfolio Options menu item
function portolio_admin_menu() {
	
	$page = add_submenu_page('edit.php?post_type=webphys_portfolio', 'WEBphysiology Portfolio Options', 'Options', 'manage_options', 'webphysiology-portfolio', 'portfolio_plugin_page' );
	
	/* Using registered $page handle to hook css for admin page load */
	add_action('admin_print_styles-' . $page, 'portfolio_admin_css');
	add_action('admin_print_styles-post.php', 'portfolio_post_css');

	remove_submenu_page( 'edit.php?post_type=webphys_portfolio', 'edit-tags.php?taxonomy=post_tag&amp;post_type=webphys_portfolio' );

}



//*************************************************//
//******* PORTFOLIO EDIT SCREEN CODE START  *******//
//*************************************************//

// Define the Save Metabox Data routine
function save_portfolio_meta($post_id, $post) {
	
	// if the save was initiated by an autosave or a quick edit, exit out as the Portfolio fields being updated here may get over written or hang the save
	if (!isset($_POST['autosave_quickedit_check'])) {
		return $post->ID;
	}
	
	// verify this call is the result of a POST
	if ( empty($_POST) ) {
		return $post->ID;
	}
 
	// if the user isn't saving a portfolio
	if (strtolower($_POST['post_type']) != "webphys_portfolio") {
		return $post->ID;
	}
	
	// verify this came from our screen and with proper authorization, because save_post can be triggered at other times
	if ( !check_admin_referer('portfolio_edit','portfoliometanonce') ) {
		return $post->ID;
	}
 
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID )) {
		return $post->ID;
	}
	
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
 	
	$portfolio_meta['_portfolio_type'] = $_POST['_portfolio_type'];
	$portfolio_meta['_createdate'] = $_POST['_createdate'];
	$portfolio_meta['_clientname'] = $_POST['_clientname'];
	$portfolio_meta['_technical_details'] = $_POST['_technical_details'];
	$portfolio_meta['_siteurl'] = $_POST['_siteurl'];
	$portfolio_meta['_imageurl'] = $_POST['_imageurl'];
	if (!empty($_POST['_sortorder'])) {
		$portfolio_meta['_sortorder'] = $_POST['_sortorder'];
	} else {
		$portfolio_meta['_sortorder'] = -1*($post->ID);
	}
	
 
	// Add values of $portfolio_meta as custom fields
 
	foreach ($portfolio_meta as $key => $value) { // Cycle through the $portfolio_meta array!
		if ( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if (get_post_meta($post->ID, $key, false)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if (!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
 
}

//*************************************************//
//******** PORTFOLIO EDIT SCREEN CODE END  ********//
//*************************************************//


// Define the Portfolio edit form custom fields
function webphys_portfolio_edit_init() {
	
	global $post;
 
	// Noncename needed to verify where the data originated
	wp_nonce_field( 'portfolio_edit', 'portfoliometanonce' );
	
	// Gather any existing custom data for the Portfolio
	$portfolio_type = get_post_meta($post->ID, '_portfolio_type', true);
	$datecreate = get_post_meta($post->ID, '_createdate', true);
	$client = get_post_meta($post->ID, '_clientname', true);
	$technical_details = get_post_meta($post->ID, '_technical_details', true);
	$siteurl = get_post_meta($post->ID, '_siteurl', true);
	$imageurl = get_post_meta($post->ID, '_imageurl', true);
	$sortorder = get_post_meta($post->ID, '_sortorder', true);
	if ($sortorder=="") $sortorder = "-" . $post->ID;
 
	// Gather the list of Portfolio Types
	$portfolio_type_list = get_terms('portfolio_type', 'hide_empty=0'); 
 
 	// Build out the form fields
	
	$detail_labels = get_option( 'webphysiology_portfolio_display_labels' );
	$type = $detail_labels["Type"];
	$created = $detail_labels["Created"];
	$clientname = $detail_labels["Client"];
	$siteURL = $detail_labels["SiteURL"];
	$tech = $detail_labels["Tech"];
	$stwcomments = '';
	$sortcomments = "";
	
	if ( get_option('webphysiology_portfolio_thumbnail_generator') == 'stw' ) {
		$stwcomments = '</span><br /><span class="attribute_instructions"><strong>note</strong>: entering an image path will override the use of ShrinkTheWeb.com. Review "<a href="http://webphysiology.com/plugins/webphysiology-portfolio-plugin/#options" title="WEBphysiology Portfolio Documentation" target="_blank">Use ShrinkTheWeb.com</a>" option</span><br /><span class="attribute_instructions">documentation for more details.';
	} elseif ( get_option('webphysiology_portfolio_thumbnail_generator') == 'pp' ) {
		$stwcomments = '</span><br /><span class="attribute_instructions"><strong>note</strong>: entering an image path will override the use of PagePeeker.com. Review "<a href="http://webphysiology.com/plugins/webphysiology-portfolio-plugin/#options" title="WEBphysiology Portfolio Documentation" target="_blank">Use PagePeeker.com</a>" option</span><br /><span class="attribute_instructions">documentation for more details.';
	}
	if ( strtolower( get_option('webphysiology_portfolio_sort_numerically')) != 'true' ) {
		$sortcomments = '<br /><span class="attribute_instructions"><strong>note</strong>: you are sorting alphanumerically</span>';
	}
	
	// hide the Portfolio edit screen Preview button
//asterisk 12/13/11	echo "\n" . '<style type="text/css" id="webphysiology_portfolio_hide_preview_css">' . "\n" . '	#preview-action { display: none; } ' . "\n" . '</style>' . "\n";

	echo '<p><label for="_portfolio_type">Select Portfolio Type (' . $type . '): </label> ';

    echo '<select name="_portfolio_type" id="_portfolio_type">';
        echo '<!-- Display portfolio types as options -->';
            echo '<option class="portfolio_type_option" value=""';
            if ( !count($portfolio_type_list) || is_wp_error($portfolio_type) || empty($portfolio_type) ) echo 'selected>None</option>';
        foreach ($portfolio_type_list as $portfolio_item) {
            if ($portfolio_item->slug == $portfolio_type) {
                echo '<option class="portfolio_type_option" value="' . $portfolio_item->slug . '" selected>' . $portfolio_item->name . '</option>\n'; 
			} else {
                echo '<option class="portfolio_type_option" value="' . $portfolio_item->slug . '">' . $portfolio_item->name . '</option>\n';
			}
        }
    echo '</select></p>';
    echo '<p class="tallbottom"><label for="_createdate">Enter Date Created (' . $created . '): </label>';
	echo '<input type="text" id="_createdate" name="_createdate" value="' . $datecreate . '" class="code shortbottom" />';
	echo '<br /><span class="attribute_instructions">note: this is freeform text and can take on whatever form you want (e.g., YYYY or MM/YYYY ...)</span></p>';
    echo '<p><label for="_clientname">Enter Client Name (' . $clientname . '): </label>';
	echo '<input type="text" id="_clientname" name="_clientname" value="' . $client . '" class="widefat" /></p>';
    echo '<p><label for="_technical_details">Enter Technical Details (' . $tech . '): </label>';
	echo '<input type="text" id="_technical_details" name="_technical_details" value="' . $technical_details . '" class="widefat" /></p>';
    echo '<p><label for="_siteurl">Enter Portfolio Web Page URL (' . $siteURL . '): </label>';
	echo '<input type="text" id="_siteurl" name="_siteurl" value="' . $siteurl . '" class="widefat" /></p>';
    echo '<p><label for="_imageurl">Enter Portfolio Image URL: </label>';
	echo '<input id="upload_portfolio_image_button" class="upload_image_button" type="button" value="Upload Image" /><br />';
	echo '<input type="text" id="_imageurl" name="_imageurl" value="' . $imageurl . '" class="widefat shortbottom" /><br />';
	echo '<span class="attribute_instructions">Enter the URL for the portfolio image. Clicking "Insert into Post" from &lt;Upload Image&gt; will paste the inserted image\'s URL.' . $stwcomments . '</span></p>';
    echo '<p><label for="_sortorder">Enter Site Sort Order: </label>';
	echo '<input type="text" id="_sortorder" name="_sortorder" value="' . $sortorder . '" class="code" />';
	echo '<input type="hidden" name="autosave_quickedit_check" value="true" />'. $sortcomments . '</p>';

}

/* Add the Portfolio custom fields (called as an argument of the custom post type registration) */
function add_portfolio_metaboxes() {
	add_meta_box('webphys_portfolio_edit_init', 'Portfolio Details', 'webphys_portfolio_edit_init', 'webphys_portfolio', 'normal', 'high');
}

// define the Portfolio Plugin settings admin page
function portfolio_plugin_page() {
	
//	session_start();
	$_SESSION['cache'] = plugin_dir_path(__FILE__);
	
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
		wp_die( __('Your user account does not have sufficient privileges to manage Portfolio options.') );
    }
	
	echo '<div class="wrap portfolio-admin">';
    echo '	<div class="company_logo">';
    echo '        <a class="webphys_logo" href="http://WEBphysiology.com/">&nbsp;</a>';
    echo '        <div id="icon-plugins" class="icon32"></div><h2>Portfolio Options</h2>';
    echo '    </div>';
    echo '    <div class="postbox-container">';
    echo '        <div class="metabox-holder">';
    echo '            <div class="meta-box-sortables">';

    // variables for the field and option names
	$hidden_field_name = 'webphys_submit_hidden';
	$display_portfolio_title = 'webphysiology_portfolio_display_portfolio_title'; // default true
	$display_portfolio_desc = 'webphysiology_portfolio_display_portfolio_desc'; // default true
	$display_desc_first = 'webphysiology_portfolio_display_desc_first'; //default true
	$display_portfolio_type = 'webphysiology_portfolio_display_portfolio_type'; // default true
	$display_createdate = 'webphysiology_portfolio_display_createdate'; // default true
	$display_clientname = 'webphysiology_portfolio_display_clientname'; // default true
	$display_siteurl = 'webphysiology_portfolio_display_siteurl'; // default true
	$display_tech = 'webphysiology_portfolio_display_tech'; // default true
	$missing_img_url = 'webphysiology_portfolio_missing_image_url'; // default images/empty_window.png
	$allowed_sites = 'webphysiology_portfolio_allowed_image_sites'; // default none
	$use_stw = 'webphysiology_portfolio_use_stw'; // default false
	$use_stw_pro = 'webphysiology_portfolio_use_stw_pro'; // default false
	$stw_ak = 'webphysiology_portfolio_stw_ak'; // default ""
	$stw_sk = 'webphysiology_portfolio_stw_sk'; // default ""
	$pp_account = 'webphysiology_portfolio_pagepeeker_account'; // default ""
	$img_click_behavior = 'webphysiology_portfolio_image_click_behavior'; // default litebox
	$thumbnail_generator = 'webphysiology_portfolio_thumbnail_generator'; // default tim ... other options stw & pp
	$target = 'webphysiology_portfolio_anchor_click_behavior'; // default False
	$check_openlitebox = '';
	$check_nav2page = '';
	$label_width = 'webphysiology_portfolio_label_width'; // default 60
	$display_labels = 'webphysiology_portfolio_display_labels'; // default array("Type" => "Type","Created" => "Created","Client" => "For","SiteURL" => "Site","Tech" => "Tech")
	$items_per_page = 'webphysiology_portfolio_items_per_page';  // default 3
	$sort_numerically = 'webphysiology_portfolio_sort_numerically'; // default true
	$include_portfolio_tags_in_tag_cloud = 'webphysiology_portfolio_include_portfolio_tags_in_tag_cloud'; // default true
	$skip_jQuery_register = 'webphysiology_portfolio_skip_jQuery_register'; // default false
	$skip_fancybox_register = 'webphysiology_portfolio_skip_fancybox_register'; // default false
	$use_full_path = 'webphysiology_portfolio_use_full_path'; // default false
	$display_credit = 'webphysiology_portfolio_display_credit'; // default true
	$gridstyle = 'webphysiology_portfolio_gridstyle'; // default false
	$gridcolor = 'webphysiology_portfolio_gridcolor'; // default #eee
	$use_css = 'webphysiology_portfolio_use_css'; // default true
	$overall_width = 'webphysiology_portfolio_overall_width'; // default is 660px
	$img_width = 'webphysiology_portfolio_image_width'; // default is 200px
	$header_color = 'webphysiology_portfolio_header_color'; // default is #004813
	$link_color = 'webphysiology_portfolio_link_color'; // default is #004813
	$odd_stripe_color = 'webphysiology_portfolio_odd_stripe_color'; // default is #eee
	$even_stripe_color = 'webphysiology_portfolio_even_stripe_color'; // default is #f9f9f9
	$legacy_even_odd_class = 'webphysiology_portfolio_legacy_even_odd_class'; // default is false
	$delete_options = 'webphysiology_portfolio_delete_options'; // default false
	$delete_data = 'webphysiology_portfolio_delete_data'; // default false


    // See if the user has posted us some information.  If they did, this hidden field will be set to 'Y'.
    if ( !empty($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		
		if ($_POST["reset_form"] == "False") {
		
			// Read their posted value
			if ( !empty($_POST[ $display_portfolio_title ]) ) {
				$opt_val_display_portfolio_title = $_POST[ $display_portfolio_title ];
			} else {
				$opt_val_display_portfolio_title = "False";
			}
			if ( !empty($_POST[ $display_portfolio_desc ]) ) {
				$opt_val_display_portfolio_desc = $_POST[ $display_portfolio_desc ];
			} else {
				$opt_val_display_portfolio_desc = "False";
			}
			if ( !empty($_POST[ $display_desc_first ]) ) {
				$opt_val_display_desc_first = $_POST[ $display_desc_first ];
			} else {
				$opt_val_display_desc_first = "False";
			}
			if ( !empty($_POST[ $display_portfolio_type ]) ) {
				$opt_val_display_portfolio_type = $_POST[ $display_portfolio_type ];
			} else {
				$opt_val_display_portfolio_type = "False";
			}
			if ( !empty($_POST[ $display_createdate ]) ) {
				$opt_val_display_createdate = $_POST[ $display_createdate ];
			} else {
				$opt_val_display_createdate = "False";
			}
			if ( !empty($_POST[ $display_clientname ]) ) {
				$opt_val_display_clientname = $_POST[ $display_clientname ];
			} else {
				$opt_val_display_clientname = "False";
			}
			if ( !empty($_POST[ $display_siteurl ]) ) {
				$opt_val_display_siteurl = $_POST[ $display_siteurl ];
			} else {
				$opt_val_display_siteurl = "False";
			}
			if ( !empty($_POST[ $display_tech ]) ) {
				$opt_val_display_tech = $_POST[ $display_tech ];
			} else {
				$opt_val_display_tech = "False";
			}
			if (!empty($_POST[ $img_click_behavior ])) {
				$opt_val_img_click_behavior = $_POST[ $img_click_behavior ];
			} else {
				$opt_val_img_click_behavior = 'litebox';
			}
			if (!empty($_POST[ $thumbnail_generator ])) {
				$opt_val_thumbnail_generator = $_POST[ $thumbnail_generator ];
			} else {
				$opt_val_thumbnail_generator = 'tim';
			}
			if ( !empty($_POST[ $target ]) ) {
				$opt_val_target = $_POST[ $target ];
			} else {
				$opt_val_target = "False";
			}
			$opt_val_label_width = $_POST[ $label_width ];
			$opt_val_display_labels["Type"] = $_POST[ $display_labels . '_Type' ];
			$opt_val_display_labels["Created"] = $_POST[ $display_labels . '_Created' ];
			$opt_val_display_labels["Client"] = $_POST[ $display_labels . '_Client' ];
			$opt_val_display_labels["SiteURL"] = $_POST[ $display_labels . '_SiteURL' ];
			$opt_val_display_labels["Tech"] = $_POST[ $display_labels . '_Tech' ];
			if (!empty($_POST[ $missing_img_url ])) {
				$opt_val_missing_img_url = $_POST[ $missing_img_url ];
			} else {
				$opt_val_missing_img_url = 'images/empty_window.png';
			}
			$opt_val_allowed_sites = $_POST[ $allowed_sites ];
			if ( !empty($_POST[ $use_stw ]) ) {
				$opt_val_use_stw = $_POST[ $use_stw ];
			} else {
				$opt_val_use_stw = "False";
			}
			if ( !empty($_POST[ $use_stw_pro ]) ) {
				$opt_val_use_stw_pro = $_POST[ $use_stw_pro ];
			} else {
				$opt_val_use_stw_pro = "False";
			}
			$opt_val_stw_ak = $_POST[ $stw_ak ];
			$opt_val_stw_sk = $_POST[ $stw_sk ];
			$opt_val_pp_account = $_POST[ $pp_account ];
			$opt_val_items_per_page = $_POST[ $items_per_page ];
			if (!empty($_POST[ $sort_numerically ])) {
				$opt_val_sort_numerically = $_POST[ $sort_numerically ];
			} else {
				$opt_val_sort_numerically = 'False';
			}
			if (!empty($_POST[ $include_portfolio_tags_in_tag_cloud ])) {
				$opt_val_include_portfolio_tags_in_tag_cloud = $_POST[ $include_portfolio_tags_in_tag_cloud ];
			} else {
				$opt_val_include_portfolio_tags_in_tag_cloud = 'False';
			}
			if (!empty($_POST[ $skip_jQuery_register ])) {
				$opt_val_skip_jQuery_register = $_POST[ $skip_jQuery_register ];
			} else {
				$opt_val_skip_jQuery_register = 'False';
			}
			if (!empty($_POST[ $skip_fancybox_register ])) {
				$opt_val_skip_fancybox_register = $_POST[ $skip_fancybox_register ];
			} else {
				$opt_val_skip_fancybox_register = 'False';
			}
			if (!empty($_POST[ $use_full_path ])) {
				$opt_val_use_full_path = $_POST[ $use_full_path ];
			} else {
				$opt_val_use_full_path = 'False';
			}
			if ( !empty($_POST[ $display_credit ]) ) {
				$opt_val_display_credit = $_POST[ $display_credit ];
			} else {
				$opt_val_display_credit = "False";
			}
			if (!empty($_POST[ $gridstyle ])) {
				$opt_val_gridstyle = $_POST[ $gridstyle ];
			} else {
				$opt_val_gridstyle = "False";
			}
			$opt_val_gridcolor = $_POST[ $gridcolor ];
			$opt_val_css = $_POST[ $use_css ];
			$opt_val_overall_width = $_POST[ $overall_width ];
			$opt_val_img_width = $_POST[ $img_width ];
			$opt_val_header_color = $_POST[ $header_color ];
			$opt_val_link_color = $_POST[ $link_color ];
			$opt_val_odd_stripe_color = $_POST[ $odd_stripe_color ];
			$opt_val_even_stripe_color = $_POST[ $even_stripe_color ];
			if (!empty($_POST[ $legacy_even_odd_class ])) {
				$opt_val_legacy_even_odd_class = $_POST[ $legacy_even_odd_class ];
			} else {
				$opt_val_legacy_even_odd_class = "False";
			}
			if ( !empty($_POST[ $delete_options ]) ) {
				$opt_val_delete_options = $_POST[ $delete_options ];
			} else {
				$opt_val_delete_options = "False";
			}
			if ( !empty($_POST[ $delete_data ]) ) {
				$opt_val_delete_data = $_POST[ $delete_data ];
			} else {
				$opt_val_delete_data = "False";
			}
			
			$validated = true;
			$validation_msg = __('settings saved', 'Portfolio' );
			
			// do some validating on whether the resulting widths will work or not
			if (!is_numeric($opt_val_items_per_page)) {
				$validated = false;
				$validation_msg = __('settings NOT saved - items per page must be stated as a numeric value.', 'Portfolio' );
			} elseif (!is_numeric($opt_val_overall_width) || !is_numeric($opt_val_img_width) || !is_numeric($opt_val_label_width)) {
				$validated = false;
				$validation_msg = __('settings NOT saved - widths must be stated as a numeric value.', 'Portfolio' );
			} elseif ($opt_val_img_width > $opt_val_overall_width) {
				$validated = false;
				$validation_msg = __('settings NOT saved - image width can\'t be wider than the overall width.', 'Portfolio' );
			} elseif ($opt_val_overall_width < 250) {
				$validated = false;
				$validation_msg = __('settings NOT saved - overall width cannot be narrower than 250 pixels.', 'Portfolio' );
			} elseif ( ($opt_val_thumbnail_generator == 'stw') && ( (empty($opt_val_stw_ak)) || (empty($opt_val_stw_sk)) ) ) {
				// ShrinkTheWeb now requires an account
				$validated = false;
				$validation_msg = __('settings NOT saved - ShrinkTheWeb.com settings are incomplete.', 'Portfolio' );
			} elseif ( !check_admin_referer('portfolio_config', 'portolio-nonce') ) {
				$validated = false;
				$validation_msg = __('settings NOT saved - authentication error.', 'Portfolio' );
			}
			
			// if everything is still okay
			if ($validated) {
				
				// calculate other related element widths
				$overall_image_width = $opt_val_img_width + 20;
				$detail_width = $opt_val_overall_width - $overall_image_width - 30;
				$meta_value_width = $detail_width - 70;
				
				if ($meta_value_width < 100) {
					$too_wide_by = 100 - $meta_value_width;
					$image_width = $opt_val_img_width - $too_wide_by;
					$dtl_width = $opt_val_overall_width + $too_wide_by;
					$validated = false;
					$validation_msg = __('settings NOT saved - Reduce image width to ' . $image_width . ' pixels wide or set the overall width to at least ' . $dtl_width . ' pixels.', 'Portfolio' );
				} elseif ($detail_width < 170) {
					$too_narrow_by = (170 - $detail_width);
					$image_width = $opt_val_img_width + $too_narrow_by;
					$dtl_width = $opt_val_overall_width + $too_narrow_by;
					$validated = false;
					$validation_msg = __('settings NOT saved - too narrow by ' . $too_narrow_by . ' pixels, you need to set the image width to at least ' . $image_width . ' pixels or the overall width to at least ' . $dtl_width . ' pixels.', 'Portfolio' );
				}
			}
			
		} else {  //if ($_POST["reset_form"] == "True") {
			
			// Reset to default settings
			update_option('webphysiology_portfolio_message', 'empty');
			update_option("webphysiology_portfolio_version", WEBPHYSIOLOGY_VERSION);
			update_option("webphysiology_portfolio_database_version", WEBPHYSIOLOGY_DB_VERSION);
			$opt_val_display_portfolio_title = "True";
			$opt_val_display_portfolio_desc = "True";
			$opt_val_display_desc_first = "True";
			$opt_val_display_portfolio_type = "True";
			$opt_val_display_createdate = "True";
			$opt_val_display_clientname = "True";
			$opt_val_display_siteurl = "True";
			$opt_val_display_tech = "True";
			$opt_val_missing_img_url = "images/empty_window.png";
			$opt_val_allowed_sites = "";
			$opt_val_use_stw = "False";
			$opt_val_use_stw_pro = "False";
			$opt_val_stw_ak = "";
			$opt_val_stw_sk = "";
			$opt_val_pp_account = "";
			$opt_val_img_click_behavior = "litebox";
			$opt_val_thumbnail_generator = 'tim'; // other options stw & pp
			$opt_val_target = "False";
			$opt_val_label_width = "60";
			$opt_val_display_labels = array("Type" => "Type", "Created" => "Created", "Client" => "For", "SiteURL" => "Site", "Tech" => "Tech");
			$opt_val_items_per_page = "3";
			$opt_val_sort_numerically = "True";
			$opt_val_include_portfolio_tags_in_tag_cloud = "True";
			$opt_val_skip_jQuery_register = "False";
			$opt_val_skip_fancybox_register = "False";
			$opt_val_use_full_path = "False";
			$opt_val_display_credit = "True";
			$opt_val_gridstyle = "False";
			$opt_val_gridcolor = "#eeeeee";
			$opt_val_css = "True";
			$opt_val_overall_width = "660";
			$opt_val_img_width = "200";
			$opt_val_header_color = "#004813";
			$opt_val_link_color = "#004813";
			$opt_val_odd_stripe_color = "#eeeeee";
			$opt_val_even_stripe_color = "#f9f9f9";
			$opt_val_legacy_even_odd_class = "False";
			$opt_val_delete_options = "False";
			$opt_val_delete_data = "False";
			$validation_msg = __('successfully reset to default values', 'Portfolio' );
			$validated = true;
			
		}
		
		// if the specified sizes are within tolerances
		if ( $validated ) {
			
			// Save the posted value in the database
			update_option( $display_portfolio_title, $opt_val_display_portfolio_title );
			update_option( $display_portfolio_desc, $opt_val_display_portfolio_desc );
			update_option( $display_desc_first, $opt_val_display_desc_first );
			update_option( $display_portfolio_type, $opt_val_display_portfolio_type );
			update_option( $display_createdate, $opt_val_display_createdate );
			update_option( $display_clientname, $opt_val_display_clientname );
			update_option( $display_siteurl, $opt_val_display_siteurl );
			update_option( $display_tech, $opt_val_display_tech );
			update_option( $missing_img_url, $opt_val_missing_img_url );
			update_option( $allowed_sites, $opt_val_allowed_sites );
			update_option( $use_stw, $opt_val_use_stw );
			update_option( $use_stw_pro, $opt_val_use_stw_pro );
			update_option( $stw_ak, $opt_val_stw_ak );
			update_option( $stw_sk, $opt_val_stw_sk );
			update_option( $pp_account, $opt_val_pp_account );
			update_option( $img_click_behavior, $opt_val_img_click_behavior );
			update_option( $thumbnail_generator, $opt_val_thumbnail_generator );
			update_option( $target, $opt_val_target );
			update_option( $label_width, $opt_val_label_width );
			update_option( $display_labels, $opt_val_display_labels );
			update_option( $items_per_page, $opt_val_items_per_page );
			update_option( $sort_numerically, $opt_val_sort_numerically );
			update_option( $include_portfolio_tags_in_tag_cloud, $opt_val_include_portfolio_tags_in_tag_cloud );
			update_option( $skip_jQuery_register, $opt_val_skip_jQuery_register );
			update_option( $skip_fancybox_register, $opt_val_skip_fancybox_register );
			update_option( $use_full_path, $opt_val_use_full_path );
			update_option( $display_credit, $opt_val_display_credit );
			update_option( $gridstyle, $opt_val_gridstyle );
			update_option( $gridcolor, $opt_val_gridcolor );
			update_option( $use_css, $opt_val_css );
			update_option( $overall_width, $opt_val_overall_width );
			update_option( $img_width, $opt_val_img_width );
			update_option( $header_color, $opt_val_header_color );
			update_option( $link_color, $opt_val_link_color );
			update_option( $odd_stripe_color, $opt_val_odd_stripe_color );
			update_option( $even_stripe_color, $opt_val_even_stripe_color );
			update_option( $legacy_even_odd_class, $opt_val_legacy_even_odd_class );
			update_option( $delete_options, $opt_val_delete_options );
			update_option( $delete_data, $opt_val_delete_data );
			
		}
		
		// Put a settings updated message on the screen
		echo ('<div class="updated"><p><strong>' . $validation_msg . '</strong></p></div>');
		
	} else {
		
		// Read in existing option value from database
		$opt_val_display_portfolio_title = get_option( $display_portfolio_title );
		$opt_val_display_portfolio_desc = get_option( $display_portfolio_desc );
		$opt_val_display_desc_first = get_option( $display_desc_first );
		$opt_val_display_portfolio_type = get_option( $display_portfolio_type );
		$opt_val_display_createdate = get_option( $display_createdate );
		$opt_val_display_clientname = get_option( $display_clientname );
		$opt_val_display_siteurl = get_option( $display_siteurl );
		$opt_val_display_tech = get_option( $display_tech );
		$opt_val_missing_img_url = get_option( $missing_img_url );
		$opt_val_allowed_sites = get_option( $allowed_sites );
		$opt_val_use_stw = get_option( $use_stw );
		$opt_val_use_stw_pro = get_option( $use_stw_pro );
		$opt_val_stw_ak = get_option( $stw_ak );
		$opt_val_stw_sk = get_option( $stw_sk );
		$opt_val_pp_account = get_option( $pp_account );
		$opt_val_img_click_behavior = get_option( $img_click_behavior );
		$opt_val_thumbnail_generator = get_option( $thumbnail_generator );
		$opt_val_target = get_option( $target );
		$opt_val_label_width = get_option( $label_width );
		$opt_val_display_labels = get_option( $display_labels );
		$opt_val_items_per_page = get_option( $items_per_page );
		$opt_val_sort_numerically = get_option( $sort_numerically );
		$opt_val_include_portfolio_tags_in_tag_cloud = get_option( $include_portfolio_tags_in_tag_cloud );
		$opt_val_skip_jQuery_register = get_option( $skip_jQuery_register );
		$opt_val_skip_fancybox_register = get_option( $skip_fancybox_register );
		$opt_val_use_full_path = get_option( $use_full_path );
		$opt_val_display_credit = get_option( $display_credit );
		$opt_val_gridstyle = get_option( $gridstyle );
		$opt_val_gridcolor = get_option( $gridcolor );
		$opt_val_css = get_option( $use_css );
		$opt_val_overall_width = get_option( $overall_width );
		$opt_val_img_width = get_option( $img_width );
		$opt_val_header_color = get_option( $header_color );
		$opt_val_link_color = get_option( $link_color );
		$opt_val_odd_stripe_color = get_option( $odd_stripe_color );
		$opt_val_even_stripe_color = get_option( $even_stripe_color );
		$opt_val_legacy_even_odd_class = get_option( $legacy_even_odd_class );
		$opt_val_delete_options = get_option( $delete_options );
		$opt_val_delete_data = get_option( $delete_data );
		
	}
	
	if ($opt_val_display_portfolio_title=="True" ) {$opt_val_display_portfolio_title="checked";}
	if ($opt_val_display_portfolio_desc=="True" ) {$opt_val_display_portfolio_desc="checked";}
	if ($opt_val_display_desc_first=="True" ) {$opt_val_display_desc_first="checked";}
	if ($opt_val_display_portfolio_type=="True" ) {$opt_val_display_portfolio_type="checked";}
	if ($opt_val_display_createdate=="True" ) {$opt_val_display_createdate="checked";}
	if ($opt_val_display_clientname=="True" ) {$opt_val_display_clientname="checked";}
	if ($opt_val_display_siteurl=="True" ) {$opt_val_display_siteurl="checked";}
	if ($opt_val_display_tech=="True" ) {$opt_val_display_tech="checked";}
	if ($opt_val_use_stw=="True" ) {$opt_val_use_stw="checked";} else {$opt_val_use_stw="";}
	if ($opt_val_use_stw_pro=="True" ) {$opt_val_use_stw_pro="checked";} else {$opt_val_use_stw_pro="";}
	if ($opt_val_img_click_behavior == "litebox") { $check_openlitebox = 'checked'; } else { $check_nav2page = 'checked'; }
	if ($opt_val_thumbnail_generator == "tim") {
		$check_wp = 'checked';
		$tt_display = " display: block";
		$stw_display = " display: none";
		$pp_display = " display: none";
	} elseif ($opt_val_thumbnail_generator == "stw") {
		$check_stw = 'checked';
		$tt_display = " display: none";
		$stw_display = " display: block";
		$pp_display = " display: none";
	} else {
		$check_pp = 'checked';
		$tt_display = " display: none";
		$stw_display = " display: none";
		$pp_display = " display: block";
	}
	if ($opt_val_target=="True" ) {$opt_val_target="checked";}
	if ($opt_val_sort_numerically=="True" ) {$opt_val_sort_numerically="checked";}
	if ($opt_val_include_portfolio_tags_in_tag_cloud=="True" ) {$opt_val_include_portfolio_tags_in_tag_cloud="checked";}
	if ($opt_val_skip_jQuery_register=="True" ) {$opt_val_skip_jQuery_register="checked";}
	if ($opt_val_skip_fancybox_register=="True" ) {$opt_val_skip_fancybox_register="checked";}
	if ($opt_val_use_full_path=="True" ) {$opt_val_use_full_path="checked";}
	if ($opt_val_css=="True" ) {$opt_val_css="checked";}
	if ($opt_val_display_credit=="True" ) {$opt_val_display_credit="checked";}
	if ($opt_val_gridstyle=="True" ) {$opt_val_gridstyle="checked";}
	if ($opt_val_legacy_even_odd_class=="True" ) {$opt_val_legacy_even_odd_class="checked";}
	if ($opt_val_delete_options=="True" ) {$opt_val_delete_options="checked";}
	if ($opt_val_delete_data=="True" ) {$opt_val_delete_data="checked";}
	
	echo "\n";
	echo '<script type="text/javascript">' . "\n";
	echo '	<!--' . "\n";
	echo '	jQuery(document).ready(function() {' . "\n";
	echo '		jQuery("#colorpicker").farbtastic("#colorselector")' . "\n";
	echo '	});' . "\n";
	echo '	-->' . "\n";
	echo '</script>' . "\n";
	echo "\n";
	echo "			<div id='newsletter_subscribe'><img src='https://s3.amazonaws.com/webphysiology_portfolio/subscribe.png' alt='subscribe to our newsletter' /></div>" . "\n";
	echo "			<div id='facebook_like'><img src='https://s3.amazonaws.com/webphysiology_portfolio/like.png' alt='like us on Facebook' /></div>" . "\n";
	echo "			<div id='donate_smiley'><img src='https://s3.amazonaws.com/webphysiology_portfolio/smiley.png' alt='donations make us happy' /></div>" . "\n";
	echo "			<div id='social_network'>" . "\n";
	echo "			<ul>" . "\n";
	echo "				<li><a href='http://www.facebook.com/WEBphysiology' title='Like us on Facebook' target='_blank'><img src='https://s3.amazonaws.com/webphysiology_portfolio/facebook-logo-square-webtreatsetc-32x32.png' alt='Like WEBphysiology on facebook' height='32px' width='32px' /></a></li>" . "\n";
	echo "				<li><a href='http://twitter.com/#!/WEBphysiology' title='Follow us on Twitter' target='_blank'><img src='https://s3.amazonaws.com/webphysiology_portfolio/twitter-logo-square-webtreatsetc-32x32.png' alt='WEBphysiology on Twitter' height='32px' width='32px' /></a></li>" . "\n";
	echo "				<li><a href='http://feeds2.feedburner.com/WEBphysiology' title='Subscribe to the WEBphysiology News Feed' target='_blank'><img src='https://s3.amazonaws.com/webphysiology_portfolio/rss-square-webtreatsetc-32x32.png' alt='WEBphysiology News Feed' height='32px' width='32px' /></a></li>" . "\n";
	echo "				<li><a href='#mc_signup_container' class='webphysiology_newsletter' title='Subscribe to the WEBphysiology Newsletter'><img src='https://s3.amazonaws.com/webphysiology_portfolio/mail-square-webtreatsetc-32x32.png' alt='Subscribe to the WEBphysiology Newsletter' height='32px' width='32px' /></a></li>" . "\n";
	echo "			</ul>" . "\n";
	echo "			</div>";
	echo "			<div id='port_option_donate'>";
	echo '				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="portfolio_donate">' . "\n";
	echo '					<input type="hidden" name="cmd" value="_s-xclick">' . "\n";
	echo '					<input type="hidden" name="hosted_button_id" value="G6YDH57GS9PCJ">' . "\n";
	echo '					<input style="background:none . "\n";border:none . "\n";" type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' . "\n";
	echo '					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">' . "\n";
	echo '				</form>' . "\n";
	echo "			</div>";
	echo '			<form action="" method="post" name="portolio-conf" id="portolio-conf">' . "\n" . '				';
	wp_nonce_field('portfolio_config', 'portolio-nonce');
	echo "\n";
	echo '				<input type="hidden" name="' . $hidden_field_name . '" value="Y">' . "\n";
	echo '				<input type="hidden" name="page_options" value="WEBphysiology_portolio_plugin_data" />' . "\n";
	echo '				<input type="hidden" value="' . get_option('version') . '" name="version"/>' . "\n";
	echo portfolio_admin_section_wrap('top', 'Portfolio Release Notes&nbsp;&nbsp;|&nbsp;&nbsp;installed version = ' . get_option('webphysiology_portfolio_version'), ' style="padding: 10px 10px; overflow: hidden;"');
	echo "				<div id='option_alerts'>";
	echo portfolio_version_alert(WEBPHYSIOLOGY_VERSION, True);  // 1.4.0
	// asterisk - add previous version alert 
	echo portfolio_version_alert('1.3.2', True);
	echo portfolio_version_alert('1.3.1', True);
	echo portfolio_version_alert('1.3.0', True);
	echo portfolio_version_alert('1.2.7', True);
	echo portfolio_version_alert('1.2.4', True);
	echo portfolio_version_alert('shortcode', False);
	echo portfolio_version_alert('release_notes', False);
	echo "				</div>";
	echo "				<div id='option_comments'>";
	echo "					<h2>WEBphysiology Portfolio</h2>";
	echo "					<p>Thank you for using the WEBphysiology Portfolio plugin.  We feel it has a lot of versatility but, like anything, there always is room for improvement.  While our resources are limited, we still welcome your input on new feature suggetions.  Just leave a comment on our <a href='http://refr.us/wpport' title='WEBphysiology Portfolio Plugin Page' target='_blank'>plugin page</a>, which also covers the many features, so, we ask that you please review the materials provided before reaching out for help.  If you have the budget, and want something more, just <a href='http://WEBphysiology.com/contact/' title='Contact WEBphysiology' target='_blank'>give us a shout</a>.  WEBphysiology services include web site design, development and troubleshooting as well as plugin and other related WordPress development.</p>";
	echo "					<p>Should you have issues of a techincal nature that require assistance, please request help via our <a href='http://refr.us/wphelp' title='WEBphysiology Online Support' target='_blank'>Online Support</a> facility.</p>";
//	echo "					<p style='margin-bottom: 0;'><strong>Release 1.3.0</strong> : THE SHORTCODE [portfolio] <span style='color:red; font-weight:bold;'>HAS BEEN DEPRECATED</span>. Check your Portfolio pages to ensure you are using the <span style='color:red;'>[webphysiology_portfolio]</span> shortcode.  This change was initially announced in release 1.2.4 but not implemented until this version.  The change was to further isolate contentions with other plugins that might utilize the same shortcode.</p>";
//	echo "					<p style='margin-bottom: 0;'><strong>Release 1.4.0</strong> : This release brings with it a couple of significant items:";
//	echo "						<ul style='list-style:square;margin-left:25px;'>";
//	echo "							<li>A basic, individual Portfolio post page: The WEBphysiology Portfolio plugin does not limit portfolios from showing up in search results.  This is all fine-and-dandy except that, until now, the WEBphysiology Portfolio plugin also did not support viewing a portfolio record on its own, which would result in a 404 page if a Portfolio was clicked from the search results. Not anymore! Styling is basic and similar to a portfolio entry.</li>";
//	echo "							<li>PageSeeker.com Support: This is a thumbnail generation service that will take a supplied URL and return a thumbnail of that page. This adds a second such service to our plugin, providing a choice for auto-generating site thumbnails.</li>";
//	echo "						</ul>";
//	echo "					</p>";
	echo "					<p style='margin-bottom: 0;'><strong>Release 1.4.1</strong> : This release brings with it a couple of significant items:";
	echo "						<ul style='list-style:square;margin-left:25px;'>";
	echo "							<li>The individual Portfolio template, single-webphys_portfolio.php, now is overridable, allowing a user to create a theme based template.</li>";
	echo "							<li>True support for Portfolio tags has been added and any existing post tags have been shifted into the new Portfolio tag taxonomy. The option to exclude Portfolio tags from the standard Tag Cloud widget is available.</li>";
	echo "							<li>A Portfolio Tag Cloud widget has been added.</li>";
	echo "						</ul>";
	echo "					</p>";
	echo "				</div>";
	echo portfolio_admin_section_wrap('bottom', null, null);
	echo '						<div class="submit top portfolio_button">' . "\n";
	echo '							<input type="submit" class="button-primary" name="Save" value="Save Portfolio Settings" id="submitbutton" />' . "\n";
	echo '						</div>' . "\n";
	echo portfolio_admin_section_wrap('top', 'Portfolio Display Settings', null);
	echo '						<h4>Labeling &amp; Data Display</h4>' . "\n";
	echo '						<input type="checkbox" id="' . $display_portfolio_title . '" name="' . $display_portfolio_title . '" value="True" ' . $opt_val_display_portfolio_title . '/><label for="' . $display_portfolio_title . '">Display portfolio title</label><br/>' . "\n";
	echo '						<div class="display_and_label">' . "\n";
	echo '							<div class="display_attrib">' . "\n";
	echo '						<input type="checkbox" id="' . $display_portfolio_desc . '" name="' . $display_portfolio_desc . '" value="True" ' . $opt_val_display_portfolio_desc . '/><label for="' . $display_portfolio_desc . '">Display portfolio description</label><br />' . "\n";
	echo '							</div>' . "\n";
	echo '							<div class="label_attrib">' . "\n";
	echo '						<input type="checkbox" id="' . $display_desc_first . '" name="' . $display_desc_first . '" value="True" ' . $opt_val_display_desc_first . '/><label for="' . $display_desc_first . '">Display portfolio description before Portfolio meta fields</label><br />' . "\n";
	echo '							</div>' . "\n";
	echo '						</div>' . "\n";
	echo '						<div class="display_and_label">' . "\n";
	echo '							<div class="display_attrib">' . "\n";
	echo '								<input type="checkbox" id="' . $display_portfolio_type . '" name="' . $display_portfolio_type . '" value="True" ' . $opt_val_display_portfolio_type . '/><label for="' . $display_portfolio_type . '">Display portfolio type</label><br/>' . "\n";
	echo '							</div>' . "\n";
	echo '							<div class="label_attrib">' . "\n";
	echo '								<label for="' . $display_labels . '_Type">Portfolio type label:</label><input type="text" id="' . $display_labels . '_Type" name="' . $display_labels . '_Type" value="' . $opt_val_display_labels["Type"] . '" /><br />' . "\n";
	echo '							</div>' . "\n";
	echo '						</div>' . "\n";
	echo '						<div class="display_and_label">' . "\n";
	echo '							<div class="display_attrib">' . "\n";
	echo '								<input type="checkbox" id="' . $display_createdate . '" name="' . $display_createdate . '" value="True" ' . $opt_val_display_createdate . '/><label for="' . $display_createdate . '">Display date created</label><br/>' . "\n";
	echo '							</div>' . "\n";
	echo '							<div class="label_attrib">' . "\n";
	echo '								<label for="' . $display_labels . '_Created">Date created label:</label><input type="text" id="' . $display_labels . '_Created" name="' . $display_labels . '_Created" value="' . $opt_val_display_labels["Created"] . '" /><br />' . "\n";
	echo '							</div>' . "\n";
	echo '						</div>' . "\n";
	echo '						<div class="display_and_label">' . "\n";
	echo '							<div class="display_attrib">' . "\n";
	echo '								<input type="checkbox" id="' . $display_clientname . '" name="' . $display_clientname . '" value="True" ' . $opt_val_display_clientname . '/><label for="' . $display_clientname . '">Display client name</label><br/>' . "\n";
	echo '							</div>' . "\n";
	echo '							<div class="label_attrib">' . "\n";
	echo '								<label for="' . $display_labels . '_Client">Client name label:</label><input type="text" id="' . $display_labels . '_Client" name="' . $display_labels . '_Client" value="' . $opt_val_display_labels["Client"] . '" /><br />' . "\n";
	echo '							</div>' . "\n";
	echo '						</div>' . "\n";
	echo '						<div class="display_and_label">' . "\n";
	echo '							<div class="display_attrib">' . "\n";
	echo '								<input type="checkbox" id="' . $display_siteurl . '" name="' . $display_siteurl . '" value="True" ' . $opt_val_display_siteurl . '/><label for="' . $display_siteurl . '">Display portfolio web page</label><br/>' . "\n";
	echo '							</div>' . "\n";
	echo '							<div class="label_attrib">' . "\n";
	echo '								<label for="' . $display_labels . '_SiteURL">Portfolio web page label:</label><input type="text" id="' . $display_labels . '_SiteURL" name="' . $display_labels . '_SiteURL" value="' . $opt_val_display_labels["SiteURL"] . '" /><br />' . "\n";
	echo '							</div>' . "\n";
	echo '						</div>' . "\n";
	echo '						<div class="display_and_label">' . "\n";
	echo '							<div class="display_attrib">' . "\n";
	echo '								<input type="checkbox" id="' . $display_tech . '" name="' . $display_tech . '" value="True" ' . $opt_val_display_tech . '/><label for="' . $display_tech . '">Display technical details</label><br/>' . "\n";
	echo '							</div>' . "\n";
	echo '							<div class="label_attrib">' . "\n";
	echo '								<label for="' . $display_labels . '_Tech">Technical details label:</label><input type="text" id="' . $display_labels . '_Tech" name="' . $display_labels . '_Tech" value="' . $opt_val_display_labels["Tech"] . '" /><br />' . "\n";
	echo '							</div>' . "\n";	
	echo '						</div>' . "\n";	
	echo '						<label for="' . $label_width . '">Label width:</label><input type="text" id="' . $label_width . '" name="' . $label_width . '" value="' . $opt_val_label_width . '" class="webphysiology_portfolio_small_input" /> pixels<br />' . "\n";
	echo '					</div>' . "\n";
	echo '					<div class="inside">' . "\n";
	echo '						<h4>Image Handling - <a class="alert_text" href="' . esc_attr(plugin_dir_url(__FILE__)) . 'images/thumbnail_image_generation_flow.png" title="Thumbnail Image Generation Flow" style="text-decoration:none;">process flowchart</a></h4>' . "\n";
	echo '						<label for="' . $missing_img_url . '">Missing image URL:</label><input type="text" id="' . $missing_img_url . '" name="' . $missing_img_url . '" value="' . $opt_val_missing_img_url . '" class="half_input shortbottom" /><br /><span class="attribute_instructions">note: url should be relative to this plugin\'s directory, be in the uploads directory (e.g., /uploads/2010/11/missing.jpg) or be the full URL path</span><br class="tallbottom" />' . "\n";
	echo '						<label for="' . $allowed_sites . '">Allowed image sites:</label><input type="text" id="' . $allowed_sites . '" name="' . $allowed_sites . '" value="' . $opt_val_allowed_sites . '" class="half_input shortbottom" /><br /><span class="attribute_instructions">note: add allowed domain separated with commas (e.g., flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com)</span><br class="tallbottom" />' . "\n";
	echo '						<label for="' . $thumbnail_generator . '">Thumbnail Generator: </label><input id="webphys_portfolio_tim_gen" type="radio" name="' . $thumbnail_generator . '" value="tim" ' .  $check_wp . ' /> Use built-in thumbnail support&nbsp;&nbsp;<input id="webphys_portfolio_pp_gen" type="radio" name="' . $thumbnail_generator . '" value="pp" ' . $check_pp . ' /> Use PagePeeker.com&nbsp;&nbsp;<input id="webphys_portfolio_stw_gen" type="radio" name="' . $thumbnail_generator . '" value="stw" ' . $check_stw . ' /> Use ShrinkTheWeb.com<br/>' . "\n";
	echo '						<div id="tt_settings" style="margin-left:30px;' . $tt_display . '">';
	echo '						</div>';
	echo '						<div id="stw_settings" style="margin-left:30px;' . $stw_display . '">ShrinkTheWeb Settings: ';
	echo '								<input type="checkbox" id="' . $use_stw_pro . '" name="' . $use_stw_pro . '" value="True" ' . $opt_val_use_stw_pro . ' /><label for="' . $use_stw_pro . '" class="half_input shortbottom">Basic/PLUS Version</label>&nbsp;&nbsp;&nbsp;' . "\n";
	echo '							<label for="' . $stw_ak . '">Access key:</label><input type="text" id="' . $stw_ak . '" name="' . $stw_ak . '" value="' . $opt_val_stw_ak . '" />&nbsp;&nbsp;&nbsp;' . "\n";
	echo '							<label for="' . $stw_sk . '">Secret key:</label><input type="password" id="' . $stw_sk . '" name="' . $stw_sk . '" value="' . $opt_val_stw_sk . '" /><br />' . "\n";
	echo '							<span class="attribute_instructions">Get your own <a href="http://www.shrinktheweb.com">Website Preview from ShrinkTheWeb</a></span><br />' . "\n";
	echo '							<span class="attribute_instructions" style="line-height:2.5em;"><strong>NOTE:</strong> ShrinkTheWeb.com Basic/Plus version with Pro features are needed to display inner pages of a website and to display a ShrinkTheWeb.com generated thumbnail in a thickbox.</span><br class="tallbottom"/>' . "\n";
	echo '						</div>';
	echo '						<div id="pp_settings" style="margin-left:30px;' . $pp_display . '">PagePeeker Settings: ';
	echo '							<label for="' . $pp_account . '">Custom Account:</label><input type="text" id="' . $pp_account . '" name="' . $pp_account . '" value="' . $opt_val_pp_account . '" /><br />' . "\n";
	echo '							<span class="attribute_instructions">For unbranded thumbnails get a custom account from <a href="http://pagepeeker.com/custom_solutions">PagePeeker.com</a></span><br />' . "\n";
	echo '						</div>';
	echo '						<input id="clear_img_caches" type="button" class="button" value="Clear Image Caches" name="Clear Image Caches" onClick="sendClearImageRequest()" style="margin: 10px 10px 5px 10px;" /><div id="show-clear-response" class="HideAjaxContent"></div><br />' . "\n";
	echo '						<input id="chmod_img_caches" type="button" class="button" value="Permission Image Cache to 0777" name="Chmod Image Cache" onClick="sendChmodImageRequest()" /><div id="show-chmod-response" class="HideAjaxContent"></div><br />' . "\n";
	echo '							<span class="attribute_instructions">If images aren\'t displaying, and you\'ve checked the image URL\'s, try upping the caching folder permissions.</span><br />' . "\n";
	echo '					</div>' . "\n";
	echo '					<div class="inside">' . "\n";
	echo '						<h4>User Interface Actions - <a class="alert_text" href="' . esc_attr(plugin_dir_url(__FILE__)) . 'images/thumbnail_click_flow.png" title="Thumbnail Click Behavior Flow" style="text-decoration:none;">image click behavior flowchart</a></h4>' . "\n";
	echo '						<label for="' . $img_click_behavior . '">Image click behavior: </label><input type="radio" name="' . $img_click_behavior . '" value="litebox" ' .  $check_openlitebox . ' /> Open fullsize image in a thickbox&nbsp;&nbsp;<input type="radio" name="' . $img_click_behavior . '" value="nav2page" ' . $check_nav2page . ' /> Navigate to the portfolio web page URL<br/>' . "\n";
	echo '						<span class="attribute_instructions">note: if you are using a non-Pro version of ShrinkTheWeb, then regardless of this setting, an image click will navigate you to the portfolio web page URL</span><br class="tallbottom"/>' . "\n";
	echo '								<input type="checkbox" id="' . $target . '" name="' . $target . '" value="True" ' . $opt_val_target . '/><label for="' . $target . '">Open links in a new tab (target="_blank")</label><br/>' . "\n";
	echo '						<span class="attribute_instructions">Commonly accepted practice is to NOT open links in a new tab or window</span><br class="tallbottom"/>' . "\n";
	echo '					</div>' . "\n";
	echo '					<div class="inside">' . "\n";
	echo '						<h4>Miscellaneous</h4>' . "\n";
	echo '						<label for="' . $items_per_page . '">Portfolio items per page:</label><input type="text" id="' . $items_per_page . '" name="' . $items_per_page . '" value="' . $opt_val_items_per_page . '" class="webphysiology_portfolio_small_input" /><br />' . "\n";
	echo '						<input type="checkbox" id="' . $sort_numerically . '" name="' . $sort_numerically . '" value="True" ' . $opt_val_sort_numerically . '/><label for="' . $sort_numerically . '">Sort numerically</label><br/>' . "\n";
	echo portfolio_admin_section_wrap('bottom', null, null);
	echo portfolio_admin_section_wrap('top', 'Portfolio Styling', ' style="clear:both;overflow:hidden;"');
	echo '						<div class="portfolio_admin_style">' . "\n";
	echo '							<input type="checkbox" id="' . $use_css . '" name="' . $use_css . '" value="True" ' . $opt_val_css . '/><label for="' . $use_css . '">Use Portfolio plugin CSS</label><br/>' . "\n";
	echo '								<input type="checkbox" id="' . $gridstyle . '" name="' . $gridstyle . '" value="True" ' . $opt_val_gridstyle . '/><label for="' . $gridstyle . '">Use Grid Style layout</label>&nbsp;&nbsp;&nbsp;' . "\n"; //<br/>' . "\n";
	echo '									<label for="' . $gridcolor . '">Grid background color:</label><input type="text" id="' . $gridcolor . '" name="' . $gridcolor . '" value="' . $opt_val_gridcolor . '" class="webphysiology_portfolio_small_input" /><br />' . "\n";
	echo '							<label for="' . $overall_width . '">Portfolio List - overall width:</label><input type="text" id="' . $overall_width . '" name="' . $overall_width . '" value="' . $opt_val_overall_width . '" class="webphysiology_portfolio_small_input" /> pixels<br />' . "\n";
	echo '							<label for="' . $img_width . '">Portfolio List - image width:</label><input type="text" id="' . $img_width . '" name="' . $img_width . '" value="' . $opt_val_img_width . '" class="webphysiology_portfolio_small_input" /> pixels<br /><span class="attribute_instructions">note: if you use the Grid Style layout, this is your overall cell width</span><br class="tallbottom" />' . "\n";
	echo '							<label for="' . $header_color . '">Portfolio Title color:</label><input type="text" id="' . $header_color . '" name="' . $header_color . '" value="' . $opt_val_header_color . '" class="webphysiology_portfolio_small_input" /><span style="color:' . $opt_val_header_color . ';margin-left:10px;">title color</span><br />' . "\n";
	echo '							<label for="' . $link_color . '">Portfolio Nav color:</label><input type="text" id="' . $link_color . '" name="' . $link_color . '" value="' . $opt_val_link_color . '" class="webphysiology_portfolio_small_input" /><span style="color:' . $opt_val_link_color . ';margin-left:10px;">link color</span><br /><span class="attribute_instructions">note: this is the color of the page navigation numbers</span><br class="tallbottom" />' . "\n";
	echo '							<label for="' . $odd_stripe_color . '">Portfolio odd stripe background color:</label><input type="text" id="' . $odd_stripe_color . '" name="' . $odd_stripe_color . '" value="' . $opt_val_odd_stripe_color . '" class="webphysiology_portfolio_small_input" /><span style="background-color:' . $opt_val_odd_stripe_color . ';margin-left:10px;">odd stripe color</span><br />' . "\n";
	echo '							<label for="' . $even_stripe_color . '">Portfolio even stripe background color:</label><input type="text" id="' . $even_stripe_color . '" name="' . $even_stripe_color . '" value="' . $opt_val_even_stripe_color . '" class="webphysiology_portfolio_small_input" /><span style="background-color:' . $opt_val_even_stripe_color . ';margin-left:10px;">even stripe color</span><br/>' . "\n";


	echo '								<input type="checkbox" id="' . $legacy_even_odd_class . '" name="' . $legacy_even_odd_class . '" value="True" ' . $opt_val_legacy_even_odd_class . '/><label for="' . $legacy_even_odd_class . '">Use legacy "even" / "odd" classes on portfolio items and "top" / "bottom" classes on nav elements</label>&nbsp;&nbsp;&nbsp;' . "\n"; //<br/>' . "\n";
	echo '						</div>' . "\n";
	echo '						<div id="color-selection-helper"">' . "\n";
	echo '							<p>color selection helper</p>' . "\n";
	echo '							<div id="colorpicker"></div>' . "\n";
	echo '							<input type="text" id="colorselector" name="colorselector" value="#cccccc" />' . "\n";
	echo '						</div>' . "\n";
	echo portfolio_admin_section_wrap('bottom', null, null);
	echo portfolio_admin_section_wrap('top', 'Odds and Ends', null);
	echo '						<input type="checkbox" id="' . $include_portfolio_tags_in_tag_cloud . '" name="' . $include_portfolio_tags_in_tag_cloud . '" value="True" ' . $opt_val_include_portfolio_tags_in_tag_cloud . '/><label for="' . $include_portfolio_tags_in_tag_cloud . '">Include Portfolio Tags in standard Tag Cloud widget</label><br/>' . "\n";
	echo '						<input type="checkbox" id="' . $skip_jQuery_register . '" name="' . $skip_jQuery_register . '" value="True" ' . $opt_val_skip_jQuery_register . '/><label for="' . $skip_jQuery_register . '">Don\'t register jQuery v1.7.1 from Google</label><br/>' . "\n";
	echo '						<span class="attribute_instructions">on the off chance that some other plugin throws jQuery errors you can simply serve up the standard jQuery provided within the WordPress install</span><br class="tallbottom"/>' . "\n";
	echo '						<input type="checkbox" id="' . $skip_fancybox_register . '" name="' . $skip_fancybox_register . '" value="True" ' . $opt_val_skip_fancybox_register . '/><label for="' . $skip_fancybox_register . '">Don\'t register Fancybox jQuery v1.3.4</label><br/>' . "\n";
	echo '						<span class="attribute_instructions">if you are using another plugin that registers Fancybox or Thickbox, you may need to disable one if there are version conflicts</span><br class="tallbottom"/>' . "\n";
	echo '						<input type="checkbox" id="' . $use_full_path . '" name="' . $use_full_path . '" value="True" ' . $opt_val_use_full_path . '/><label for="' . $use_full_path . '">Use full paths on images and css/js files</label><br/>' . "\n";
	echo '						<span class="attribute_instructions">some hosts don\'t like HTTP:// within the resource paths while others require it, so, if images aren\'t displaying you might try turning this on</span><br class="tallbottom"/>' . "\n";
	echo '						<input type="checkbox" id="' . $display_credit . '" name="' . $display_credit . '" value="True" ' . $opt_val_display_credit . '/><label for="' . $display_credit . '">Display WEBphysiology credit and/or a donation would be nice (though neither is required).</label>' . "\n";
	echo portfolio_admin_section_wrap('bottom', null, null);
	echo portfolio_admin_section_wrap('top', 'Portfolio Deactivation Settings', null);
	echo '				    		<input type="checkbox" id="' . $delete_options . '" name="' . $delete_options . '" value="True" ' . $opt_val_delete_options . '/><label for="' . $delete_options . '">Delete Portfolio Option Settings</label><br/>' . "\n";
	echo '				    		<input type="checkbox" id="' . $delete_data . '" name="' . $delete_data . '" value="True" ' . $opt_val_delete_data . '/><label for="' . $delete_data . '">Delete Portfolio Records (includes Portfolio Types)</label>' . "\n";
	echo portfolio_admin_section_wrap('bottom', null, null);
	echo '				<div class="submit portfolio_button">' . "\n";
	echo '					<input type="submit" class="button-primary" name="Save" value="Save Portfolio Settings" id="submitbutton" />' . "\n";
	echo '					<input type="button" class="button" name="Default" value="Revert to Default Values" id="resetbutton" onClick="reset_to_default(this.form)" />' . "\n";
	echo '				</div>' . "\n";
	echo '				<input id="reset_form" name="reset_form" type="hidden" value="False" />' . "\n";
	echo '			</form>' . "\n";
	echo '			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="portfolio_donate">' . "\n";
	echo '				<input type="hidden" name="cmd" value="_s-xclick">' . "\n";
	echo '				<input type="hidden" name="hosted_button_id" value="G6YDH57GS9PCJ">' . "\n";
	echo '				<input style="background:none . "\n";border:none . "\n";" type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' . "\n";
	echo '				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">' . "\n";
	echo '			</form>' . "\n";
	echo '			</div> <!-- <div class="meta-box-sortables"> -->' . "\n";
	echo '		</div> <!-- <div class="metabox-holder"> -->' . "\n";
	echo '	</div> <!-- <div class="postbox-container"> -->' . "\n";
	echo '</div> <!-- <div class="wrap portfolio-admin"> -->' . "\n";
	
	echo '<script type="text/javascript">' . "\n";
    echo '	<!--' . "\n";
    echo '    function reset_to_default(settingsForm) {' . "\n";
	echo '		settingsForm.reset_form.value = "True"' . "\n";
    echo '      settingsForm.submit()' . "\n";
    echo '    }' . "\n";
    echo '    //-->' . "\n";
    echo '</script>' . "\n";
	
}

// Add plugin Settings link
function add_plugin_settings_link($links) {
	$x = str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	$settings_link = '<a href="edit.php?post_type=webphys_portfolio&page=' . $x .'">' . __('Settings','Portfolio') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

function get_google_jquery() {
	wp_deregister_script('jquery');
	// https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js
	wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
	wp_enqueue_script('jquery');
}

function get_colorpicker_jquery() {
	
	$base = plugin_dir_url(__FILE__) . 'scripts/farbtastic/';
	
	wp_deregister_script('webphys_farbtastic');
	$file = esc_attr($base . 'farbtastic.js');
	wp_register_script('webphys_farbtastic', $file);
	wp_enqueue_script('webphys_farbtastic');
	
	$file = esc_attr($base . 'farbtastic.css');
	wp_register_style('webphys_farbtastic_css', $file);
	wp_enqueue_style('webphys_farbtastic_css');
	
}

function check_options() {
	
	// ASTERISK = make certain to update this with new releases //
	// check the most recently added option, if it doesn't exist then pass down through all of them and add any that are missing
	$return = get_option('webphysiology_portfolio_include_portfolio_tags_in_tag_cloud');
	
	if ( empty($return) ) {
		
		// added in v1.4.1
		$return = get_option('webphysiology_portfolio_include_portfolio_tags_in_tag_cloud');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_include_portfolio_tags_in_tag_cloud", 'True'); // This is the default value for whether to include Portfolio Tags in the standard Tag Cloud widget
		}
		
		// added in v1.4.0
		$return = get_option('webphysiology_portfolio_use_full_path');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_use_full_path", 'False'); // This is the default value for whether to use full paths for images and some css/js files
		}
		$return = get_option('webphysiology_portfolio_legacy_even_odd_class');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_legacy_even_odd_class", "False"); // This is the default value for whether to use the legacy "even" and "odd" portfolio item classes
		}
		$return = get_option('webphysiology_portfolio_thumbnail_generator');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_thumbnail_generator", "tim"); // This is the default value for the thumbnail generator to use
		}
		
		// added in v1.3.0
		$return = get_option('webphysiology_portfolio_display_desc_first');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_desc_first", 'True'); // This is the default value for whether to display the Portfolio Description before the meta data
		}
		
		// added in v1.2.7
		$return = get_option('webphysiology_portfolio_skip_jQuery_register');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_skip_jQuery_register", 'False'); // This is the default value for whether to not register jQuery from Google
		}
		$return = get_option('webphysiology_portfolio_skip_fancybox_register');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_skip_fancybox_register", 'False'); // This is the default value for whether to not register Fancybox
		}
		$return = get_option('webphysiology_portfolio_use_stw_pro');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_use_stw_pro", 'False'); // This is the default value for whether to display images using ShrinkTheWeb.com PRO version
		}
		
		// added in v1.2.4
		$return = get_option('webphysiology_portfolio_message');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_message", 'empty');
		}
		
		// added in v1.2.3
		$return = get_option('webphysiology_portfolio_anchor_click_behavior');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_anchor_click_behavior", 'False'); // This is the default value for whether to open links in a new tab
		}
		
		// added in v1.2.2
		$return = get_option('webphysiology_portfolio_sort_numerically');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_sort_numerically", 'True'); // This is the default value for whether to sort numerically off the sort column
		}
		
		// added in v1.2.0
		$return = get_option('webphysiology_portfolio_use_stw');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_use_stw", 'False'); // This is the default value for whether to display images using ShrinkTheWeb.com
		}
		$return = get_option('webphysiology_portfolio_stw_ak');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_stw_ak", ""); // This is the default value for the ShrinkTheWeb.com Access Key
		}
		$return = get_option('webphysiology_portfolio_stw_sk');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_stw_sk", ""); // This is the default value for the ShrinkTheWeb.com Security Key
		}
		$return = get_option('webphysiology_portfolio_pagepeeker_account');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_pagepeeker_account", ""); // This is the default value for the PagePeeker.com Custom Account
		}
		
		// added in v1.1.5
		$return = get_option('webphysiology_portfolio_allowed_image_sites');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_allowed_image_sites", ""); // This is the default value for the allowed image sites
		}
		
		// added in v1.1.3
		$return = get_option('webphysiology_portfolio_display_portfolio_title');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_portfolio_title", 'True'); // This is the default value for whether to display the Portfolio Title
		}
		$return = get_option('webphysiology_portfolio_display_portfolio_desc');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_portfolio_desc", 'True'); // This is the default value for whether to display the Portfolio Description
		}
		$return = get_option('webphysiology_portfolio_display_desc_first');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_desc_first", 'True'); // This is the default value for whether to display the Portfolio Description before the meta data
		}
		$return = get_option('webphysiology_portfolio_gridstyle');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_gridstyle", "False"); // This is the default value for whether to display the portfolio in a grid style
		}
		$return = get_option('webphysiology_portfolio_gridcolor');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_gridcolor", "#eeeeee"); // This is the default value for the grid background color
		}
		
		// added in v1.0.3
		$return = get_option('webphysiology_portfolio_display_clientname');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_clientname", 'True'); // This is the default value for whether to display the client name
		}
		$return = get_option('webphysiology_portfolio_display_siteurl');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_siteurl", 'True'); // This is the default value for whether to display the site URL
		}
		$return = get_option('webphysiology_portfolio_display_tech');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_tech", 'True'); // This is the default value for whether to display the technical data
		}
		$return = get_option('webphysiology_portfolio_label_width');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_label_width", "60"); // This is the default value for the detail label width
		}
		$return = get_option('webphysiology_portfolio_display_labels');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_labels", array("Type" => "Type","Created" => "Created","Client" => "For","SiteURL" => "Site","Tech" => "Tech")); // This is the default values for the field labels on the site UI
		}
		$return = get_option('webphysiology_portfolio_missing_image_url');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_missing_image_url", "images/empty_window.png"); // This is the default value for the missing image path
		}
		$return = get_option('webphysiology_portfolio_image_click_behavior');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_image_click_behavior", 'litebox'); // This is the default value for whether to display the image in a thickbox or navigate to the associated site
		}
		
		// added in v1.0.0
		$return = get_option('webphysiology_portfolio_display_credit');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_display_credit", "True"); // This is the default value for whether to display a plugin publisher credit
		}
		$return = get_option('webphysiology_portfolio_delete_options');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_delete_options", "False"); // This is the default value for whether to delete plugin options on plugin deactivation
		}
		$return = get_option('webphysiology_portfolio_delete_data');
		if ( empty($return) ) {
			add_option("webphysiology_portfolio_delete_data", "False"); // This is the default value for whether to delete Portfolio data on plugin deactivation
		}
	}
}

function portfolio_admin_section_wrap($wrap_location, $title, $style) {
	
	if (strtolower($wrap_location) == 'top') {
		$html  = '<div id="pluginsettings" class="postbox">' . "\n";
		$html .= '	<h3 class="hndle"><span>' . $title . '</span></h3>' . "\n";
		$html .= '	<div class="inside"' . $style . '>' . "\n";
	} else {
		$html  = "	</div>" . "\n";
		$html .= "</div>" . "\n";
	}
	
	return $html;
	
}

/* Define Portfolio Plugin Activation process */
function portfolio_install() {
	
	$return = get_option('webphysiology_portfolio_items_per_page');
	
	// if one of the original options is not set then this is a new install
	if ( empty($return) ) {
		
		// just in case the new options are added here and not below, trap whether the db option exists or not because, if it doesn't,
		// this may be an update requiring db updates
		$dbver = get_option('webphysiology_portfolio_database_version');
		if ( empty($dbver) ) { $dbver = '3.0.0'; }
		
		$pluginver = get_option('webphysiology_portfolio_version');
		if ( empty($pluginver) ) { $pluginver = '1.2.3'; }
		
		add_option('webphysiology_portfolio_message', 'empty');
		add_option("webphysiology_portfolio_version", WEBPHYSIOLOGY_VERSION);
		add_option("webphysiology_portfolio_database_version", WEBPHYSIOLOGY_DB_VERSION);
		add_option("webphysiology_portfolio_display_portfolio_title", 'True'); // This is the default value for whether to display the Portfolio Title
		add_option("webphysiology_portfolio_display_portfolio_desc", 'True'); // This is the default value for whether to display the Portfolio Description
		add_option("webphysiology_portfolio_display_desc_first", 'True'); // This is the default value for whether to display the Portfolio Description before the meta data
		add_option("webphysiology_portfolio_display_portfolio_type", 'True'); // This is the default value for whether to display the Portfolio Type
		add_option("webphysiology_portfolio_display_createdate", 'True'); // This is the default value for whether to display the create date
		add_option("webphysiology_portfolio_display_clientname", 'True'); // This is the default value for whether to display the client name
		add_option("webphysiology_portfolio_display_siteurl", 'True'); // This is the default value for whether to display the site URL
		add_option("webphysiology_portfolio_display_tech", 'True'); // This is the default value for whether to display the technical data
		add_option("webphysiology_portfolio_missing_image_url", 'images/empty_window.png'); // This is the default value for the missing image url
		add_option("webphysiology_portfolio_allowed_image_sites",""); // This is the default value for the allowed image sites
		add_option("webphysiology_portfolio_use_stw", 'False'); // This is the default value for whether to display images using ShrinkTheWeb.com
		add_option("webphysiology_portfolio_use_stw_pro", 'False'); // This is the default value for whether user is using ShrinkTheWeb.com PRO version
		add_option("webphysiology_portfolio_stw_ak", ""); // This is the default value for the ShrinkTheWeb.com Access Key
		add_option("webphysiology_portfolio_stw_sk", ""); // This is the default value for the ShrinkTheWeb.com Security Key
		add_option("webphysiology_portfolio_pagepeeker_account", ""); // This is the default value for the PagePeeker.com Custom Account
		add_option("webphysiology_portfolio_image_click_behavior", 'litebox'); // This is the default value for whether to display the image in a thickbox or navigate to the associated site
		add_option("webphysiology_portfolio_anchor_click_behavior", 'False'); // This is the default value for whether to open links in a new window
		add_option("webphysiology_portfolio_label_width", "60"); // This is the default value for the label width
		add_option("webphysiology_portfolio_display_labels", array("Type" => "Type", "Created" => "Created", "Client" => "For", "SiteURL" => "Site", "Tech" => "Tech")); // This is the default values for the field labels on the site UI
		add_option("webphysiology_portfolio_items_per_page", '3'); // This is the default value for the number of portfolio items to display per page
		add_option("webphysiology_portfolio_sort_numerically", 'True'); // This is the default value for whether to sort numerically off the sort column
		add_option('webphysiology_portfolio_include_portfolio_tags_in_tag_cloud', 'True'); // This is the default value for whether to include Portfolio Tags in the standard Tag Cloud widget
		add_option('webphysiology_portfolio_skip_jQuery_register', 'False'); // This is the default value for whether to not register jQuery from Google
		add_option('webphysiology_portfolio_skip_fancybox_register', 'False'); // This is the default value for whether to not register Fancybox
		add_option('webphysiology_portfolio_use_full_path', 'False'); // This is the default value for whether to not to use full paths for images and some css/js files
		add_option("webphysiology_portfolio_display_credit", "True"); // This is the default value for whether to display a plugin publisher credit
		add_option("webphysiology_portfolio_gridstyle", "False"); // This is the default value for whether to display portfolio items in a grid format
		add_option("webphysiology_portfolio_gridcolor", "#eeeeee"); // This is the default value for the grid background color
		add_option("webphysiology_portfolio_delete_options", "False"); // This is the default value for whether to delete plugin options on plugin deactivation
		add_option("webphysiology_portfolio_delete_data", "False"); // This is the default value for whether to delete Portfolio data on plugin deactivation
		add_option("webphysiology_portfolio_use_css", 'True'); // This is the default value for the Portfolio CSS usage switch
		add_option("webphysiology_portfolio_overall_width", '660'); // This is the overall width of the portfolio listing
		add_option("webphysiology_portfolio_image_width", '200'); // This is the width to use on the portfolio image in the listing
		add_option("webphysiology_portfolio_header_color", '#004813'); // This is the h1 and h2 color
		add_option("webphysiology_portfolio_link_color", '#004813'); // This is the anchor link color
		add_option("webphysiology_portfolio_odd_stripe_color", '#eeeeee'); // This is the portfolio list odd row stripe background color
		add_option("webphysiology_portfolio_even_stripe_color", '#f9f9f9'); // This is the portfolio list even row stripe background color
		add_option("webphysiology_portfolio_legacy_even_odd_class", "False"); // This is the default value for whether to use the "even" and "odd" classes on portfolio items
		
		check_temp_dir(); // check to see that the temp directory exists, as this is needed when images from different domains are utilized
		
	}
}

/* Define Portfolio Plugin De-activation process */
function portfolio_remove() {
	
	$deleteoptions = strtolower(get_option( 'webphysiology_portfolio_delete_options' ));
	$deletedata = strtolower(get_option( 'webphysiology_portfolio_delete_data' ));
	
	// if the option to delete options is set, delete the Portfolio options data
	//   note: Portfolio data cannot be deleted if options are not deleted too
	if ( $deleteoptions == "true" ) {
		
		/* Deletes the Portfolio plugin database field */
		delete_option("webphysiology_portfolio_database_version");
		delete_option('webphysiology_portfolio_display_portfolio_title');
		delete_option('webphysiology_portfolio_display_portfolio_desc');
		delete_option('webphysiology_portfolio_display_desc_first');
		delete_option('webphysiology_portfolio_display_portfolio_type');
		delete_option('webphysiology_portfolio_display_createdate');
		delete_option('webphysiology_portfolio_display_clientname');
		delete_option('webphysiology_portfolio_display_siteurl');
		delete_option('webphysiology_portfolio_display_tech');
		delete_option('webphysiology_portfolio_missing_image_url');
		delete_option('webphysiology_portfolio_allowed_image_sites');
		delete_option('webphysiology_portfolio_use_stw');
		delete_option('webphysiology_portfolio_use_stw_pro');
		delete_option('webphysiology_portfolio_stw_ak');
		delete_option('webphysiology_portfolio_stw_sk');
		delete_option('webphysiology_portfolio_pagepeeker_account');
		delete_option('webphysiology_portfolio_image_click_behavior');
		delete_option('webphysiology_portfolio_anchor_click_behavior');
		delete_option('webphysiology_portfolio_label_width');
		delete_option('webphysiology_portfolio_display_labels');
		delete_option('webphysiology_portfolio_items_per_page');
		delete_option('webphysiology_portfolio_sort_numerically');
		delete_option('webphysiology_portfolio_include_portfolio_tags_in_tag_cloud');
		delete_option('webphysiology_portfolio_skip_jQuery_register');
		delete_option('webphysiology_portfolio_skip_fancybox_register');
		delete_option('webphysiology_portfolio_use_full_path');
		delete_option('webphysiology_portfolio_display_credit');
		delete_option('webphysiology_portfolio_gridstyle');
		delete_option('webphysiology_portfolio_gridcolor');
		delete_option('webphysiology_portfolio_use_css');
		delete_option('webphysiology_portfolio_overall_width');
		delete_option('webphysiology_portfolio_image_width');
		delete_option('webphysiology_portfolio_header_color');
		delete_option('webphysiology_portfolio_link_color');
		delete_option('webphysiology_portfolio_odd_stripe_color');
		delete_option('webphysiology_portfolio_even_stripe_color');
		delete_option('webphysiology_portfolio_legacy_even_odd_class');
		delete_option('webphysiology_portfolio_delete_options');
		delete_option('webphysiology_portfolio_delete_data');
		delete_option('webphysiology_portfolio_message');
		delete_option('webphysiology_portfolio_version');
		
	}
	
	// if the delete data option is set to delete, then delete the Portfolio records and Portfolio Type taxonomy records
	if ( $deletedata == "true" ) {
		
		// Gather the Portfolios
		$portfolios_to_delete = new WP_Query(array('post_type' => 'webphys_portfolio', 'post_status' => 'any', 'orderby' => 'ID', 'order' => 'DESC'));
		
		// Loop through and delete the Portfolios
		if ( $portfolios_to_delete->have_posts() ) {
			while ( $portfolios_to_delete->have_posts() ) : $portfolios_to_delete->the_post();
				wp_delete_post( get_the_id(), true );
			endwhile;
		}

		// Gather the list of Portfolio Types
		$portfolio_type_list = get_terms('portfolio_type', 'hide_empty=0');
		
		// Loop thru the types and delete each one, the last will clear the taxonomy
		foreach ($portfolio_type_list as $portfolio_item) {
			wp_delete_term( $portfolio_item->term_id, 'portfolio_type' );
		}
	}

}

function check_version() {
	
	check_plugin_version();
	check_database_version();
	
}

function check_plugin_version() {
	
	$pluginver = get_option('webphysiology_portfolio_version');
	
	// if the plugin version isn't set then set it
	if ( empty($pluginver) ) {
		
		version_update(WEBPHYSIOLOGY_VERSION, "");
		
		// update the plugin db version to the current version
		add_option("webphysiology_portfolio_version", WEBPHYSIOLOGY_VERSION);
		
	} elseif ( version_compare(WEBPHYSIOLOGY_VERSION, $pluginver, ">" ) ) {
		
		$msg = "";
		$x = str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		$settings_link = '<a href="edit.php?post_type=webphys_portfolio&page=' . $x .'">' . __('Portfolio Options page','Portfolio') . '</a>';
		
		switch (WEBPHYSIOLOGY_VERSION) {
			
			case '1.4.1':
				
				deletefile(plugin_dir_path(__FILE__), "scripts/clear_img_caches.js", "");
				break;
				
			case '1.2.7':
				
				$msg = 'Please read the important WEBphysiology Portfolio Version 1.2.7 Release Notes available on the ' . $settings_link ;
				break;
				
			case (WEBPHYSIOLOGY_VERSION == '1.3.0' || WEBPHYSIOLOGY_VERSION == '1.3.1'):
				
				$msg = '<span style="color:red">Please read the important WEBphysiology Portfolio Version ' . WEBPHYSIOLOGY_VERSION . ' Release Notes available on the ' . $settings_link . '</span>';
				break;
				
		}
		
		// perform any updates required between the last installed version and the current version and then update the
		// current version option so that this doesn't run again
		version_update($pluginver, $msg);
		
		// update the current installed version to this new version
		update_option( 'webphysiology_portfolio_version', WEBPHYSIOLOGY_VERSION );
		
	} else {
		
		// update the current installed version to this new version
		update_option("webphysiology_portfolio_version", WEBPHYSIOLOGY_VERSION);
		
	}
	
}

function check_database_version() {
	
	$dbver = get_option('webphysiology_portfolio_database_version');
	
	// if the database version isn't set then set it
	if (empty($dbver)) {
		
		update_database(WEBPHYSIOLOGY_DB_VERSION);
		
		// update the plugin db version to the current version
		add_option("webphysiology_portfolio_database_version", WEBPHYSIOLOGY_DB_VERSION);
		
	} elseif ( version_compare(WEBPHYSIOLOGY_DB_VERSION, $dbver, ">" ) ) {
		
		update_database($dbver);
		
		// update the plugin db version to the current version
		update_option("webphysiology_portfolio_database_version", WEBPHYSIOLOGY_DB_VERSION);
		
	} elseif (WEBPHYSIOLOGY_DB_VERSION != $dbver) {
		
		update_option("webphysiology_portfolio_database_version", WEBPHYSIOLOGY_DB_VERSION);
		
	}
	
}

function version_update($pluginver, $alert_msg) {
	
	if ( ! is_admin() ) { return; }
	
	// check to see that the temp directory exists, as this is needed when images from different domains are utilized
	check_temp_dir();
	
	$alert = "";
	
	// if the current plugin version is newwer than the last version installed
	if ( version_compare(WEBPHYSIOLOGY_VERSION, $pluginver, ">" ) ) {
		
		// if the currently installed version is less than version 1.4.0 then run the updates that came with version 1.4.0
		if ( version_compare($pluginver, '1.4.0', '<=') ) {
			update_option('webphysiology_portfolio_legacy_even_odd_class','True');
			if (get_option('webphysiology_portfolio_use_stw') == 'True') {
				update_option('webphysiology_portfolio_thumbnail_generator','stw');
			}
		}
		
		// if the currently installed version is less than version 1.3.1 then run the updates that came with version 1.3.1
		if ( version_compare($pluginver, '1.3.1', '<=') ) {
			
			// remove the originally defaulted allowed sites from the current settings leaving any that were added since the install
			
			// get the current allowed sites
			$current_allowedSites = strtolower(get_option( 'webphysiology_portfolio_allowed_image_sites' ));
			$allowedSites = $current_allowedSites;
			
			// string out the default allowed sites
			$allowedSites = str_replace(", flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com, ", ", ", $allowedSites, $cnt);
			
			// if no sites were removed then try another iteration
			if ( $cnt == 0 ) {
				$allowedSites = str_replace(", flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com,", ",", $allowedSites, $cnt);
			}
			
			// if no sites were removed then try another iteration
			if ( $cnt == 0 ) {
				$allowedSites = str_replace(",flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com, ", ",", $allowedSites, $cnt);
			}
			
			// if no sites were removed then try another iteration
			if ( $cnt == 0 ) {
				$allowedSites = str_replace(",flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com,", ",", $allowedSites, $cnt);
			}
			
			// if no sites were removed then try another iteration
			if ( $cnt == 0 ) {
				$allowedSites = str_replace(",flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com", "", $allowedSites, $cnt);
			}
			
			// if no sites were removed then try another iteration
			if ( $cnt == 0 ) {
				$allowedSites = str_replace("flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com,", "", $allowedSites, $cnt);
			}
			
			// if no sites were removed then try a final iteration
			if ( $cnt == 0 ) {
				$allowedSites = str_replace("flickr.com,picasa.com,blogger.com,wordpress.com,img.youtube.com", "", $allowedSites, $cnt);
			}
			// if sites were removed then update the allowed sites option
			if ( $cnt != 0 ) {
				update_option( 'webphysiology_portfolio_allowed_image_sites', $allowedSites );
				if ( strlen($allowedSites) < 4 ) {
					$alert = "<br />To further secure this plugin's use of timthumb.php image scaling routine the original list of Allowed Sites was removed.  These were <br />&nbsp;&nbsp;&nbsp;".$current_allowedSites."<br />";
				} else {
					$alert .= "<br />To further secure this plugin's use of timthumb.php image scaling routine, the original list of Allowed Sites was updated from<br />&nbsp;&nbsp;&nbsp;".$current_allowedSites."<br />to<br />&nbsp;&nbsp;&nbsp;".$allowedSites."<br />";
				}
			}
			
			// delete the old timthumb script folder as it was replaced with a renamed set of files in "scripts/imageresizer"
			$dir = str_replace("function.php","",__FILE__)."scripts/thumb";
			rrmdir($dir);
			
			// set the permissions to the plugin temp directory 744
			$dir = str_replace("function.php","",__FILE__)."temp";
			if ( is_dir($dir) ) {
				chmod($dir, 0744);
			}
			
			// set the permissions of the timthumb cache directory to 744
			$dir = str_replace("function.php","",__FILE__)."scripts/imageresizer/cache";
			if ( is_dir($dir) ) {
				chmod($dir, 0744);
			}
			
		} // if ( version_compare($pluginver, '1.3.1', '<') )
		
	} // if ( version_compare(WEBPHYSIOLOGY_VERSION, $pluginver, ">" ) )
	
	// if there is an alert message to display, display it
	if ( (! empty($alert)) || (! empty($alert_msg)) ) {
		
		if ( (! empty($alert)) && (! empty($alert_msg)) ) { $alert_msg = $alert_msg . "<br />"; }
		
		$alert = $alert_msg . $alert ;
		
		set_admin_message($alert);
		
	}
}

function update_database($ver) {
	
	global $wpdb;
	
	if ( ! is_admin() ) { return; }
	
	// if the current database version is newwer than the last version installed
	if ( version_compare(WEBPHYSIOLOGY_DB_VERSION, $ver, ">" ) ) {
		
		// if the version of WEBphysiology Portfolio in use before this version is earlier than 3.1.0
		if ( version_compare($ver, '3.1.0', '<=') ) {
			
			// check to see if there are any old "Portfolio" records in the database
			$row = $wpdb->get_row("SELECT COUNT(*) 'portfolio_count' FROM $wpdb->posts WHERE post_type = 'Portfolio'");
			
			// if old "Portfolio" records were found update them to "webphys_portfolio"
			if ( $row->portfolio_count > 0 ) {
	//			echo $row->portfolio_count . '<br />';
				/* update post types from "Portfolio" to "webphys_portfolio" */
				$wpdb->query("UPDATE $wpdb->posts SET post_type = 'webphys_portfolio' WHERE post_type = 'Portfolio'");
				
				$x = str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
				$settings_link = '<a href="edit.php?post_type=webphys_portfolio&page=' . $x .'">' . __('Portfolio Options page','Portfolio') . '</a>';
				$msg = 'Please read the important WEBphysiology Portfolio Version 1.2.4 Release Notes available on the ' . $settings_link ;
				set_admin_message($msg);
				
			}
		}
		
		// if this version of WEBphysiology Portfolio is using the 3.2.1 db version or better
		if ( version_compare($ver, '3.2.1', '<=') ) {
			
			/* Insert missing Portfolio | Portfolio Types into the Term Relationship table */
			$wpdb->query("	INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order)
							SELECT	sp.id 'object_id',
								(	SELECT	ssstt.term_taxonomy_id
									FROM	$wpdb->postmeta spm INNER JOIN
											$wpdb->terms ssst ON spm.meta_value = ssst.slug INNER JOIN
											$wpdb->term_taxonomy ssstt ON ssst.term_id = ssstt.term_id AND ssstt.taxonomy = 'portfolio_type'
									WHERE	spm.meta_key = '_portfolio_type'
									AND		spm.post_id = sp.id) 'term_taxonomy_id',
									0 'term_order'
							FROM	$wpdb->posts sp
							WHERE	sp.post_type = 'webphys_portfolio'
							AND		NOT EXISTS (
									SELECT	1
									FROM	$wpdb->term_relationships str INNER JOIN
											$wpdb->term_taxonomy stt ON str.term_taxonomy_id = stt.term_taxonomy_id AND stt.taxonomy = 'portfolio_type' INNER JOIN
											$wpdb->terms st ON stt.term_id = st.term_id
									WHERE	str.object_id = sp.id)");
			
			// update the Portfolio (Post) counts on the Portfolio Types
			$wpdb->query("	UPDATE	$wpdb->term_taxonomy
							SET		count = (SELECT count(ssp.id) FROM $wpdb->posts ssp INNER JOIN $wpdb->term_relationships str ON ssp.id = str.object_id WHERE ssp.post_type = 'webphys_portfolio' AND ssp.post_status = 'publish' AND str.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE	taxonomy = 'portfolio_type'");
			
		}
		
		// if this version of WEBphysiology Portfolio is using the 3.3.1 db version or better
		if ( version_compare($ver, '3.3.1', '<=') ) {
			
			/* update Post Tags hooked to Portfolio records to set them to be Portfolio Tags */
			$wpdb->query("	UPDATE	$wpdb->term_taxonomy tt
							SET		taxonomy = 'webphys_portfolio_tag'
							WHERE	taxonomy = 'post_tag'
							AND		EXISTS (
									SELECT	1
									FROM	$wpdb->term_relationships tr INNER JOIN
											$wpdb->posts p ON tr.object_id = p.id AND p.post_type = 'webphys_portfolio'
									WHERE	tr.term_taxonomy_id = tt.term_taxonomy_id)");
			
		}
	}
}

function set_admin_message($message) {
	
	// if we aren't clearing the message
	if ( $message != 'empty' ) {
		
		$msg = get_option('webphysiology_portfolio_message');
		
		// if
		if (( ! empty($msg) ) && ( $msg != 'empty' )) {
			$message = $msg."<br /><br />".$message;
		}
	}
	
	update_option('webphysiology_portfolio_message', $message);
	
}

// check and display any plugin messages
function display_update_alert() {
	
	// if the current user has no ability to manage options then don't bother showing them the transient message
    if (!current_user_can('manage_options')) { return; }
	
	// grab the message option and see if it is populated
	$message = get_option('webphysiology_portfolio_message');
	if ( ( ! empty($message) ) && ( $message != 'empty' ) ) {
		
		echo '	<div class="webphys_portfolio_message">';
		echo '		<div class="errrror">	<p>' . $message . '</p></div>';
		echo '	</div>';
		
		// now that we've displayed the alert, clear it out
		// asterisk - at a later date add the ability to make the clearing of the message based upon user action only
		set_admin_message('empty');
	}
}

// Make certain the scripts and css necessary to support the file upload button are active
function portfolio_admin_scripts() {
	
	global $post;
	
	$continue = "False";
	
	// don't include the media upload script if we are not on a portfolio edit page, otherwise,
	// the standard image upload will be hijacked and not work on other post and page admin pages
	if (!empty($post)) {
		if (strtolower($post->post_type) == "webphys_portfolio") {
			$continue = "True";
		}
	}
	
	if ($continue == "True") {
		$script = plugins_url('scripts/file_uploader.js', __FILE__);
		$script = clear_pre_content($script);
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('portfolio-image-upload', $script, array('jquery','media-upload','thickbox'));
		wp_enqueue_script('portfolio-image-upload');
		
	}
}

function portfolio_admin_styles() {
	
	global $post;
	
	$continue = "False";
	
	// don't include the media upload script if we are not on a portfolio edit page, otherwise,
	// the standard image upload will be hijacked and not work on other post and page admin pages
	if (!empty($post)) {
		if (strtolower($post->post_type) == "webphys_portfolio") {
			$continue = "True";
		}
	}
	
	if ($continue == "True") {
		
		$css = plugins_url('scripts/fancybox/jquery.fancybox-1.3.4.css', __FILE__);
		$css = clear_pre_content($css);
		wp_register_style('lightbox_css', $css);
		wp_enqueue_style('lightbox_css');
		
		wp_enqueue_style('thickbox');
		
	}
}
	
// remove the Porfolio Type tag sidebar widget from the Portfolio edit screen as the Portfolio Type dropdown manages this
// also remove author dropdown list as this really doesn't apply to Portfolios
function remove_post_custom_fields() {
	remove_meta_box( 'tagsdiv-portfolio_type' , 'webphys_portfolio' , 'side' );
	remove_meta_box( 'authordiv' , 'webphys_portfolio' , 'content' );
}

/* Register the Portfolio columns to display in the Portfolio Admin listing */
function add_new_portfolio_columns($columns) {
	
	$detail_labels = get_option( 'webphysiology_portfolio_display_labels' );
	$type = $detail_labels["Type"];
	$createdate = $detail_labels["Created"];
	$clientname = $detail_labels["Client"];
	$siteURL = $detail_labels["SiteURL"];
	$tech = $detail_labels["Tech"];
	
	if (empty($type)) {
		$type = 'Type';
	}
	if (empty($createdate)) {
		$createdate = 'Create Date';
	}
	if (empty($clientname)) {
		$clientname = 'Client';
	}
	if (empty($siteURL)) {
		$siteURL = 'Website URL';
	}
	if (empty($tech)) {
		$tech = 'Technical Details';
	}
	
	// perhaps update column names (e.g., _clientname) to make them more unique (e.g., _webphys_clientname)
	
	// note: columns in the listing are ordered in line with where they are created below
	$new_columns['title'] = _x('Portfolio Name', 'column name');
	$new_columns['_createdate'] = _x( $createdate, 'column name' );
	$new_columns['_clientname'] = _x( $clientname, 'column name' );
	$new_columns['_technical_details'] = _x( $tech, 'column name' );
	$new_columns['_siteurl'] = _x( $siteURL, 'column name' );
	$new_columns['_portfolio_type'] = _x( $type, 'column name' );
	$new_columns['_sortorder'] = _x( 'Sort Order', 'column name' );
	$new_columns['date'] = _x('Create Date', 'column name');
	$new_columns['id'] = __('ID');

	return $new_columns;
	
}

// hide the Post Tags and Portfolio Types Quick Edit fields on the Portfolio listing
function webphysiology_portfolio_quickedit() {
	
	global $post;
	
	if ( is_object($post) ) {
	    if ( $post->post_type == 'webphys_portfolio' ) {
			echo '<style type="text/css">';
			echo '	.inline-edit-tags {display: none !important;}';
			echo '</style>';
		}
	}
	
}

/* Define the data retrieval arguments for the Portfolio list columns */
function manage_portfolio_columns($column_name, $id) {
	
	global $wpdb;
	
	switch ($column_name) {
	case '_sortorder':
		echo get_post_meta( $id , '_sortorder' , true );
		break;
	case '_createdate':
		// Get the date the Portfolio was created, typically a year
		echo get_post_meta( $id , '_createdate' , true );
		break;
	case '_clientname':
		// Get the name of the client for whom the development was performed
		echo get_post_meta( $id , '_clientname' , true );
		break;
	case '_technical_details':
		// Get the technical details
		echo get_post_meta( $id , '_technical_details' , true );
		break;
	case '_siteurl':
		// Get the URL to the actual website
		echo get_post_meta( $id , '_siteurl' , true );
		break;
	case '_portfolio_type':
		// Get the Portfolio Type
		$type = get_post_meta( $id , '_portfolio_type' , true );
		$portfolio_type = get_term_by( 'slug', $type, 'portfolio_type' );
		if (!empty($portfolio_type->name)) {
			echo $portfolio_type->name;
		} else {
			echo "";
		}
		break;
	case 'id':
		//Get the Post ID
		echo ($id);
		break;
	default:
		break;
	} // end switch
	
}

//removes view from portfolio list
function remove_quick_edit( $actions ) {
	
	global $post;
    if( $post->post_type == 'webphys_portfolio' ) {
//		unset($actions['inline hide-if-no-js']);
		unset($actions['view']);
	}
	
    return $actions;
	
}

function portfolio_version_alert($alert_ver,$spacer) {
	
	$html = "";
	$space = False;
	
	switch ($alert_ver) {
	
		case '1.3.2':
			
			$html .= '		<a class="alert_text" href="#v132_notes">IMPORTANT Version 1.3.2 release notes</a>' . "\n";
			$html .= '		<div style="display: none;">' . "\n";
			$html .= '			<div id="v132_notes" >' . "\n";
			$html .= '				<h3 style="font-size:1.4em;text-align: center;">WEBphysiology Portfolio Plugin - Version 1.3.2 Release Notifications</h3>' . "\n";
			$html .= '				<ol><li>As it would appear that many themes define a "top" and "bottom" classes, and because the top and bottom navigation buttons in this plugin also have these classes defined, we&rsquo;ve added new classes, "webphysport_nav_top" and "webphysport_nav_bottom", and updated our styling around these.  The previous styling and inclusion of the "top" and "bottom" classes still remain, for backward compatibility, but <span style="font-weight:bold;color:red;">WILL BE DEPRECATED</span> in a future release.  So, if you&rsquo;ve created any CSS around the "top" or "bottom" classes specifically for this plugin, you should change them to use the "webphysport_nav_top" and "webphysport_nav_bottom" classes, respectively.</li><li>As it would appear that many themes define a "odd" and "even" classes, and because the odd and even background striping in this plugin also have these classes defined, we&rsquo;ve added new classes, "webphysport_odd_stripe" and "webphysport_odd_stripe", and updated our styling around these.  The previous styling and inclusion of the "odd" and "even" classes still remain, for backward compatibility, but <span style="font-weight:bold;color:red;">WILL BE DEPRECATED</span> in a future release.  So, if you&rsquo;ve created any CSS around the "odd" or "even" classes specifically for this plugin, you should change them to use the "webphysport_odd_stripe" and "webphysport_odd_stripe" classes, respectively.</li></ol>' . "\n";
			$html .= '				<p>For a complete list of changes refer to the Readme.txt file in the WEBphysiology Portfolio plugin directory or the Change Log on the <a href="http://refr.us/wpport" target="_blank">WEBphysiology Portfolio</a> page.</p>' . "\n";
			$html .= "			</div>" . "\n";
			$html .= "		</div>" . "\n";
			
			$space = $spacer;
			
			break;
			
		case '1.3.1':
			
			$html .= '		<a class="alert_text" href="#v131_notes">IMPORTANT Version 1.3.1 release notes</a>' . "\n";
			$html .= '		<div style="display: none;">' . "\n";
			$html .= '			<div id="v131_notes" >' . "\n";
			$html .= '				<h3 style="font-size:1.4em;text-align: center;">WEBphysiology Portfolio Plugin - Version 1.3.1 Release Notifications</h3>' . "\n";
			$html .= '				<ol><li>Do to a <a href="http://ma.tt/2011/08/the-timthumb-saga/" title="TimThumb Zero Day Security Vulnerability">security issue with the TimThumb</a> image resizing (PHP) plugin utilized in this WordPress plugin, this release was pushed out quickly on the heals of the v1.3.0 release.  This release incorporates TimThumb 2.0.</li><li>The default "Allowed Image Sites" have been stripped for security purposes. This means if you host any of your portfolio images on flickr.com, picasa.com, blogger.com, wordpress.com or img.youtube.com, you likely will need to add them back into the "Allowed Image Sites" field in Portfolio Options.  You only should list those domains where you actually host images.</li></ol>' . "\n";
			$html .= '				<p>For a complete list of changes refer to the Readme.txt file in the WEBphysiology Portfolio plugin directory or the Change Log on the <a href="http://refr.us/wpport" target="_blank">WEBphysiology Portfolio</a> page.</p>' . "\n";
			$html .= "			</div>" . "\n";
			$html .= "		</div>" . "\n";
			
			$space = $spacer;
			
			break;
			
		case '1.3.0':
			
			$html .= '		<a class="alert_text" href="#v130_notes">IMPORTANT Version 1.3.0 release notes</a>' . "\n";
			$html .= '		<div style="display: none;">' . "\n";
			$html .= '			<div id="v130_notes" >' . "\n";
			$html .= '				<h3 style="font-size:1.4em;text-align: center;">WEBphysiology Portfolio Plugin - Version 1.3.0 Release Notifications</h3>' . "\n";
			$html .= '				<p style="font-weight:bold; color:red;text-align:center;">!!! PLEASE NOTE - YOU NEED TO BE AWARE OF A SHORTCODE CHANGE !!!<br />Announced Shortcode Deprecation Enforced in this Release</p>' . "\n";
			$html .= '				<ol><li>The support for the &#91;Portfolio&#93; shortcode has been removed in this release.  The only supported shortcode for this plugin is &#91;webphysiology_portfolio&#93;.</li>' . "\n";
			$html .= '				<li>With this release you now have the ability to position the Portfolio description below the Portfolio Meta Data.  To do this just un-check the &quot;Display portfolio description before Portfolio meta fields&quot; checkbox to the right of &quot;Display portfolio description&quot; within the &quot;Labeling & Data Display&quot; section.</li></ol>' . "\n";
			$html .= '				<p>For a complete list of changes refer to the Readme.txt file in the WEBphysiology Portfolio plugin directory or the Change Log on the <a href="http://refr.us/wpport" target="_blank">WEBphysiology Portfolio</a> page.</p>' . "\n";
			$html .= "			</div>" . "\n";
			$html .= "		</div>" . "\n";
			
			$space = $spacer;
			
			break;
			
		case '1.2.7':
			
			$html .= '		<a class="alert_text" href="#v127_notes">IMPORTANT Version 1.2.7 release notes</a>' . "\n";
			$html .= '		<div style="display: none;">' . "\n";
			$html .= '			<div id="v127_notes" >' . "\n";
			$html .= '				<h3 style="font-size:1.4em;text-align: center;">WEBphysiology Portfolio Plugin - Version 1.2.7 Release Notifications</h3>' . "\n";
			$html .= '				<p style="font-weight:bold; color:red;text-align:center;">!!! PLEASE NOTE - YOU NEED TO BE AWARE OF A FEW CHANGES !!!<br />Announced Stylesheet Deprecation Enforced in this Release<br />ShrinkTheWeb.com Rolls Out Feature Changes for Non-Paying Customers<br /></p>' . "\n";
			$html .= '				<ol>' . "\n";
			$html .= '					<li>The "#portfolios" &lt;div&gt; ID wrapper has been removed from the stylesheet as it was replaced several versions back with the &lt;div&gt; ".webphysiology_portfolio" class.</li>' . "\n";
			$html .= '					<li>ShrinkTheWeb.com has changed their model for non-paying customers to not allow for local caching of thumbnails they generate. This also results in not being able to display these images in a thickbox. Non-Pro accounts also will generate a <a href="http://www.shrinktheweb.com/content/what-stw-preview-verification.html" target="_blank">STW Preview Verification</a> middle page when navigating to the underlying website. If you would like these features re-instated you will need to upgrade to a paid Pro level account. This also would give you the ability to generate website thumbnails for inner pages.</p><p><a href="http://www.shrinktheweb.com/content/updates-03272011-lock-account-enforced.html" target="_blank">Lock to Account Enforcement</a> also has been added</p><p>Additional details are available at <a href="http://www.shrinktheweb.com/content/major-overhaul-project-security-notice.html" target="_blank">ShrinkTheWeb.com</a></li>' . "\n";
			$html .= '					<li>Added Option settings to allow for not registering the Google served jQuery library or the more current Fancybox script.  This is to assist in dealing with contentions with other plugins that register these scripts.</li>' . "\n";
			$html .= '					<li>The &#91;portfolio&#93; shortcode will be deprecated in the near future.  The shortcode that should be used is &#91;webphysiology_portfolio&#93;.</li>' . "\n";
			$html .= '				</ol>' . "\n";
			$html .= '				<p>For a complete list of changes refer to the Readme.txt file in the WEBphysiology Portfolio plugin directory or the Change Log on the <a href="http://refr.us/wpport" target="_blank">WEBphysiology Portfolio</a> page.</p>' . "\n";
			$html .= "			</div>" . "\n";
			$html .= "		</div>" . "\n";
			
			$space = $spacer;
			
			break;
			
		case '1.2.4':
			
			$html .= '		<a class="alert_text" href="#v124_notes">IMPORTANT Version 1.2.4 release notes</a>' . "\n";
			$html .= '		<div style="display: none;">' . "\n";
			$html .= '			<div id="v124_notes" >' . "\n";
			$html .= '				<h3 style="font-size:1.4em;text-align: center;">WEBphysiology Portfolio Plugin - Version 1.2.4 Release Notifications</h3>' . "\n";
			$html .= '				<p style="font-weight:bold; color:red;text-align:center;">!!! PLEASE NOTE A FEW CHANGES THAT YOU NEED TO BE AWARE OF AS SOME DEPRECATION WILL BE COMING SOON  !!!<br /></p>' . "\n";
			$html .= '				<ol>' . "\n";
			$html .= "					<li>To proactively try and avoid future plugin contentions, the shortcode will be changing from [portfolio] to [webphysiology_portfolio].  The later is currently available. The former will go away in the near future, so, update your portoflio pages with the new code.</li>" . "\n";
			$html .= '					<li>If you are doing custom CSS work on the portfolio, be aware that back in version 1.2.0 we noted that the standard CSS that comes with the plugin was having the ID "#portfolio" selector changed to the class ".webphysiology_portfolio" selector.  The ID selector will be removed from the CSS in the next release.  You will not be affected if you have not customized any backend CSS.  If you have, just make certain you are not using "#portfolio".</li>' . "\n";
			$html .= '					<li>The WEBphysiology Portfolio settings have been moved out from under the Admin "Settings" menu and relabeled. The plugin configuration options are now labeled "Options" and are located under the Portfolio menu block.</li>' . "\n";
			$html .= '				</ol>' . "\n";
			$html .= '				<p style="font-size:1.2em;font-weight:bold;margin-top:10px;">1.2.4 Enhancements to be aware of:</p>' . "\n";
			$html .= '				<ol>' . "\n";
			$html .= '					<li>The custom post type has been changed from "Portfolio" to "webphys_portfolio". Reason #1 is that WP v3.1 has disallowed the use of uppercase characters in the custom post type name, which broke the plugin.  "webphys_" also was added to proactively try and avoid any contentions with other plugins and code.  When you upgraded to v1.2.4 of this plugin the Portfolio Post data was automagically updated to the new custom post type value "webphys_portfolio".</li>' . "\n";
			$html .= "					<li>Four new shortcode parameters have been added to allow for additional functionality:<br />" . "\n";
			$html .= '					<p style="margin-left:10px;"><span style="font-weight:bold;">id</span> : this string parameter allows you to specify a &lt;div&gt; ID that will wrap the data returned by the shortcode. This will provide the ability to style a given instance of the shortcode differently from another instance.<br />' . "\n";
			$html .= '					    <span style="font-weight:bold;">per_page</span> : this numeric parameter, if specified, will override the Option setting and allow you to, on a particular instance of the shortcode, specify how many portfolio items will be included per page for that instance of the shortcode.<br />' . "\n";
			$html .= '			    		<span style="font-weight:bold;">thickbox</span> : this boolean (true/false) parameter will let you override the Option setting, allowing you to open items in a thickbox or direct the click to the specified URL<br />' . "\n";
			$html .= '			      		<span style="font-weight:bold;">credit</span> : this boolean (true/false) parameter will let you override the Option setting, allowing you to only display the plugin credit where you want to. specific reason for this parm is to allow you, in instances where you have more than one [webphysiology_portfolio] shortcode on a page, to just display the credit on one instance.</li>' . "\n";
			$html .= "					<li>YouTube and Vimeo are now supported within the Fancybox thickbox interface.  If you enter a Portfolio Web Page URL for a video hosted on one of these sites, and you have set the WEBphysiology Portfolio options to display the image in a thickbox, then the video will be displayed in the thickbox as opposed to sending you to Vimeo/Youtube.  The required format for these URLs are as follows:<br />" . "\n";
			$html .= '					<p style="margin-left:10px;"><span style="font-weight:bold;">Youtube</span>:  http://www.youtube.com/watch?v=<span style="font-style:italic;">071KqJu7WVo</span><br />' . "\n";
			$html .= '				    	  <span style="font-weight:bold;">Vimeo</span>:  http://vimeo.com/<span style="font-style:italic;">16756306</span><br /></li>' . "\n";
			$html .= '				</ol>' . "\n";
			$html .= "				<p>For a complete list of changes refer to the Readme.txt file in the WEBphysiology Portfolio plugin directory.</p>" . "\n";
			$html .= "			</div>" . "\n";
			$html .= "		</div>" . "\n";
			
			$space = $spacer;
			
			break;
			
		case 'shortcode':
			
			$html .= '		<div style="margin-bottom: 10px;"><a class="iframe_msg" href="' . plugin_dir_url(__FILE__) . 'shortcode_help.html">Shortcode Help</a></div>' . "\n";		
			$space = $spacer;
			
			break;
			
		case 'release_notes':
			
			$html .= '		<div><a href="http://webphysiology.com/plugins/webphysiology-portfolio-plugin/#updates" title="WEBphysiology Portfolio Release Notes" target="_blank">All Release Notes</a></div>' . "\n";		
			$space = $spacer;
			
			break;
		
	}
	
	if ($space) {
		$html .= '<p style="margin:0;padding:0;line-height:1em;">&nbsp;</p>';
	}
	
	return $html;
	
}

// test for whether a hook should be applied or not
function portfolio_apply_hook( $query, $hook ) {
	return (
		// We have query vars
		property_exists( $query, 'query_vars' ) &&
		// The query is for webphysiology portfolio
		( array_key_exists( 'post_type', $query->query_vars ) && $query->query_vars['post_type'] == 'webphys_portfolio' )
	);
}

// add "portfoliotype" into the recognized set of query variables
function portfolio_queryvars( $qvars ) {
	$qvars[] = 'portfoliotype';
	return $qvars;
}

// augment the JOIN if a Portfolio Type is part of the search
function portfolio_search_join( $join, $query ) {
	
	global $wpdb, $wp_query;
	
	// if the portfolio type has been defined in the search vars
	if ( portfolio_apply_hook( $query, 'join' ) ) {
		
// asterisk 12/23/2011  1.4.0  -  commented out as I think it's unnecessary and was getting some bad SQL statements by not always including the join
/*
		// if the JOIN statement currently is not empty append a 'LEFT OUTER JOIN'
		if ( ! empty($join) ) {
			$join .= " LEFT OUTER JOIN ";
		}
*/
		
		// add the join to the wp_postmeta table for meta records that are of a Portfolio Type
		$join .=  " LEFT OUTER JOIN " . $wpdb->prefix . "postmeta AS port ON (" . $wpdb->posts . ".ID = port.post_id AND port.meta_key = '_portfolio_type') ";
		
	}
	
	return $join;
}

// augment the WHERE clause if a Portfolio Type is part of the search
function portfolio_search_where( $where, $query ) {
	
	global $wp_query;
	
	if ( is_admin() ) { return $where; }

	// if the portfolio type has been defined in the search vars
	if ( portfolio_apply_hook( $query, 'where' ) ) {
		
		// clear out our portfolio type buckets
		$IN = "";
		$OUT = "";
		
		$types = get_query_var('portfoliotype');
		
		// place the portfolio types into an array so that it is easier to process them
		$ptypes = explode(",",$types);
		
		// loop through the portfolio array
		foreach ($ptypes as $value) {
			
			// if the portfolio type is not lead by a minus sign then add it to the IN bucket
			if (substr($value, 0, 1) != '-') {
				if ( !empty($IN) ) $IN .= ",";
				$IN .= $value;
			} else { // otherwise, add it to the OUT bucket
				if ( !empty($OUT) ) $OUT .= ",";
				$OUT .= substr($value, 1);
			}
		}
		
		// if some of the portfolio types were flagged for inclusion then add an IN() clause
		if ( !empty($IN) ) {
			if (!empty($where)) $where .= " AND ";
			$where .= " port.meta_value IN ('" . str_replace(',', "','", $IN) . "')";
		}
		
		// if some of the portfolio types were flagged for exclusion then add a NOT IN() clause
		if ( !empty($OUT) ) {
			if (!empty($where)) $where .= " AND ";
			$where .= " port.meta_value NOT IN ('" . str_replace(',', "','", $OUT) . "')";
		}
		
	}
	
	return $where;
}

function get_click_behavior($cb) {
	
	global $click_behavior;
	
	if ( ( strtolower($cb) == 'true' ) || ( $cb == 1 ) || ( strtolower($cb) == 'litebox' ) ) {
		$click_behavior = "litebox";
	} else {
		$click_behavior = "nav2page";
	}
	
}

/* define the Portfolio ShortCode and set defaults for available arguments */
function portfolio_loop($atts, $content = null) {
	
	if ( is_admin() ) { return null; }
	
	global $for;
	global $portfolio_types;
	global $portfolio_output;
	global $num_per_page;
	global $display_the_credit;
	
	clear_globals();
	
	$max_nav_spread = '';
	$portfolio_type = '';
	$on_click = get_option('webphysiology_portfolio_image_click_behavior');
	$showme_the_credit = get_option('webphysiology_portfolio_display_credit');
	
	extract( shortcode_atts( array(
      'max_nav_spread' => 5,
	  'portfolio_type' => '',
	  'thickbox' => $on_click,
	  'id' => '',
	  'per_page' => '',
      'credit' => $showme_the_credit), $atts ) );
	
	get_click_behavior($thickbox);
	
	if ( ( strtolower($credit) == 'true' ) || ( $credit == 1 ) ) {
		$display_the_credit = "True";
	} else {
		$display_the_credit = "False";
	}
	
	$for = $max_nav_spread;
	$portfolio_types = $portfolio_type;
	
	if ( !empty($per_page) && is_numeric($per_page) ) {
		$num_per_page = $per_page;
	} else {
		$num_per_page = get_option('webphysiology_portfolio_items_per_page');
	}
	
	if ( !empty($id) ) {
		$portfolio_output = '<div id="' . $id . '">';
	}
	
	if ( !empty($content) ) {
		$portfolio_output .= '<div class="webphysiology_portfolio_page_content">' . $content . '</div>';
	}
	
	include('loop-portfolio.php');
	
	if ( !empty($id) ) {
		$portfolio_output .= '</div>';
	}
	
	return $portfolio_output;
	
}


/* clear out the shortcode values otherwise they get re-used if more than one shortcode is used per page */
function clear_globals() {
	
	global $wp_query;
	global $for;
	global $portfolio_types;
	global $click_behavior;
	global $portfolio_output;
	global $num_per_page;
	global $display_the_credit;
	
	$wp_query->query_vars['portfoliotype'] = '';
	$for = '';
	$portfolio_types = '';
	$click_behavior = '';
	$portfolio_output = '';
	$num_per_page = '';
	$display_the_credit = '';
	
}


// define a custom Portfolio Type taxonomy and populate it
function create_portfolio_type_taxonomy() {
	
	if (!taxonomy_exists('portfolio_type')) {
		
		$labels = array(
				
			'name'              => __( 'Portfolio Types', 'Portfolio' ),
			'singular_name'     => __( 'Portfolio Type', 'Portfolio' ),
			'search_items'      => __( 'Search Portfolio Types', 'Portfolio' ),
			'popular_items'     => __( 'Popular Portfolio Types', 'Portfolio' ),
			'all_items'         => __( 'All Portfolio Types', 'Portfolio' ),
			'parent_item'       => __( 'Parent Portfolio Type', 'Portfolio' ),
			'parent_item_colon' => __( 'Parent Portfolio Type:', 'Portfolio' ),
			'edit_item'         => __( 'Edit Portfolio Type', 'Portfolio' ),
			'update_item'       => __( 'Update Portfolio Type', 'Portfolio' ),
			'add_new_item'      => __( 'Add New Portfolio Type', 'Portfolio' ),
			'new_item_name'     => __( 'New Portfolio Type Name', 'Portfolio' ),
			'menu_name'         => __( 'Portfolio Types', 'Portfolio' )
				
		);
		
		register_taxonomy('portfolio_type', 
						  'webphys_portfolio',
						  array(	'hierarchical' => false, 
									'labels' => $labels,
									'show_tagcloud' => true,
									'public' => true,
									'show_in_nav_menus' => true,
									'show_ui' => true,
									'query_var' => 'portfolio_type',
									'rewrite' => array( 'slug' => 'portfolio_type')));
	 	
		// if there are no Portfolio Type terms, add a default term
		if (count(get_terms('portfolio_type', 'hide_empty=0')) == 0) {
			wp_insert_term('Default', 'portfolio_type');
		}
	}
	
	if (!taxonomy_exists('webphys_portfolio_tag')) {
		
		$labels = array(
				
			'name'              => __( 'Portfolio Tags', 'Portfolio' ),
			'singular_name'     => __( 'Portfolio Tag', 'Portfolio' ),
			'search_items'      => __( 'Search Portfolio Tags', 'Portfolio' ),
			'popular_items'     => __( 'Popular Portfolio Tags', 'Portfolio' ),
			'all_items'         => __( 'All Portfolio Tags', 'Portfolio' ),
			'parent_item'       => __( 'Parent Portfolio Tag', 'Portfolio' ),
			'parent_item_colon' => __( 'Parent Portfolio Tag:', 'Portfolio' ),
			'edit_item'         => __( 'Edit Portfolio Tag', 'Portfolio' ),
			'update_item'       => __( 'Update Portfolio Tag', 'Portfolio' ),
			'add_new_item'      => __( 'Add New Portfolio Tag', 'Portfolio' ),
			'new_item_name'     => __( 'New Portfolio Tag Name', 'Portfolio' ),
			'menu_name'         => __( 'Portfolio Tags', 'Portfolio' )
				
		);
		
		register_taxonomy('webphys_portfolio_tag', 
						  'webphys_portfolio',
						  array(	'hierarchical' => false, 
									'labels' => $labels,
									'show_tagcloud' => true,
									'public' => true,
									'show_in_nav_menus' => true,
									'show_ui' => true,
									'query_var' => 'webphys_portfolio_tag',
									'rewrite' => array( 'slug' => 'webphys_portfolio_tag')));
	 	
	}
}


// extend standard WordPress tag cloud to include Portfolio tags
function webphys_portfolio_tag_cloud_hijack($args = array()) {
	
	$include = get_option('webphysiology_portfolio_include_portfolio_tags_in_tag_cloud');
	
	if ($include == 'True') {
		
		if (is_array($args['taxonomy'])) {
			array_push($args['taxonomy'],"webphys_portfolio_tag");
		} else {
			$args['taxonomy'] = array($args['taxonomy'],'webphys_portfolio_tag');
		}
		
	}
	
	return $args;
	
}

if ( ! is_admin() ) {
	add_filter('widget_tag_cloud_args', 'webphys_portfolio_tag_cloud_hijack', 90);
}

/**
 * webphys_portfolio_widget Class
 * 
 * add a Portfolio tag cloud widget
 */
class webphys_portfolio_widget extends WP_Widget {
	
	/** constructor */
	function __construct() {
		parent::WP_Widget( /* Base ID */'webphys_portfolio_widget', /* Name */'Portfolio Tag Cloud', array( 'description' => 'A WEBphysiology Widget' ) );
	}
	
	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		
		extract( $args );
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
		
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		wp_tag_cloud(array('taxonomy' => 'webphys_portfolio_tag'));
		
		echo $after_widget;
		
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
		
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		} else {
			$title = __( 'Portfolio Tags', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php 
	}

} // class webphys_portfolio_widget

// register webphys_portfolio_widget tag cloud widget
add_action( 'widgets_init', create_function( '', 'register_widget("webphys_portfolio_widget");' ) );

function get_webphys_port_options() {
	
	$options = array(
		"display_portfolio_title" => get_option( 'webphysiology_portfolio_display_portfolio_title' ),
		"display_portfolio_desc" => get_option( 'webphysiology_portfolio_display_portfolio_desc' ),
		"display_desc_first" => get_option( 'webphysiology_portfolio_display_desc_first' ),
		"display_portfolio_type" => get_option( 'webphysiology_portfolio_display_portfolio_type' ),
		"display_created_on" => get_option( 'webphysiology_portfolio_display_createdate' ),
		"display_clientname" => get_option( 'webphysiology_portfolio_display_clientname' ),
		"display_siteurl" => get_option( 'webphysiology_portfolio_display_siteurl' ),
		"display_tech" => get_option( 'webphysiology_portfolio_display_tech' ),
		"detail_labels" => get_option( 'webphysiology_portfolio_display_labels' ),
		"gridstyle" => get_option( 'webphysiology_portfolio_gridstyle' ),
		"sort_numerically" => get_option( 'webphysiology_portfolio_sort_numerically' ),
		"url_target" => get_option( 'webphysiology_portfolio_anchor_click_behavior' ),
		"img_click_behavior" => get_option( 'webphysiology_portfolio_image_click_behavior' ),
		"thumbnail_generator" => get_option( 'webphysiology_portfolio_thumbnail_generator' ),
		"legacy_even_odd_class" => get_option( 'webphysiology_portfolio_legacy_even_odd_class' )
	);
	
	return $options;
}

// if we are on a post or a page with the webphysiology_portfolio shortcode in the content then carry off certain actions
function has_shortcode() {
	
	$cont = "";
	
	global $post;
	
	if ( is_single() || is_page() ) {
		$cont = getPageContent($post->ID);
	}
	
	// if the webphysiology_portfolio shortcode is within the content take the actions indicated
	if ( strpos($cont, "webphysiology_portfolio") > 0 ) {
		add_action('wp_print_styles', 'prepare_portfolio');
	} else {
		if ($_SERVER["REMOTE_ADDR"] == '127.0.0.1') { // asterisk - when running locally this was needed to avert a non-ending re-direct
			remove_filter('template_redirect', 'redirect_canonical');
		}
//		add_action('template_redirect', 'use_single_portfolio_page_template');
		add_filter('template_include', 'webphys_portfolio_template_include');
//		add_filter('template_include', 'webphys_portfolio_tag_template_include');
		add_action('wp_print_styles', 'prepare_single_portfolio');
	}
	
}

function webphys_portfolio_template_include($incFile) {
	
	if ( is_tax('webphys_portfolio_tag') ) {
		$incFile = webphys_portfolio_tag_template_include($incFile);
	} elseif ( get_post_type() == 'webphys_portfolio' ) {
		$incFile = webphys_portfolio_post_template_include($incFile);
	}
	
	return $incFile;
	
}

if(!function_exists('webphys_portfolio_tag_template_include')) {
function webphys_portfolio_tag_template_include($incFile) {
	
	global $wp_query;
	
	if (have_posts()) {
		$file = get_stylesheet_directory() . '/archive-webphys_portfolio_tag.php';
		if ( ! file_exists($file) ) {
			$file = plugin_dir_path(__FILE__) . 'archive-webphys_portfolio_tag.php';
		}
		if (file_exists($file)) {
			$incFile = $file;
		}
	} else {
		$wp_query->is_404 = true;
	}
	
	return $incFile;
}
}

if(!function_exists('webphys_portfolio_post_template_include')) {
function webphys_portfolio_post_template_include($incFile) {
	
	global $wp_query;
	
	if (is_single()) {
		add_action('wp_print_styles', 'prepare_portfolio');
		$file = get_stylesheet_directory() . '/single-webphys_portfolio.php';
		if ( ! file_exists($file) ) {
			$file = plugin_dir_path(__FILE__) . 'single-webphys_portfolio.php';
		}
		if (file_exists($file)) {
			$incFile = $file;
		}
	} else {
		$wp_query->is_404 = true;
	}
	
	return $incFile;
}
}

if(!function_exists('prepare_portfolio')) {
function prepare_portfolio() {
		use_googleapis_jquery();
		use_fancybox_styling();
		set_base_portfolio_css();
		deregister_competing_plugin_styles();
		add_action('wp_head', 'set_portfolio_css'); // we are already in wp_print_styles, so, calling this in wp_head
		add_action('wp_print_scripts', 'set_stw_nopro_script');
		add_action('wp_print_scripts', 'deregister_competing_plugin_scripts');
}
}

if(!function_exists('prepare_single_portfolio')) {
function prepare_single_portfolio() {
	$js = plugins_url('css/portfolio_single.css', __FILE__);
	$js = clear_pre_content($js);
	wp_register_style('webphysiology_single_portfolio', $js);
	wp_enqueue_style('webphysiology_single_portfolio');
}
}

if(!function_exists('getPageContent')) {
function getPageContent($pageId) {
	if(!is_numeric($pageId)) {
		return;
	}
	global $wpdb;
	$sql_query = 'SELECT DISTINCT * FROM ' . $wpdb->posts .
	' WHERE ' . $wpdb->posts . '.ID=' . $pageId;
	$posts = $wpdb->get_results($sql_query);
	if(!empty($posts)) {
		foreach($posts as $post) {
			return nl2br($post->post_content);
		}
	}
}
}

// smart jquery inclusion
function use_googleapis_jquery() {
	
	// as long as no one overrode this plugin's standard setting of loading jQuery from Google
	$opt_val_skip_jQuery_register = strtolower(get_option('webphysiology_portfolio_skip_jQuery_register'));
	if ( $opt_val_skip_jQuery_register == 'false' ) {
		//https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
		wp_enqueue_script('jquery');
	}
}

function use_fancybox_styling() {
	
	$skip_fancybox_jquery_register = strtolower(get_option('webphysiology_portfolio_skip_fancybox_register'));
	if ( $skip_fancybox_jquery_register == 'false' ) {
		jquery_fancybox_styles();
		jquery_fancybox_init();
	}
	add_action('wp_footer', 'fancy_script');
}

function deregister_competing_plugin_styles() {
	wp_deregister_style('thickbox');
}

function deregister_competing_plugin_scripts() {
	wp_deregister_script('thickbox');
}

// add scripts and styling needed for the lightbox functionality
function jquery_fancybox_init () {
	$js = plugins_url('scripts/fancybox/', __FILE__);
	$js = clear_pre_content($js);
	wp_register_script('fancybox', $js . 'jquery.fancybox-1.3.4.pack.js', array('jquery'));
	wp_enqueue_script('fancybox');
	wp_register_script('mousewheel', $js . 'jquery.mousewheel-3.0.4.pack.js');
	wp_enqueue_script('mousewheel');
}

function jquery_fancybox_styles() {
	$js = plugins_url('scripts/fancybox/jquery.fancybox-1.3.4.css', __FILE__);
	$js = clear_pre_content($js);
	wp_register_style('fancybox_css', $js);
	wp_enqueue_style('fancybox_css');
}

function admin_settings_jquery() {
	
	if ( is_admin() ) {
		
	echo ( "\n" . '<script type="text/javascript">' . "\n");
	echo ( "<!--" . "\n");
	echo ( "\n");
	echo ( "jQuery.noConflict();" . "\n");
	echo ( "\n");
	echo ( "jQuery(document).ready(function() {" . "\n");
	echo ( "\n");
	echo ( '	jQuery("#webphys_portfolio_tim_gen").click(function() {' . "\n");
	echo ( "		jQuery('#tt_settings').show();" . "\n");
	echo ( "		jQuery('#stw_settings').hide();" . "\n");
	echo ( "		jQuery('#pp_settings').hide();" . "\n");
//	echo ( "		jQuery('#stw_settings :input').attr('disabled', true);" . "\n");
//	echo ( "		jQuery('#pp_settings :input').attr('disabled', true);" . "\n");
	echo ( "		return true;" . "\n");
	echo ( "	});" . "\n");
	echo ( '	jQuery("#webphys_portfolio_stw_gen").click(function() {' . "\n");
	echo ( "		jQuery('#tt_settings').hide();" . "\n");
	echo ( "		jQuery('#stw_settings').show();" . "\n");
	echo ( "		jQuery('#pp_settings').hide();" . "\n");
//	echo ( "		jQuery('#stw_settings :input').removeAttr('disabled', true);" . "\n");
//	echo ( "		jQuery('#pp_settings :input').attr('disabled', true);" . "\n");
	echo ( "		return true;" . "\n");
	echo ( "	});" . "\n");
	echo ( '	jQuery("#webphys_portfolio_pp_gen").click(function() {' . "\n");
	echo ( "		jQuery('#tt_settings').hide();" . "\n");
	echo ( "		jQuery('#stw_settings').hide();" . "\n");
	echo ( "		jQuery('#pp_settings').show();" . "\n");
//	echo ( "		jQuery('#stw_settings :input').attr('disabled', true);" . "\n");
//	echo ( "		jQuery('#pp_settings :input').removeAttr('disabled', true);" . "\n");
	echo ( "		return true;" . "\n");
	echo ( "	});" . "\n");
	echo ( "\n");
	echo ( "});" . "\n");
	echo ( "-->" . "\n");
	echo ( '</script>' . "\n");
	
	}
}

function fancy_script() {
	echo ( "\n" . '<script type="text/javascript">' . "\n");
	echo ( "<!--" . "\n");
	echo ( "\n");
	echo ( "jQuery.noConflict();" . "\n");
	echo ( "\n");
	echo ( "jQuery(document).ready(function() {" . "\n");
	echo ( "\n");
	echo ( '	jQuery("a.thickbox").fancybox({' . "\n");
	echo ( "			'overlayOpacity'		: 0.95," . "\n");
	echo ( "			'overlayColor'			: '#333'," . "\n");
	echo ( "			'transitionIn'			: 'fade'," . "\n");
	echo ( "			'transitionOut'			: 'fade'," . "\n");
	echo ( "			'speedIn'				: 350," . "\n");
	echo ( "			'speedOut'				: 350," . "\n");
	echo ( "			'hideOnContentClick'	: true," . "\n");
	echo ( "			'href'					: this.src," . "\n");
	echo ( "			'showCloseButton'		: true," . "\n");
	echo ( "			'titleShow'				: false" . "\n");
	echo ( "	});" . "\n");
	echo ( "\n");
	echo ( '	jQuery("a.alert_text").fancybox({' . "\n");
	echo ( "			'overlayOpacity'		: 0.95," . "\n");
	echo ( "			'overlayColor'			: '#333'," . "\n");
	echo ( "			'speedIn'				: 350," . "\n");
	echo ( "			'speedOut'				: 350," . "\n");
	echo ( "			'hideOnContentClick'	: false," . "\n");
	echo ( "			'transitionIn'			: 'fade'," . "\n");
	echo ( "			'transitionOut'			: 'fade'," . "\n");
	echo ( "			'href'					: this.href," . "\n");
	echo ( "			'showCloseButton'		: true," . "\n");
	echo ( "			'titleShow'				: false" . "\n");
	echo ( "	});" . "\n");
	echo ( "\n");
	if ( is_admin() ) {
	echo ( '	jQuery("a.webphysiology_newsletter").click(function() {' . "\n");
	echo ( '		jQuery.fancybox({' . "\n");
	echo ( "			'overlayOpacity'		: 0.95," . "\n");
	echo ( "			'overlayColor'			: '#333'," . "\n");
	echo ( "			'speedIn'				: 350," . "\n");
	echo ( "			'speedOut'				: 350," . "\n");
	echo ( "			'width'					: 264," . "\n");
	echo ( "			'height'				: 273," . "\n");
	echo ( "			'autoScale'				: true," . "\n");
	echo ( "			'transitionIn'			: 'fade'," . "\n");
	echo ( "			'transitionOut'			: 'fade'," . "\n");
	echo ( "			'title'					: this.title," . "\n");
	echo ( "			'showCloseButton'		: true," . "\n");
	echo ( "			'href'					: 'http://refr.us/wpnews'," . "\n");
	echo ( "			'titleShow'				: false," . "\n");
	echo ( "			'scrolling'				: 'no'," . "\n");
	echo ( "			'beforeSubmit'			: 'mc_beforeForm'," . "\n");
	echo ( "			'success'				: 'mc_success'," . "\n");
	echo ( "			'type'					: 'iframe'," . "\n");
	echo ( "			'onClosed'				: function() { " . "\n");
	echo ( "										jQuery('#mc_message').hide();" . "\n");
	echo ( "										jQuery('#mc_signup_form').each(function(){" . "\n");
	echo ( "											this.reset();" . "\n");
	echo ( "										});" . "\n");
	echo ( "									  }" . "\n");
	echo ( "		});" . "\n");
	echo ( "		return false;" . "\n");
	echo ( "	});" . "\n");
	echo ( "\n");
	}
	echo ( '	jQuery("a.vimeo").click(function() {' . "\n");
	echo ( '		jQuery.fancybox({' . "\n");
	echo ( "			'overlayOpacity'		: 0.95," . "\n");
	echo ( "			'overlayColor'			: '#333'," . "\n");
	echo ( "			'speedIn'				: 350," . "\n");
	echo ( "			'speedOut'				: 350," . "\n");
	echo ( "			'padding'				: 10," . "\n");
	echo ( "			'autoScale'				: false," . "\n");
	echo ( "			'transitionIn'			: 'fade'," . "\n");
	echo ( "			'transitionOut'			: 'fade'," . "\n");
	echo ( "			'title'					: this.title," . "\n");
	echo ( "			'titleShow'				: false," . "\n");
	echo ( "			'showCloseButton'		: true," . "\n");
	echo ( "			'width'					: 680," . "\n");
	echo ( "			'height'				: 495," . "\n");
	echo ( "			'href'					: this.href.replace(new RegExp(\"([0-9])\",\"i\"),'moogaloop.swf?clip_id=$1')," . "\n");
	echo ( "			'type'					: 'swf'" . "\n");
	echo ( "		});" . "\n");
	echo ( "		return false;" . "\n");
	echo ( "	});" . "\n");
	echo ( "\n");
	echo ( '	jQuery("a.youtube").click(function() {' . "\n");
	echo ( '		jQuery.fancybox({' . "\n");
	echo ( "			'overlayOpacity'		: 0.95," . "\n");
	echo ( "			'overlayColor'			: '#333'," . "\n");
	echo ( "			'speedIn'				: 350," . "\n");
	echo ( "			'speedOut'				: 350," . "\n");
	echo ( "			'padding'				: 10," . "\n");
	echo ( "			'autoScale'				: false," . "\n");
	echo ( "			'transitionIn'			: 'fade'," . "\n");
	echo ( "			'transitionOut'			: 'fade'," . "\n");
	echo ( "			'title'					: this.title," . "\n");
	echo ( "			'titleShow'				: false," . "\n");
	echo ( "			'showCloseButton'		: true," . "\n");
	echo ( "			'width'					: 680," . "\n");
	echo ( "			'height'				: 495," . "\n");
	echo ( "			'href'					: this.href.replace(new RegExp(\"watch\\\?v=\", \"i\"), 'v/')," . "\n");
	echo ( "			'type'					: 'swf'," . "\n");
	echo ( "			'swf'					: {" . "\n");
	echo ( "			'wmode'					: 'transparent'," . "\n");
	echo ( "			'allowfullscreen'		: 'false'" . "\n");
	echo ( "			}" . "\n");
	echo ( "		});" . "\n");
	echo ( "		return false;" . "\n");
	echo ( "	});" . "\n");
if (1==0) {
	if (1==0) {
		echo ( "\n");
		echo ( '	jQuery("a.swflash").click(function() {' . "\n");
		echo ( '		jQuery.fancybox({' . "\n");
		echo ( "			'overlayOpacity'		: 0.95," . "\n");
		echo ( "			'overlayColor'			: '#333'," . "\n");
		echo ( "			'speedIn'				: 350," . "\n");
		echo ( "			'speedOut'				: 350," . "\n");
		echo ( "			'padding'				: 10," . "\n");
		echo ( "			'autoScale'				: false," . "\n");
		echo ( "			'transitionIn'			: 'fade'," . "\n");
		echo ( "			'transitionOut'			: 'fade'," . "\n");
		echo ( "			'title'					: this.title," . "\n");
		echo ( "			'titleShow'				: false," . "\n");
		echo ( "			'showCloseButton'		: true," . "\n");
		echo ( "			'width'					: 680," . "\n");
		echo ( "			'height'				: 495," . "\n");
		echo ( "			'href'			: 'http://nononsense.loc/wpb/wp-content/plugins/webphysiology-portfolio/scripts/mediaplayer-viral/player.swf?' & this.href," . "\n");
		echo ( "			'type'					: 'swf'," . "\n");
		echo ( "			'swf'					: {" . "\n");
		echo ( "			'wmode'					: 'transparent'," . "\n");
		echo ( "			'allowfullscreen'		: 'false'" . "\n");
		echo ( "			}" . "\n");
		echo ( "		});" . "\n");
		echo ( "		return false;" . "\n");
		echo ( "	});" . "\n");
	}
	if (1==1) {
		echo ( "\n");
		echo ( '	jQuery("a.swflash").fancybox({' . "\n");
		echo ( "			'overlayOpacity'		: 0.95," . "\n");
		echo ( "			'overlayColor'			: '#333'," . "\n");
		echo ( "			'overlayShow'			: true," . "\n");
		echo ( "			'speedIn'				: 350," . "\n");
		echo ( "			'speedOut'				: 350," . "\n");
		echo ( "			'hideOnContentClick'	: true," . "\n");
		echo ( "			'showCloseButton'		: true," . "\n");
		echo ( "			'titleShow'				: false," . "\n");
		echo ( "			'transitionIn'			: 'fade'," . "\n");
		echo ( "			'transitionOut'			: 'fade'," . "\n");
		echo ( "			'width'					: 680," . "\n");
		echo ( "			'height'				: 495," . "\n");
	//	echo ( "			'href'			: 'http://nononsense.loc/wpb/wp-content/plugins/webphysiology-portfolio/scripts/mediaplayer-viral/player.swf?' & this.href," . "\n");
		echo ( "			'autoScale'				: true," . "\n");
		echo ( "			'type'					: 'flash'" . "\n");
		echo ( "	});" . "\n");
		echo ( "\n");
	}
}
	echo ( '	jQuery("a.iframe_msg").fancybox({' . "\n");
	echo ( "			'overlayOpacity'		: 0.95," . "\n");
	echo ( "			'overlayColor'			: '#333'," . "\n");
	echo ( "			'speedIn'				: 350," . "\n");
	echo ( "			'speedOut'				: 350," . "\n");
	echo ( "			'hideOnContentClick'	: true," . "\n");
	echo ( "			'showCloseButton'		: true," . "\n");
	echo ( "			'titleShow'				: false," . "\n");
	echo ( "			'transitionIn'			: 'fade'," . "\n");
	echo ( "			'transitionOut'			: 'fade'," . "\n");
	echo ( "			'width'					: 680," . "\n");
	echo ( "			'height'				: 495," . "\n");
	echo ( "			'autoScale'				: true," . "\n");
	echo ( "			'type'					: 'iframe'" . "\n");
	echo ( "	});" . "\n");
	echo ( "\n");
	echo ( '});' . "\n");
	echo ( "\n");
//	echo ( '	jQuery("a.webcast-tv").click(function() {' . "\n");
//	echo ( '		jQuery.fancybox({' . "\n");
//	echo ( "			'overlayOpacity'	: 0.95," . "\n");
//	echo ( "			'overlayColor'	: '#333'," . "\n");
//	echo ( "			'speedIn'		: 350," . "\n");
//	echo ( "			'speedOut'		: 350," . "\n");
//	echo ( "			'padding'		: 10," . "\n");
//	echo ( "			'autoScale'		: false," . "\n");
//	echo ( "			'transitionIn'	: 'fade'," . "\n");
//	echo ( "			'transitionOut'	: 'fade'," . "\n");
//	echo ( "			'title'			: this.title," . "\n");
//	echo ( "			'titleShow'		: false," . "\n");
//	echo ( "			'showCloseButton'	: true," . "\n");
//	echo ( "			'width'			: 680," . "\n");
//	echo ( "			'height'		: 495," . "\n");
//	echo ( "			'href'					: this.href.replace(new RegExp(\"watch\\\?v=\", \"i\"), 'v/')," . "\n");
//	echo ( "			'type'			: 'swf'" . "\n");
//	echo ( "		});" . "\n");
//	echo ( "		return false;" . "\n");
//	echo ( "	});" . "\n");
//	echo ( "\n");

//	echo ( '	jQuery("a.youtube").click(function() {' . "\n");
//	echo ( 'jQuery(document).ready(function() {' . "\n");
//	echo ( "    jQuery('#webphysiology_newsletter').trigger('click');" . "\n");
//	echo ( '});' . "\n");

	echo ( "-->" . "\n");
	echo ( '</script>' . "\n");
}

// Grab the Portfolio image for the current Portfolio in the loop
if ( ! function_exists( 'get_Loop_Site_Image' ) ) :
function get_Loop_Site_Image() {
	
	global $click_behavior;
	
	$anchor_open = '';
	$anchor_close = '';
	$class = '';
	$stw = 'false';
	$stw_pro = 'false';
	$pp = 'false';
	$pp_pro = 'false';
	$continue = 'false';
	$generator = get_option( 'webphysiology_portfolio_thumbnail_generator' );
	
	// how are thumbnails being generated?
	switch ($generator) {
		case 'stw':
			$stw = 'true';
			$stw_pro = strtolower(get_option( 'webphysiology_portfolio_use_stw_pro' ));
			break;
		case 'pp':
			$pp = 'true';
//asterisk			$pp_pro = 'true';  // Maybe someday
			break;
	}
	
//	$stw = strtolower(get_option( 'webphysiology_portfolio_use_stw' ));
//	$stw_pro = strtolower(get_option( 'webphysiology_portfolio_use_stw_pro' ));
	$target = get_option( 'webphysiology_portfolio_anchor_click_behavior' );
	$opt_val_img_width = get_option( 'webphysiology_portfolio_image_width' );
	$missing_img_url = get_option( 'webphysiology_portfolio_missing_image_url' );
	
    $full_size_img_url = get_post_meta(get_the_ID(), "_imageurl", true);
	$img_url = str_replace(content_url(), "", $full_size_img_url);
    $site_url = get_post_meta(get_the_ID(), "_siteurl", true);
	
	$supported_video = is_supported_video($site_url);
	
	if ( empty($opt_val_img_width) ) { $opt_val_img_width = '150'; }
	
	if ( empty($target) || ($target == "False") ) {
		$target = '';
	} else {
		$target = ' target="_blank"';
	}
	
	if ( $stw == "true" ) {
		
		require_once("scripts/stw/stw.php");
		
		if ( ( empty($img_url) ) && ( ! empty($site_url) ) ) {
			$non_external_service_full_size_img_url = $full_size_img_url;
			if ( $stw_pro == "true" ) {
				$full_size_img_url = AppSTW::getScaledThumbnail($site_url, 640, 480);
				$continue = 'true';
			} else {
				$full_size_img_url = AppSTW::getScaledThumbnail($site_url, 640, 480);
			}
		} else {
			$stw = "false";
		}
		
	} elseif ($pp == "true") {
		
		require_once("scripts/pp/pp.php");
		
		if ( ( empty($img_url) ) && ( ! empty($site_url) ) ) {
			$non_external_service_full_size_img_url = $full_size_img_url;
			if ( $pp_pro == "true" ) {
				$full_size_img_url = App_PgPkr::getScaledThumbnail($site_url, 640, 480);
				$continue = 'true';
			} else {
				$full_size_img_url = App_PgPkr::getScaledThumbnail($site_url, $opt_val_img_width);
				$anchor_open = '<a class="Portfolio-Link' . $fbclass . '" href="' . $site_url . '" title="' . the_title_attribute( 'echo=0' ) . '"' . $target . '>';
				$anchor_close = '</a>';
			}
		} else {
			$pp = "false";
		}
		
	}
	
	// If not using ShrinkTheWeb or PagePeeker and an image URL was assigned
	if ( ( $stw != "true" ) && ( $pp != "true" ) && ( ! empty($full_size_img_url) ) ) {
		$continue = 'true';
		$non_external_service_full_size_img_url = $full_size_img_url;
	}
	
	if ( ( ! empty($full_size_img_url) ) && ( $continue == 'true' ) ) {
		
		$img_url = clean_source($full_size_img_url);
		
		// if there was an issue with the image url
		if ( empty($img_url) ) { $full_size_img_url = ""; }
		
		// if the image url was cleaned and not cleared, check that it really exists
		if ( ($img_url != $full_size_img_url) && ( !empty($img_url)) ) {
			if (!file_exists(dirname ( __FILE__ ) . '/' . $img_url)) {
				if (!file_exists($img_url)) {
					$full_size_img_url = "";
				}
			}
		}
	}
	
	// if the image was not specified or was cleared due to issues, use the default empty image
	if ( empty($full_size_img_url) ) {
		
		$img_url = $missing_img_url;
		
		if ( empty($img_url) ) {
			$img_url = 'images/empty_window.png';
		}
		if ( ! empty($img_url) ) {
			$class = ' class="missing"';
			$continue = 'true';
		}
		
	}
	
	if ( $continue == 'true' ) {
		$img_url = clear_pre_content($img_url);
	}
	
	// if the portfolio is a supported video, it is being displayed in a litebox and there was no image associated, allow the litebox
	//   to still be used as the video hosting services supported will provide a thumbnail
	if ( ( $click_behavior == 'litebox' ) && ( $supported_video ) && ( $continue != 'true' ) ) { $continue = 'true'; }
	
	if ( ($click_behavior == 'litebox') && ($continue == 'true') && ( ( ! empty($full_size_img_url) ) || $supported_video ) ) {
		
		if ( $supported_video ) {
			
			$fbclass = get_video_class($site_url);
			$anchor_open = '<a class="Portfolio-Link' . $fbclass . '" href="' . $site_url . '" title="' . the_title_attribute( 'echo=0' ) . '"' . $target . '>';
			
			if ( empty($non_external_service_full_size_img_url) ) {
				$img_html = get_Video_Thumbnail($site_url, $img_url, $opt_val_img_width);
			} else {
				$img_html = esc_attr(plugin_dir_url(__FILE__) . 'scripts/imageresizer/thumbnail.php?src=' . $img_url . '&w=' . $opt_val_img_width . '&zc=1');
			};
			
		} else {
			
			$anchor_open = '<a class="Portfolio-Link thickbox" href="' . $full_size_img_url . '" title="' . the_title_attribute( 'echo=0' ) . '"' . $target . '>';
			$img_html = esc_attr(plugin_dir_url(__FILE__) . 'scripts/imageresizer/thumbnail.php?src=' . $img_url . '&w=' . $opt_val_img_width . '&zc=1');
			
		}
		
		$anchor_close = '</a>';
			
	} elseif ( ( $click_behavior == 'nav2page' ) && ( $supported_video ) ) {
		
		$anchor_open = '<a href="' . $site_url . '" title="' . the_title_attribute( 'echo=0' ) . '" class="Portfolio-Link"' . $target . '>';
		$anchor_close = '</a>';
		
		if ( empty($non_external_service_full_size_img_url) ) {
			$img_html = get_Video_Thumbnail($site_url, $img_url, $opt_val_img_width);
		} else {
			$img_html = esc_attr(plugin_dir_url(__FILE__) . 'scripts/imageresizer/thumbnail.php?src=' . $img_url . '&w=' . $opt_val_img_width . '&zc=1');
		};
		
	} elseif ( ( $click_behavior == 'nav2page' ) && ( ! empty($site_url) ) && ($continue == 'true') ) {
		
		$anchor_open = '<a href="' . $site_url . '" title="' . the_title_attribute( 'echo=0' ) . '" class="Portfolio-Link"' . $target . '>';
		$anchor_close = '</a>';
		$img_html = esc_attr(plugin_dir_url(__FILE__) . 'scripts/imageresizer/thumbnail.php?src=' . $img_url . '&w=' . $opt_val_img_width . '&zc=1');
		
	} elseif ( ( ! empty($img_url) ) && ($continue == 'true') ) {
				
		$img_html = esc_attr(plugin_dir_url(__FILE__) . 'scripts/imageresizer/thumbnail.php?src=' . $img_url . '&w=' . $opt_val_img_width . '&zc=1');
		
	}
	
	if ( ! empty($img_html) ) {
		$path = $anchor_open . '<img src="' . $img_html . '" alt="' . the_title_attribute('echo=0') . '"' . $class . ' width="' . $opt_val_img_width . '" />' . $anchor_close;
	} elseif ( ($continue == 'false') && ($stw == "true") ) {
		$path = $anchor_open . $full_size_img_url . $anchor_close;
	} elseif ( ($continue == 'false') && ($pp == "true") ) {
		$path = $anchor_open . $full_size_img_url . $anchor_close;
	} else {
		$path = $anchor_open . 'no image' . $anchor_close;
	}
	
	return $path;
}
endif;

// Check to see if the specified portfolio URL is a supportedd video URL
if ( ! function_exists( 'is_supported_video' ) ) :

function is_supported_video($siteurl) {
	
	// Currently supported video formats: Vimeo and YouTube
//	if ( ( strpos($siteurl, 'vimeo.com/') !== false ) || ( strpos($siteurl, 'youtube.com/watch') !== false ) || ( strpos($siteurl, 'blip.tv') !== false ) ) {
	if ( ( strpos($siteurl, 'vimeo.com/') !== false ) || ( strpos($siteurl, 'youtube.com/watch') !== false ) ) {
		return true;
	} else {
		return false;
	}
}

endif;

// clear the passed in path up to wp-content as some code and hosting providers don't play nicely with arguments containing http://www
if ( ! function_exists( 'clear_pre_content' ) ) :

function clear_pre_content($url) {
	
	$return = $url;

	$use_full_path = get_option('webphysiology_portfolio_use_full_path');
	
	if ( $use_full_path != 'True' ) {
		
		$pos = strpos($return, 'wp-content');
		
		if ( ! empty($pos) ) {
			$return = str_replace(substr($return, 0, strpos($return, 'wp-content') - 1), "", $return);
		}
		
	}
	
	return $return;
}

endif;


// Determine the class to assign to the anchor tag based upon the video provider
if ( ! function_exists( 'get_video_class' ) ) :

function get_video_class($siteurl) {
	if ( strpos($siteurl, 'vimeo') !== false ) {
		$fbclass = ' vimeo';
	} elseif ( strpos($siteurl, 'youtube.com/watch') !== false ) {
		$fbclass = ' youtube';
//	} elseif ( strpos($siteurl, 'blip.tv') !== false ) {
//		$fbclass = ' swflash';
//	} elseif ( strpos($siteurl, 'webcast-tv') !== false ) {
//		$fbclass = ' webcast-tv';
	} else {
		$fbclass = '';
	}
	return $fbclass;
}

endif;

// Get the thumbnail for the specified video
if ( ! function_exists( 'get_Video_Thumbnail' ) ) :

function get_Video_Thumbnail($vid, $stw, $img_width) {
	
	if ( strpos($vid, 'vimeo.com/') !== false ) {
		$thumb = str_replace("http://vimeo.com/","",$vid);
		if ( is_numeric($thumb) ) {
			$url = 'http://vimeo.com/api/v2/video/' . $thumb . '.php';
			$contents = @file_get_contents($url);
			$array = @unserialize(trim($contents));
			$img_url = $array[0]['thumbnail_large'];
		}
	} elseif ( strpos($vid, 'youtube.com/watch') !== false ) {
		$thumb = str_replace("www.youtube.com/watch?v=","i.ytimg.com/vi/",$vid);
		if ( strpos($thumb, "&feature=player_embedded") !== false ) {
			$img_url = str_replace("&feature=player_embedded","/hqdefault.jpg",$thumb);
		} else {
			$img_url = $thumb . "/hqdefault.jpg";
		}
	} else {
		$img_url = $vid;
	}
	
	if ( ( empty($img_url) ) && ( ! empty($stw) ) ) {
		if ( empty($img_width) ) {
			$img_width = get_option( 'webphysiology_portfolio_image_width' );
		}
		$img_url = esc_attr(plugin_dir_url(__FILE__) . 'scripts/imageresizer/thumbnail.php?src=' . $stw . '&w=' . $img_width . '&zc=1');
	}
	
	return $img_url;
	
}

endif;


// Grab the Portfolio title for the current Portfolio in the loop
if ( ! function_exists( 'get_Loop_Portfolio_Title' ) ) :
function get_Loop_Portfolio_Title() {
	
	global $portfolio_output;
	
	$portfolio_output .= '<h2>' . the_title_attribute('echo=0') . '</h2>';
}
endif;


function set_stw_nopro_script() {
	
	// if we are configured to use STW, but not the Pro version, then add in the necessary preview script
	if ( (strtolower(get_option('webphysiology_portfolio_thumbnail_generator')) == 'stw') && (strtolower(get_option('webphysiology_portfolio_use_stw_pro')) != 'true') ) {
		wp_deregister_script('stw-pagepix'); // the is how WordPress Portfolio Plugin is registering this javascript library
		wp_register_script('shrinktheweb', "http://www.shrinktheweb.com/scripts/pagepix.js");
		wp_enqueue_script('shrinktheweb');
	}
}

/* Add the Portfolio Stylsheet to the <head> section of the page */
/*  note: the user may override this option from the Portfolio settings page */
function set_base_portfolio_css() {
	
	$portfolio_css_on = get_option('webphysiology_portfolio_use_css');
	
	if (is_wp_error($portfolio_css_on) || $portfolio_css_on=='True') {
		$css = plugins_url('css/portfolio.css', __FILE__);
		wp_register_style('portfolio_css', $css);
		wp_enqueue_style('portfolio_css');
	}
	
}

function set_portfolio_css() {
	
	$portfolio_css_on = get_option('webphysiology_portfolio_use_css');
	
	if (is_wp_error($portfolio_css_on) || $portfolio_css_on=='True') {
		
		$gridstyle = 'webphysiology_portfolio_gridstyle'; // default false
		$gridcolor = 'webphysiology_portfolio_gridcolor'; // default #eee
		$overall_width = 'webphysiology_portfolio_overall_width'; // default is 660px
		$img_width = 'webphysiology_portfolio_image_width'; // default is 200px
		$meta_key_width = 'webphysiology_portfolio_label_width'; // default is 60px
		$header_color = 'webphysiology_portfolio_header_color'; // default is #004813
		$link_color = 'webphysiology_portfolio_link_color'; // default is #004813
		$odd_stripe_color = 'webphysiology_portfolio_odd_stripe_color'; // default is #eee
		$even_stripe_color = 'webphysiology_portfolio_even_stripe_color'; // default is #f9f9f9
		$opt_val_gridstyle = get_option( $gridstyle );
		$opt_val_gridcolor = get_option( $gridcolor );
		$opt_val_overall_width = get_option( $overall_width );
		$opt_val_img_width = get_option( $img_width );
		$opt_val_meta_key_width = get_option( $meta_key_width );
		$opt_val_header_color = get_option( $header_color );
		$opt_val_link_color = get_option( $link_color );
		$opt_val_odd_stripe_color = get_option( $odd_stripe_color );
		$opt_val_even_stripe_color = get_option( $even_stripe_color );
		
		$overall_image_width = $opt_val_img_width + 20;
		if ($opt_val_gridstyle != 'True') {
			$detail_width = $opt_val_overall_width - $overall_image_width - 30;
			$meta_value_width = $detail_width - ($opt_val_meta_key_width + 4);
			$class = '.portfolio_details';
		} else {
			$detail_width = $overall_image_width - 10;
			$meta_value_width = $detail_width - ($opt_val_meta_key_width + 4);
			$class = '.portfolio_entry';
		}
		$grid_image_width = $detail_width - 10;
		$single_details_width = $detail_width + 30;
		
		$embedded_css = "\n" .
						'<style type="text/css" id="webphysiology_portfolio_embedded_css">' . "\n" .
						'    .webphysiology_portfolio {	' . "\n" .
						'        width: ' . $opt_val_overall_width . 'px;' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio ' . $class . ' {' . "\n" .
						'        width: ' . $detail_width . 'px;' . "\n" .
						'    }' . "\n" .
						'     #webphysiology_portfolio.single_portfolio_page .portfolio_details {' . "\n" .
						'        width: ' . $single_details_width . 'px;' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_page_img {' . "\n" .
						'        width: ' . $overall_image_width . 'px;' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .grid .portfolio_page_img {' . "\n" .
						'        width: ' . $detail_width . 'px;' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_page_img img {' . "\n" .
						'        width: ' . $opt_val_img_width . 'px;' . "\n" .
						'        max-width: ' . $opt_val_img_width . 'px;' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_meta .key, #webphysiology_portfolio.single_portfolio_page .portfolio_meta .key {' . "\n" .
						'    	width: ' . $opt_val_meta_key_width . 'px;' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_meta .value, #webphysiology_portfolio.single_portfolio_page .portfolio_meta .value {' . "\n" .
						'        width: ' . $meta_value_width . 'px;' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio ul.grid {' . "\n" .
						'    	background-color: ' . $opt_val_gridcolor . ';' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_title h1, .webphysiology_portfolio .portfolio_title h2 {' . "\n" .
						'        color: ' . $opt_val_header_color . ';' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_nav a {' . "\n" .
						'        color: ' . $opt_val_link_color . ';' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_entry {' . "\n" .
						'        background-color: ' . $opt_val_even_stripe_color . ';' . "\n" .
						'    }' . "\n" .
						'    .webphysiology_portfolio .portfolio_entry.webphysport_odd_stripe {' . "\n" .
						'        background-color: ' . $opt_val_odd_stripe_color . ';' . "\n" .
						'    }' . "\n" .
						'</style>' . "\n";
		
		echo "\n";
		echo "<!-- WEBphysiology Portfolio " . WEBPHYSIOLOGY_VERSION . " by http://WEBphysiology.com -->";
		echo $embedded_css;
		
		$css = plugins_url('css/', __FILE__);
		
		// note: wp_enqueue_style does not support conditional stylesheets at this time
		
		echo "\n";
		echo '<!--[if lte IE 8]>' . "\n";
		echo '	<link rel="stylesheet" id="webphysiology_portfolio_ie_adjustment_css" type="text/css" href="' . $css . 'portfolio_lte_ie8.css" />' . "\n";
		echo '<![endif]-->' . "\n";
		echo '<!--[if lte IE 7]>' . "\n";
		echo '	<link rel="stylesheet" id="webphysiology_portfolio_ie_adjustment_css" type="text/css" href="' . $css . 'portfolio_lte_ie7.css" />' . "\n";
		echo '<![endif]-->' . "\n";
	}
}


/* Build out the navigation elements for paging through the Portfolio pages */
function nav_pages($qryloop, $pageurl, $class) {
	
	global $for;
	global $portfolio_output;
	global $navcontrol;
	
	// get total number of pages in the query results
	$pages = $qryloop->max_num_pages;
	$legacy = get_option('webphysiology_portfolio_legacy_even_odd_class');
	$top = "";
	$bottom = "";
	if ($legacy == 'True') {
		$top = " top";
		$bottom = " bottom";
	}
	// if there is more than one page of Portfolio query results
	if ($pages > 1) {
		
		// if this is the bottom nav then there is no point in rebuilding everything, just take what we
		// built for the top nav and put it in the bottom nav <div>
		if ( ($class == "webphysport_nav_bottom") && ( !empty($navcontrol) ) ) {
			$portfolio_output .= '<div class="portfolio_nav' . $bottom . ' ' . $class . '">' . $navcontrol . '</div>';
			$navcontrol = array();
			return $portfolio_output;
		}
		
		// if the user is not using pretty permalinks, then the nav page reference is a second parameter
		// 1.1.5 - also building out a full URL to the particular page
		if ( strpos($pageurl, "?page_id=") > 0 ) {
			$paged = $pageurl . "&paged=";
		} else {
			$paged = $pageurl . "?paged=";
		}		
		
		// get current page number
		intval(get_query_var('paged')) == 0 ? $curpage=1 : $curpage = intval(get_query_var('paged'));
		
		// determine the starting page number of the nav control
		
		// figure out where to start and end the nav control numbering as well as what arrow elements we need on each end, if any
		$start = $curpage - round(($for/2),0) + 1;
		if ( ($start + $for) > $pages ) { $start = $pages - $for + 1; }
		if ($start < 1) { $start = 1; }
		if ( ($start + $for) > $pages ) { $for = $pages - $start + 1; }
		$before = 0;
		if ($start > 2) {
			$before = 2;
		} elseif ($start > 1) {
			$before = 1;
		}
		$after = $pages - ($start + $for - 1);
		if ($after > 2) {
			$after = 2;
		} elseif ( $after < 0) {
			$after = 0;
		}		
		
		// now build out the navigation page control elements
		$nav = '<ul>';
		if ($before == 1) {
			$nav .= '<li><a href="' . $paged . ($start - 1) . '">&lt;</a></li>';
		} elseif ($before == 2) {
			$nav .= '<li><a href="' . $paged . '1">&laquo;</a></li>';
			$nav .= '<li><a href="' . $paged . ($start - 1) . '">&lt;</a></li>';
		}
		for ($i=$start;$i<=($start+$for-1);$i++) {
			if ($curpage!=$i) {
				$nav .= '<li><a href="' . $paged . $i .'"';
			} else {
				$nav .= '<li class="selected"><a href="' . $paged . $i .'" class="selected"';
			}
			$nav .= '>' . $i . '</a></li>';
		}
		if ($after == 1) {
			$nav .= '<li><a href="' . $paged . ($start + $for) . '">&gt;</a></li>';
		} elseif ($after == 2) {
			$nav .= '<li><a href="' . $paged . ($start + $for) . '">&gt;</a></li>';
			$nav .= '<li><a href="' . $paged . $pages . '">&raquo;</a></li>';
		}
		$nav .= '</ul>';
		
		$portfolio_output .= '<div class="portfolio_nav' . $top . ' ' . $class . '">' . $nav . '</div>';
		
		if ($class == "webphysport_nav_top") {
			$navcontrol = $nav;
		}
		
	}
	
	return $portfolio_output;
}

/*
// RESERVED FOR POTENTIAL FUTURE USE - there is no single Portfolio page at this time
function use_portfolio_template() {
	
	if ( !( is_page('Portfolio') ) && (( !(get_post_type() == 'webphys_portfolio') || is_404() )))  return;
	if ( is_page('Portfolio') ) {
		include('portfolioNEW.php');
		exit;
	} elseif ( is_single() ) {
		include('single-portfolio.php');
		exit;
	}
	
}
add_action('template_redirect', 'use_portfolio_template');
*/

//*************************************************//
//*************************************************//
//*************************************************//
//******  PORTFOLIO USER INTERFACE CODE END  ******//
//*************************************************//
//*************************************************//
//*************************************************//



// For multisite installs, change the virtual path to the true image path
if ( ! function_exists( 'multisite_image_adjustment' ) ) :
function multisite_image_adjustment($src) {
	
	global $blog_id;
	
	$multisite = (isset($blog_id) && $blog_id > 0);
	
	// if this is a multi-site, then update the path to the true image path, not the virtual path
	if ($multisite) {
		$imageParts = explode('/files/' , $src);
		if(isset($imageParts[1])) {
			$src = 'wp-content/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
		}
	}
	
	return $src;
	
}
endif;

//*************************************************//
//*************************************************//
//*************************************************//
//*****  CODE "BORROWED" FROM THUMBNAILER.PHP  ****//
//*************************************************//
//*************************************************//
//*************************************************//

/**
 * tidy up the image source url
 *
 * @param <type> $src
 * @return string
 */
function clean_source($src) {
	
	$orig_src = "";
	
	$src = multisite_image_adjustment($src);
	
	// if the image file is on the current server, grab the path as we'll be setting it back to this if all is good
	if (strpos(strtoupper($src),strtoupper($_SERVER['HTTP_HOST'])) > 0) {
		$orig_src = $src;
	}
	
	$host = str_replace ('www.', '', $_SERVER['HTTP_HOST']);
	$regex = "/^(http(s|):\/\/)(www\.|)" . $host . "\//i";
	$src = preg_replace ($regex, '', $src);
	$src = strip_tags ($src);
	$src = check_external ($src);
	
	if ( empty($src) ) {return $src;}
	
    // remove slash from start of string
    if (strpos ($src, '/') === 0) {
        $src = substr ($src, -(strlen ($src) - 1));
    }
	
    // don't allow users the ability to use '../'
    // in order to gain access to files below document root
    $src = preg_replace ("/\.\.+\//", "", $src);
	
    // get path to image on file system
	if (substr($src,0,4) != 'temp') {
    	$src = str_replace("//","/",get_document_root($src) . '/' . $src);
	}
	
	if ( ! empty($orig_src) ) {
		if (file_exists($src)) {
			$src = $orig_src;
		} else {
			$src = "";
		}
	}
	
    return $src;

}

/**
 *
 * @param <type> $url
 * @return <type> 
 */
function validate_url ($url) {
        $pattern = "/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
        return preg_match ($pattern, $url);
}

/**
 * @param string $src
 * @return string
 */
function check_external($src) {

	// external domains that are allowed to be displayed on your website
	$allowedSites = explode (",", get_option( 'webphysiology_portfolio_allowed_image_sites' ));
	
	$error = false;
	
    if ( (preg_match ('/http:\/\//', $src) == true) || (preg_match ('/https:\/\//', $src) == true) ) {
		
		if ( ! validate_url($src) ) {
			display_error ('invalid url');
			$error = true;
		}
		
        $url_info = parse_url ($src);

        $isAllowedSite = false;
        foreach ($allowedSites as $site) {
			$site = '/' . addslashes ($site) . '/';
            if (preg_match ($site, $url_info['host']) == true) {
                $isAllowedSite = true;
            }
		}
		
		if ($isAllowedSite) {
			
			$fileDetails = pathinfo ($src);
			$ext = strtolower ($fileDetails['extension']);

			$filename = md5 ($src);
			$newsrc = 'temp/' . $filename . '.' . $ext;
			
			$local_filepath = dirname ( __FILE__ ) . '/' . $newsrc;
//asterisk s3.amazon.com echo "1 - ".$local_filepath."<br />";
			if (!file_exists ($local_filepath)) {
				
				if (function_exists ('curl_init')) {

					$fh = fopen ($local_filepath, 'w');
					$ch = curl_init ($src);
					curl_setopt ($ch, CURLOPT_URL, $src);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt ($ch, CURLOPT_HEADER, 0);
					curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
					curl_setopt ($ch, CURLOPT_FILE, $fh);
					
					// Could do better
					// http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
					if (preg_match ('/https:\/\//', $src) == true) {
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					}
					
					if (curl_exec ($ch) === FALSE) {
//asterisk s3.amazon.com echo "2 - ".$local_filepath."<br />";
						if (file_exists ($local_filepath)) {
							unlink ($local_filepath);
						}
						display_error ('error reading file ' . $src . ' from remote host: ' . curl_error($ch));
						$error = true;
					}

					curl_close ($ch);
					fclose ($fh);

                } else {

					if (!$img = file_get_contents($src)) {
						display_error('remote file for ' . $src . ' can not be accessed. It is likely that the file permissions are restricted');
						$error = true;
					}
					
//asterisk s3.amazon.com echo "3 - ".$local_filepath."<br />";
					if (file_put_contents ($local_filepath, $img) == FALSE) {
						display_error ('error writing temporary file');
						$error = true;
					}

				}

				if (!file_exists($local_filepath)) {
					display_error('local file for ' . $src . ' can not be created');
					$error = true;
				}
				
			}
			
			if ((!$error) && ($newsrc!="")) {
				$src = $newsrc;
			} else {
				$src = "";
			}

		} else {

			display_error('remote host "' . $url_info['host'] . '" not allowed');
			$src = "";

		}

    }
	
    return $src;

} //function check_external($src)

/**
 *
 * @param <type> $src
 * @return string
 */
function get_document_root($src) {
	
    // check for unix servers
    if (file_exists ($_SERVER['DOCUMENT_ROOT'] . '/' . $src)) {
        return $_SERVER['DOCUMENT_ROOT'];
    }
	
    // check from script filename (to get all directories to timthumb location)
    $parts = array_diff (explode ('/', $_SERVER['SCRIPT_FILENAME']), explode ('/', $_SERVER['DOCUMENT_ROOT']));
	
	$path = './';
	
	foreach ($parts as $part) {
		if (file_exists ($path . '/' . $src)) {
			return realpath ($path);
		}
		$path .= '../';
	}
	
    // special check for microsoft servers
    if (!isset ($_SERVER['DOCUMENT_ROOT'])) {
        $path = str_replace ("/", "\\", $_SERVER['ORIG_PATH_INFO']);
        $path = str_replace ($path, '', $_SERVER['SCRIPT_FILENAME']);

        if (file_exists ($path . '/' . $src)) {
            return realpath ($path);
        }
    }
	
    display_error ('file not found - check your image URLs');
	
}

function get_document_rootie($src) {

    // check for unix servers
    if (file_exists ($_SERVER['DOCUMENT_ROOT'] . '/' . $src)) {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    // check from script filename (to get all directories to imageresizer location)
    $parts = array_diff (explode ('/', $_SERVER['SCRIPT_FILENAME']), explode('/', $_SERVER['DOCUMENT_ROOT']));
    $path = $_SERVER['DOCUMENT_ROOT'];
    foreach ($parts as $part) {
        $path .= '/' . $part;
        if (file_exists($path . '/' . $src)) {
            return $path;
        }
    }

    // the relative paths below are useful if imageresizer is moved outside of document root
    // specifically if installed in wordpress themes
	$paths = array (
        ".",
        "..",
        "../..",
        "../../..",
        "../../../..",
        "../../../../.."
    );

    foreach ($paths as $path) {
        if (file_exists($path . '/' . $src)) {
            return $path;
        }
    }

	// special check for microsoft servers
    if (!isset ($_SERVER['DOCUMENT_ROOT'])) {
        $path = str_replace ("/", "\\", $_SERVER['ORIG_PATH_INFO']);
        $path = str_replace ($path, "", $_SERVER['SCRIPT_FILENAME']);

        if (file_exists ($path . '/' . $src)) {
            return $path;
        }
    }

}

function display_error ($errorString = '') {

	echo '<pre>' . htmlentities($errorString) . '</pre>';

}

// check that the current environment supports the WEBphysiology Portfolio plugin
function portfolio_requirements_message() {
	
    global $wpdb;
	
	if (empty($portfolio_rqmts_checked)) {
		
		if (empty($top_message_head) && empty($message) && empty($message_head)) {
			
			$is_php_valid = version_compare(phpversion(), '5.0.0', '>');
			$is_mysql_valid = version_compare($wpdb->db_version(), '5.0.0', '>');
			$is_wp_valid = version_compare(get_bloginfo("version"), '3.0.0', '>');
			$meets_requirements = ($is_php_valid && $is_mysql_valid && $is_wp_valid);
			$class = $meets_requirements ? "update-message" : "error";
			
			if ( !$meets_requirements ) {
	
				$top_message_head = "<div class='error' style='margin:5px; padding:3px; text-align:left; width:93%; margin-bottom: 15px;'>";
		
				$message = "Your host setup is not compatible with WEBphysiology Portfolio. The following items must be upgraded:<br/> ";
		
				if(!$is_php_valid){
					$message .= " - <strong>PHP</strong> (Current version: " .  phpversion() . ", Required: 5.0)<br/> ";
				}
		
				if(!$is_mysql_valid){
					$message .= " - <strong>MySql</strong> (Current version: " .  $wpdb->db_version() . ", Required: 5.0)<br/> ";
				}
		
				if(!$is_wp_valid){
					$message .= " - <strong>Wordpress</strong> (Current version: " .  get_bloginfo("version") . ", Required: 3.0)<br/> ";
				}
		
				$message .= "</div>";
				
				echo $top_message_head . $message;
				
			}
		}
	}

}
?>