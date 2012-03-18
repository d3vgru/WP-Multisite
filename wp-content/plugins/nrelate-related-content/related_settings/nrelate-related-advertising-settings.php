<?php
/**
 * nrelate Related Advertising Settings
 *
 * @package nrelate
 * @subpackage Functions
 */
 

function options_init_nr_rc_ads(){
	register_setting('nrelate_related_options_ads', 'nrelate_related_options_ads', 'related_adv_options_validate' );
	
	$options = get_option('nrelate_related_options_ads');
	// Div style on initial load for showing ad_title
	$divstyle=($options['related_ad_placement']=="Separate")?'style="display:block;"':'style="display:none;"';

	// Ad Section
	add_settings_section('ad_section',__('Advertising Settings','nrelate'), 'nrelate_text_advertising', __FILE__);
	add_settings_field('related_display_ad_image','', 'related_display_ad_money', __FILE__, 'ad_section');
	add_settings_field('related_display_ad',__('Would you like to display ads?','nrelate'), 'setting_adv_display_ad', __FILE__, 'ad_section');
	add_settings_field('related_ad_number',__('How many ad spaces do you wish to show?','nrelate'), 'setting_adv_ad_number', __FILE__, 'ad_section');
	add_settings_field('related_ad_placement',__('Where would you like to place the ads?','nrelate') . nrelate_tooltip('_adplacement'), 'setting_adv_ad_placement', __FILE__, 'ad_section');
	add_settings_field('related_ad_title', __('<div class="nr_separate_ad_opt" '.$divstyle.'>Please enter a title for advertising section</div>','nrelate'), 'setting_adv_ad_title', __FILE__, 'ad_section');
	add_settings_field('related_ad_animation',__('Would you like to show animated "sponsored" text in ads?','nrelate'), 'setting_adv_ad_animation', __FILE__, 'ad_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'ad_section');
	
}
add_action('admin_init', 'options_init_nr_rc_ads' );


/****************************************************************
 ************************** Admin Sections ********************** 
*****************************************************************/


///////////////////////////
//   Advertising Settings
//////////////////////////


// Show "Wanna make some money?" image
function related_display_ad_money(){
	
	 // Get Advertising options
	$ad_options = get_option('nrelate_related_options_ads');
	
	// get ad show option
	$ad_show = isset($ad_options['related_display_ad']) ? $ad_options['related_display_ad'] : null;
	
	// If not showing ads, display image
	if ($ad_show == null) { nrelate_wanna_make_money(); }
}


// CHECKBOX - Display ads
function setting_adv_display_ad() {
	$options = get_option('nrelate_related_options_ads');
	$checked = (isset($options['related_display_ad']) && $options['related_display_ad']=='on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='show_ad' name='nrelate_related_options_ads[related_display_ad]' type='checkbox' />";
}

// DROPDOWN - number of ads to show
function setting_adv_ad_number(){
	$options = get_option('nrelate_related_options_ads');
	$items = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
	echo "<div id='adnumber'><select id='related_number_of_ads' name='nrelate_related_options_ads[related_number_of_ads]'>";
	foreach($items as $item) {
		$selected = ($options['related_number_of_ads']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select></div>";
}

// DROPDOWN - ad placement
function setting_adv_ad_placement(){	
	$options = get_option('nrelate_related_options_ads');
	$items = array("Mixed","First","Last","Separate");
	echo "<div id='adplacement'><select id='related_ad_placement' name='nrelate_related_options_ads[related_ad_placement]' onChange='if(this.value==\"Separate\"){jQuery(\".nr_separate_ad_opt\").show(\"slow\");}else{jQuery(\".nr_separate_ad_opt\").hide(\"slow\");}'>";
	foreach($items as $item) {
		$selected = ($options['related_ad_placement']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select></div>";
}

// TEXTBOX - Name: nrelate_related_options_ads[related_ad_title]
function setting_adv_ad_title() {
	$options = get_option('nrelate_related_options_ads');
	// Div style on initial load for showing ad_title
	$divstyle=($options['related_ad_placement']=="Separate")?'style="display:block;"':'style="display:none;"';
	$nr_ad_title = stripslashes(stripslashes($options['related_ad_title']));
	$nr_ad_title = htmlspecialchars($nr_ad_title);
	echo '<input id="related_ad_title" class="nr_separate_ad_opt" name="nrelate_related_options_ads[related_ad_title]" size="40" type="text" value="'.$nr_ad_title.'" '.$divstyle.'/>';
}

// CHECKBOX - Animated "sponsored" text in ads
function setting_adv_ad_animation(){
	$options = get_option('nrelate_related_options_ads');
	$checked = !empty($options['related_ad_animation']) ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='ad_animation' name='nrelate_related_options_ads[related_ad_animation]' type='checkbox' />";
}



/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/

function nrelate_related_ads_do_page() {
	$options = get_option('nrelate_related_options');
	$style_options = get_option('nrelate_related_options_styles');
?>
	
	<?php nrelate_related_settings_header(); ?>
    <script type="text/javascript">
		//<![CDATA[
		var nr_plugin_settings_url = '<?php echo NRELATE_RELATED_SETTINGS_URL; ?>';
		var nr_plugin_domain = '<?php echo NRELATE_BLOG_ROOT ?>';
		var nr_plugin_version = '<?php echo NRELATE_RELATED_PLUGIN_VERSION ?>';
		//]]>
    </script>
		<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
			<?php settings_fields('nrelate_related_options_ads'); ?>
			<?php do_settings_sections(__FILE__);?>
			
			<div class="nrelate-hidden">
		      <input type="hidden" id="related_number_of_posts" value="<?php echo isset($options['related_number_of_posts']) ? $options['related_number_of_posts'] : ''; ?>" />
		      <input type="hidden" id="related_number_of_posts_ext" value="<?php echo isset($options['related_number_of_posts_ext']) ? $options['related_number_of_posts_ext'] : ''; ?>" />
		      <input type="hidden" id="related_title" value="<?php echo isset($options['related_title']) ? $options['related_title'] : ''; ?>" />
		      <input type="checkbox" id="related_show_post_title" <?php echo empty($options['related_show_post_title']) ? '' : 'checked="checked"'; ?> value="on" />
		      <input type="hidden" id="related_max_chars_per_line" value="<?php echo isset($options['related_max_chars_per_line']) ? $options['related_max_chars_per_line'] : ''; ?>" />
		      <input type="checkbox" id="related_show_post_excerpt" <?php echo empty($options['related_show_post_excerpt']) ? '' : 'checked="checked"'; ?> value="on" />
		      <input type="hidden" id="related_max_chars_post_excerpt" value="<?php echo isset($options['related_max_chars_post_excerpt']) ? $options['related_max_chars_post_excerpt'] : ''; ?>" />
		      <input type="checkbox" id="show_logo" <?php echo empty($options['related_display_logo']) ? '' : 'checked="checked"'; ?> value="on" />
		      <input type="hidden" id="related_thumbnail" value="<?php echo isset($options['related_thumbnail']) ? $options['related_thumbnail'] : ''; ?>" />
		      <input type="hidden" id="related_textstyle" value="<?php echo empty($style_options['related_text_style']) ? 'default' : $style_options['related_text_style']; ?>" />
		      <input type="hidden" id="related_imagestyle" value="<?php echo empty($style_options['related_thumbnails_style']) ? 'default' : $style_options['related_thumbnails_style']; ?>" />
		      <input type="hidden" id="related_default_image" value="<?php echo $options['related_default_image']; ?>" />
		      <input type="hidden" id="related_max_age_num" value="<?php echo $options['related_max_age_num']; ?>" />
		      <input type="hidden" id="related_max_age_frame" value="<?php echo $options['related_max_age_frame']; ?>" />
		      <input type="hidden" id="related_blogoption" value="<?php echo ( is_array($options['related_blogoption']) && count($options['related_blogoption'] > 0) ) ? 1 : 0; ?>" />
		      <input type="hidden" id="related_thumbnail_size" value="<?php echo $options['related_thumbnail_size']; ?>" />
		      <input type="hidden" id="related_imagestyle" value="<?php echo $style_options['related_thumbnails_style']; ?>" />
		      <input type="hidden" id="related_textstyle" value="<?php echo $style_options['related_text_style']; ?>" />
			  <input type="hidden" id="related_blogoption" value="<?php echo ( is_array($options['related_blogoption']) && count($options['related_blogoption'] > 0) ) ? 1 : 0; ?>" />
		    </div>
		</form>

	</div>
<?php
	
	update_nrelate_data_rc_adv();
}

// Loads all of the nrelate_related_options from wp database
// Makes necessary conversion for some parameters.
// Sends nrelate_related_options entries, rss feed mode, and wordpress home url to the nrelate server
// Returns Success if connection status is "200". Returns error if not "200"
function update_nrelate_data_rc_adv(){
	
	// Get nrelate_related options from wordpress database
	$ad_option = get_option('nrelate_related_options_ads');
	
	$r_display_ad = empty($ad_option['related_display_ad']) ? false : true;
	$related_ad_num = $ad_option['related_number_of_ads'];
	$related_ad_place = $ad_option['related_ad_placement'];
	$related_ad_title = $ad_option['related_ad_title'];
	
	$ad = ($r_display_ad) ? 1:0;

	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ADOPT'=>$ad,
		'ADNUM'=>$related_ad_num,
		'ADPLACE'=>$related_ad_place,
		'ADTITLE'=>$related_ad_title,
		'VERSION'=>NRELATE_RELATED_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key')
	);
	$url = 'http://api.nrelate.com/rcw_wp/'.NRELATE_RELATED_PLUGIN_VERSION.'/processWPrelated_ad.php';
	
	$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false, 'timeout'=>15));
}



// Validate user data for some/all of our input fields
function related_adv_options_validate($input) {
	// Make sure that unchecked checkboxes are stored as empty strings
	global $nr_rc_ad_options;
	$options = array_keys($nr_rc_ad_options);
	$values = array_fill(0, count($options), '');
	$empty_settings_array = array_combine($options, $values);
	
	$input = wp_parse_args( $input, $empty_settings_array );
	
	return $input; // return validated input
}
?>